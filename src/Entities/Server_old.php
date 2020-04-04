<?php
namespace Lyignore\WxAuthorizedLogin\Entities;

use Lyignore\WxAuthorizedLogin\Domain\Entities\LoginSubjectEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\ServerEntityInterface;
use Lyignore\WxAuthorizedLogin\ResponseTypes\StatusResponse;
use Lyignore\WxAuthorizedLogin\Thrift\Server\LoginCommonCallServiceProcessor;
use Lyignore\WxAuthorizedLogin\Thrift\Server\LoginCommonService;
use Lyignore\WxAuthorizedLogin\Thrift\Server\ServerTransport;
use Lyignore\WxAuthorizedLogin\Thrift\Server\Transport;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Transport\TFramedTransport;

class Server implements ServerEntityInterface
{
    protected $port='8000';
    public $server;      // swoole的实例化
    public $table;       // 内存结构表
    protected $listern;     // 监听进程
    protected $loginSubject;
    protected $loginObserverPool;

    public function __construct()
    {
        // 初始化swoole的配置
        $this->initServerConfig();

        // 初始化共享存储
        $this->createShareMemory();

        // 单例模式实例化被观察者，连接池存储观察者
        $this->initLoginSubject();

        // 监听用户登录通知事件
        $this->initListeningEvent();
    }

    public function initServerConfig(array $config=[])
    {
        if(!$this->server instanceof \Swoole\WebSocket\Server){
            $config = array_merge(config('websocketlogin.websocket'), $config);
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

    /**
     * Enable monitoring TCP service interface
     */
    public function initListeningEvent()
    {
        if(!$this->server instanceof \Swoole\WebSocket\Server){
            throw new \Exception('swoole的websocket服务还未开启');
        }
        try{
            $processor = new LoginCommonCallServiceProcessor(new LoginCommonService($this->server));
            $tFactory = new TTransportFactory();
            $pFactory = new TBinaryProtocolFactory();
            $transport = new ServerTransport($this->server);
            $server = new \src\Thrift\Server\Server($processor, $transport, $tFactory, $tFactory, $pFactory, $pFactory);
            $server->serve();
        }catch (\TException $e){
            throw new \Exception('thrift启动失败');
        }
    }

    /**
     * Initialize TCP listening service
     */
    public function initTcpListenServer()
    {
        $listenConfig = config('websocketlogin.listern');
        $this->listern = $this->server->addListener($listenConfig['uri'], $listenConfig['port'], $listenConfig['type']);
        return $this->listern;
    }

    public function initLoginSubject()
    {
        if(!$this->loginSubject instanceof LoginSubjectEntityInterface){
            $this->loginSubject = new LoginSubect();
        }
        return $this->loginSubject;
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
        $observer = new LoginObserver();
        $observer->generateObserver();
        $this->loginSubject->attach($observer);
        $ticket = $observer->getIdentify();
        $fd = $request->fd;
        $memoryData = compact('fd', 'ticket');
        $this->table->set($ticket, $memoryData);

        if($this->observerBindingUid($fd, $ticket)){
            $return = StatusResponse::openWebsocket($ticket);
            $this->server->push($fd, \GuzzleHttp\json_encode($return));
        }
    }

    public function wsMessage($server, $frame)
    {
        // TODO: Implement wsMessage() method.
    }

    public function wsClose($server, $frame, $reactorId)
    {
        if($reactorId>0){
            $fdInfo = $this->server->getClientInfo($frame);
            $ticket = $fdInfo['uid'];
            if($this->table->exist($ticket)){
                $observer = new LoginObserver();
                $observer->setIdentify($ticket);
                $this->loginSubject->detach($observer);
            }
        }

        echo "{$frame}连接关闭".PHP_EOL;
    }

    public function allClose()
    {
        echo "websocket服务器关闭".PHP_EOL;
        $this->server->close();
    }

    /**
     * Connection binding observer
     */
    public function observerBindingUid($fd, $ticket)
    {
        return $this->server->bind($fd, $ticket);
    }


    /**
     * Initialize Shared storage
     */
    public function createShareMemory($config = [])
    {
        $config = array_merge(config('websocketlogin.memory'), $config);
        $this->table = ShareMemory::getInstance($config);
    }
}
