<h1 align="center"> wx-authorized-login </h1>

<p align="center"> The PC side USES websocket authorization, the third party on the mobile side obtains user information, and the PC side logs in real time after authentication</p>


## Installing

Install import plug-in
> $ composer require lyignore/wx-authorized-login -vvv

laravel Introduce the service and the corresponding configuration information and front-end interface
> $ php artisan vendor:publish --provider="Lyignore\WxAuthorizedLogin\LoginServiceProvider"

A database table is introduced to record the login information
> $ php artisan migrate

Start websocket service
> $ php artisan websocket: start
## Usage

```angularjs
// The front end connects to the websocket, and the successful connection returns a unique login identification ticket
// Assuming a successful websocket connection, the ticket obtained is '729ed9b40ad8'

$ticket = '729ed9b40ad8';

// Gets the data stream for the login qr code

$clientRepository = new Lyignore\WxAuthorizedLogin\Repositories\ClientRepository();
$ticketObj = $clientRepository->getTicket($ticket);
$result = $clientRepository->initUserLoginEntry($ticketObj, []);

// After the third party authorization, the calling background obtains the authenticated user information and then performs the PC side login

$ticket = $request->input('ticket');
$userRepository = new Lyignore\WxAuthorizedLogin\Repositories\UserRepository();
$ticketObj = $userRepository->getTicket($ticket);
$ticketObj->setIdentify($ticket);
$result = $userRepository->authorizedLogin($ticketObj, ['phone'=> '15641566789', 'user' => 'ly']);

// The PC front-end websocket gets the array passed by the second argument of authorizedLogin
```

## Collcation
```angularjs
// If using applet login, configure next time
// open file "/config/websocketlogin.php"
return [
    'helpfunction' => true, // 插件自定义的一些方法，命名和laravel方法冲突，引入laravel后设置成false即可
    'websocket' => [    // websocket配置的监听端口号和地址等信息
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
        'appid' => 'wxde4673a351awe123',    // 小程序的APPID
        'secret'=> '2e9a9eec48f4824df22de90a422ase34',  // 小程序的SECRET
        'path'  => 'page/index/index',  // 小程序生成二维码，配置二维码的路由地址
    ],
    'memory' => [
        'fd' => 'int',
        'ticket' => 'string',
        'status' => 'int',
    ],
    'entry' => [
        'driver' => 'Lyignore\WxAuthorizedLogin\ResponseTypes\WechatQrResponse',    // 配置授权登录入口的驱动
    ]
];
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/LYignore/wx-authorized-login/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/LYignore/wx-authorized-login/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
