<?php
namespace Lyignore\WxAuthorizedLogin\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Repositories\ServerRepositoryInterface;

class ServerRepository implements ServerRepositoryInterface
{
    public function bindObserver()
    {
        // TODO: Implement bindObserver() method.
    }

    public function unbindObserver()
    {
        // TODO: Implement unbindObserver() method.
    }

    public function receiveNotifyMessage()
    {
        // TODO: Implement receiveNotifyMessage() method.
        // 实现客户登录的服务端
    }

    public function start()
    {
        // 启动websocket服务

        // 初始化共享存储空间

        // 绑定登录观察者
    }

    public function allClose()
    {
        // 解绑全部登录观察者
    }
}
