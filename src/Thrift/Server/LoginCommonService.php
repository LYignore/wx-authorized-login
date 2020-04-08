<?php
namespace Lyignore\WxAuthorizedLogin\Thrift\Server;

use Lyignore\WxAuthorizedLogin\Domain\Entities\MemoryEntityInterface;
use Lyignore\WxAuthorizedLogin\Domain\Entities\WebsocketServerEntityInterface;
use Lyignore\WxAuthorizedLogin\Repositories\ServerRepository;

class LoginCommonService implements LoginCommonCallServiceIf
{
    protected $websocketServer;

    protected $table;

    public function __construct(WebsocketServerEntityInterface $websocketServer, MemoryEntityInterface $memoryEntity)
    {
        $this->websocketServer = $websocketServer;
        $this->table = $memoryEntity;
    }

    public function notify($ticket)
    {
        $serverRepository = new ServerRepository();

        $loginSubject = $serverRepository->receiveNotifyMessage($ticket);
        $pushData = json_encode($loginSubject->params);
        if($loginSubject->notifyResult == 200){
            $LoginObserverData = $this->table->get($loginSubject->params['ticket']);
            $this->websocketServer->server->push($LoginObserverData['fd'], $pushData);
        }
        $response = new Response();
        $response->code = 200;
        $response->msg = "login succcess";
        $response->data = $pushData;
        return $response;
    }
}
