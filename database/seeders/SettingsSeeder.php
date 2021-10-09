<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\DataCrypter;
use DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $crypt=new DataCrypter;
        DB::table('settings')->insert([
            ['setting'=>'access','option'=>$crypt->crypt_router(md5(env('DEFAULT_ACCESS'))."",false,'encode')],
            ['setting'=>'contact','option'=>'enable'],
            ['setting'=>'comment','option'=>'enable'],
            ['setting'=>'post','option'=>'enable'],
            ['setting'=>'recommended','option'=>'enable',],
            ['setting'=>'imageupload','option'=>'enable',],
            ['setting'=>'imagedelete','option'=>'enable'],
            ['setting'=>'search','option'=>'enable']
        ]);
    }
}
