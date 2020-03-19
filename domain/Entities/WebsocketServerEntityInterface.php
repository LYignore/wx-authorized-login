<?php
namespace Lyignore\WxAuthorizedLogin\Domain\Entities;

interface WebsocketServerEntityInterface
{
    /**
     * Websocket start interface
     */
    public function start();

    /**
     * Configure websocket startup parameters
     */
    public function initSocketServerConfig(array $config);

    /**
     * Initializing the listening event interface
     */
    public function initListeningEvent(array $config);

    /**
     * Websocket connection successful callback interface
     */
    public function wsOpen($server, $request);

    /**
     * Websocket successfully received the message callback interface
     */
    public function wsMessage($server, $frame);

    /**
     * Websocket close callback interface
     */
    public function wsClose($server, $frame);
}
