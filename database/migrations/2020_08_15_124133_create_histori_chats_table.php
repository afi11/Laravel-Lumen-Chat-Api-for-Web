<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histori_chats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id_chat')->unsigned();
            $table->bigInteger('another_user_id_chat')->unsigned();
            $table->datetime('last_chat_at');

            $table->foreign('user_id_chat')->references('id')->on('users')->onDelete('NO ACTION');
            $table->foreign('another_user_id_chat')->references('id')->on('users')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histori_chats');
    }
}
