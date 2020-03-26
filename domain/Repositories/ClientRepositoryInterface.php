<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Entities\TicketEntityInterface;

interface ClientRepositoryInterface
{
    /**
     * Initialize user login entry
     * @param $ticket The unique ID of the ticket class
     * @param $params User-defined entry passes parameters
     * @return
     */
    public function initUserLoginEntry(TicketEntityInterface $ticket, array $params);

    /**
     * After the user is authorized successfully
     * confirm the user login in real time
     */
    public function confirmUserLoginHandle();
}
