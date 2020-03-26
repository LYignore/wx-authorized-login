<?php
return [
    'helpfunction' => true,
    'websocket' => [
        'uri'  => '0.0.0.0',
        'port' => 8000,
        'worker_num'  => 8,
        'dispatch_mode' => 5,
        'package_max_length' => 8 * 1024 * 1024,
        'open_eof_check' => true,
        'original_name' => true,
        'daemonize' => false,
    ],
    'listern' => [
        'uri'  => '0.0.0.0',
        'port' => 8081,
        'worker_num' => 1,
        'dispatch_mode' => 1,
        'open_length_check' => true,
        'package_length_type' => 'N',
        'package_body_offset' => 4,
        'package_length_offset' => 0,
        'mode' => SWOOLE_PROCESS,
        'type' => SWOOLE_SOCK_TCP,
    ],
    'wxapplet' => [
        'wx_qr_logos' => 'https://api.weixin.qq.com/wxa/getwxacodeunlimit',
        'get_wx_openid' => 'https://api.weixin.qq.com/sns/jscode2session',
        'get_token_uri' => 'https://api.weixin.qq.com/cgi-bin/token',
        'appid' => 'wxde4673a351a17c43',
        'secret'=> '2f9a9eec48f4824df22de90a422ff479',
        'path'  => 'page/index/index',
    ],
    'memory' => [
        'fd' => 'int',
        'ticket' => 'string',
        'status' => 'int',
    ],
    'entry' => [
        'driver' => 'Lyignore\WxAuthorizedLogin\ResponseTypes\WechatQrResponse',
    ]
];
