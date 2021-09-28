<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class BlogController extends Controller
{
    //
    public function getMainInfo(){
        try {
            $getInfos=DB::table('main_info')->where('title','!=','access')->where('status',0)->get(['title','info']);
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],500);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$getInfos
        ],200);
    }
    public function getAboutUs(){
        try {
            $getInfos=DB::table('about_us')->where('status',0)->get(['title','list']);
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],500);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$getInfos
        ],200);
    }
    public function sendContact(Request $request){

        $name=$request->name;
        $surname=$request->surname;
        $email=$request->email;
        $message=$request->message;
        $ip=$_SERVER['REMOTE_ADDR'];

        if(strlen($name)<2 || strlen($name)>25){ $error="Ad çok kısa/uzun!"; }
        else if(strlen($surname)<2 || strlen($surname)>25){ $error="Soyad çok kısa/uzun!"; }
        else if(strlen($message)<10 || strlen($message)>1500){ $error=" Mesaj kısa/uzun!"; }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $error="Geçersiz e-posta!"; }

        if(isset($error)){
            return response()->json([
                'status'=>'error',
                'message'=>$error
            ],401);
        }

        $control=DB::table('contact')
                        ->where('remote_ip',$ip)
                        ->orwhere('email',$email)
                        ->orwhere('message',$message)
                        ->get();
        if(!empty($control)){
            return response()->json([
                'status'=>'error',
                'message'=>'Son gönderdiğiniz mesajınız incelenene kadar yeni bir mesaj gönderemezsiniz.'
            ],401);
        }

        $save=DB::table('contact')->insert([
            'name'=>$name.' '.$surname,
            'email'=>$email,
            'message'=>$message,
            'remote_ip'=>$ip,
            'status'=>0,
            'create_date'=>date('Y-m-d H:i:s'),
            'update_date'=>date('Y-m-d H:i:s')
        ]);

        if($save){
            return response()->json([
                'status'=>'success',
                'message'=>'Mesaj başarı ile iletildi'
            ],200);
        }

        return response()->json([
            'status'=>'error',
            'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin.'
        ],401);


    }
}
