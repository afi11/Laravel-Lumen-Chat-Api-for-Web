<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

    public function __construct() {
        
    }

    public function register(Request $request)
    {
        // validate data
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);   

        $input = $request->only('name','email','password');

        try {

            $email = $input['email'];
            $name = $input['name'];

            $data = compact('email','name');
            Mail::send("mail", $data, function($message) use ($email, $name) {
                $message->to($email, $name)->subject("Account Verifications");
                $message->from("ahmadfatakhulafifudin@gmail.com","IM | Instant Message");
            });

            $user = new User;
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->photo = 'default.jpg';
            $password = $input['password'];

            $user->password = app('hash')->make($password);

            if($user->save()){
                
                $code = 200;
                $ouput = [
                    'user' => $user,
                    'code' => $code,
                    'message' => 'User created successfully'
                ];
            }else{
                $code = 500;
                $ouput = [
                    'code' => $code,
                    'message' => 'An error occured while creating user'
                ];
            }
        }catch (Exception $e){
            $code = 500;
            $ouput = [
                'code' => $code,
                'message' => 'An error occured while creating user'
            ];
        }

        return response()->json($ouput, $code);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $input = $request->only('email','password');
        $authorized = Auth::attempt($input);
        if(!$authorized){
            $code = 401;
            $output = [
                'code' => $code,
                'message' => 'User is not authorized'
            ];
        }else{
            if(\Auth::user()->verified_at != null){
                $code = 200;
                $token = $this->respondWithToken($authorized);
                $output = [
                    'code' => $code,
                    'message' => 'User is loggin',
                    'id_user' => \Auth::id(),
                    'token' => $token  
                ];
            }else{
                $code = 200;
                $output = [
                    'code' => 1728,
                    'message' => "Email akun belum melakukan verifikasi",
                ];
            }
        }

        return response()->json($output, $code);
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function updateUser($id)
    {
        $user = User::find($id);
        $user->login_at = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $update = $user->save();
        return $update;
    }

    public function verifiyUser(Request $request)
    {
        $email = $request->email;
        $count = User::where('email',$email)->count();
        if($count > 0){
            $update = User::where('email',$email)
                ->update(['verified_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')]);
            return $update;
        }else {
            return "Email belum terdaftar";
        }
        
    }

    public function upUserClose($id)
    {
        $user = User::find($id);
        $user->login_at = null;
        $update = $user->save();
        return $update;
    }

    public function update(Request $request)
    {
        $user = User::find(\Auth::id());
        $user->name = $request->name;
        if($request->new_photo != ""){
            if($request->photo != "default.jpg"){
                File::delete(public_path().'/profil/'.$request->photo);
                $exploded = explode(',',$request->new_photo);
                $decoded = base64_decode($exploded[1]);
                if(FindCharacter($exploded[0], 'jpeg'))
                    $extension = 'jpg';
                else
                    $extension = 'png';
                    $fileName = \Illuminate\Support\Str::random(32).'.'.$extension;
                    $path = public_path().'/profil/'.$fileName;
                    file_put_contents($path,$decoded);
                    $user->photo = $fileName;
            }else {
                $exploded = explode(',',$request->new_photo);
                $decoded = base64_decode($exploded[1]);
                    if(FindCharacter($exploded[0], 'jpeg'))
                        $extension = 'jpg';
                    else
                        $extension = 'png';
                $fileName = \Illuminate\Support\Str::random(32).'.'.$extension;
                $path = public_path().'/profil/'.$fileName;
                file_put_contents($path,$decoded);
                $user->photo = $fileName;
            }
        }
        $update = $user->save();
        return $update;
    }

    public function resetPassword(Request $request) 
    {
        $update = User::where('email',$request->email)
            ->update([
                'password' => app('hash')->make($request->password),
            ]);
        return $update;
    }

    public function sendEmailToResetPass(Request $request)
    {
        $email = $request->email;
        $cekuser = User::where('email',$email)->count();
        if($cekuser > 0){
            $user = User::where('email',$email)->first();
            $name = $user->name;
            $data = array("user" => $user);
            Mail::send("mailreset",$data,function($message) use ($email, $name) {
                $message->to($email, $name)->subject("Reset Password");
                $message->from("ahmadfatakhulafifudin@gmail.com","IM | Instant Message");
            });
            $output = [
                "code" => 321,
                "message" => "Akun dengan email tersebut berhasil ditemukan, silahkan periksa email anda untuk mendapat link reset password.",
            ];
        }else {
            $output = [
                "code" => 123,
                "message" => "Akun dengan email tersebut tidak ditemukan.",
            ];
        }
        return response()->json($output);
    }

    public function sendEmail($email,$name)
    {
        $data = array("name" => $name, "email" => $email);
        Mail::send("mail", $data, function($message) {
            $message->to($email, $name)->subject("Account Verifications");
            $message->from("ahmadfatakhulafifudin@gmail.com","Afi");
        });
        return true;
    }
}