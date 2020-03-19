<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Repositories;

interface ClientRepositoryInterface
{
    /**
     * Initialize user login entry
     */
    public function initUserLoginEntry();

    /**
     * After the user is authorized successfully
     * confirm the user login in real time
     */
    public function confirmUserLogin();
}
