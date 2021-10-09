<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DataCrypter;
use App\Models\Setting;

class ApiController extends Controller
{


    public function login(Request $request){
        $crypt=new DataCrypter;
        $access=$crypt->crypt_router($request->password,false,'encode');

        if(isset($access) && !empty($access)){
            $check=Setting::where('setting','access')->first(['option']);
            dd($check->option,$access,123,env('DEFAULT_ACCESS'));

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

}
