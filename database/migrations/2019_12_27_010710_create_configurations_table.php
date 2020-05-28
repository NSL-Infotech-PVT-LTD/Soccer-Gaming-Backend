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
