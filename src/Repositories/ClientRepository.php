<?php
namespace Lyignore\WxAuthorizedLogin\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Entities\TicketEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Repositories\ClientRepositoryInterface;
use Lyignore\WxAuthorizedLogin\Entities\Ticket;
use Lyignore\WxAuthorizedLogin\Observer\LoginObserver;
use Lyignore\WxAuthorizedLogin\Observer\LoginSubect;

class ClientRepository implements ClientRepositoryInterface
{
    public function getTicket($str)
    {
        $ticket = Ticket::getInstance();
        $ticket->setIdentify($str);
         return $ticket;
    }

    public function initUserLoginEntry(TicketEntityInterface $ticket, array $params = [])
    {
        // 确定返回模式，现阶段定义使用微信小程序，返回小程序二维码
        $loginDriver = config('websocketlogin.entry.driver');
        try{
            // 初始化微信小程序配置信息
            $wxapplet = config('websocketlogin.wxapplet');
            $loginEntryDriver = new $loginDriver($wxapplet);
            // 生成微信登录图片
            $ticketIdentify = $ticket->getIdentify();
            return $loginEntryDriver->generateEntry($ticketIdentify);
        }catch (\Exception $e){
            throw new \Exception('驱动缺失');
        }
    }

    /*
     * 用户通过login的thrift接口,User类通知Client类用户登录，Client类实现接受消息后向Server类的
     * websocket的发送消息接口向前端用户发送消息，所以此方法需要在Server类初始化的时候注入Server类中
     * 作为websocket发送消息的回调方法
     */
    public function confirmUserLoginHandle()
    {
        // 确定接收到登录成功的接口消息

        // 通过websocket向前端发送登录成功信息
    }

    /**
     *
     */
    public function loginAttach()
    {

    }
}
