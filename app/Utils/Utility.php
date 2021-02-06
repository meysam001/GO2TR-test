<?php


namespace App\Utils;


class Utility
{
    public static function transformShortCodes($str)
    {
        return preg_replace('/\[banner\s+text="(.*?)"\s+color="(.*?)"]/',
            '<div class="color-$2">$1</div>', $str);
    }
}
