<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Repositories;

interface ServerRepositoryInterface
{
    public function receiveNotifyMessage($params);
}
