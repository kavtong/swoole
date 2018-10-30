<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/10/30
 * Time: 21:34
 * Tag:主要用于模拟客户端请求
 */
class Client{
    private $client;
    public function __construct()
    {
        // 初始化一个客户端
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
    }
    public function connect(){
        // 发送请求
        if(!$this->client->connect('127.0.0.1',8081,-1)){
                echo 'Error:'.$this->client->errMsg."\n".'ErrorCode:'.$this->client->errCode."\n";
        }

        // 提示用户输入数据
        fwrite(STDOUT,'请输入你要发送的消息'."\n");

        // 接收用户的输入数据
        $userData = trim(fgets(STDIN));

        // 发送数据到服务端
        $this->client->send($userData);

        //接收服务器返回的数据
        $response = $this->client->recv();
        echo  'The Sever Response Data is :'.$response;
    }
}

$client = new Client();
$client->connect();