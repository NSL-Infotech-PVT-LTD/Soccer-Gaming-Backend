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
//        dd($request->Player_.$i);
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
            if (User::where('id', $request->$key)->get()->isEmpty())
                return parent::error('Players one does not exist');
        endfor;
        for ($i = 1; $i <= $request->number_of_players; $i++):
            $key = 'player_' . $i . '_teams';
            if (!isset($request->$key))
                return parent::error('Player ' . $i . ' teams is required');
            $data = (array) json_decode($request->$key, false);
            if (!is_array($data))
                parent::error('Numbers of team provided in Player ' . $i . ' does not Serialized');
//            dd($data);
            if (count($data) != $request->number_of_teams_per_player)
                return parent::error('Numbers of team provided in Player ' . $i . ' does not match with count');
        endfor;
//        dd($input);
//        dd($input['number_of_players']);
        $input['created_by'] = \Auth::id();
        $input['updated_by'] = \Auth::id();
        $tournament = Tournament::create($input);
        $tournamentPlayer = [];
        for ($i = 1; $i <= $request->number_of_players; $i++):
            $key = 'player_' . $i;
            $teamkey = 'player_' . $i . '_teams';
            $data = (array) json_decode($request->$teamkey, false);
            foreach ($data as $k => $team_id):
                $tournamentPlayer[$i][$k] = ['tournament_id' => $tournament->id, 'player_id' => $request->$key, 'team_id' => $team_id];
                ;
            endforeach;
            \App\TournamentPlayerTeam::insert($tournamentPlayer[$i]);


        endfor;
//        $product = self::cartesian($playerteams);
//        dd($playerteams);
        /*         * ***********************************Fixture Add Start** */

        if ($request->type === 'league'):
            $fixture = [];
            $fixture2 = [];
            for ($j = 1; $j < $request->number_of_players; $j++):
                $key_one = 'player_' . $j;
                $teamkey = 'player_' . $j . '_teams';
                $data = (array) json_decode($request->$teamkey, false);
                foreach ($data as $k => $team_id_one):
                    for ($i = $j + 1; $i <= $request->number_of_players; $i++):
                        if ($i != $j):
                            ${'key' . $i} = 'player_' . $i;
                            $teamkey = 'player_' . $i . '_teams';
                            ${'data' . $i} = (array) json_decode($request->$teamkey, false);
                            foreach ((array) json_decode($request->$teamkey, false) as $team_id_two):
                                $fixture[] = ['tournament_id' => $tournament->id, 'player_id_1' => $request->$key_one, 'player_id_1_team_id' => $team_id_one, 'player_id_2' => $request->${'key' . $i}, 'player_id_2_team_id' => $team_id_two, 'stage' => ($request->number_of_plays_against_each_team == '2') ? 'round-1' : 'no-round'];
                                $fixture2[] = ['tournament_id' => $tournament->id, 'player_id_1' => $request->$key_one, 'player_id_1_team_id' => $team_id_one, 'player_id_2' => $request->${'key' . $i}, 'player_id_2_team_id' => $team_id_two, 'stage' => ($request->number_of_plays_against_each_team == '2') ? 'round-2' : 'no-round'];
                            endforeach;
                        endif;
                    endfor;
                endforeach;
            endfor;
//                    dd($fixture);
            \App\TournamentFixture::insert($fixture);
            if ($request->number_of_plays_against_each_team == '2'):
                \App\TournamentFixture::insert($fixture2);
            endif;
        endif;

        if ($request->type == 'knockout'):
//            dd('s');
            if (!in_array($request->number_of_players, ["2", "4", "8", "16"])):
                return parent::error('Invalid number of players for knockout type');
            endif;
            if ($request->number_of_players == '2'):
                $stage = 'final';
            elseif ($request->number_of_players == '4'):
                $stage = 'semi-final';
            elseif ($request->number_of_players == '8'):
                $stage = 'quarter-final';
            elseif ($request->number_of_players == '16'):
                $stage = 'pre-quarter-final';
            elseif ($request->number_of_players == '32'):
                $stage = 'Round-1';
            endif;

            $fixture = [];
            for ($j = 1; $j < $request->number_of_players; $j++):
                $key_one = 'player_' . $j;
                $teamkey = 'player_' . $j . '_teams';

                $data = (array) json_decode($request->$teamkey, false);
