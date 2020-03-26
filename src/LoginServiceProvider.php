<?php
namespace Lyignore\WxAuthorizedLogin;

use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Lyignore\LaravelOauth2\Design\AuthenticationServer;
use Lyignore\LaravelOauth2\Design\AuthorizationServer;
use Lyignore\LaravelOauth2\Design\CryptKey;
use Lyignore\LaravelOauth2\Design\Grant\AuthCodeGrant;
use Lyignore\LaravelOauth2\Design\Grant\ClientCredentialsGrant;
use Lyignore\LaravelOauth2\Design\Grant\PasswordGrant;
use Lyignore\LaravelOauth2\Design\Grant\RefreshTokenGrant;
use Lyignore\LaravelOauth2\Design\ResponseTypes\BearerTokenResponse;
use Lyignore\LaravelOauth2\Entities\AccessTokenRepository;
use Lyignore\LaravelOauth2\Entities\AuthCodeRepository;
use Lyignore\LaravelOauth2\Entities\ClientRepository;
use Lyignore\LaravelOauth2\Entities\RefreshTokenRepository;
use Lyignore\LaravelOauth2\Entities\ScopeRepository;
use Lyignore\LaravelOauth2\Entities\UserRepository;
use Lyignore\LaravelOauth2\Guards\TokenGuard;
use Lyignore\LaravelOauth2\Models\Client;

class LoginServiceProvider extends ServiceProvider
{
    public static $runsMigrations = true;
    public function boot()
    {
        if($this->app->runningInConsole()){
            $this->commands([
                Command\Websocket::class
            ]);
        }
    }

    public function register()
    {
        $this->publishes([
            __DIR__ . '/../config/websocketlogin.php' => config_path('websocketlogin.php')
        ]);
    }
}
