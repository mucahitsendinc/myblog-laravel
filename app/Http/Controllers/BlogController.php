<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class BlogController extends Controller
{
    //
    public function getMainInfo(){
        $getInfos=DB::table('main_info')->where('title','!=','access')->where('status',0)->get(['title','info']);
        return response()->json([
            'status'=>'success',
            'data'=>$getInfos
        ],200);
    }
}
