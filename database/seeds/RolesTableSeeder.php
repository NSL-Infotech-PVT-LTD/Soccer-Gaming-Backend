<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

       DB::table('roles')->insert([
           [
              'name' => 'Super-Admin',
              'label' => 'Super-Admin',
              'created_at' => \Carbon\Carbon::now(),
              'updated_at' => \Carbon\Carbon::now(),
            ],
           [
              'name' => 'Customer',
              'label' => 'customer',
              'created_at' => \Carbon\Carbon::now(),
              'updated_at' => \Carbon\Carbon::now(),
            ],
        ]);

    }

}
