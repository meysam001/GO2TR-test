<?php


namespace App\Utils;


class GenerateList
{
    public static function handle($html)
    {
        $dom = new \DomDocument();
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DomXPath($dom);
       return $output = self::extractH(2, $xpath, $dom);
    }

    public static function extractH ( $level, $xpath, $dom, $position = 0, $number = ''  )  {
        $output = [];
        $prevLevel = $level-1;
        $headings = $xpath->query("//*/h{$level}[count(preceding-sibling::h{$prevLevel})={$position}]");
        foreach ( $headings as $key => $heading )   {
            $sectionNumber = ltrim($number.".".($key+1), ".");
            $newOutput = [
                "text" => $heading->nodeValue,
                "level" => $level
            ];
            $children = self::extractH($level+1, $xpath, $dom, $key+1, $sectionNumber);
            if ( !empty($children) )    {
                $newOutput["children"] = $children;
            }
            $output[] =$newOutput;
        }

        return $output;
    }

}
