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
            $table->string('terms_and_conditions_customer');
            $table->string('terms_and_conditions_service_provider')->nullable();
            $table->string('private_policy_customer');
            $table->string('private_policy_service_provider')->nullable();
            $table->string('about_us_customer');
            $table->string('about_us_service_provider')->nullable();
            $table->string('facebook_url')->nullable()->after('about_us_service_provider');
            $table->string('youtube_url')->nullable()->after('facebook_url');
            $table->string('instagram_url')->nullable()->after('youtube_url');
            $table->string('twitch')->nullable()->after('instagram_url');
            $table->string('google_play_url')->nullable()->after('twitch');
            $table->string('app_store_url')->nullable()->after('google_play_url');
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
