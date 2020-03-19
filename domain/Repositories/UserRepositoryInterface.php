<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Repositories;

interface UserRepositoryInterface
{
    /**
     * Authorized login of user's third-party platform
     */
    public function AuthorizedLogin();
}
