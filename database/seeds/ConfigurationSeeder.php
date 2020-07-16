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
        ];
        $configuration = App\Configuration::create($data);
    }

}
