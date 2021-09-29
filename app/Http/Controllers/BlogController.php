<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Symfony\Component\VarDumper\Cloner\Data;

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
    public function createPost(Request $request){

        $check=DB::table('settings')->where('setting','post')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Post paylaşma işlemi yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin.'
            ],403);
        }

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
            ],403);
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
            ],403);
        }

        $save=DB::table('posts')->insertGetId([
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
                $save=DB::table('tags')->insert([
                    'post_id'=>$save,
                    'tag'=>$tag
                ]);
                if(!$save){
                    return response()->json([
                        'status'=>'error',
                        'message'=>'Post oluşturuldu fakat Tag oluşturulurken bir hata oluştu'
                    ],403);
                }
            }

            return response()->json([
                'status'=>'success',
                'message'=>'Post başarı ile oluşturuldu'
            ],200);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'Teknik bir hata oluştu lütfen tekrar deneyin.'
        ],403);
    }
    public function createRecommended(Request $request){

        $check=DB::table('settings')->where('setting','recommended')->first(['option']);

        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Önerilen ekleme işlemi yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
            ],403);
        }

        $crypt=new DataCrypter;
        $post=$request->post;

        $checkPost=DB::table('posts')->where('id',$crypt->crypt_router($post,false,'decode'))->where('status',0)->first(['id']);
        if(empty($checkPost) || $checkPost->id!=$crypt->crypt_router($post,false,'decode')){
            return response()->json([
                'status'=>'error',
                'message'=>'Gönderi yayından kaldırıldı veya bulunamadığı için yorumunuz gönderilemedi'
            ],403);
        }

        $post=$checkPost->id;

        $max=((DB::table('recommendeds')->max('arrangement'))*1)+1;

        $checkRecommend=DB::table('recommendeds')->where('post_id',$post)->update(['arrangement'=>$max]);
        if($checkRecommend){
            return response()->json([
                'status'=>'success',
                'message'=>'Gönderi zaten öne çıkanlarda olduğu için öne çıkanlar listesinde en üst sıraya alındı'
            ],200);
        }else{
            $save=DB::table('recommendeds')->insert([
                'post_id'=>$post,
                'arrangement'=>$max,
                'status'=>0,
                'create_date'=>date('Y-m-d H:i:s'),
                'update_date'=>date('Y-m-d H:i:s')
            ]);

            if($save){
                return response()->json([
                    'status'=>'success',
                    'message'=>'Gönderi başarı ile öne çıkanlara eklendi'
                ],200);
            }else{
                return response()->json([
                    'status'=>'error',
                    'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
                ],403);
            }

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
        else if(strlen($comment)>255){ $error="Yorum uzun!"; }
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

}
