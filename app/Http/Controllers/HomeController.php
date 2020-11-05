<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return response()->json('OK');
    }

    public function getprofil()
    {
        return \App\User::where('id',\Auth::id())->get();
    }

    public function letMessageWithPeople()
    {
        return \App\User::where('id','<>',\Auth::id())->get();
    }

    // For People not message
    public function latestMessage()
    {
        return \App\HistoriChat::join('users','users.id','=','histori_chats.another_user_id_chat')
            ->where('histori_chats.user_id_chat',\Auth::id())
            ->orderBy('histori_chats.last_chat_at','DESC')
            ->get();
    }

    public function countUnreadMessage($sender,$receiver)
    {
        return \App\Chats::where('sender',$sender)
            ->where('receiver',$receiver)->where('is_read','0')->count();
    }

    public function unReadMessage($sender)
    {
        return \App\Chats::join('users','users.id','=','chats.sender')
            ->where('chats.sender',$sender)
            ->where('chats.is_read','0')
            ->orderBy('chats.created_at','DESC')
            ->first();
    }

    // For Message
    public function getLatestMessage($sender,$receiver)
    {
        return \App\Chats::where('sender',$sender)
            ->where('receiver',$receiver)
            ->orWhere('sender',$receiver)
            ->where('receiver',$sender)
            ->orderBy('created_at','DESC')
            ->first();
    }

}