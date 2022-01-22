<?php
namespace app\classes;
class CurlTop20 {
    const SITE_URL = 'https://www.imdb.com/chart/top/';
    public static function loadTop250page(){
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, self::SITE_URL );
        curl_setopt( $ch, CURLOPT_POST, false ); 
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

        $exec = curl_exec($ch);
        curl_close($ch);
        return $exec;
    }

    public static function loadMovie($url){
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, self::SITE_URL.$url );
        curl_setopt( $ch, CURLOPT_POST, false ); 
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

        $exec = curl_exec($ch);
        curl_close($ch);
        return $exec;
    }
        
}

