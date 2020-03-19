<?php
namespace Lyignore\WxAuthorizedLogin\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function initUserLoginEntry()
    {
        // 确定websocket开启

        // 确定返回模式，现阶段定义使用微信小程序，返回小程序二维码
    }

    public function confirmUserLogin()
    {
        // 确定接收到登录成功的接口消息

        // 通过websocket向前端发送登录成功信息
    }
}
