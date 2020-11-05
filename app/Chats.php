<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    protected $table = 'chats';
    protected $fillable = ['messages','image','audio','sender','receiver','is_read'];
}