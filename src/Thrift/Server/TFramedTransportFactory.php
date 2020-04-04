<?php
namespace Lyignore\WxAuthorizedLogin\Thrift\Server;

use Thrift\Factory\TTransportFactory;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TTransport;

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
