<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DataCrypter;
use DB;

class BlogAdminController extends Controller
{
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
}
