<?php


namespace App\Util;


class RequestUtils {

    public static function getPOSTValue($key) {
        return isset($_POST[$key]) ? $_POST[$key] : '';
    }

    public static function getGETValue($key) {
        return isset($_GET[$key]) ? $_GET[$key] : '';
    }

}