<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function testapi(Request $request)
    {
        $test1 = $request->test1;
        return response()->json(['data' => $test1]);
    }

    public function getChat($receiver)
    {
        return \App\Chats::where('sender',\Auth::id())
            ->where('receiver',$receiver)
            ->orWhere('sender',$receiver)
            ->where('receiver',\Auth::id())
            ->get();
    }

    public function readMessage($receiver)
    {
        return \App\Chats::where('receiver',\Auth::id())
            ->where('sender',$receiver)
            ->update(['is_read' => '1']);
    }

    public function sendMessage(Request $request)
    {
        // cek histori message
        $check_history = \App\HistoriChat::where('user_id_chat',\Auth::id())
            ->where('another_user_id_chat',$request->receiver)
            ->orWhere('user_id_chat',$request->receiver)
            ->where('another_user_id_chat',\Auth::id())
            ->count();
        if(!$check_history > 0){
            $input = new \App\HistoriChat();
            $input->user_id_chat = \Auth::id();
            $input->another_user_id_chat = $request->receiver;
            $input->last_chat_at = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input->save();

            $input2 = new \App\HistoriChat();
            $input2->user_id_chat = $request->receiver;
            $input2->another_user_id_chat = \Auth::id();
            $input2->last_chat_at = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input2->save();
        }
        if($request->audio != ''){
            $exploded = explode(',',$request->audio);
            $decoded = base64_decode($exploded[1]);
            $fileName = \Illuminate\Support\Str::random(32).".webm";
            $path = public_path().'/audios/'.$fileName;
            file_put_contents($path,$decoded);
            $message = \App\Chats::create($request->except(['image','audio'])+
                ['sender' => \Auth::id(),
                'audio' => $fileName,
                'is_read' => '0']);
            return $message;
        }
        if($request->image != ''){
            $exploded = explode(',',$request->image);
            $decoded = base64_decode($exploded[1]);
            if(FindCharacter($exploded[0], 'jpeg'))
                $extension = 'jpg';
            else
                $extension = 'png';
            $fileName = \Illuminate\Support\Str::random(32).'.'.$extension;
            $path = public_path().'/messages/'.$fileName;
            file_put_contents($path,$decoded);
            $message = \App\Chats::create($request->except(['image','audio'])+
                ['sender' => \Auth::id(),
                'image' => $fileName,
                'is_read' => '0'
            ]);
            return $message;
        }else{
            $message = \App\Chats::create($request->except(['image','audio'])+
                ['sender' => \Auth::id(),'is_read' => '0']
            );
            return $message;
        }
    }
}