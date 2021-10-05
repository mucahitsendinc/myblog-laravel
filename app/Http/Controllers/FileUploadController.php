<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ImageKit\ImageKit;
use App\Models\Setting;
use App\Models\Image;

class FileUploadController extends Controller
{
    //
    public function newImage(Request $request){

        $check=Setting::where('setting','imageupload')->first(['option']);
        if($check->option!="enable"){
            return response()->json([
                'status'=>'error',
                'message'=>'Resim ekleme işlemi yetkili tarafından devre dışı bırakılmış durumda. Lütfen daha sonra tekrar deneyin'
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
                    'name'=>$name,'path'=>$path
                ]);
                $imager->save();
                return response()->json(['status'=>'success','message'=>'Resim ekleme işlemi başarılı'],200);
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
}
