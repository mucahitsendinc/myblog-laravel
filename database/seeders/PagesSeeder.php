<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert(
            array(
                [
                    'name'=>'Ana Sayfa',
                    'url'=>'/',
                    'status'=>0,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'Blogum',
                    'url'=>'/blogum',
                    'status'=>0,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'Hakkımda',
                    'url'=>'/hakkimda',
                    'status'=>0,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'İletişim',
                    'url'=>'/iletisim',
                    'status'=>0,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ]
            )
        );
    }
}
