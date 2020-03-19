<?php
namespace Lyignore\WxAuthorizedLogin\Entities;

use Lyignore\WxAuthorizedLogin\Domain\Entities\LoginSubjectEntityInterface;
use SplObserver;

class LoginSubect implements LoginSubjectEntityInterface
{
    /**
     * Bind login observer after user applies for login entry
     * @param $observer SplObserver
     * @return void
     */
    public function attach(SplObserver $observer)
    {
        // TODO: Implement attach() method.
    }

    /**
     * When the user closes the login channel, unbind the login observer
     * @param $observer SplObserver
     * @return void
     */
    public function detach(SplObserver $observer)
    {
        // TODO: Implement detach() method.
    }

    /**
     * The client of thrift is implemented. When the user logs in,
     * the login principal notifies the login observer through the notification interface
     */
    public function notify()
    {
        // TODO: Implement notify() method.
    }
}
