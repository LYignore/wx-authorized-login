<?php
namespace Lyignore\WxAuthorizedLogin\Entities;

use Lyignore\WxAuthorizedLogin\Domain\Entities\MemoryEntityInterface;
use Swoole\Table;

class ShareMemory implements MemoryEntityInterface
{
    const SIZE = 10;

    public static $table;

    protected static $config = ['fd' => 'int', 'ticket' => 'string'];

    public static $instance;

    protected function __construct(array $config=[])
    {
        if(!empty($config)){
            self::$config = $config;
        }
        $size = pow(2, self::SIZE);
        self::$table = new Table($size);
    }

    public static function getInstance(array $changeConfig)
    {
        if(!self::$instance){
            $config = array_merge(config('websocketlogin.memory'), $changeConfig);
            self::$instance = new self($config);
            if(self::isNumArray(self::$config)){
                $configs = [];
                foreach(self::$config as $value){
                    $configs[$value] = 'string';
                }
                self::$config = $configs;
            }array_merge(config('websocketlogin.memory'), $config);
            foreach (self::$config as $keys => $values){
                if($values == 'int'){
                    self::$table->column($keys, Table::TYPE_INT, 8);
                }elseif($values == 'float'){
                    self::$table->column($keys, Table::TYPE_FLOAT, 8);
                }else{
                    self::$table->column($keys, Table::TYPE_STRING, 255);
                }
            }
            self::$table->create();
        }
        return self::$instance;
    }

    /**
     * Determine whether to associate an array or a numeric array
     * return true for associative array，false numeric array
     */
    public static function isNumArray(array $arr)
    {
        $index = 0;
        if(empty($arr)) return true;
        foreach (array_keys($arr) as $key){
            if($index++ != $key) return false;
        }
        return true;
    }

    public function set($keys, $data)
    {
        $check = array_keys(self::$config);
        foreach ($check as $value){
            if(!isset($data[$value])){
                $data[$value] = "";
            }
        }
        return self::$table->set($keys, $data);
    }

    public function get($keys)
    {
        return self::$table->get($keys);
    }

    public function del($keys)
    {
        return self::$table->del($keys);
    }

    public function exist($keys)
    {
        return self::$table->exist($keys);
    }
}
