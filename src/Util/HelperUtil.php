<?php

namespace App\Util;

class HelperUtil
{
    public static function getIPAddress()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            list($ipAddr) = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            return $ipAddr;
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}
