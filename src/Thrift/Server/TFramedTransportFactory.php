<?php
namespace src\Thrift\Server;

use Thrift\Factory\TTransportFactory;
use Thrift\Transport\TFramedTransport;

class TFramedTransportFactory extends TTransportFactory
{
    /**
     * @static
     * @param TTransport $transport
     * @return TTransport
     */
    public static function getTransport(TTransport $transport)
    {
        return new TFramedTransport($transport);
    }
}
