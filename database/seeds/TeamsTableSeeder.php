<?php

use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
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

}
