<?php

use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            'terms_and_conditions_customer' => 'terms and conditions',
            'private_policy_customer' => 'privacy policy',
            'about_us_customer' => 'about us',
            'facebook_url' => 'https://www.facebook.com/',
            'youtube_url' => 'https://www.youtube.com/',
            'instagram_url' => 'https://www.instagram.com/',
            'twitch' => 'https://www.twitch.tv/',
            'google_play_url' => 'https://play.google.com/',
            'app_store_url' => 'https://www.apple.com/',
        ];
        $configuration = App\Configuration::create($data);
    }

}
