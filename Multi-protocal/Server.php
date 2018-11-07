<?php

/**
 * Description:主要用于实现多端口多协议
 * User: yongStar
 * Date: 2018/11/7
 * Time: 22:56
 * Email:kavtong@163.com
 */
class Server
{
    private $server;

    public function __construct()
    {
        $this->server = new  swoole_server('0.0.0.0', 8081);
        $this->server->set(
            array(
                'worker_num' => 4,
                'daemonize' => false,
            )
        );

        $this->server->on('connect', array($this, 'onConnect'));
        $this->server->on('receive', array($this, 'onReceive'));
        $this->server->on('close', array($this, 'onClose'));
        $this->server->on('start', array($this, 'onStart'));

        $this->server->start();
    }

    public function onStart($server)
    {
        echo 'Server is Start' . PHP_EOL;
    }

    public function onConnect($server,$fd,$from_id)
    {
        echo 'Server is Connect' . PHP_EOL;
    }

    public function onReceive(swoole_server $server, $fd, $from_id, $data)
    {
        echo 'Server Receive Data' . $data . PHP_EOL;
        $server->send($fd,'333');
    }

    public function onClose($server, $fd)
    {
        echo 'Server is Close';
    }
}

$server = new Server();