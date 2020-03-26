<?php
namespace Lyignore\WxAuthorizedLogin\Observer;

use Lyignore\WxAuthorizedLogin\Domain\Entities\LoginObserverEntityInterface;
use Lyignore\WxAuthorizedLogin\Tools\Tools;
use SplSubject;

class LoginObserver implements LoginObserverEntityInterface
{
    use Tools;
    protected $identify;

    protected static $instance;

    /**
     * Implementation of thrift login confirmation update interface,
     * The server that implements thrift login notification
     * @param $subject SplSubject
     * @return void
     */
    public function update(SplSubject $subject)
    {
        // TODO: Implement update() method.
    }

    /**
     * Decouple the observer and the observed, so that the notification
     * method no longer needs to input the observation subject class
     * @param $ticket unique identification of the observer
     * @return void
     */
    public function decouplingUpdate()
    {
        // TODO: Implement decouplingUpdate() method.
    }

    public function setIdentity($str)
    {
        $this->identify = $str;
    }

    public function getIdentity()
    {
        return $this->identify;
    }

    public function generateObserver($length = 6)
    {
        $ticket = self::generateTicket($length);
        $this->setIdentity($ticket);
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
}
