<?php
namespace Lyignore\WxAuthorizedLogin\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Entities\TicketEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Repositories\UserRepositoryInterface;
use Lyignore\WxAuthorizedLogin\Entities\Ticket;
use Lyignore\WxAuthorizedLogin\Thrift\Client\LoginCommonClient;

class UserRepository implements UserRepositoryInterface
{
    public function authorizedLogin(TicketEntityInterface $ticketEntity, array $params)
    {
        $ticket = $ticketEntity->getIdentify();
        $phone = $params['phone']??"匿名用戶";
        $data = compact('ticket', 'phone');
        $loginCommonClient = new LoginCommonClient();
        return $loginCommonClient->notify($data);
    }

    /**
     * Gets the Ticket for the specified identity identifier
     * @param string $str identifier of ticket
     * @return entity Lyignore\WxAuthorizedLogin\Entities\Ticket
     */
    public function getTicket($str)
    {
        $ticket = Ticket::getInstance();
        $ticket->setIdentify($str);
        return $ticket;
    }
}
