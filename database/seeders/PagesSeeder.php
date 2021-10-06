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
                    'status'=>0
                ],
                [
                    'name'=>'Blogum',
                    'url'=>'/blogum',
                    'status'=>0
                ],
                [
                    'name'=>'Hakkımda',
                    'url'=>'/hakkimda',
                    'status'=>0
                ],
                [
                    'name'=>'İletişim',
                    'url'=>'/iletisim',
                    'status'=>0
                ]
            )
        );
    }
}
