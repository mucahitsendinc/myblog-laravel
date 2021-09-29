<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Symfony\Component\VarDumper\Cloner\Data;

class BlogController extends Controller
{
    //
    public function sendContact(Request $request){

        $check=DB::table('settings')->where('setting','contact')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'İletişim formu yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
            ],403);
        }

        $name=$request->name;
        $surname=$request->surname;
        $email=$request->email;
        $message=$request->message;
        $ip=$_SERVER['REMOTE_ADDR'];

        if(strlen($name)<2 || strlen($name)>25){ $error="Ad çok kısa/uzun"; }
        else if(strlen($surname)<2 || strlen($surname)>25){ $error="Soyad çok kısa/uzun"; }
        else if(strlen($message)<10 || strlen($message)>1500){ $error=" Mesaj kısa/uzun"; }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $error="Geçersiz e-posta"; }

        if(isset($error)){
            return response()->json([
                'status'=>'error',
                'message'=>$error
            ],403);
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
                'message'=>'Son gönderdiğiniz mesajınız incelenene kadar yeni bir mesaj gönderemezsiniz'
            ],403);
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
            'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
        ],403);


    }
    public function sendComment(Request $request){

        $crypt=new DataCrypter;

        $check=DB::table('settings')->where('setting','comment')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Yorum formu yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
            ],403);
        }

        $post=$request->post??'';
        $name=$request->name;
        $surname=$request->surname;
        $email=$request->email??'';
        $comment=$request->comment??'';
        $ip=$_SERVER['REMOTE_ADDR'];

        $checkPost=DB::table('posts')->where('id',$crypt->crypt_router($post,false,'decode'))->where('status',0)->first(['id']);

        if(empty($checkPost) || $checkPost->id!=$crypt->crypt_router($post,false,'decode')){
            return response()->json([
                'status'=>'error',
                'message'=>'Gönderi yayından kaldırıldı veya bulunamadığı için yorumunuz gönderilemedi'
            ],403);
        }

        $post=$checkPost->id;

        if(strlen($name)<2 || strlen($name)>25){ $error="Ad çok kısa/uzun"; }
        else if(strlen($surname)<2 || strlen($surname)>25){ $error="Soyad çok kısa/uzun"; }
        else if(strlen($comment)<10 ||strlen($comment)>255){ $error="Yorum kısa/uzun"; }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $error="Geçersiz e-posta"; }

        if(isset($error)){
            return response()->json([
                'status'=>'error',
                'message'=>$error
            ],403);
        }

        $control=DB::table('comments')
            ->where(function($query) use ($ip,$email,$comment){
                $query->where('remote_ip',$ip)
                    ->orwhere('email',$email)
                    ->orwhere('comment',$comment);
            })
            ->where('status',0)
            ->where('post_id',$post)
            ->get();

        if(count($control)>0){
            return response()->json([
                'status'=>'error',
                'message'=>'Her gönderiye yalnızca bir yorum yapabilirsiniz'
            ],403);
        }

        $save=DB::table('comments')->insert([
            'post_id'=>$post,
            'name'=>$name.' '.$surname,
            'email'=>$email,
            'comment'=>$comment,
            'remote_ip'=>$ip,
            'status'=>0,
            'create_date'=>date('Y-m-d H:i:s'),
            'update_date'=>date('Y-m-d H:i:s')
        ]);

        if($save){
            return response()->json([
                'status'=>'success',
                'message'=>'Yorum başarı ile iletildi'
            ],200);
        }

        return response()->json([
            'status'=>'error',
            'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
        ],403);


    }
    public function getMainInfo(){
        try {
            $getInfos=DB::table('main_info')->where('title','!=','access')->where('status',0)->get(['title','info']);
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],403);
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
            ],403);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$getInfos
        ],200);
    }
    public function getPosts(){
        $crypt=new DataCrypter;
        try {
            $getPosts=DB::table('posts')->where('status',0)->orderBy('id', 'desc')->get([
                'title','description','url','image','content','id'
            ]);

            $posts=[];
            $i=0;
            foreach($getPosts as $post){
                $i++;
                $newPost=[
                    'unid'=>md5($post->title).'id-'.$i,
                    'id'=>$crypt->crypt_router($post->id."",false,'encode'),
                    'title'=>$post->title,
                    'description'=>$post->description,
                    'url'=>$post->url,
                    'image'=>$post->image,
                    'content'=>$post->content
                ];
                array_push($posts,$newPost);
            }
        }catch(\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],403);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$posts
        ],200);
    }

    public function getComments(Request $request){
        $crypt=new DataCrypter;

        $post=$request->post;


        try {
            $comments=DB::table('comments')->where('status',0)->where('post_id',$crypt->crypt_router($post,false,'decode'))->orderBy('id', 'desc')->get([
                'name','email','comment','create_date'
            ]);
            $newComments=[];
            $i=0;
            foreach($comments as $comment){
                $i++;
                $newComment=[
                    'uid'=> md5($comment->name).'id-'.$i,
                    'name'=>$comment->name,
                    'comment'=>$comment->comment,
                    'date'=>$comment->create_date
                ];
                array_push($newComments,$newComment);
            }
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],403);
        }

        return response()->json([
            'status'=>'success',
            'data'=>$newComments
        ],200);
    }

}
