<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Entities;

interface MemoryEntityInterface
{
    public function set($key, $data);

    public function get($key);

    public function del($key);

    public function exist($key);

    public static function getInstance(array $config);
}
