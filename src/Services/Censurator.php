<?php

namespace App\Services;

class Censurator
{
    const banWord = ["test","aled","chepa"];


    public function purify(string $text): string
    {
        foreach (self::banWord as $grosmot){
            $nbEtoile = str_repeat("*", strlen($grosmot));
            $text = str_ireplace($grosmot, $nbEtoile, $text);
        }
        return $text;
//        return str_ireplace(
//          self::banWord, "***", $text
//        );
    }
}