<?php

namespace App\Http\Controllers;

use App\Models\Recommended;
use Illuminate\Http\Request;
use App\Http\Controllers\DataCrypter;
use App\Models\Setting;
use App\Models\Post;
use App\Models\Image;
use App\Models\Page;
use App\Models\Tag;

class BlogAdminController extends Controller
{
    function format_uri( $string, $separator = '-' )
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and', "'" => '');
        $string = mb_strtolower( trim( $string ), 'UTF-8' );
        $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
        $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
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

}
