<?php


namespace App\Utils;


use App\Models\Photo;

class TransformContent
{

    public static function handle($str)
    {
        return self::transformShortCodes(self::addLazyLoadAttr(self::addAltToInternalImages($str)));
    }

    private static function transformShortCodes($str)
    {
        return preg_replace('/\[banner\s+text="(.*?)"\s+color="(.*?)"]/',
            '<div class="color-$2">$1</div>', $str);
    }

    private static function addLazyLoadAttr($str)
    {
        return preg_replace('/(<(img|iframe)\b[^><]*)>/i', '$1 loading="lazy">', $str);
    }

    private static function addAltToInternalImages($str)
    {
        if ( preg_match_all('/<img[^>]*src="(\/storage\/[a-zA-Z0-9.]+)"[^>]*\/*>/im', $str, $output_array) ) {
            for ($i=0; $i<count($output_array[0]);$i++) {
                $img = $output_array[0][$i];
                $filename = $output_array[1][$i];
                if ( strpos($img, 'alt') ) { //check if alt attr does not exist
                    continue;
                }
                $photo = Photo::where(['url' => $filename])->first();
                if ( !$photo ) {
                    continue;
                }

                $imgWithAlt = str_replace('<img', '<img alt="'.$photo->description.'"', $img);
                $str = str_replace($img, $imgWithAlt, $str);
            }
        }

        return $str;
    }
}
