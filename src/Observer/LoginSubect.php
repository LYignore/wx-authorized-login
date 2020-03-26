<?php
namespace Lyignore\WxAuthorizedLogin\Observer;

use Lyignore\WxAuthorizedLogin\Domain\Entities\LoginSubjectEntityInterface;
use Lyignore\WxAuthorizedLogin\ResponseTypes\StatusResponse;
use SplObserver;

class LoginSubect implements LoginSubjectEntityInterface
{
    public $loginObserverPool = [];
    public $params;
    /**
     * Bind login observer after user applies for login entry
     * @param $observer SplObserver
     * @return void
     */
    public function attach(SplObserver $observer)
    {
        $identify = $observer->getIdentify();
        $this->loginObserverPool[$identify] = $observer;
    }

    /**
     * When the user closes the login channel, unbind the login observer
     * @param $observer SplObserver
     * @return void
     */
    public function detach(SplObserver $observer)
    {
        $identify = $observer->getIdentify();
        unset($this->loginObserverPool[$identify]);
    }

    /**
     * The client of thrift is implemented. When the user logs in,
     * the login principal notifies the login observer through the notification interface
     */
    public function notify()
    {
        StatusResponse::success($this->params);
    }

    public function decouplingNotify($params)
    {
        $this->params = $params;
        $this->notify();
    }
}
