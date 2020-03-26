<?php
namespace src\Thrift\Server;

use Thrift\Server\TServer;
use Swoole\Server as SwooleServer;

class Server extends TServer
{
    public function serve()
    {
        $this->transport_->server->on('receive', [$this, 'handleReceive']);
//        $this->transport_->listen();
    }

    public function stop()
    {
        $this->transport_->close();
    }

    /**
     * Processing RPC requests
     */
    public function handleReceive(SwooleServer $server, $fd, $fromId, $data)
    {
        $transport = new Transport($server, $fd, $data);
        $inputTransport = $this->inputTransportFactory_->getTransport($transport);
        $outputTransport= $this->outputTransportFactory_->getTransport($transport);
        $inputProtocol = $this->inputProtocolFactory_->getProtocol($inputTransport);
        $outputProtocol= $this->outputProtocolFactory_->getProtocol($outputTransport);
        $this->processor_->process($inputProtocol, $outputProtocol);
    }
}