//                dd($data);
                foreach ($data as $k => $team_id_one):
                    for ($i = $j + 1; $i < $j + 2; $i++):
                        if ($i != $j):
                            ${'key' . $i} = 'player_' . $i;
                            $teamkey = 'player_' . $i . '_teams';
                            ${'data' . $i} = (array) json_decode($request->$teamkey, false);
                            foreach ((array) json_decode($request->$teamkey, false) as $team_id_two):
                                $fixture[] = ['tournament_id' => $tournament->id, 'player_id_1' => $request->$key_one, 'player_id_1_team_id' => $team_id_one, 'player_id_2' => $request->${'key' . $i}, 'player_id_2_team_id' => $team_id_two, 'stage' => $stage];
                            endforeach;
                        endif;
                    endfor;
                endforeach;
                $j++;
            endfor;
            \App\TournamentFixture::insert($fixture);
//            dd($fixture);
            if ($request->legs_per_match_in_knockout_stage == '2' && $request->number_of_players != '2'):
                \App\TournamentFixture::insert($fixture);
            endif;
            if ($request->number_of_players == '2' && $request->number_of_legs_in_final == '2'):
                \App\TournamentFixture::insert($fixture);
            endif;
        endif;


        /*         * ***********************************Fixture Add End** */
        $tournamentGet = new Tournament();
        $tournamentGet = $tournamentGet->select('id', 'name', 'type', 'number_of_players', 'number_of_teams_per_player', 'number_of_plays_against_each_team', 'number_of_players_that_will_be_in_the_knockout_stage', 'legs_per_match_in_knockout_stage', 'number_of_legs_in_final');
        $tournamentGet = $tournamentGet->where("id", $tournament->id);
        $tournamentGet = $tournamentGet->with(['players', 'fixtures']);

//        $playerIDs=[];
//        for ($i = 1; $i <= $request->number_of_players; $i++):
//            $key = 'player_' . $i;
//            $playerIDs[]=$request->$key;
//        endfor;
//        $playerIDs)
//        parent::pushNotifications(['title' => 'Tournament created', 'body' => 'You have added in a tournament', 'data' => ['target_id' => $tournament->id, 'target_model' => 'Tournament', 'data_type' => 'AddedInTournament']],$playerIDs , TRUE);

        return parent::success(['message' => 'Your Tournament has been successfully created', 'tournaments' => $tournamentGet->first()]);
    }

//    public static function cartesian($input) {
//        $result = array(array());
//
//        foreach ($input as $key => $values) {
//            $append = array();
//
//            foreach ($result as $product) {
//                foreach ($values as $item) {
//                    $product[$key] = $item;
//                    $append[] = $product;
//                }
//            }
//
//            $result = $append;
//        }
//
//        return $result;
//    }

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
//            if ($request->show_my == 'my')
//            $tournament = $tournament->where("created_by", \Auth::id());

            $ids = \App\TournamentPlayerTeam::where('player_id', \Auth::id())->get()->pluck('tournament_id')->toArray();
//            dd($ids);
            $ids = array_merge($ids, MyModel::where("created_by", \Auth::id())->get()->pluck('id')->toArray());
