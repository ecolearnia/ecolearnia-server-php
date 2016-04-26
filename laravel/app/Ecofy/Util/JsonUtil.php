<?php namespace App\Ecofy\Util;

class JsonUtil
{

    /**
     * Creates the application.
     * @param $filepath
     * @param bool $assoc
     * @return \Illuminate\Foundation\Application
     */
    public static function loadFromFile($filepath, $assoc = false)
    {
        $filedata = file_get_contents($filepath);
        $json = json_decode($filedata, $assoc);
        return $json;
    }
}
