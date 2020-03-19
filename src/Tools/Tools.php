<?php
namespace Lyignore\WxAuthorizedLogin\Tools;

trait Tools
{
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
}
