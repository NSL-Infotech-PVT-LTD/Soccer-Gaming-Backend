<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');

// $table->string('local_message_id')->nullable();
// $table->string('message_id')->nullable();
            $table->string('team_id')->nullable();
            $table->string('player_id')->nullable();
// $table->bigInteger('reply_id')->default(0);
// $table->bigInteger('sender_id')->unsigned()->index();
// $table->foreign('sender_id')->references('id')->on('users');

            $table->bigInteger('sender_id')->default(0);
            $table->bigInteger('receiver_id')->default(0);

            $table->text('attachment')->nullable();
            $table->text('message')->nullable();
            $table->enum('type', ['text', 'attachment'])->default('text');
            $table->bigInteger('is_read_customer')->default(0);
            $table->enum('replied_by_customer', [0, 1])->default(0);
// $table->text('details')->nullable();
            App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('messages');
    }

}
