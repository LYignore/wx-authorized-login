<?php
namespace Lyignore\WxAuthorizedLogin\Entities;

use Lyignore\WxAuthorizedLogin\Domain\Entities\LoginSubjectEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\MemoryEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\ServerEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\TcpServerEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\WebsocketServerEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Repositories\ServerRepositoryInterface;

class WebsocketServer implements ServerEntityInterface, WebsocketServerEntityInterface
{
    public $config;

    public $server;

    public $listern;

    public $table;

    public function __construct(MemoryEntityInterface $memoryEntity, array $config=[])
    {
        $this->config = array_merge(config('websocketlogin.websocket'), $config);

        $this->table = $memoryEntity;

        $this->initServer();
    }

    public function initServer()
    {
        if(!$this->server instanceof \Swoole\WebSocket\Server){
            $config = $this->config;
            $this->server = new \Swoole\WebSocket\Server($config['uri'], $config['port']);
            $this->server->set([
                'worker_num'  => $config['worker_num'],
                'package_max_length' => $config['package_max_length'],
                'open_eof_check' => $config['open_eof_check'],
                'daemonize' => $config['daemonize'],
                'dispatch_mode' => $config['dispatch_mode']
            ]);
        }
        return $this->server;
    }

    public function initListenEvent(TcpServerEntityInterface $tcpServerEntity)
    {
        if(!$this->server instanceof \Swoole\WebSocket\Server){
            throw new \Exception('swoole的websocket服务还未开启');
        }
        try{
            $this->listern = $tcpServerEntity;
            $this->listern->on('connect', [$tcpServerEntity, 'listernConnect']);
            //$this->listern->on('receive', [$tcpServerEntity, 'listernReceive']);
            return $this->listern;
        }catch (\TException $e){
            throw new \Exception('监听启动失败');
        }
    }

    public function start(ServerRepositoryInterface $serverRepository)
    {
        $this->server->on("open", [$serverRepository, 'wsOpen']);
        $this->server->on("message", [$serverRepository, 'wsMessage']);
        $this->server->on("close", [$serverRepository, 'wsClose']);
        echo "开启websocket服务:";
        $this->server->start();
    }

    public function allClose()
    {
        echo "websocket服务器关闭".PHP_EOL;
        $this->server->close();
    }
}
