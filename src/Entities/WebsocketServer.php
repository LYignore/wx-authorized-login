<?php
namespace Lyignore\WxAuthorizedLogin\Entities;

use Lyignore\WxAuthorizedLogin\Domain\Entities\WebsocketServerEntityInterface;

class WebsocketServer implements WebsocketServerEntityInterface
{
    protected $port='8000';
    protected $server;      // swoole的实例化
    protected $table;       // 内存结构表
    protected $listern;     // 监听进程
    protected $user;

    public function __construct()
    {
        // 初始化swoole的websocket配置
        $this->initServerConfig();

        // 初始化共享存储
        $this->createTable();

        // 监听用户登录通知事件
        $this->initListeningEvent();
    }

    public function initSocketServerConfig(array $config=[])
    {
        if(!$this->server instanceof \Swoole\WebSocket\Server){
            $config = array_merge(config('websocketlogin.websocket'), $config);
            $this->server = new \Swoole\WebSocket\Server($config['uri'], $config['port']);
            $this->server->set([
                'worker_num'  => $config['worker_num'],
                'package_max_length' => $config['package_max_length'],
                'open_eof_check' => $config['open_eof_check'],
                'daemonize' => $config['daemonize'],
            ]);
        }
        return $this->server;
    }

    public function initListeningEvent(array $config=[])
    {
        // 调用LoginSubject绑定观察者，实现thrift的客户端
    }

    /**
     * Bind the callback function of websocket and call start to start
     */
    public function start()
    {
        $this->server->on("open", [$this, 'wsOpen']);
        $this->server->on("message", [$this, 'wsMessage']);
        $this->server->on("close", [$this, 'wsClose']);

        $this->server->start();
    }

    public function wsOpen($server, $request)
    {
        // TODO: Implement wsOpen() method.
    }

    public function wsMessage($server, $frame)
    {
        // TODO: Implement wsMessage() method.
    }

    public function wsClose($server, $frame)
    {
        // TODO: Implement wsClose() method.
    }

    public function allClose()
    {
        echo "websocket服务器关闭".PHP_EOL;
        $this->server->close();
    }

    public function createTable()
    {

    }
}
