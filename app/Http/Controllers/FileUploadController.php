<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Images;
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
            $name=md5(time());
            try {
                $name = time().'_'.substr($image, 0, strpos($image, ';'));
                $path=$image->storeAs('uploads', $image, 'public');
                $date=date('Y-m-d H:i:s')."";
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
