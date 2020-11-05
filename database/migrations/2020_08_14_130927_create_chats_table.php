<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('chatId');
            $table->mediumText('messages')->nullable();
            $table->string('image')->nullable();
            $table->string('audio')->nullable();
            $table->bigInteger('sender')->unsigned();
            $table->bigInteger('receiver')->unsigned();
            $table->enum('is_read',['0','1']);
            $table->timestamps();

            $table->foreign('sender')->references('id')->on('users')->onDelete('NO ACTION');
            $table->foreign('receiver')->references('id')->on('users')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
