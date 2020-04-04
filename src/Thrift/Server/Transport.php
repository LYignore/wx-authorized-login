<?php
namespace Lyignore\WxAuthorizedLogin\Thrift\Server;

use Swoole\Server;
use Thrift\Exception\TTransportException;
use Thrift\Transport\TTransport;

class Transport extends TTransport
{
    protected $server;

    protected $fd=-1;

    protected $data = '';

    protected $offset = 0;

    public function __construct(Server $server, $fd, $data)
    {
        $this->server = $server;
        $this->fd = $fd;
        $this->data = $data;
    }

    public function isOpen()
    {
        return $this->fd > -1;
    }

    public function open()
    {
        if($this->isOpen()){
            throw new TTransportException('Swoole Transport already connected.', TTransportException::ALREADY_OPEN);
        }
    }

    public function close()
    {
        if(!$this->isOpen()){
            throw new TTransportException('Swoole Transport not open.', TTransportException::NOT_OPEN);
        }
        $this->server->close($this->fd, true);
        $this->fd = -1;
    }

    /**
     * Read some data into the array.
     *
     * @param int $len How much to read
     * @return string The data that has been read
     * @throws TTransportException if cannot read any more data
     */
    public function read($len)
    {
        if(strlen($this->data)-$this->offset < $len){
            throw new TTransportException('Swoole Transport[' . strlen($this->data) . '] read ' . $len . ' bytes failed.');
        }
        $data = substr($this->data, $this->offset, $len);
        $this->offset += $len;
        return $data;
    }

    /**
     * Writes the given data out.
     *
     * @param string $buf The data to write
     * @throws TTransportException if writing fails
     */
    public function write($buf)
    {
        if (!$this->isOpen()) {
            throw new TTransportException('Swoole Transport not open.', TTransportException::NOT_OPEN);
        }
        $this->server->send($this->fd, $buf);
    }
}
