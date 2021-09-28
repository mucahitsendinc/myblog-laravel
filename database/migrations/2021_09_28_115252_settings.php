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
            $table->string('option');
            $table->string('create_date');
            $table->string('update_date');
        });
        DB::table('settings')->insert([
            ['setting'=>'access','option'=>'123123'],
            ['setting'=>'contact','option'=>'enable'],
            ['setting'=>'comment','option'=>'enable'],
            ['setting'=>'post','option'=>'enable'],
            ['setting'=>'search','option'=>'enable']
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
