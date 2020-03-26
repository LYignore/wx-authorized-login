<?php
namespace src\Thrift\Server;

use Lyignore\WxAuthorizedLogin\Repositories\ServerRepository;

class LoginCommonService implements LoginCommonCallServiceIf
{
    protected $server;

    public function __construct(\Swoole\WebSocket\Server $websocketServer)
    {
        $this->server = $websocketServer;
    }

    public function notify($params)
    {
        $serverRepository = new ServerRepository();

        $result = $serverRepository->receiveNotifyMessage($params);
        if($result['return_code'] == 200){
            $LoginObserverData = $this->table->get($result['data']['ticket']);
            $this->server->push($LoginObserverData['fd'], $params);
        }
    }
}
