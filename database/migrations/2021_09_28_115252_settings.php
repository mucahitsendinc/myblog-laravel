<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Settings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting');
            $table->longText('option');
            $table->string('create_date');
            $table->string('update_date');
        });
        DB::table('settings')->insert([
            ['setting'=>'access','option'=>'123123','create_date'=>date('Y-m-d H:i:s'),'update_date'=>date('Y-m-d H:i:s')],
            ['setting'=>'contact','option'=>'enable','create_date'=>date('Y-m-d H:i:s'),'update_date'=>date('Y-m-d H:i:s')],
            ['setting'=>'comment','option'=>'enable','create_date'=>date('Y-m-d H:i:s'),'update_date'=>date('Y-m-d H:i:s')],
            ['setting'=>'post','option'=>'enable','create_date'=>date('Y-m-d H:i:s'),'update_date'=>date('Y-m-d H:i:s')],
            ['setting'=>'search','option'=>'enable','create_date'=>date('Y-m-d H:i:s'),'update_date'=>date('Y-m-d H:i:s')]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}
