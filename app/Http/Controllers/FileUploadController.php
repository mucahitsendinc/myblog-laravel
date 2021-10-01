<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Images;
use ImageKit\ImageKit;
use DB;

class FileUploadController extends Controller
{
    //
    public function newImage(Request $request){

        $check=DB::table('settings')->where('setting','imageupload')->first(['option']);

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

                DB::table('images')->insert(['name'=>$name,'path'=>$path,'create_date'=>$date,'update_date'=>$date]);
                return response()->json(['status'=>'success','message'=>'Resim ekleme işlemi başarılı'],200);
            }catch (\Exception $ex){
                return $ex;
                return response()->json([
                    'status'=>'error',
                    'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'.$ex
                ],200);
            }
        }

        return response()->json([
            'status'=>'error',
            'message'=>'Teknik bir hata oluştu lütfen daha sonra tekrar deneyin'
        ],403);
    }
}
