<?php
if (! function_exists('config')) {
    function config($path = '')
    {
        $path = str_replace(\Lyignore\WxAuthorizedLogin\Tools\Tools::$envName.'.', '', $path);
        $configObj = $_ENV[Lyignore\WxAuthorizedLogin\Tools\Tools::$envName];
        return $configObj->get($path);
    }
}
