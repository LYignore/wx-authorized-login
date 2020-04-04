<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Entities;

use SplSubject;

interface LoginObserverEntityInterface extends \SplObserver
{
    /**
     * Decouple the observer and the observed, so that the notification method
     * no longer needs to input the observation subject class
     */
    public function decouplingUpdate();

    /**
     * Generate unique bill ID interface
     */
    public static function generateTicket($length);

    public function setIdentify($str);

    public function getIdentify();
}
