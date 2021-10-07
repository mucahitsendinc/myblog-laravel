<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DataCrypter extends Controller
{

    /**
     *DataCrypter-Php  © 2021
     * Tüm hakları saklıdır
     *
     *
     *   mt = Süreli şifreleme fonksiyonlarıdır.
     *
     */

    public $explode_key="@MucahitSendinc@";
    public $access_time=31556926;  //seconds

    public $method="aes-128-cbc";
    public $key="9071bvc3d";
    public $secret="5rrvbn81";

    public $methodd="aes-256-cbc";
    public $keyy="3e5adfgvb";
    public $secrett="a18080";

    public $methoddd="aes-128-cfb8";
    public $keyyy="84sfghbn";
    public $secrettt="424cdeff";

    private function crypter(Request $request){ // Test Request Method
        $data=$request->data;
        $time=$request->time=="true" ? true : false;
        $mode=$request->mode;
        if($mode=="encode"){
            return $this->crypt_router($data,$time,$mode);
        }else{
            return $this->crypt_router($data,$time,$mode);
        }
    }
    private function KEI1_mt_encode_data($string, $access_time = "")
    {
        $access_time = $access_time == "" ? $this->access_time : $access_time;
        $method = $this->method;
        $key = $this->key;
        $secret = $this->secret;
        $key = hash('sha256', $key);
        $iv = substr(hash('sha256', $secret), 0, 16);
        if ($string != "" && gettype($string) == "string") {
            $string = $string . $this->explode_key . strtotime('+' . $access_time . ' seconds');
            $output = openssl_encrypt($string, $method, $key, 0, $iv);
        } else if (count($string) > 0 && gettype($string) == "array") {
            array_push($string, strtotime('+' . $access_time . ' seconds'));
            $string = json_encode($string);
            $output = openssl_encrypt($string, $method, $key, 0, $iv);
        }

        return ($output);
    }
    private function KEI1_mt_decode_data($string, $type = "")
    {
        $method = $this->method;
        $key = $this->key;
        $secret = $this->secret;
        $key = hash('sha256', $key);
        $iv = substr(hash('sha256', $secret), 0, 16);
        $string = ($string);
        $string = openssl_decrypt($string, $method, $key, 0, $iv);
        if ($type == "") {
            $string = explode($this->explode_key, $string);
        } else {
            $string = ($string);
        }
        if ($string[count($string) - 1] <= time()) {
            $string[count($string) - 1] = "false";
            return false;
        }
        $response=[];
        foreach($string as $str){
            if($str!=$string[count($string)-1]){
                array_push($response,$str);
            }
        }
        return $response;
    }
    public function crypt_router($string,$time=false,$mode="encode"){

        /**
         * Bu fonksiyon içerisindeki komut satırına alınmış satırları sırasını değiştirerek
         * Algoritmada değişiklik yapın sonrasında else bloğunda tam tersine çevirin
         */

        if($mode=="encode"){
            //$crypt=$this->KEI1_encode($string);
            $crypt=$string;
            if($time==true){
                $crypt=$this->KEI1_mt_encode_data($crypt,$this->access_time);
            }
            $crypt=$this->KEI1_encode($crypt,$this->method,$this->key,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->method,$this->keyy,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->method,$this->keyyy,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->method,$this->key,$this->secrett);
            //$crypt=$this->KEI1_encode($crypt,$this->method,$this->key,$this->secrettt);
            //$crypt=$this->KEI1_encode($crypt,$this->method,$this->keyy,$this->secrett);
            //$crypt=$this->KEI1_encode($crypt,$this->method,$this->keyy,$this->secrettt);
            //$crypt=$this->KEI1_encode($crypt,$this->method,$this->keyyy,$this->secrett);
            $crypt=$this->KEI1_encode($crypt,$this->method,$this->keyyy,$this->secrettt);
            /*    ----- -- -- - - -- */
            $crypt=$this->KEI1_encode($crypt,$this->methodd,$this->key,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->methodd,$this->keyy,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->methodd,$this->keyyy,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->methodd,$this->key,$this->secrett);
            //$crypt=$this->KEI1_encode($crypt,$this->methodd,$this->key,$this->secrettt);
            //$crypt=$this->KEI1_encode($crypt,$this->methodd,$this->keyy,$this->secrett);
            $crypt=$this->KEI1_encode($crypt,$this->methodd,$this->keyy,$this->secrettt);
            //$crypt=$this->KEI1_encode($crypt,$this->methodd,$this->keyyy,$this->secrett);
            //$crypt=$this->KEI1_encode($crypt,$this->methodd,$this->keyyy,$this->secrettt);
            /*    ----- -- -- - - -- */
            $crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->key,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->keyy,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->keyyy,$this->secret);
            //$crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->key,$this->secrett);
            //$crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->key,$this->secrettt);
            //$crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->keyy,$this->secrett);
            $crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->keyy,$this->secrettt);
            //$crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->keyyy,$this->secrett);
            //$crypt=$this->KEI1_encode($crypt,$this->methoddd,$this->keyyy,$this->secrettt);

        }else{
            $crypt=($string);
            /*  -    -- -- - -- --- -- ---- --  */
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->keyyy,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->keyyy,$this->secrett);
            $crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->keyy,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->keyy,$this->secrett);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->key,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->key,$this->secrett);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->keyyy,$this->secret);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->keyy,$this->secret);
            $crypt=$this->KEI1_decode($crypt,'off',$this->methoddd,$this->key,$this->secret);
            /*  - -- - -- - -- - --  */
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->keyyy,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->keyyy,$this->secrett);
            $crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->keyy,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->keyy,$this->secrett);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->key,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->key,$this->secrett);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->keyyy,$this->secret);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->keyy,$this->secret);
            $crypt=$this->KEI1_decode($crypt,'off',$this->methodd,$this->key,$this->secret);
            /*  -    -- -- - -- --- -- ---- --  */
            $crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->keyyy,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->keyyy,$this->secrett);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->keyy,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->keyy,$this->secrett);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->key,$this->secrettt);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->key,$this->secrett);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->keyyy,$this->secret);
            //$crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->keyy,$this->secret);
            $crypt=$this->KEI1_decode($crypt,'off',$this->method,$this->key,$this->secret);

            if($time==true){
                $crypt=$this->KEI1_mt_decode_data($crypt);
            }

        }
        return $crypt;

    }
    private function KEI1_encode($string,$method="",$key="",$secret=""){
        if($method=="") { $method=$this->methodd; }
        if($key=="") { $key=$this->keyy; }
        if($secret=="") { $secret=$this->secrett; }
        $string=($string);
        $key = hash('sha256', $key);
        $iv = substr(hash('sha256', $secret), 0, 16);
        if ($string != "" && gettype($string) == "string") {
            //$string = $string . '@DehaSoft@' . strtotime('+' . env('ACCESS_TIME') . ' seconds');

            $output = openssl_encrypt($string, $method, $key, 0, $iv);

        } else if (count($string) > 0 && gettype($string) == "array") {
            //array_push($string,strtotime('+' . env('ACCESS_TIME') . ' seconds'));
            $string = json_encode($string);
            $output = openssl_encrypt($string, $method, $key, 0, $iv);
        }

        return base64_encode($output);
    }
    private function KEI1_decode($string,$type="off",$method="",$key="",$secret="")
    {
        if($method=="") { $method=$this->methodd; }
        if($key=="") { $key=$this->keyy; }
        if($secret=="") { $secret=$this->secrett; }
        $key = hash('sha256', $key);
        $iv = substr(hash('sha256', $secret), 0, 16);
        $string = base64_decode($string);

        $string = openssl_decrypt($string, $method, $key, 0, $iv);

        if ($type != "off") {
            $string = json_decode($string);
        }
        $string=($string);
        return $string;
    }

    public static function timeHasPassed($time){
        $now=Carbon::now();

        $time=Carbon::parse($time);

        $saniye=$now->diffInSeconds($time, false);
        $dakika=$now->diffInMinutes($time, false);
        $saat=$now->diffInHours($time, false);
        $gun=$now->diffInDays($time, false);
        $ay=$now->diffInMonths($time, false);
        $yil=$now->diffInYears($time, false);
        $hafta=$gun>-7 ? 0 : ( $gun>-13 ? 1 : ( $gun>-20 ? 2 : 3 ) );
        $sure=$yil!= 0 ? $yil." yıl" : ($ay!=0 ? $ay." ay" : ( $hafta!=0 ? $hafta." hafta" : ( $gun!=0 ? $gun." gün" : ( $saat!=0 ? $saat." saat" : ( $dakika!=0 ? $dakika." dakika" : $saniye." saniye" ) ) ) ) );

        return str_replace("-","",$sure)." önce";

    }

}
