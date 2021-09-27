<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ApiController extends Controller
{
    //

    public function login(Request $request){
//        $email=$request->json()->email;
        $username=$request->username;
        $password=$request->password;
        if(Auth::attempt(['email'=>$username,'password'=>$password])){
            $user = Auth::user();
            $success['token']=$user->createToken()->accessToken;
            return response()->json([
                'success'=>$success,
                'message'=>'Giriş başarılı'
            ],200);
        }else{
            return response()->json(['success'=>false,'message'=>'Giriş bilgileri hatalı'],401);
        }
    }

    public function register(Request $request){
        $insert=DB::table('users')->insert([
                                'name'=>'mucahitsndc',
                                'email'=>'me@mucahitsendinc.com',
                                'password'=>'Mucahitsndc52.',
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                                ]);
        return $request->all();
    }






}
