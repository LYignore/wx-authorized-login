<?php
namespace Lyignore\WxAuthorizedLogin\Entities;

use Lyignore\WxAuthorizedLogin\Domain\Entities\LoginSubjectEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\MemoryEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\ServerEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\WebsocketServerEntityInterface;
use Lyignore\WxAuthorizedLogin\ResponseTypes\StatusResponse;
use src\Thrift\Server\LoginCommonCallServiceProcessor;
use src\Thrift\Server\LoginCommonService;
use src\Thrift\Server\ServerTransport;
use src\Thrift\Server\Transport;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Transport\TFramedTransport;

class TcpServer implements ServerEntityInterface
{
    public $config;

    public $server;

    public $mainServer;

    public $table;

    public $loginSubject;

    public $loginObserverPool;

    public static $receiveFun;

    public function __construct(
        WebsocketServerEntityInterface $websocketServerEntity,
        MemoryEntityInterface $memoryEntity,
        LoginSubjectEntityInterface $loginSubjectEntity,
        array $config=[])
    {
        $this->config = array_merge(config('websocketlogin.listern'), $config);

        $this->table = $memoryEntity;

        $this->mainServer = $websocketServerEntity;

        $this->initLoginSubject($loginSubjectEntity);

        $this->initServer();
    }

    public function initServer()
    {
        if(!$this->server instanceof \Swoole\Server){
            $config = $this->config;
            $this->server = $this->mainServer->addListener($config['uri'], $config['port'], $config['type']);
            $this->server->set([
                'worker_num'  => $config['worker_num'],
                'dispatch_mode' => $config['dispatch_mode'],
                'mode' => $config['mode'],
                'open_length_check' => $config['open_length_check'],
                'package_length_type' => $config['package_length_type'],
                'package_body_offset' => $config['package_body_offset'],
                'package_length_offset' => $config['package_length_offset']
            ]);
        }
        return $this->server;
    }

    public function initLoginSubject(LoginSubjectEntityInterface $loginSubjectEntity)
    {
        if(!$this->loginSubject instanceof LoginSubjectEntityInterface){
            $this->loginSubject = $loginSubjectEntity;
        }
        return $this->loginSubject;
    }

    public static function listernConnect($serv, $fd)
    {
        echo 'Listern thrift: Connect'.PHP_EOL;
    }

    public static function listernReceive($serv, $fd, $from_id, $data)
    {
        if(!self::$receiveFun instanceof \Closure){
            throw new \Exception('请先绑定接收函数');
        }
        $callback = self::$receiveFun;
        return $callback($serv, $fd, $from_id, $data);
    }

    public function bindReceive(\Closure $callback)
    {
        self::$receiveFun = $callback;
        return true;
    }
}
