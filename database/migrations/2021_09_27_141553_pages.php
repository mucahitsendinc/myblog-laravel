<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->integer('status');
            $table->string('create_date');
            $table->string('update_date');
        });
        DB::table('pages')->insert(
            array(
                [
                    'name'=>'Ana Sayfa',
                    'url'=>'/',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'Blogum',
                    'url'=>'/blogum',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'Hakkımda',
                    'url'=>'/hakkimda',
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'İletişim',
                    'url'=>'/iletisim',
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
        //
    }
}
