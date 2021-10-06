<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MainInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('main_info')->insert(
            array(
                [
                    'title'=>'user',
                    'info'=>'Mücahit Sendinç',
                    'status'=>0
                ],
                [
                    'title'=>'profile_image',
                    'info'=>'https://media-exp1.licdn.com/dms/image/C4E03AQHKJkMMK6VuxQ/profile-displayphoto-shrink_800_800/0/1607021398194?e=1636588800&v=beta&t=PlSnc-zTLsjmFGyZoXAW5KZz7YhpUstFG6EJsVmU6NU',
                    'status'=>0
                ],
                [
                    'title'=>'favicon_logo',
                    'info'=>'https://www.mucahitsendinc.com/favicon.ico',
                    'status'=>0
                ],
                [
                    'title'=>'degree',
                    'info'=>'Web & Mobil Yazılım Geliştirici',
                    'status'=>0
                ],
                [
                    'title'=>'location',
                    'info'=>'İstanbul',
                    'status'=>0
                ],
                [
                    'title'=>'email',
                    'info'=>'me@mucahitsendinc.com',
                    'status'=>0
                ],
                [
                    'title'=>'phone',
                    'info'=>'+90 537 xxx xx52',
                    'status'=>0
                ],
                [
                    'title'=>'copyright',
                    'info'=>'Development by Mücahit Sendinç',
                    'status'=>0
                ],
                [
                    'title'=>'social_github',
                    'info'=>'https://github.com/mucahitsendinc',
                    'status'=>0
                ],
                [
                    'title'=>'social_linkedin',
                    'info'=>'https://www.linkedin.com/in/m%C3%BCcahit-sendin%C3%A7/',
                    'status'=>0
                ],
                [
                    'title'=>'social_instagram',
                    'info'=>'https://www.instagram.com/mucahitsndc/',
                    'status'=>0
                ]
            )
        );
    }
}
