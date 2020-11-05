<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class HistoriChat extends Model
{
    protected $table = 'histori_chats';
    protected $fillable = ['user_id_chat','another_user_id_chat','last_chat_at'];
    public $timestamps = false;
}