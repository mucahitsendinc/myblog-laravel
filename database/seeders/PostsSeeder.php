<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'title'=>'Blog sayfamı hazırlama sürecim',
            'description'=>'Bu paylaşımımda blog sayfamı kodlarken kullandığım teknolojileri ve geliştirmekte olduğum bu blog projemin detaylarını paylaşacağım',
            'page_id'=>5,
            'image_id'=>70,
            'content'=>'<div style="font-size:16px;line-height: 1.6;">&nbsp;&nbsp;Bir yazılımcı olarak not defteri gibi kullanabileceğim, yaptıklarımı paylaşabileceğim bir sayfaya ihtiyacım vardı. Blog sayfamı önce WordPress ile kurup geçmeyi düşündüm. <br/> Fakat WordPress \'in tema bulma derdi,  ücretli ise ücret ödeme kısmı değilse temanın erişilebilir kısımlarını düzenlemek oldukça güçtü. Her şeyi geçtim temayı düzenlemesini de WordPress\'in sunduğu hız gerçekten beni bu seçenekten soğutan kısım oldu. Daha sonra Php ile backend \'ini tamamlayıp düz html yapmayı düşündüm. Bu seçenek ise oldukça garip olacaktır ki Kod alışkanlığım gereği proje bir kısımdan sonra Spagettiye bağlanıyor ve işin içinden çıkılamaz bir hal alacağını bildiğimden bir Framework kullanarak backend\'i halletme kararı aldım. <br/> &nbsp;&nbsp;Önümde yine 2 adet seçenek vardı Codeigniter veya Laravel.Burada kararımı Laravel dan yana kullandım sebebi ise laravel topluluğuydu. Hem kaynak yeterli hemde hakim olduğum bir frameworktü. &nbsp;Front end için hali hazırda yeni tanıştığım React\'ı seçtim hem performans olarak hemde yapısal olarak baya işimi görüyordu . <br/> Neden laravel içerisinde React projesi geliştirmediğime gelirsek, bunu projeye başladıktan sonra öğrendim 🥺. Sonra da geri başlamaktansa Laravel projemi API olarak kullanmak daha mantıklı geldi. <br/>&nbsp;&nbsp;Proje sırasında çeşitli şeyler öğrendim tabi bunlardan en önemlileri veri tabanı ile ilgili işlemler oldu. Normal bir backend çalışmalarımda tablolar arası ilişkiye önem vermeden yapardım bu projede karmaşıklık her adımda dahada arttığı için projeyi karmaşık yapıdan kurtarmak için sürekli başa dönerek projemi düzenli ve en son öğrendiğim sade yalın bir hale çevirdim. Hala daha devam eden bu projedeki gelişmeleri bu paylaşımıma ekleyeceğim. </div>',
            'status'=>0
        ]);
    }
}
