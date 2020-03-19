<?php
namespace Lyignore\WxAuthorizedLogin\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function AuthorizedLogin()
    {
        // TODO: Implement AuthorizedLogin() method.
        // 外部代码调用，当逻辑判断用户登录后调用此方法

        // 继承观察者模式主体对象，调用notify方法通知观察者

        // 通知方法为向调用thrift客户端方法，向thrift服务器发送消息
    }
}
