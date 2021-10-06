<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataCrypter;
use App\Models\Comment;
use App\Models\MainInfo;
use App\Models\Recommended;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Post;
use App\Models\Image;
use App\Models\Page;
use App\Models\Tag;
use Symfony\Component\VarDumper\Cloner\Data;

class BlogAdminController extends Controller
{
    function format_uri( $string, $separator = '-' )
    {
        $string=str_replace("ı","i",$string);
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and', "'" => '');
        $string = mb_strtolower( trim( $string ), 'UTF-8' );
        $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
        $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }
    public function createComment(Request $request){
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
            $query->where('email',$email)
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
    public function createPost(Request $request){

        $check=Setting::where('setting','post')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Post paylaşma işlemi yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin.'
            ],403);
        }
        $title=$request->title??'';
        $desc=$request->description??'';
        $image=$request->image??'';
        $content=$request->content??'';
        $tags=$request->tags??[];

        $tags=$tags!=[] ? ($tags) : [];


        if(strlen($title)<3 || strlen($title)>255){ $error="Başık uzun/kısa!"; }
        else if(strlen($desc)>255){ $error="Açıklama uzun!"; }
        else if(strlen($image)<3 || strlen($image)>255){ $error="Resim uzun/kısa!"; }
        else if(strlen($content)<10){ $error="İçerik uzun/kısa!"; }

        $imageCheck=Image::where('name',$image)->orWhere('path',$image)->first(['id']);
        if(empty($imageCheck) || !isset($imageCheck)){
            return response()->json([
                'status'=>'error',
                'message'=>'Görsel bulunamadı.'
            ],403);
        }
        if(isset($error)){
            return response()->json([
                'status'=>'error',
                'message'=>$error
            ],403);
        }

        $control=Post::where(function($query) use ($title){
                $query->where('title',$title);
            })
            ->where('status',0)
            ->get();

        if(count($control)>0){
            return response()->json([
                'status'=>'error',
                'message'=>'Bu başlık/url farklı bir postda aktif olarak kullanılıyor'
            ],403);
        }

        try {
            $page=Page::create([
                'name'=>$title,
                'url'=>$this->format_uri($title),
                'status'=>0
            ]);
            $page->save();
            $post=Post::create([
                'title'=>$title,
                'description'=>$desc,
                'page_id'=>$page->id,
                'image_id'=>$imageCheck->id,
                'content'=>$content,
                'status'=>0
            ]);
            $post->save();
            foreach ($tags as $tag){
                try {
                    $tag=Tag::create([
                        'post_id'=>$post->id,
                        'tag'=>$tag
                    ]);
                    $tag->save();
                }catch (\Exception $ex){
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
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu lütfen tekrar deneyin.'.$ex
            ],403);
        }
        return response()->json([
            'status'=>'error',
            'message'=>'Teknik bir hata oluştu lütfen tekrar deneyin.'
        ],403);
    }
    public function createRecommended(Request $request){
        $check=Setting::where('setting','recommended')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Önerilen ekleme işlemi yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
            ],403);
        }

        $crypt=new DataCrypter;
        $post=$request->post;
        $checkPost=Post::where('id',$crypt->crypt_router($post,false,'decode'))->where('status',0)->first(['id']);
        if(empty($checkPost) || $checkPost->id!=$crypt->crypt_router($post,false,'decode')){
            return response()->json([
                'status'=>'error',
                'message'=>'Gönderi yayından kaldırıldı veya bulunamadığı için yorumunuz gönderilemedi'
            ],403);
        }
        $post=$checkPost->id;
        $max=((Recommended::max('arrangement'))*1)+1;
        $checkRecommend=Recommended::where('post_id',$post)->update(['arrangement'=>$max]);
        if($checkRecommend){
            return response()->json([
                'status'=>'success',
                'message'=>'Gönderi zaten öne çıkanlarda olduğu için öne çıkanlar listesinde en üst sıraya alındı'
            ],200);
        }else{
            try {
                $recommended=Recommended::create([
                    'post_id'=>$post,
                    'arrangement'=>$max,
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ]);
                $recommended->save();
                return response()->json([
                    'status'=>'success',
                    'message'=>'Gönderi başarı ile öne çıkanlara eklendi'
                ],200);
            }catch (\Exception $ex){
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
    public function updateMainInfo(Request $request){
        return $request->all();
        try {
            foreach ($request->all() as $key=> $element){
                MainInfo::where('title',$key)->update(['info'=>$element]);
            }
            return response()->json(['status'=>'success','message'=>'Bilgiler başarı ile güncellendi'],200);
        }catch (\Exception $ex){
            return response()->json(['status'=>'error','message'=>'Bazı bilgiler güncellenirken bir hata ile karşılaşıldı'.$ex],403);
        }
        return response()->json(['status'=>'error','message'=>'Teknik bir hata oluştu'],403);
    }
    public function updateSettings(Request $request){
        if(count($request->all())>1){
            return response()->json(['status'=>'error','message'=>'Ayn anda birden fazla ayar güncelleyemezsiniz'],403);
        }

        foreach ($request->all() as $key=>$element){
            if($element!="enable" && $element!="disable"){
                return response()->json(['status'=>'error','message'=>'Ayar tanımı hatalı'],403);
            }
            try {
                Setting::where('setting',$key)->update(['option',$element]);
                return response()->json(['status'=>'success','message'=>'Ayar başarı ile güncellendi'],200);
            }catch (\Exception $ex){
                return response()->json(['status'=>'error','message'=>'Teknik bir hata oluştu'],403);
            }
        }
        return response()->json(['status'=>'error','message'=>'Teknik bir hata oluştu'],403);

    }
    public function getPostsWithoutRecommended(Request $request){
        $data=[];
        $crypt=new DataCrypter;
        try {
            $posts=Post::where('status',0)->get();
            foreach($posts as $post){
                $check=Recommended::where('post_id',$post->id)->first();
                if(empty($check->id)){

                    array_push($data,[
                        'uid'=>$crypt->crypt_router($post->id."",false,'encode'),
                        'title'=>$post->title,
                        'description'=>$post->description,
                        'date'=>date('d-m-Y H:i', strtotime($post->created_at)),
                        'image'=>$post->getImage->path
                    ]);
                }
            }
            return response()->json(['status'=>'success','data'=>$data],403);
        }catch (\Exception $ex){
            return response()->json(['status'=>'error','message'=>'Teknik bir hata oluştu'.$ex],403);
        }
        return response()->json(['status'=>'error','message'=>'Teknik bir hata oluştu'],403);


    }
}
