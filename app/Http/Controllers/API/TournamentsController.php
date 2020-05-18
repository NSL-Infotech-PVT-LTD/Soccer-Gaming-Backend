<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Tournament;
use App\Tournament as MyModel;
use Illuminate\Http\Request;

class TournamentsController extends ApiController {

    public function ratings(Request $request) {

        $rules = ['rating' => '', 'feed_back' => '', 'provider_id' => 'required|exists:users,id', 'quality_of_repair' => '', 'overall_experience' => '', 'use_again_or_recommend' => '', 'media' => ''];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }
        $input = $request->all();
        if (isset($request->media))
            $input['media'] = parent::__uploadImage($request->file('media'), public_path('uploads/ratings'), $thumbnail = true);
        $input['user_id'] = Auth::id();

        $Ratings = Rating::create($input);

        return parent::success(['message' => 'Created Successfully', 'Rating' => $Ratings]);
    }

    public function getRating(Request $request) {

        $rules = ['provider_id' => 'required|exists:users,id', 'limit' => ''];

        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);

        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {

            $model = new User;
            $perPage = isset($request->limit) ? $request->limit : 20;

            $model = $model->where('id', $request->provider_id)->with(['getRatings']);


            return parent::success($model->first());
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function createTournaments(Request $request) {

        $rules = ['name' => 'required|string', 'type' => 'required|in:league,league_and_knockout,knockout', 'number_of_players' => 'required|integer|min:1|max:32', 'number_of_teams_per_player' => 'required|integer|min:1|max:4', 'number_of_plays_against_each_team' => 'required_if:type,league_and_knockout,league|integer|min:1|max:2', 'number_of_players_that_will_be_in_the_knockout_stage' => 'required_if:type,knockout|in:16_player,8_player,4_player,2_player', 'legs_per_match_in_knockout_stage' => 'required_if:type,==,knockout|integer|min:1|max:2', 'number_of_legs_in_final' => 'required_if:type,==,knockout|integer|min:1|max:2'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors);
        }

        $input = $request->all();
        for ($i = 1; $i <= $request->number_of_players; $i++):
            $key = 'player_' . $i;
            if (!isset($request->$key))
                return parent::error('Player ' . $i . ' is required');
            $data = (array) json_decode($request->$key, false);
            if (count($data[array_keys($data)['0']]) != $request->number_of_teams_per_player)
                return parent::error('Numbers of team provided in Player ' . $i . ' does not match with count');
        endfor;
//        dd(array_keys($input));
//        dd($input['number_of_players']);
        $input['created_by'] = \Auth::id();
        $input['updated_by'] = \Auth::id();
        $tournament = Tournament::create($input);
        $tournamentPlayer = [];
        for ($i = 1; $i <= $request->number_of_players; $i++):
            $key = 'player_' . $i;
            $data = (array) json_decode($request->$key, false);
            foreach ($data[array_keys($data)['0']] as $k => $team_id):
                $tournamentPlayer[$i][$k] = ['tournament_id' => $tournament->id, 'player_id' => array_keys($data)['0'], 'team_id' => $team_id];
            endforeach;
//            dd($tournamentPlayer[$i]);
            \App\TournamentPlayerTeam::insert($tournamentPlayer[$i]);
        endfor;
        return parent::success(['message' => 'Your Tournaments has been successfully created', 'tournaments' => $tournament]);
    }

    public function tournamentList(Request $request) {

        $rules = ['search' => '', 'show_my' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $tournament = new Tournament();
            $tournament = $tournament->select('id', 'name', 'type', 'number_of_players', 'number_of_teams_per_player', 'number_of_plays_against_each_team', 'number_of_players_that_will_be_in_the_knockout_stage', 'legs_per_match_in_knockout_stage', 'number_of_legs_in_final');
            if ($request->show_my == 'my')
                $tournament = $tournament->where("created_by", \Auth::id());

            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $tournament = $tournament->where(function($query) use ($request) {
                    $query->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('type', 'LIKE', "%$request->search%");
                });
            }
            $tournament = $tournament->with(['players']);
            $tournament = $tournament->orderby('id', 'desc');

            return parent::success($tournament->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function addScoreToTournament(Request $request) {

        $rules = ['tournament_id' => 'required|exists:tournaments,id', 'player_id_1' => 'required|exists:users,id', 'player_id_1_team_id' => 'required|integer', 'player_id_1_score' => 'required|integer', 'player_id_2' => 'required|exists:users,id', 'player_id_2_team_id' => 'required|integer', 'player_id_2_score' => 'required|integer'];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }

        $playerdata = \App\TournamentPlayerTeam::where('tournament_id', '=', $request->tournament_id)->get();
//        dd($playerdata->toArray());
        $i = 0;
        foreach ($playerdata as $data):
            if ($data->player_id == $request->player_id_1 || $data->player_id == $request->player_id_2) {
                $i++;
                if ($i != '2') {
                    return parent::error('Players are not available in the tournament');
                }
            }
        endforeach;
        $tournamentfixtured = \App\TournamentFixture::where('tournament_id', '=', $request->tournament_id)->where('player_id_1', '=', $request->player_id_1)->where('player_id_1_team_id', '=', $request->player_id_1_team_id)->where('player_id_2_team_id', '=', $request->player_id_2_team_id)->where('player_id_2', '=', $request->player_id_2)->get();
        //                dd($tournamentfixtured);
        if (count($tournamentfixtured) > 0) {
            return parent::error(['message' => 'Score already Added']);
        } else {
            //            dd('s');
            $input = $request->all();

            $input['created_by'] = \Auth::id();
            $input['updated_by'] = \Auth::id();
            //            dd($input);
            $TournamnetFixed = \App\TournamentFixture::create($input);
            return parent::success(['message' => 'Scores has been successfully Added', 'tournamentFixtures' => $TournamnetFixed]);
        }
    }

    public function teamList(Request $request) {
//        dd('s');
        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $teams = new \App\Team();
            $teams = $teams->select('id', 'team_name', 'league_name', 'image');

            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $teams = $teams->where(function($query) use ($request) {
                    $query->where('team_name', 'LIKE', "%$request->search%")
                            ->orWhere('league_name', 'LIKE', "%$request->search%");
                });
            }
//            $tournament = $tournament->with(['players']);
            $teams = $teams->orderby('id', 'desc');

            return parent::success($teams->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function playerList(Request $request) {
//        dd('st');
        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $players = new User();
            $players = $players->select('id', 'username', 'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'image', 'field_to_play', 'field_to_play_id', 'video_stream', 'video_stream_id', 'is_login', 'is_notify', 'params', 'state')->whereHas(
                    'roles', function($q) {
                $q->where('name', 'Customer');
            }
            );
            $players = $players->where('id', '!=', \Auth::id());


            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $players = $players->where(function($query) use ($request) {
                    $query->where('username', 'LIKE', "%$request->search%")
                            ->orWhere('email', 'LIKE', "%$request->search%");
                });
            }
//            $tournament = $tournament->with(['players']);
            $players = $players->orderby('id', 'desc');

            return parent::success($players->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function findFriend(Request $request) {

        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $players = new User();

            $players = $players->select('id', 'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'image', 'field_to_play', 'field_to_play_id', 'video_stream', 'video_stream_id', 'is_login', 'is_notify', 'params', 'state');

            $players = $players->where("id", '!=', \Auth::id());
            $players = $players->wherein('id', \DB::table('role_user')->where('role_id', '2')->pluck('user_id'));

            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $players = $players->where(function($query) use ($request) {
                    $query->where('first_name', 'LIKE', "%$request->search%")
                            ->orWhere('email', 'LIKE', "%$request->search%");
                });
            }

//            $players = $players->orderby('id', 'desc');

            return parent::success($players->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function addFriend(Request $request) {
//            dd(Auth::id());
        $rules = ['friend_id' => 'required|exists:users,id'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors);
        }
        $friendsdata = \App\UserFriend::where('status', '=', 'pending');

        $friendsdata = $friendsdata->where(function($query) use ($request) {
            $query->where('user_id', \Auth::id());
            $query->where('friend_id', $request->friend_id);
        });
        $friendsdata = $friendsdata->orWhere(function($query) use ($request) {
                    $query->where('friend_id', \Auth::id());
                    $query->where('user_id', $request->friend_id);
                })->get();
//        dd(count($friendsdata));
        if (count($friendsdata) > 0) {
            return parent::error(['message' => 'Friend request already sent']);
        }
        $input = $request->all();
        $input['user_id'] = \Auth::id();

        $input['status'] = 'pending';


        $userfriends = \App\UserFriend::create($input);

        parent::pushNotifications(['title' => 'Friend Request', 'body' => 'You received one Friend Request', 'data' => ['target_id' => \Auth::id(), 'target_model' => 'UserFriend', 'data_type' => 'FriendRequest']], $request->friend_id, TRUE);

        return parent::success(['message' => 'Your friend request has been sent', 'userfriends' => $userfriends]);
    }

    public function myFriends(Request $request) {

        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $myfriends = new \App\UserFriend();
            $myfriends = $myfriends->select('id', 'user_id', 'friend_id', 'status', 'params', 'state');
            $myfriends = $myfriends->where(function($query) use ($request) {
                $query->where('user_id', \Auth::id());
                $query->orWhere('friend_id', \Auth::id());
            });
            $myfriends = $myfriends->where("status", "accepted");
//            $myfriends = $myfriends->with(['userDetails']);
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $myfriends = $myfriends->where(function($query) use ($request) {
                    $query->where('status', 'LIKE', "%$request->search%")
                            ->orWhere('user_id', 'LIKE', "%$request->search%");
                });
            }
            $myfriends = $myfriends->orderby('id', 'desc');
            return parent::success($myfriends->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function pendingRequests(Request $request) {

        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $myfriends = new \App\UserFriend();
            $myfriends = $myfriends->select('id', 'user_id', 'friend_id', 'status', 'params', 'state');

            $myfriends = $myfriends->where("user_id", \Auth::id())->where("status", "pending");

            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $myfriends = $myfriends->where(function($query) use ($request) {
                    $query->where('status', 'LIKE', "%$request->search%")
                            ->orWhere('user_id', 'LIKE', "%$request->search%");
                });
            }

            $myfriends = $myfriends->orderby('id', 'desc');

            return parent::success($myfriends->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function acceptRejectRequests(Request $request) {
//        dd(\Auth::id());
        $rules = ['friend_id' => 'required|exists:users,id', 'status' => 'required|in:accepted,rejected'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $friends = new \App\UserFriend();
            $friends = $friends->select('id', 'user_id', 'friend_id', 'status', 'params', 'state');

            $friends = $friends->where("user_id", $request->friend_id)->where("friend_id", \Auth::id())->where("status", "pending")->get();
//            dd($friends);
            if (count($friends) < 1):
                return parent::error(['message' => 'Request not found for this player']);
            endif;
            $frienddata = \App\UserFriend::where([['user_id', $request->friend_id], ['friend_id', \Auth::id()]])->update(['status' => $request->status]);


//            parent::pushNotifications(['title' => 'Friend Request', 'body' => 'You received one Friend Request', 'data' => ['target_id' => \Auth::id(), 'target_model' => 'UserFriend', 'data_type' => 'FriendRequest']], $request->friend_id, TRUE);

            return parent::success(['message' => 'Status updated', 'friendFound' => $friends]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function clubList(Request $request) {

        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;

        //ends

        $a = [];
        $a[] = self::getClubsCurl('https://fut.best/api/clubs?page=1&limit=5');
        $clubs = $a[0]['data']->clubs;
        foreach ($clubs as $club):
            if (\App\Team::where('team_name', $club->name)->get()->isEmpty()):
                $input['team_name'] = $club->name;
                $input['image'] = $club->Image->url;
                $club = \App\Team::create($input);
            endif;
        endforeach;
    }

    private static function getClubsCurl($url) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
//            echo $response;
        }
//        dd($response);
        return (array) json_decode($response);
    }

    public function getVideosByTwitchId(Request $request) {
//        dd('s');
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;

        //mycode for channel id    

        $myfriends = new \App\UserFriend();
        $myfriends = $myfriends->select('id', 'user_id', 'friend_id', 'status', 'params', 'state');
        $myfriends = $myfriends->where("user_id", \Auth::id())->where("status", "accepted")->get();
        $friendsChannelId = [];
        foreach ($myfriends as $friends):
            $friendsData = User::select('video_stream_id')->where("id", $friends->friend_id)->where("video_stream", "twitch")->get();
            foreach ($friendsData as $data):
                $friendsChannelId[] = $data->video_stream_id;
            endforeach;
        endforeach;
//        dd($friendsChannelId);
        //ends

        $a = [];
        foreach ($friendsChannelId as $chanelId):
//            dd($chanelId);
            $a[$chanelId] = self::getCurl('https://api.twitch.tv/kraken/channels/' . $chanelId . '/videos')['videos'][0];
        endforeach;
        dd($a);
    }

    private static function getCurl($url) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Client-ID: blxzkdpum1su6aq4aqq9w5gnviawq7',
                'Accept: application/vnd.twitchtv.v5+json'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
//            echo "cURL Error #:" . $err;
        } else {
//            echo $response;
        }
//        dd($response);
        return (array) json_decode($response);
    }

    private static function GetTwitchToken() {
        try {
            $data = array(
                "client_id" => "blxzkdpum1su6aq4aqq9w5gnviawq7",
                "client_secret" => "1u0dzwqcqemxbmo3szsrj7u9akau8z",
                "grant_type" => "client_credentials",
                "scope" => "collections_edit"
            );
            $curl = curl_init();
//            dd(http_build_query($data));
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://id.twitch.tv/oauth2/token?" . http_build_query($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
//                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [],
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
//            dd($err);
            curl_close($curl);
            if ($err) {
//            echo "cURL Error #:" . $err;
            } else {
//            echo $response;
            }
            dd(json_decode($response)->access_token);
            dd();
            return json_decode($response);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function notifications(Request $request) {
        $rules = ['search' => '', 'limit' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $model = new \App\Notification();
            $perPage = isset($request->limit) ? $request->limit : 20;

            if (isset($request->search))
                $model = $model->Where('title', 'LIKE', "%$request->search%")
                        ->orWhere('body', 'LIKE', "%$request->search%")
                        ->orWhere('data', 'LIKE', "%$request->search%");

//            $model = $model->where('target_id', \Auth::id());
            \App\Notification::whereIn('id', $model->get()->pluck('id'))->update(['is_read' => '1']);

            $model = $model->where('target_id', \Auth::id())->select('id', 'title', 'body', 'data', 'target_id', 'is_read', 'params', 'state');
//            $model = $model->with('userDetail')->orderBy('created_at', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
    public function notificationCount(Request $request) {

        $rules = ['search' => '',];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $model = new \App\Notification();
            $model = $model->where('target_id', \Auth::id())->where('is_read', '0')->count();
            return parent::success('notification_count' => $model);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    

}
