<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Entities\TicketEntityInterface;
interface UserRepositoryInterface
{
    /**
     * Authorized login of user's third-party platform
     */
    public function authorizedLogin(TicketEntityInterface $ticketEntity, array $data);
}
