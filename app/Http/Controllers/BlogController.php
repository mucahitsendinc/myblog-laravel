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

        $check=DB::table('settings')->where('setting','contact')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'İletişim formu yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin.'
            ],402);
        }

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
                        ->where(function($query) use ($ip,$email,$message){
                            $query->where('remote_ip',$ip)
                                ->orwhere('email',$email)
                                ->orwhere('message',$message);
                        })
                        ->where('status',0)
                        ->get();
        if(count($control)>0){
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
    public function createPost(Request $request){
        $title=$request->title??'';
        $desc=$request->description??'';
        $url=$request->url??'';
        $image=$request->image??'';
        $content=$request->content??'';
        $tags=$request->tags??[];

        $tags=$tags!=[] ? json_decode($tags) : [];

        if(strlen($title)<3 || strlen($title)>255){ $error="Başık uzun/kısa!"; }
        else if(strlen($desc)>255){ $error="Açıklama uzun!"; }
        else if(strlen($url)<3 || strlen($url)>255){ $error="Url uzun/kısa!"; }
        else if(strlen($image)<3 || strlen($image)>255){ $error="Resim uzun/kısa!"; }
        else if(strlen($content)<10){ $error="İçerik uzun/kısa!"; }

        if(isset($error)){
            return response()->json([
                'status'=>'error',
                'message'=>$error
            ],402);
        }

        $control=DB::table('posts')
                        ->where(function($query) use ($title,$url){
                            $query->where('title',$title)
                                ->orwhere('url',$url);
                        })
                        ->where('status',0)
                        ->get();

        if(count($control)>0){
            return response()->json([
                'status'=>'error',
                'message'=>'Bu başlık/url farklı bir postda aktif olarak kullanılıyor'
            ],402);
        }

        $save=DB::table('posts')->insert([
            'title'=>$title,
            'description'=>$desc,
            'url'=>$url,
            'image'=>$image,
            'content'=>$content,
            'status'=>0,
            'create_date'=>date('Y-m-d H:i:s'),
            'update_date'=>date('Y-m-d H:i:s')
        ]);

        if($save){

            foreach ($tags as $tag){

            }

        }

        //return 123;
    }
}
