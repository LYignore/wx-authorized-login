<?php
namespace Lyignore\WxAuthorizedLogin\Entities;

use Lyignore\WxAuthorizedLogin\Domain\Entities\TicketEntityInterface;

class Ticket implements TicketEntityInterface
{
    protected $identify;

    protected static $instance;

    protected function __construct($length)
    {
        $ticket = self::generateTicket($length);
        $this->setIdentify($ticket);
    }

    public function setIdentify($str)
    {
        $this->identify = $str;
    }

    public function getIdentify()
    {
        return $this->identify;
    }

    /**
     * Generate unique bill ID interface
     * @param $length Will generate a unique string twice the length you specify
     * @return string
     */
    public static function generateTicket($length=6)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * 单例模式输出
     * @param $length Output is 2 times the specified length
     * @return  Lyignore\WxAuthorizedLogin\Entities\Ticket
     */
    public static function getInstance($length=6)
    {
        if(!self::$instance){
            self::$instance = new self($length);
        }
        return self::$instance;
    }
}
