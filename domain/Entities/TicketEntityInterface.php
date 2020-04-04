<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Entities;

interface TicketEntityInterface
{
    /**
     * Generate unique bill ID interface
     */
    public static function generateTicket($length);

    public function setIdentify($str);

    public function getIdentify();
}
