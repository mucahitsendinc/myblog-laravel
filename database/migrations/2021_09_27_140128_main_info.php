<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MainInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_info', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('info');
            $table->integer('status');
            $table->string('create_date');
            $table->string('update_date');
        });

        DB::table('main_info')->insert(
            array(
                [
                    'title'=>'user',
                    'info'=>'Mücahit Sendinç',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'profile_image',
                    'info'=>'https://media-exp1.licdn.com/dms/image/C4E03AQHKJkMMK6VuxQ/profile-displayphoto-shrink_800_800/0/1607021398194?e=1636588800&v=beta&t=PlSnc-zTLsjmFGyZoXAW5KZz7YhpUstFG6EJsVmU6NU',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'favicon_logo',
                    'info'=>'https://www.mucahitsendinc.com/favicon.ico',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'degree',
                    'info'=>'Web & Mobil Yazılım Geliştirici',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'location',
                    'info'=>'İstanbul',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'email',
                    'info'=>'me@mucahitsendinc.com',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'phone',
                    'info'=>'+90 537 xxx xx52',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'copyright',
                    'info'=>'Development by Mücahit Sendinç',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'social_github',
                    'info'=>'https://github.com/mucahitsendinc',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'social_linkedin',
                    'info'=>'https://www.linkedin.com/in/m%C3%BCcahit-sendin%C3%A7/',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'social_instagram',
                    'info'=>'https://www.instagram.com/mucahitsndc/',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('main_info');
    }
}
