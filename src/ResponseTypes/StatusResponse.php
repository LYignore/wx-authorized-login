<?php
namespace Lyignore\WxAuthorizedLogin\ResponseTypes;

class StatusResponse
{
    public static function success($data=[])
    {
        return [
            'return_code' => 200,
            'return_msg' => 'success',
            'data' => $data
        ];
    }

    public static function uploadSuccess($data)
    {
        return [
            'return_code' => 201,
            'return_msg' => 'Picture uploaded successfully',
            'data' => $data,
        ];
    }

    public static function openWebsocket($data)
    {
        return [
            'return_code' => 202,
            'return_msg' => 'Binding to monitor',
            'data' => $data,
        ];
    }

    public static function loginSuccess($data)
    {
        return [
            'return_code' => 203,
            'return_msg' => 'login successful',
            'data' => $data,
        ];
    }

    public static function authError($data=[])
    {
        return [
            'return_code' => 401,
            'return_msg' => 'appid authentication error',
            'data' => $data
        ];
    }

    public static function discernError()
    {
        return [
            'return_code' => 402,
            'return_msg' => 'Picture uploaded discern server error',
        ];
    }

    public static function typeError($mess='input type error', $data=[])
    {
        return [
            'return_code' => 428,
            'return_msg' => $mess,
            'data' => $data
        ];
    }

}
