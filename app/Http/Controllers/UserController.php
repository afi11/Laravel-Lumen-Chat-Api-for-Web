<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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
            $code = 200;
            $token = $this->respondWithToken($authorized);
            $output = [
                'code' => $code,
                'message' => 'User is loggin',
                'id_user' => \Auth::id(),
                'token' => $token  
            ];
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
        $user->email = $request->email;
        if($request->password != ""){
            $user->password = app('hash')->make($request->password);
        }
        if($request->photo != "default.jpg"){
            if($request->old_photo != "default.jpg"){
                File::delete(public_path().'/profil/'.$request->old_photo);
            }
            $exploded = explode(',',$request->photo);
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
        $update = $user->save();
        return $update;
    }

}