<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\VarDumper\Cloner\Data;
use App\Models\Post;
use App\Models\Page;
use App\Models\Recommended;
use App\Models\Setting;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\MainInfo;
use App\Models\AboutUs;

class BlogController extends Controller
{
    //
    public function sendContact(Request $request){

        $check=Setting::where('setting','contact')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'İletişim formu yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
            ],200);
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
            ],200);
        }

        $control=Contact::where(function($query) use ($ip,$email,$message){
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
            ],200);
        }

        try {
            $contact=Contact::create([
                'name'=>$name.' '.$surname,
                'email'=>$email,
                'message'=>$message,
                'remote_ip'=>$ip,
                'status'=>0
            ]);
            $contact->save();
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
            ],200);

        }

        return response()->json([
            'status'=>'success',
            'message'=>'Mesaj başarı ile iletildi'
        ],200);



    }
    public function sendComment(Request $request){

        $crypt=new DataCrypter;

        $check=Setting::where('setting','comment')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Yorum formu yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
            ],200);
        }

        $post=$request->post??'';
        $name=$request->name;
        $surname=$request->surname;
        $email=$request->email??'';
        $comment=$request->comment??'';
        $ip=$_SERVER['REMOTE_ADDR'];
        $checkPost=Post::where('id',$crypt->crypt_router($post,false,'decode'))->where('status',0)->first(['id']);

        if(empty($checkPost) || $checkPost->id!=$crypt->crypt_router($post,false,'decode')){
            return response()->json([
                'status'=>'error',
                'message'=>'Gönderi yayından kaldırıldı veya bulunamadığı için yorumunuz gönderilemedi'
            ],200);
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
            ],200);
        }
        $control=Comment::where(function($query) use ($ip,$email,$comment){
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
            ],200);
        }
        $comment=Comment::create([
            'post_id'=>$post,
            'name'=>$name.' '.$surname,
            'email'=>$email,
            'comment'=>$comment,
            'remote_ip'=>$ip,
            'status'=>0
        ]);
        $comment->save();
        if($comment){
            return response()->json([
                'status'=>'success',
                'message'=>'Yorum başarı ile iletildi'
            ],200);
        }

        return response()->json([
            'status'=>'error',
            'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
        ],200);


    }
    public function getMainInfo(){
        try {
            $getInfos=MainInfo::where('title','!=','access')->where('status',0)->get(['title','info']);
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],200);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$getInfos
        ],200);
    }
    public function getAboutUs(){
        try {
            $getInfos=AboutUs::where('status',0)->get(['title','list']);
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],200);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$getInfos
        ],200);
    }
    public function getPosts(){
        $crypt=new DataCrypter;
        try {
            $getPosts=Post::where('status',0)->orderBy('id', 'desc')->get();
            $posts=[];
            $i=0;
            foreach($getPosts as $post){
                $i++;
                $tags=$post->getTags;
                $newPost=[
                    'unid'=>md5($post->title).'id-'.$i,
                    'id'=>$crypt->crypt_router($post->id."",false,'encode'),
                    'title'=>$post->title,
                    'description'=>$post->description,
                    'url'=>$post->getUrl->url,
                    'image'=>$post->getImage->path,
                    'tags'=>$tags
                ];
                array_push($posts,$newPost);
            }
        }catch(\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],200);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$posts
        ],200);
    }
    public function getPostDetail(Request $request){
        $crypt=new DataCrypter;
        $url=$request->url;

        try {
            $page = Page::where('url',$url)->first(['id']);
            if(empty($page->id)){
                return response()->json([
                    'status'=>'error',
                    'message'=>'Gönderi bulunamadı.Silinmiş veya yayından kaldırılmış olabilir'
                ],200);
            }

            $getPostDetail = Post::where('page_id',$page->id)->first();
            if(empty($getPostDetail)){
                return response()->json([
                    'status'=>'error',
                    'message'=>'Gönderi bulunamadı.Silinmiş veya yayından kaldırılmış olabilir'
                ],200);
            }
            $postDetail=[
                'detail'=>[
                    'id'=>$crypt->crypt_router($getPostDetail->id."",false,'encode'),
                    'title'=>$getPostDetail->title,
                    'description'=>$getPostDetail->description,
                    'content'=>$getPostDetail->content,
                    'date'=>$getPostDetail->created_at,
                    'image'=>$getPostDetail->getImage->path
                ],
                'comments'=>$getPostDetail->getComments,
                'tags'=>$getPostDetail->getTags
            ];
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'.$ex
            ],200);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$postDetail
        ],200);
    }
    public function getComments(Request $request){
        $crypt=new DataCrypter;
        $post=$request->post;
        try {
            $post=Post::where('id',$crypt->crypt_router($post,false,'decode'))->first();
            $comments=$post->getComments;
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'
            ],200);
        }

        return response()->json([
            'status'=>'success',
            'data'=>$comments
        ],200);
    }
    public function getRecommended(){
        $crypt=new DataCrypter;
        //return $crypt->crypt_router('2',false,'encode');
        try {
            $getRecommended=Recommended::where('status',0)->orderByDesc('arrangement')->get();
            $posts=[];
            $i=0;
            foreach($getRecommended as $poster){
                $i++;
                $post=Post::where('id',$poster->getPost->id)->first();
                $newPost=[
                    'unid'=>md5($post->title).'id-'.$i,
                    'id'=>$crypt->crypt_router($poster->getPost->id."",false,'encode'),
                    'title'=>$post->title,
                    'description'=>$post->description,
                    'url'=>$post->getUrl->url,
                    'image'=>$post->getImage->path,
                    'date'=>$post->create_date,
                    'tags'=>$post->getTags
                ];
                array_push($posts,$newPost);
            }
        }catch(\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu'.$ex
            ],200);
        }
        return response()->json([
            'status'=>'success',
            'data'=>$posts
        ],200);
    }

}
