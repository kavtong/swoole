<?php
/**
 * Description: httpClient 客户端
 * User: yongStar
 * Date: 2018/11/7
 * Time: 22:47
 * Email:kavtong@163.com
 */

class  Client
{
    private $client;
    public function __construct()
    {
            $this->client = new  swoole_client(SWOOLE_SOCK_TCP);
    }

    /**
     * @param int $port 连接的端口号
     */
    public function connect($port = 8081)
    {
        if(!$this->client->connect('127.0.0.1',$port,1)){
                echo 'Error:'.$this->client->errMsg.' ErrorCode:'.$this->client->errCode.PHP_EOL;
        }
//        fwrite(STDOUT,'请输入消息：'.PHP_EOL);
//        $content = fgets(STDIN);
        $content = 'Test';
        $this->client->send($content);
        $response = $this->client->recv();
        echo 'Get Message is: '.$response.PHP_EOL;
    }
}

$client = new Client();
$client->connect();