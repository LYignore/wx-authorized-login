<?php
namespace Lyignore\WxAuthorizedLogin\Repositories;

use Lyignore\WxAuthorizedLogin\Domain\Entities\LoginSubjectEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\MemoryEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\WebsocketServerEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Repositories\ServerRepositoryInterface;
use Lyignore\WxAuthorizedLogin\Entities\ShareMemory;
use Lyignore\WxAuthorizedLogin\Entities\TcpServer;
use Lyignore\WxAuthorizedLogin\Entities\WebsocketServer;
use Lyignore\WxAuthorizedLogin\Observer\LoginObserver;
use Lyignore\WxAuthorizedLogin\Observer\LoginSubect;
use Lyignore\WxAuthorizedLogin\ResponseTypes\StatusResponse;
use Lyignore\WxAuthorizedLogin\Thrift\Server\LoginCommonCallServiceProcessor;
use Lyignore\WxAuthorizedLogin\Thrift\Server\LoginCommonService;
use Lyignore\WxAuthorizedLogin\Thrift\Server\ServerTransport;
use Lyignore\WxAuthorizedLogin\Thrift\Server\TFramedTransportFactory;
use Swoole\WebSocket\Server;
use Thrift\Exception\TException;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Transport\TFramedTransport;
use Lyignore\WxAuthorizedLogin\Thrift\Server\Server as TServer;

class ServerRepository implements ServerRepositoryInterface
{
    protected static $websocketServer;

    protected static $listernServer;

    protected static $table;

    protected static $loginSubject;

    public function receiveNotifyMessage($inputParams)
    {
        $params = $this->formatInput($inputParams);
        $this->verifyInput($params);

        if(!self::$loginSubject instanceof LoginSubjectEntityInterface){
            throw new \Exception('未绑定登录监听主体');
        }
        $ticket = $params['ticket'];
        $memoryData = self::$table->get($ticket);
        if(!empty($memoryData)){
            self::$websocketServer->server->push($memoryData['fd'], \GuzzleHttp\json_encode($params));
        }
        return self::$loginSubject->decouplingNotify($params);
    }

    public static function wsOpen($server, $request)
    {
        $observer = new LoginObserver();
        $observer->generateObserver();
        if(!self::$loginSubject instanceof  LoginSubjectEntityInterface)
        {
            throw new \Exception('Please call "initLoginSubject" function binding obsubject');
        }
        if(!self::$table instanceof  MemoryEntityInterface)
        {
            throw new \Exception('Please init shareMemory obj');
        }
        self::$loginSubject->attach($observer);
        $ticket = $observer->getIdentify();
        $fd = $request->fd;
        $memoryData = compact('fd', 'ticket');

        self::$table->set($ticket, $memoryData);

        $return = StatusResponse::openWebsocket($ticket);
        $server->push($fd, \GuzzleHttp\json_encode($return));
//        if(self::observerBindingUid($fd, $ticket)){
//            $return = StatusResponse::openWebsocket($ticket);
//            $server->push($fd, \GuzzleHttp\json_encode($return));
//        }
    }

    public static function wsMessage($server, $frame)
    {
        $server->push($frame, \GuzzleHttp\json_encode(StatusResponse::typeError()));
    }

    public static function wsClose($server, $fd, $reactorId)
    {
        if($reactorId>0){
            $fdInfo = $server->getClientInfo($fd);
            $ticket = $fdInfo['uid'];
            if(self::$table->exist($ticket)){
                $observer = new LoginObserver();
                $observer->setIdentify($ticket);
                self::$loginSubject->detach($observer);
            }
        }

        echo "{$fd}连接关闭".PHP_EOL;
    }

    public function start()
    {
        self::$table = ShareMemory::getInstance();

        self::$websocketServer = new WebsocketServer(self::$table);

        $this->initLoginSubject();

        self::$listernServer = new TcpServer(self::$websocketServer, self::$table, self::$loginSubject);
        //self::$listernServer->bindReceive();
        $this->initListenEvent();
        self::$websocketServer->start($this);
    }


    protected function initLoginSubject()
    {
        if(!self::$loginSubject instanceof LoginSubjectEntityInterface){
            self::$loginSubject = new LoginSubect();
        }
        return self::$loginSubject;
    }

    protected function initListenEvent()
    {
        if(!self::$websocketServer->server instanceof Server){
            throw new \Exception('swoole的websocket服务还未开启');
        }
        try{
            $processor = new LoginCommonCallServiceProcessor(new LoginCommonService(self::$websocketServer, self::$table));
            //$processor = new Lyignore\WxAuthorizedLogin\Thrift\Server\LoginCommonCallServiceProcessor(new LoginCommonService(self::$websocketServer));
            //$tFactory = new TTransportFactory();
            //$tFactory = new TFramedTransport();
            $tFactory = new TFramedTransportFactory();
            $pFactory = new TBinaryProtocolFactory();
            $transport = new ServerTransport(self::$listernServer->server);
            $server = new TServer($processor, $transport, $tFactory, $tFactory, $pFactory, $pFactory);
            $server->serve();
        }catch(TException $e){
            throw new \Exception('thrift启动失败');
        }
    }


    public function formatInput($params)
    {
        if(!is_array($params)){
            $params = \GuzzleHttp\json_decode($params,true);
        }
        $ticket = $params['ticket']??'';
        $phone = $params['phone']??'';
        return compact('phone', 'ticket');
    }

    public function verifyInput(array $params)
    {
        if(empty($params['phone'])){
            throw new \Exception('电话参数缺失');
        }
        if(empty($params['ticket'])){
            throw new \Exception('ticket参数缺失');
        }
        return $params;
    }

    /**
     * Connection binding observer
     */
    public static function observerBindingUid($fd, $ticket)
    {
        return self::$websocketServer->server->bind($fd, $ticket);
    }
}
