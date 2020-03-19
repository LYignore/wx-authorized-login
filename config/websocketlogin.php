<?php
return [
    'helpfunction' => true,
    'websocket' => [
        'uri'  => '0.0.0.0',
        'port' => 8000,
        'worker_num'  => 8,
        'package_max_length' => 40 * 1024 * 1024,
        'open_eof_check' => true,
        'original_name' => true,
        'daemonize' => false,
    ],
    'listern' => [
        'uri'  => '0.0.0.0',
        'port' => '8081',
        'type' => SWOOLE_SOCK_TCP,
    ],
    'wxapplet' => [
        'wx_qr_logos' => 'https://api.weixin.qq.com/wxa/getwxacodeunlimit',
        'get_wx_openid' => 'https://api.weixin.qq.com/sns/jscode2session',
        'get_token_uri' => 'https://api.weixin.qq.com/cgi-bin/token',
        'appid' => 'wxde4673a351a17c43',
        'secret'=> '2f9a9eec48f4824df22de90a422ff479'
    ]
];
