<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
        <title>微信登录二维码</title>
        <style>
            html, body {
                height: 100%;
                margin: 0px;
            }
            .container{
                width: 100vw;
                height: 100vh;
                display: flex;
                justify-content: flex-start;
                flex-direction: column;
                align-items: center;
            }
            .entry{
                width: 20%;
                margin: 40px auto;
            }
            .scan-title{
                background-color: #303030;
                width: 300px;
                height: 60px;
                line-height: 60px;
                border-radius: 30px;
                text-align: center;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <view class="container" style="background-color:{{$color??'#3E3F42'}}">
            <img src="{{$result}}" alt="" class="entry">
            <div class="scan-title">请用微信扫码登录</div>
        </view>
    </body>
</html>
