<?php
namespace Lyignore\WxAuthorizedLogin\Tools;

class Config implements \ArrayAccess
{
    protected $config;
    protected $path = '';
    protected $loadFile;

    private static $instance;

    protected function __construct(array $config = [])
    {
        if(!empty($config)){
            foreach ($config as $key => $val){
                $this->config[$key] = $val;
            }
        }
    }

    /**
     * 单例模式
     */
    public static function getInstance(array $config=[])
    {
        if(!self::$instance){
            self::$instance = new self($config);
        }
        return self::$instance;
    }


    public function get($key, $default=[])
    {
        $config = $this->config;

        if(isset($config[$key])){
            return $config[$key];
        }

        if(strpos($key, '.') == false){
            return $default;
        }

        foreach (explode('.', $key) as $segment){
            if(!is_array($config)|| !array_key_exists($segment, $config)){
                if(isset($config[$segment])){
                    return $config[$segment];
                }
                return $default;
            }
            $config = $config[$segment];
        }
        return $config;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->config);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        if(isset($this->config[$offset])){
            $this->config[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        if(isset($this->config[$offset])){
            unset($this->config[$offset]);
        }
    }
}
