<?php 
namespace Utilities\Cart;

class CartFns {

    public static function getAuthTimeDelta()
    {
        return 21600; // 6 * 60 * 60; // horas * minutos * segundo // 6 horas
        // No puede ser mayor a 34 días
    }

    public static function getUnAuthTimeDelta()
    {
        return 900 ;// 15 * 60; //h , m, s // 9 minutos
        // No puede ser mayor a 34 días
    }

    public static function getAnnonCartCode(){
        if(isset($_SESSION["annonCartCode"]) ) {
            return $_SESSION["annonCartCode"];
        };
        $_SESSION["annonCartCode"] = substr(md5("cart202502" . time() . random_int(1000, 99999)), 0, 128);
        return $_SESSION["annonCartCode"];
    }
}

?>
