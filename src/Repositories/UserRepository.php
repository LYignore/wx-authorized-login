<?php
namespace Lyignore\WxAuthorizedLogin\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Entities\TicketEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Repositories\UserRepositoryInterface;
use Lyignore\WxAuthorizedLogin\Entities\Ticket;
use Lyignore\WxAuthorizedLogin\Models\Login;
use Lyignore\WxAuthorizedLogin\Thrift\Client\LoginCommonClient;

class UserRepository implements UserRepositoryInterface
{
    public function authorizedLogin(TicketEntityInterface $ticketEntity, array $params)
    {
        $ticket = $ticketEntity->getIdentify();
        $items = json_encode($params);
        $data = compact('ticket', 'items');
        if(Login::create($data)){
            $loginCommonClient = new LoginCommonClient();
            return $loginCommonClient->notify($ticket);
        }else{
            throw new \Exception('Logged-in user information is not recorded');
        }
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
