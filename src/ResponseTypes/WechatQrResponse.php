<?php
namespace Lyignore\WxAuthorizedLogin\ResponseTypes;

use Lyignore\WxAuthorizedLogin\Tools\Tools;

class WechatQrResponse
{
    use Tools;
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Generate wechat access_token
     */
    public function generateAccessToken()
    {
        $uri = $this->config['get_token_uri'];
        $config = [
            'grant_type'=> 'client_credential',
            'appid'     => $this->config['appid'],
            'secret'    => $this->config['secret']
        ];
        $url = $uri.'?'.http_build_query($config);
        $https = self::getInstanceHttp();
        try{
            $response = $https->request('get', $url, ['verify' => false]);
            return \GuzzleHttp\json_decode($response->getBody(), true);
        }catch (\Exception $e){
            throw new \Exception('微信获取access_token失败');
        }
    }

    /**
     * Generate wechat login QR code
     * @param $scenes string Specify QR code transfer parameters less than 32 bits
     * @return array Data stream pictures that can be rendered directly in the browser
     */
    public function generateEntry($scenes)
    {
        $uri = $this->config['wx_qr_logos'];
        $tokenResponse = $this->generateAccessToken();
        try{
            $params = ['access_token' => $tokenResponse['access_token']];
            $url = $uri.'?'.http_build_query($params);
            if(mb_strlen($scenes,'UTF8')>32){
                throw new \Exception('Scene the specified string length cannot exceed 32 bits');
            }
            $body = [
                "scene" => $scenes,
                "path"  => $this->config['path']
            ];
            $https = self::getInstanceHttp();
            $response = $https->request('post', $url, [
                'verify' => false,
                'json'  => $body
            ]);
//            return [
//                'status_code' => $response->getStatusCode(),
//                'info' => $this->binaryImageRedering($response->getBody(),'image/png')
//            ];
            return $this->binaryImageRedering($response->getBody(),'image/png');
        }catch (\Exception $e){
            throw new \Exception('获取微信登录二维码失败');
        }
    }
}
