<?php
namespace Lyignore\WxAuthorizedLogin\Thrift\Server;

use Lyignore\WxAuthorizedLogin\Domain\Entities\TcpServerEntityInterface;
use Thrift\Exception\TTransportException;
use Thrift\Server\TServerTransport;

class ServerTransport extends TServerTransport
{
    /**
     * @var array 服务器选项
     */
    public $options = [
        'worker_num' => 1,
        'dispatch_mode' => 1, //1: 轮循, 3: 争抢
        'open_length_check' => true, //打开包长检测
        'package_max_length' => 8192000, //最大的请求包长度,8M
        'package_length_type' => 'N', //长度的类型，参见PHP的pack函数
        'package_length_offset' => 0,   //第N个字节是包长度的值
        'package_body_offset' => 4,   //从第几个字节计算长度
    ];

    /**
     * @var SwooleListenServer
     */
    public $server;

    /**
     * SwooleServerTransport constructor.
     */
    public function __construct(\Swoole\Server\Port $server, $options=[])
    {
        $this->server =$server;
//        $options = array_merge($this->options, $options);
//        $this->server->set($options);
    }

    /**
     * listen for new clients
     * @return void
     */
    public function listen()
    {
//        if(!$this->server->start()){
//            throw new TTransportException('Swoole ServerTransport start failed.', TTransportException::UNKNOWN);
//        }
    }

    /**
     * close tcp server
     */
    public function close()
    {
        $this->server->shutdown();
    }

    /**
     * swoole Gets the request through the callback function，
     * You cannot call the accept method
     * @return TTransport
     */
    protected function acceptImpl()
    {
        return null;
    }
}
