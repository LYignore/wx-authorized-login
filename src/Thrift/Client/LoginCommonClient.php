<?php
namespace src\Thrift\Client;

use Lyignore\WxAuthorizedLogin\ResponseTypes\StatusResponse;
use Thrift\Exception\TException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TFramedTransport;

class LoginCommonClient
{
    public function notify($params)
    {
        $config = config('websocketlogin.listern');
        try{
            $socket = new ClientTransport($config['uri'], $config['port']);
            $transport = new TFramedTransport($socket);
            $protocol = new TBinaryProtocol($transport);
            $client = new LoginCommonCallServiceClient($protocol);
            $ticket = $params['ticket'];
            $phone = $params['phone'];
            $sendData =compact('ticket', 'phone');
            $transport->open();
            $result = $client->notify($sendData);
            $transport->close();
            return $result;
        }catch(TException $e){
            throw new \Exception('客户端thrift出错');
        }
    }
}
