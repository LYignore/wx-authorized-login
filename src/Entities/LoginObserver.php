<?php
namespace Lyignore\WxAuthorizedLogin\Entities;

use Lyignore\WxAuthorizedLogin\Domain\Entities\LoginObserverEntityInterface;
use Lyignore\WxAuthorizedLogin\Tools\Tools;
use SplSubject;

class LoginObserver implements LoginObserverEntityInterface
{
    use Tools;
    /**
     * Implementation of thrift login confirmation update interface,
     * The server that implements thrift login notification
     * @param $subject SplSubject
     * @return void
     */
    public function update(SplSubject $subject)
    {
        // TODO: Implement update() method.
    }
}