//            dd($ids);
            $tournament = $tournament->whereIn("id", $ids);
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

    public function getTournament(Request $request) {
        $rules = ['search' => '', 'tournament_id' => 'required|exists:tournaments,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $tournament = new Tournament();
            $tournament = $tournament->select('id', 'name', 'type', 'number_of_players', 'number_of_teams_per_player', 'number_of_plays_against_each_team', 'number_of_players_that_will_be_in_the_knockout_stage', 'legs_per_match_in_knockout_stage', 'number_of_legs_in_final');
            $tournament = $tournament->where("id", $request->tournament_id);
            $tournament = $tournament->with(['fixtures']);
            $tournament = $tournament->with(['players']);
            return parent::success($tournament->first());
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
    public function tournamentHistory(Request $request) {
       
        $rules = ['search' => '', 'type' => 'required|in:league,league_and_knockout,knockout'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $tournament1 = new Tournament();
            $tournament1 = $tournament1->select('id', 'name', 'type', 'number_of_players', 'number_of_teams_per_player', 'number_of_plays_against_each_team', 'number_of_players_that_will_be_in_the_knockout_stage', 'legs_per_match_in_knockout_stage', 'number_of_legs_in_final');
            $tournament1 = $tournament1->where("type", $request->type)->get();
//            dd($tournament->toArray());
            foreach ($tournament1 as $items):
                if(\App\TournamentFixture::where('tournament_id', '=', $items->id)->get()->isEmpty() != true):
                   if(\App\TournamentFixture::where('tournament_id', '=', $items->id)->Where('player_id_2_score', '=', null)->get()->isEmpty() === true):
                        $completedTournamentIds[] = $items->id;
                    endif; 
                endif;
            endforeach;
            if(!isset($completedTournamentIds))
                    return parent::error('No Tournament has completed yet');
                    
            $tournament = new Tournament();
            $tournament = $tournament->select('id', 'name', 'type', 'number_of_players', 'number_of_teams_per_player', 'number_of_plays_against_each_team', 'number_of_players_that_will_be_in_the_knockout_stage', 'legs_per_match_in_knockout_stage', 'number_of_legs_in_final');
            $tournament = $tournament->where("type", $request->type)->wherein('id',$completedTournamentIds);
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $tournament = $tournament->where(function($query) use ($request) {
                    $query->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('type', 'LIKE', "%$request->search%");
                });
            }

            return parent::success($tournament->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
    public function tournamentUpcoming(Request $request) {
//       dd('s');
        $rules = ['search' => '', 'type' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $tournament1 = new Tournament();
            $tournament1 = $tournament1->select('id', 'name', 'type', 'number_of_players', 'number_of_teams_per_player', 'number_of_plays_against_each_team', 'number_of_players_that_will_be_in_the_knockout_stage', 'legs_per_match_in_knockout_stage', 'number_of_legs_in_final');
            $tournament1 = $tournament1->where("type", $request->type)->get();
//            dd($tournament->toArray());
            foreach ($tournament1 as $items):
                if(\App\TournamentFixture::where('tournament_id', '=', $items->id)->get()->isEmpty() != true):
                   if(\App\TournamentFixture::where('tournament_id', '=', $items->id)->Where('player_id_2_score', '=', null)->get()->isEmpty() === true):
                        $completedTournamentIds[] = $items->id;
                    endif; 
                endif;
            endforeach;
            if(!isset($completedTournamentIds))
                    return parent::error('No Upcoming Tournament has completed yet');
                    
            $tournament = new Tournament();
            $tournament = $tournament->select('id', 'name', 'type', 'number_of_players', 'number_of_teams_per_player', 'number_of_plays_against_each_team', 'number_of_players_that_will_be_in_the_knockout_stage', 'legs_per_match_in_knockout_stage', 'number_of_legs_in_final','created_at');
            $tournament = $tournament->where("type", $request->type)->wherein('id',$completedTournamentIds);
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $tournament = $tournament->where(function($query) use ($request) {
                    $query->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('type', 'LIKE', "%$request->search%");
                });
            }

            return parent::success($tournament->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }    

    public function addScoreToTournament(Request $request) {

        $rules = ['tournament_id' => 'required|exists:tournaments,id', 'player_id_1' => 'required|exists:users,id', 'player_id_1_team_id' => 'required', 'player_id_1_score' => 'required|integer', 'player_id_2' => 'required|exists:users,id', 'player_id_2_team_id' => 'required', 'player_id_2_score' => 'required|integer', 'stage' => 'required'];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }
        if (\App\TournamentPlayerTeam::where('tournament_id', $request->tournament_id)->where('player_id', $request->player_id_1)->where('team_id', $request->player_id_1_team_id)->get()->isEmpty())
            return parent::error('Players one are not available in the tournament');
        if (\App\TournamentPlayerTeam::where('tournament_id', $request->tournament_id)->where('player_id', $request->player_id_2)->where('team_id', $request->player_id_2_team_id)->get()->isEmpty())
            return parent::error('Players two are not available in the tournament');
//        if ($request->player_id_1_score == $request->player_id_2_score):
//            return parent::error('Equal scores will resultant into a Draw');
//        endif;

        $tournamentfixtured = \App\TournamentFixture::where('tournament_id', '=', $request->tournament_id)->where('player_id_1', '=', $request->player_id_1)->where('player_id_1_team_id', '=', $request->player_id_1_team_id)->where('player_id_2_team_id', '=', $request->player_id_2_team_id)->where('player_id_2', '=', $request->player_id_2)->where('stage', '=', $request->stage)->first();

        $fixtureForLegs = \App\TournamentFixture::where('tournament_id', '=', $request->tournament_id)->where('player_id_1', '=', $request->player_id_1)->where('player_id_1_team_id', '=', $request->player_id_1_team_id)->where('player_id_2_team_id', '=', $request->player_id_2_team_id)->where('player_id_2', '=', $request->player_id_2)->where('stage', '=', $request->stage)->get();

        $checkTournamentLegs = Tournament::where('id', '=', $request->tournament_id)->first();
        $knockoutLegs = $checkTournamentLegs->legs_per_match_in_knockout_stage;

        if (\App\TournamentFixture::where('tournament_id', '=', $request->tournament_id)->where('player_id_1', '=', $request->player_id_1)->where('player_id_1_team_id', '=', $request->player_id_1_team_id)->where('player_id_2_team_id', '=', $request->player_id_2_team_id)->where('player_id_2', '=', $request->player_id_2)->where('stage', '=', $request->stage)->get()->isEmpty() === true)
            return parent::error('fixture does not exist');

//        if ($tournamentfixtured->player_id_1_score != null || $tournamentfixtured->player_id_2_score != null):
//            return parent::error(['message' => 'Score already Updated']);
//        endif;
        // --------- if stage is round-1|| round-2 || no-round || final-------------
        if ($tournamentfixtured->stage == 'round-1' || $tournamentfixtured->stage == 'round-2' || $tournamentfixtured->stage == 'final' || $tournamentfixtured->stage == 'no-round'):
            if ($checkTournamentLegs->type == 'knockout' && $checkTournamentLegs->number_of_legs_in_final == '2' && $tournamentfixtured->stage == 'final'):
                
                foreach ($fixtureForLegs as $legs):
                    $i = 0;
                    if ($legs->player_id_1_score == null):
                        $input = $request->all();
                        $input['created_by'] = \Auth::id();
                        $input['updated_by'] = \Auth::id();
                        $TournamnetFixed = \App\TournamentFixture::findOrFail($legs->id);
                        $TournamnetFixed->fill($input);
                        $TournamnetFixed->save();
                        return parent::success(['message' => 'Scores has been successfully updated for final', 'tournamentFixtures' => $TournamnetFixed]);
                        $i++;
                    endif;
                endforeach;
                if($i == '0'):
                    return parent::success(['message' => 'Scores has already updated for final', 'tournamentFixtures' => $fixtureForLegs]);
                endif;
                
            else:
                $input = $request->all();
                $input['created_by'] = \Auth::id();
                $input['updated_by'] = \Auth::id();
                $TournamnetFixed = \App\TournamentFixture::findOrFail($tournamentfixtured->id);
                $TournamnetFixed->fill($input);
                $TournamnetFixed->save();
                return parent::success(['message' => 'Scores has been successfully Added', 'tournamentFixtures' => $TournamnetFixed]);
            endif;
        endif;

        //---------if stage is semi-final || Quarter-final || Pre-Quarter Final-----

        if ($tournamentfixtured->stage == 'semi-final' || $tournamentfixtured->stage == 'quarter-final' || $tournamentfixtured->stage == 'pre-quarter-final'):

            //Setting next fixture stage based on current stage    
            if ($request->stage == 'pre-quarter-final'):
                $stage = 'quarter-final';
            elseif ($request->stage == 'quarter-final'):
                $stage = 'semi-final';
            else:
                $stage = 'final';
            endif;

            //--------If knockout has couple of legs----------------------------------- 
            $player1score = 0;
            $player2score = 0;
            if ($knockoutLegs == '2'):
                $i = 1;
                foreach ($fixtureForLegs as $legs):
                    if ($legs->player_id_1_score == null):
                        $input = $request->all();
                        $input['created_by'] = \Auth::id();
                        $input['updated_by'] = \Auth::id();
                        $TournamnetFixed = \App\TournamentFixture::findOrFail($legs->id);
                        $TournamnetFixed->fill($input);
                        $TournamnetFixed->save();
                        if ($i == '1')
                            return parent::success(['message' => 'Scores has been successfully updated', 'tournamentFixtures' => $TournamnetFixed]);
                    endif;
                    $player1score += $legs->player_id_1_score;
                    $player2score += $legs->player_id_2_score;
                    $i++;
                endforeach;
                if ($player1score > $player2score):
                    if (\App\TournamentFixture::where([['tournament_id', '=', $request->tournament_id], ['stage', '=', $stage], ['player_id_2', '=', NULL], ['player_id_2_team_id', '=', NULL]])->get()->isEmpty() === true):
                        $fixture[] = ['tournament_id' => $request->tournament_id, 'player_id_1' => $request->player_id_1, 'player_id_1_team_id' => $request->player_id_1_team_id, 'stage' => $stage];
                        \App\TournamentFixture::insert($fixture);
                        return parent::success(['message' => 'Scores has been successfully Added and fixture generated for ' . $stage]);
                    else:
                        $input1['player_id_2'] = $request->player_id_1;
                        $input1['player_id_2_team_id'] = $request->player_id_1_team_id;
                        $input1['stage'] = $stage;
                        $input1['created_by'] = \Auth::id();
                        $input1['updated_by'] = \Auth::id();
                        $TournamnetFinal = \App\TournamentFixture::where([['tournament_id', '=', $request->tournament_id], ['stage', '=', $stage], ['player_id_2', '=', NULL]])->first();
                        $TournamnetFinal->fill($input1);
                        $TournamnetFinal->save();


                        //creating double fixtures for auto-generated semi-finals  
                        $fixture[] = ['tournament_id' => $TournamnetFinal->tournament_id, 'player_id_1' => $TournamnetFinal->player_id_1, 'player_id_1_team_id' => $TournamnetFinal->player_id_1_team_id, 'player_id_2' => $TournamnetFinal->player_id_2, 'player_id_2_team_id' => $TournamnetFinal->player_id_2_team_id, 'stage' => $stage];
                        if ($stage != 'final'):
                            \App\TournamentFixture::insert($fixture);
                        endif;
                        $tournamentdetails = MyModel::where('id', '=', $TournamnetFinal->tournament_id)->first();
                        if ($tournamentdetails->number_of_legs_in_final == '2' && $stage == 'final'):
                            \App\TournamentFixture::insert($fixture);
                        endif;
                        //ends            
                        return parent::success(['message' => 'Scores has been successfully Added and 2 fixture generated for ' . $stage, 'tournamentFixtures' => $TournamnetFinal]);
                    endif;
                else:
                    if (\App\TournamentFixture::where([['tournament_id', '=', $request->tournament_id], ['stage', '=', $stage], ['player_id_2', '=', NULL], ['player_id_2_team_id', '=', NULL]])->get()->isEmpty() === true):
                        $fixture[] = ['tournament_id' => $request->tournament_id, 'player_id_1' => $request->player_id_2, 'player_id_1_team_id' => $request->player_id_2_team_id, 'stage' => $stage];
                        \App\TournamentFixture::insert($fixture);
                        return parent::success(['message' => 'Scores has been successfully Added and fixture generated for ' . $stage]);
                    else:
                        $input2['player_id_2'] = $request->player_id_2;
                        $input2['player_id_2_team_id'] = $request->player_id_2_team_id;
                        $input2['stage'] = $stage;
                        $input2['created_by'] = \Auth::id();
                        $input2['updated_by'] = \Auth::id();
                        $TournamnetFinal = \App\TournamentFixture::where([['tournament_id', '=', $request->tournament_id], ['stage', '=', $stage], ['player_id_2', '=', NULL]])->first();
                        $TournamnetFinal->fill($input2);
                        $TournamnetFinal->save();

                        //creating double fixtures for auto-generated semi-finals        
                        $fixture[] = ['tournament_id' => $TournamnetFinal->tournament_id, 'player_id_1' => $TournamnetFinal->player_id_1, 'player_id_1_team_id' => $TournamnetFinal->player_id_1_team_id, 'player_id_2' => $TournamnetFinal->player_id_2, 'player_id_2_team_id' => $TournamnetFinal->player_id_2_team_id, 'stage' => $stage];
                        if ($stage != 'final'):
                            \App\TournamentFixture::insert($fixture);
                        endif;

                        $tournamentdetails = MyModel::where('id', '=', $TournamnetFinal->tournament_id)->first();
                        if ($tournamentdetails->number_of_legs_in_final == '2' && $stage == 'final'):
                            \App\TournamentFixture::insert($fixture);
                        endif;
                        //ends            
                        return parent::success(['message' => 'Scores has been successfully Added and fixture generated for ' . $stage, 'tournamentFixtures' => $TournamnetFinal]);
                    endif;
                endif;
            else:
                //--------if number of legs per match in knockout stage is 1----------------
                $input = $request->all();
                $input['created_by'] = \Auth::id();
                $input['updated_by'] = \Auth::id();
                $TournamnetFixed = \App\TournamentFixture::findOrFail($tournamentfixtured->id);
                $TournamnetFixed->fill($input);
                $TournamnetFixed->save();

                $player1score = $request->player_id_1_score;
                $player2score = $request->player_id_2_score;
//            endif;

                if ($player1score > $player2score):
                    if (\App\TournamentFixture::where([['tournament_id', '=', $request->tournament_id], ['stage', '=', $stage], ['player_id_2', '=', NULL], ['player_id_2_team_id', '=', NULL]])->get()->isEmpty() === true):
                        $fixture[] = ['tournament_id' => $request->tournament_id, 'player_id_1' => $request->player_id_1, 'player_id_1_team_id' => $request->player_id_1_team_id, 'stage' => $stage];
                        \App\TournamentFixture::insert($fixture);
                        return parent::success(['message' => 'Scores has been successfully Added and fixture generated for ' . $stage]);
                    else:
                        $input1['player_id_2'] = $request->player_id_1;
                        $input1['player_id_2_team_id'] = $request->player_id_1_team_id;
                        $input1['stage'] = $stage;
                        $input1['created_by'] = \Auth::id();
                        $input1['updated_by'] = \Auth::id();
                        $TournamnetFinal = \App\TournamentFixture::where([['tournament_id', '=', $request->tournament_id], ['stage', '=', $stage], ['player_id_2', '=', NULL]])->first();
                        $TournamnetFinal->fill($input1);
                        $TournamnetFinal->save();
                        return parent::success(['message' => 'Scores has been successfully Added and fixture generated for ' . $stage, 'tournamentFixtures' => $TournamnetFinal]);
                    endif;
                else:
                    if (\App\TournamentFixture::where([['tournament_id', '=', $request->tournament_id], ['stage', '=', $stage], ['player_id_2', '=', NULL], ['player_id_2_team_id', '=', NULL]])->get()->isEmpty() === true):
                        $fixture[] = ['tournament_id' => $request->tournament_id, 'player_id_1' => $request->player_id_2, 'player_id_1_team_id' => $request->player_id_2_team_id, 'stage' => $stage];
                        \App\TournamentFixture::insert($fixture);
                        return parent::success(['message' => 'Scores has been successfully Added and fixture generated for ' . $stage, 'tournamentFixtures' => $TournamnetFixed]);
                    else:
                        $input2['player_id_2'] = $request->player_id_2;
                        $input2['player_id_2_team_id'] = $request->player_id_2_team_id;
                        $input2['stage'] = $stage;
                        $input2['created_by'] = \Auth::id();
                        $input2['updated_by'] = \Auth::id();
                        $TournamnetFinal = \App\TournamentFixture::where([['tournament_id', '=', $request->tournament_id], ['stage', '=', $stage], ['player_id_2', '=', NULL]])->first();
                        $TournamnetFinal->fill($input2);
                        $TournamnetFinal->save();
                        return parent::success(['message' => 'Scores has been successfully Added and fixture generated for ' . $stage, 'tournamentFixtures' => $TournamnetFinal]);
                    endif;
                endif;
            endif;
        endif;
        //-------------------------------------------code ends-------------------------------------       
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
            $teams = $teams->select('id', 'team_name', 'league_name', 'image')->orderBy('team_name');
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $teams = $teams->where(function($query) use ($request) {
                    $query->where('team_name', 'LIKE', "$request->search%")
                            ->orWhere('league_name', 'LIKE', "$request->search%");
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
//            $myfriends = new \App\UserFriend();
//            $myfriends = $myfriends->select('id', 'user_id', 'friend_id');
//            $myfriends = $myfriends->where(function($query) use ($request) {
//                $query->where('user_id', \Auth::id());
//                $query->orWhere('friend_id', \Auth::id());
//            });
//            $myfriends = $myfriends->where("status", "accepted")->pluck('user_id')->get()->toArray();


            $players = new User();

            $myfriends = User::where('id', '!=', \Auth::id())->wherein('id', \DB::table('user_friends')->where('status', 'accepted')->where('user_id', \Auth::id())->orWhere('friend_id', \Auth::id())->pluck('friend_id'))->get()->toArray();

//            dd($myfriends);
            $players = $players->select('id', 'username', 'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'image', 'xbox_id', 'ps4_id', 'youtube_id', 'twitch_id', 'is_login', 'is_notify', 'params', 'state')->whereHas(
                    'roles', function($q) {
                $q->where('name', 'Customer');
            }
            );
            $players = $players->where('id', '!=', \Auth::id());
            $players = $players->whereNotIn('id', \DB::table('user_friends')->where('status', 'accepted')->where('user_id', \Auth::id())->orWhere('friend_id', \Auth::id())->pluck('friend_id'))->orderBy('id', 'DESC')->get()->toArray();
            $players = array_merge($myfriends, $players);

//            $players = $myfriends;
//            $perPage = isset($request->limit) ? $request->limit : 20;
//            if (isset($request->search)) {
//                $players = $players->where(function($query) use ($request) {
//                    $query->where('username', 'LIKE', "%$request->search%")
//                            ->orWhere('email', 'LIKE', "%$request->search%");
//                });
//            }
//            $tournament = $tournament->with(['players']);
//            $players = $players->orderBy('id', 'DESC');
//            dd($result);
            return parent::success($players);
//            return parent::success($players->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function findFriend(Request $request) {
//        dd(\Auth::id());
        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
//            $myfriends = new \App\UserFriend();
//
//            $myfriends = $myfriends->Where('user_id', \Auth::id());
//
//            $myfriends = $myfriends->where("status", "accepted")->pluck('friend_id')->toArray();

            $players = new User();

//            $players = $players->select('id', 'username', 'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'image', 'xbox_id', 'ps4_id', 'youtube_id', 'twitch_id', 'is_login', 'is_notify', 'params', 'state')->whereNotIn('id', $myfriends);

            $players = $players->select('id', 'username', 'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'image', 'xbox_id', 'ps4_id', 'youtube_id', 'twitch_id', 'is_login', 'is_notify', 'params', 'state');

//            $players = $players->where("id", '!=', \Auth::id());
            $players = $players->wherein('id', \DB::table('role_user')->where('role_id', '2')->pluck('user_id'));

            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)) {
                $players = $players->where(function($query) use ($request) {
                    $query->where('username', 'LIKE', "%$request->search%")
                            ->orWhere('first_name', 'LIKE', "%$request->search%")
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
        parent::pushNotifications(['title' => 'Friend Request', 'body' => 'You have a friend request', 'data' => ['target_id' => \Auth::id(), 'target_model' => 'UserFriend', 'data_type' => 'FriendRequest']], $request->friend_id, TRUE);

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

            if ($request->status == 'accepted'):
                parent::pushNotifications(['body' => 'Your Friend Request has been accepted', 'data' => ['target_id' => \Auth::id(), 'target_model' => 'UserFriend', 'data_type' => 'FriendRequest']], $request->friend_id, TRUE);
            endif;


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
//        $myfriends = $myfriends->where("user_id", \Auth::id())->where("status", "accepted")->get();
        $myfriends = $myfriends->where(function($query) use ($request) {
            $query->where('user_id', \Auth::id());
            $query->orWhere('friend_id', \Auth::id());
        });
        $myfriends = $myfriends->where("status", "accepted")->get();
//        dd($myfriends->get()->toArray());
        $friendsChannelId = [];
        foreach ($myfriends as $friends):
            $id = ($friends->friend_id == \Auth::id()) ? $friends->user_id : $friends->friend_id;
            $friendsData = User::select('twitch_id')->where("id", $id)->get();
            foreach ($friendsData as $data):
                $friendsChannelId[] = $data->twitch_id;
            endforeach;
        endforeach;
//        dd($friendsChannelId);
        //ends

        $a = [];
        foreach ($friendsChannelId as $chanelId):
//            dd($chanelId);
            $video = self::getCurl('https://api.twitch.tv/kraken/channels/' . $chanelId . '/videos');

            $a = (isset($video['videos']['0'])) ? $video['videos']['0'] : [];
        endforeach;
//        dd(json_encode($a));
        return parent::success($a);
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
//            \App\Notification::whereIn('id', $model->get()->pluck('id'))->update(['is_read' => '1']);

            $model = $model->where('target_id', \Auth::id())->select('id', 'title', 'body', 'data', 'target_id', 'is_read', 'params', 'state');
            $model = $model->orderBy('id', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function notificationRead(Request $request) {
//        dd('s');
        $rules = ['notification_id' => '', 'sender_id' => 'required|exists:users,id', 'type' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());

            $model = new \App\Notification();

            $perPage = isset($request->limit) ? $request->limit : 20;

            $notificationread = \App\Notification::where('title', $request->type)->where('target_id', \Auth::id());
            $not = $notificationread->get();
            foreach ($not as $data):
                if ($data->data->target_id == $request->sender_id):
                    $notificationId[] = $data->id;
                endif;
            endforeach;
            $notificationread = \App\Notification::whereIn('id', $notificationId)->update(['is_read' => '1']);
            return parent::success(['message' => 'Notification mark Read', 'notification' => $notificationread]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function notificationCount(Request $request) {

        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $model = new \App\Notification();
            $model = $model->where('target_id', \Auth::id())->where('is_read', '0')->count();
            return parent::success(['notification_count' => $model]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
