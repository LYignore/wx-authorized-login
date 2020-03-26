<?php

namespace Lyignore\WxAuthorizedLogin\Command;

use Illuminate\Console\Command;
use Lyignore\WxAuthorizedLogin\Domain\Repositories\ServerRepositoryInterface;
use Lyignore\WxAuthorizedLogin\Repositories\ServerRepository;

class Websocket extends Command
{
    /**
     * The server object
     *
     * @var obj
     */
    protected $server;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "websocket:{action?}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Websocket third party login support';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action')?? 'start';
        switch($action){
            case 'close':
                $result = $this->close();
                break;
            default:
                $result = $this->start();
        }
        return $result;
    }

    protected function start()
    {
        $this->server = new ServerRepository();
        return $this->server->start();
    }

    protected function close()
    {
        if(! $this->server instanceof ServerRepositoryInterface){
            $this->server = new ServerRepository();
        }
        return $this->server->allClose();
    }
}
