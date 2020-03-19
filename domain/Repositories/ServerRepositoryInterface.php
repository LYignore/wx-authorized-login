<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Repositories;

interface ServerRepositoryInterface
{
    public function bindObserver();

    public function unbindObserver();

    public function receiveNotifyMessage();
}
