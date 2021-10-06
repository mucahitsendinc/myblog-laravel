<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ImageKit\ImageKit;
use App\Models\Setting;
use App\Models\Image;

class ImageController extends Controller
{
    //
    public function getImages(Request $request){
        return Image::get();
    }
    public function newImage(Request $request){

        $check=Setting::where('setting','imageupload')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Fotoğraf ekleme işlemi yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
            ],403);
        }
        if($request->formData['file']){
            $image=$request->formData['file'];
            $name=time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $path=env("IMAGEKIT_URL_END_POINT").'/images/'.$name;
            $date=date('Y-m-d H:i:s');
            if(strpos($name,'.png')==false && strpos($name,'.jpg')==false && strpos($name,'.jpeg')==false && strpos($name,'.gif')==false){
                return response()->json(['status'=>false,'message'=>'Yalnızca görsel yükleyebilirsiniz'],403);
            }
            try {
                $imageKit = new ImageKit(
                    env("IMAGEKIT_PUBLIC_KEY"),
                    env("IMAGEKIT_PRIVATE_KEY"),
                    env("IMAGEKIT_URL_END_POINT")
                );
                $result=$imageKit->uploadFiles(array(
                    "file" => $image, // required
                    "fileName" =>  $name, // required
                    "useUniqueFileName" => false, // optional
                    "folder" => "images/", // optional
                    "isPrivateFile" => false, // optional
                ));
                $imager=Image::create([
                    'name'=>$name,'path'=>$path,'fileId'=>json_decode(json_encode($result))->success->fileId
                ]);
                $imager->save();
                return response()->json(['status'=>'success','message'=>'Fotoğraf ekleme işlemi başarılı','image'=>$path],200);
            }catch (\Exception $ex){
                return $ex;
                return response()->json([
                    'status'=>'error',
                    'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
                ],200);
            }
        }

        return response()->json([
            'status'=>'error',
            'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
        ],403);
    }
    public function deleteImage(Request $request){

        $check=Setting::where('setting','imagedelete')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Fotoğraf silme işlemi yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
            ],403);
        }else if(!isset($request->image) || empty($request->image)){
            return response()->json([
                'status'=>'error',
                'message'=>'Fotoğraf bulunamadı'
            ],403);
        }
        $image=Image::where('path',$request->image)->orWhere('name',$request->image)->first(['fileId','id']);
        if(!isset($image->id) || empty($image->id)){
            return response()->json([
                'status'=>'error',
                'message'=>'Fotoğraf bulunamadı'
            ],403);
        }
        try {
            Image::find($image->id)->delete();
            $imageKit = new ImageKit(
                env("IMAGEKIT_PUBLIC_KEY"),
                env("IMAGEKIT_PRIVATE_KEY"),
                env("IMAGEKIT_URL_END_POINT")
            );
            $deleteFile = $imageKit->deleteFile($image->fileId);
            if(json_decode(json_encode($deleteFile))->err==null){

                return response()->json([
                    'status'=>'success',
                    'message'=>'Fotoğraf başarı ile silindi'
                ],200);
            }
        }catch (\Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
            ],403);
        }

        return response()->json([
            'status'=>'error',
            'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
        ],403);
    }
}
