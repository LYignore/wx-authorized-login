<?php
namespace Lyignore\WxAuthorizedLogin\Tools;

use GuzzleHttp\Client;

trait Tools
{
    protected static $https;
    public static $envName = 'websocketlogin';
    /**
     * Defines the global helper functions
     *
     * @return void
     */
    public function globalHelpersFunction()
    {
        $a = require __DIR__.'/../../config/websocketlogin.php';
        if(isset($a['helpfunction']) &&$a['helpfunction']){
            $_ENV[self::$envName] = Config::getInstance($a);
            require_once __DIR__.'/Function.php';
            return true;
        }
        return false;
    }

    /**
     * Singleton https request
     */
    public static function getInstanceHttp()
    {
        if(!self::$https instanceof Client){
            self::$https = new Client();
        }
        return self::$https;
    }

    /**
     * Transcoding binary image rendering
     */
    public function binaryImageRedering($contents, $mime='image/png')
    {
        $base64 = base64_encode($contents);
        return ('data:'. $mime . ';base64,'. $base64);
        // Enter the rendered image directly
        //return '<img src="data:'. $mime . ';base64,'. $base64.'" />';
    }
}
