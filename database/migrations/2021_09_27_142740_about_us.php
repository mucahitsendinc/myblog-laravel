<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AboutUs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('list');
            $table->integer('status');
            $table->string('create_date');
            $table->string('update_date');
        });
        DB::table('about_us')->insert(
            array(
                [
                    'title'=>'Deneyim',
                    'list'=>json_encode([
                        'Dehasoft 5 Ay'
                    ]),
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'Eğitim',
                    'list'=>json_encode([
                        'İstanbul Ayvansaray Üniversitesi ( Bilgisayar Programcılığı )',
                        'Otocenter Mesleki ve Teknik Anadolu Lisesi ( Veri Tabanı Programcılığı ) '
                    ]),
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'Ödül & Başarı',
                    'list'=>json_encode([
                        '2019 Pi Wars Türkiye 2.lik',
                        '2020 Pi Wars Türkiye 2.lik'

                    ]),
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'Yetenek',
                    'list'=>json_encode([
                        'HTML',
                        'CSS',
                        'JavaScript',
                        'Jquery/Ajax',
                        'SASS/SCSS',
                        'React JS',
                        'React Native',
                        'Redux',
                        'MobX',
                        'PHP',
                        'MySql',
                        'MsSql',
                        'PostgreSql',
                        'Laravel',
                        'CodeIgniter',
                        'Python',
                        'Java',
                        'C#',
                        'VB.net',
                        'REST Api'

                    ]),
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'Projeler',
                    'list'=>json_encode([
                        'Forum Sayfası',
                        'Kurumsal Sayfa ve Başvuru Paneli',
                        'B2B Sayfası için Image Upload Sistemi',
                        'B2B Mobil Uygulama',
                        'Uygulama Arka Plan Şifrelemesi',
                        'Sanal Pos Yönetim Paneli',
                        'Choose Your Doctor',
                        'Personel Sohbet Uygulaması',
                        'B2B Yönetim ve Lisans Kontrol Paneli'

                    ]),
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'Sertifikalar',
                    'list'=>json_encode([
                        '2019 Robotik Kodlama ( Hisar CS ) ',
                        '2020 Robotik Kodlama ( Hisar CS ) ',
                        '2019 3D Tasarım ( Hisar CS ) ',
                        '2020 3D Tasarım ( Hisar CS ) ',
                        '2019 Elektronik ( Hisar CS ) ',
                        '2020 Elektronik ( Hisar CS ) ',
                        'React Native ile Mobil Uygulama ( Udemy ) ',
                        'React Native, IOS, ANDROID ve REDUX ( Udemy ) ',
                        'React JS Hooks : Modern React & Context ( Udemy ) ',
                        'Laravel 8 & React JS ile CRM Projesi ( Udemy ) ',
                        'Laravel ile sıfırdan e-ticaret ( Udemy ) ',
                        'Sıfırdan ileri seviye laravel ( Udemy ) '
                    ]),
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],
                [
                    'title'=>'İlgi Alanları',
                    'list'=>json_encode([
                        'Yapay Zeka',
                        'Big Data',
                        'Proje Planlama',
                        'Proje Yönetim',
                        'Sorun Çözme',
                        'Kariyer Geliştirme',
                        'Web Tasarım',
                        'Strateji',
                        'Araştırma',
                        'Ekip Çalışması',

                    ]),
                    'status'=>0,
                    'create_date'=>date('Y-m-d H:i:s'),
                    'update_date'=>date('Y-m-d H:i:s')
                ],

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
        Schema::drop('about_us');
    }
}
