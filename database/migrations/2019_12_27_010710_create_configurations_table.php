<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigurationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('configurations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('terms_and_conditions_customer');
            $table->longText('private_policy_customer');
            $table->longText('about_us_customer');
            $table->string('facebook_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitch')->nullable();
            $table->string('google_play_url')->nullable();
            $table->string('app_store_url')->nullable();
            App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('configurations');
    }

}
