<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataCrypter;
use App\Models\MainInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\VarDumper\Cloner\Data;

class ApiController extends Controller
{


    public function login(Request $request){
        $crypt=new DataCrypter;

        $access=$crypt->crypt_router($request->password,false,'encode');

        if(isset($access) && !empty($access)){
            $check=Setting::where('setting','access')->first(['option']);
            if(strlen($check->option)>0 && $check->option==$access){
                $newToken=$crypt->crypt_router($request->password,true,'encode');
                return response()->json(['success'=>true,'token'=>$newToken],200);
            }
        }
        return response()->json(['success'=>false,'message'=>'Giriş bilgileri hatalı'],401);

    }

    public function checkToken($token){
        $crypt=new DataCrypter;
        $access=$crypt->crypt_router($token,true,'decode');
        $access=$access==false ? 'null' : $access;
        $access=$crypt->crypt_router($access[0],false,'encode');
        $check=Setting::where('setting','access')->first(['option']);
        if(strlen($check->option)>0 && $check->option==$access){
            return true;
        }
        return false;
    }
    public function forgotAccess(Request $request){
        $crypt=new DataCrypter;
        try {
            $token=json_encode([
                'access'=>md5((Setting::where('setting','access')->first(['option']))->option),
                'type'=>'resetaccess'
            ]);
            $token=$crypt->crypt_router($token,true,'encode',180);
            $data = [
                'link'=>env('APP_FRONT_URL').'/yonetici/parola-sifirla/'.$token,
                'email'=>(MainInfo::where('title','email')->where('status',0)->first(['info']))->info
            ];
            $mail=env('MAIL_FROM_ADDRESS');
            Mail::send('email.code', $data, function ($message) use($data,$mail) {
                $message->from($mail, 'Erişim Parolası Sıfırlama - Mücahit Sendinç Blog');
                $message->subject("Erişim Parolası Sıfırlama - Mücahit Sendinç Blog");
                $message->to($data['email']);
            });
        }catch (\Exception $ex){
            return response()->json(['success'=>false,'message'=>'E Posta gönderilirken bir hata oluştu'],403);
        }
    }
    public function resetAccess(Request $request){
        if(!isset($request->newaccess) || !isset($request->rnewaccess)){
            return response()->json(['success'=>false,'message'=>'Yeni erişim parolası olmadan güncelleme yapamazsınız'],403);
        }else if(strlen($request->newaccess)!=32 || $request->newaccess!=$request->rnewaccess){
            return response()->json(['success'=>false,'message'=>'Yeni erişim parolası ve tekrarı kabul edilemeyen formatta!'],403);
        }else if(isset($request->token) && strlen($request->token)>10){
            $crypt=new DataCrypter;
            $token=$request->token;
            $token=$crypt->crypt_router($token,true,'decode');
            try {
                $token=(json_decode($token[0]));
                if($token->type=="resetaccess"){
                    $access=md5((Setting::where('setting','access')->first(['option']))->option);
                    if($access==$token->access){
                        Setting::where('setting','access')->update(['option'=>$crypt->crypt_router($request->newaccess,false,'encode')]);
                        return response()->json([
                            'success'=>true,
                            'message'=>'Erişim parolası başarı ile güncellendi',
                            'token'=>$crypt->crypt_router($request->newaccess,true,'encode')
                        ],200);
                    }
                }
            }catch (\Exception $ex){
                return response()->json(['success'=>false,'message'=>'Bağlantı geçersiz veya geçerliliğini yitirmiş'],403);
            }
        }
        return response()->json(['success'=>false,'message'=>'Bağlantı geçersiz veya geçerliliğini yitirmiş'],403);
    }
}
