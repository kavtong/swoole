<?php

/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/10/30
 * Time: 20:55
 * Tag: 主要用来实现绑定多端口的问题
 */
class Server
{
    // 定义一个私有的swoole_server对象
    private $server;

    public function __construct()
    {
        // 创建一个swoole_server对象: 监听地址 端口号
        $this->server = new  swoole_server('127.0.0.1', 8081);

        //  设置 swoole_server 对象的参数
        $this->server->set(array(
            // 设置 2 个 worker 进程
            'worker_num' => 2,
            // 设置 2 个 task 进程
            'task_worker_num' => 2,
            'daemonize' => false,
        ));

        // 绑定事件：server
        $this->server->on('Start', array($this, 'onStart'));
        $this->server->on('Connect', array($this, 'onConnect'));
        $this->server->on('Receive', array($this, 'onReceive'));
        $this->server->on('Close', array($this, 'onClose'));

        // 绑定事件: task
        $this->server->on('Task', array($this, 'onTask'));
        $this->server->on('Finish', array($this, 'onFinish'));

        // 启动服务器
        $this->server->start();
    }

    /**
     *  swoole_server 对象的启动时绑定的方法
     * @param $server swoole_server 对象
     */
    public function onStart($server)
    {
        echo 'Server is Start' . "\n";
    }

    /**
     *  swoole_server服务器接收客户端的连接时调用的方法
     * @param $server swoole_server 对象
     * @param $fd 客户端的连接id
     * @param $from_id  处理该请求的 worker_id
     */
    public function onConnect($server, $fd, $from_id)
    {
        echo 'Server is Connected' . "\n";
    }

    /**
     * swoole_server服务器接收到客户端的发送数据时调用的方法
     * @param swoole_server $server swoole_server对象
     * @param $fd 客户端的连接id
     * @param $from_id 处理请求的用户id
     * @param $data 用户 发送的数据
     */
    public function onReceive(swoole_server $server, $fd, $from_id, $data)
    {
        echo 'Server is Received' . "\n";
        // 接收用户收到的数据
        /*$receiveData = $server->recv();*/
        // 向客户端发送数据
        $server->send($fd, $data . ' - ' . time());

        // 调用 task  -1代表不指定task进程
//        $server->task('11111', -1);
        /**
         * 在高级的版本中 还可以存在该写法 在1.8.6+的版本中，可以动态指定onFinish函数
         * 此种状态下 就会覆盖 系统默认绑定的方法  onFinish
         */
        $server->task('1111', -1, function (swoole_server $server, $task_id, $data) {
            echo "Task Finish Callback\n";
        });
    }

    /**
     * swoole_server 对象关闭时调用的方法
     * @param $server swoole_server 对象
     * @param $fd 客户端的连接id
     * @param $from_id 处理请求的用户id
     */
    public function onClose($server, $fd, $from_id)
    {
        echo 'Server is Closed' . "\n";
    }

    /**
     * task 对象需要绑定的方法
     * @param swoole_server $server swoole_server对象
     * @param $task_id 任务id
     * @param $from_id 投递任务的worker_id
     * @param $data 投递的数据
     * @return  mixed 返回执行完的处理结果 一定要返回 否则 task 的 onFinish方法不调用
     */
    public function onTask(swoole_server $server, $task_id, $from_id, $data)
    {
        echo 'Task is begin' . "\n";
        return $data;
    }

    /**
     * @param swoole_server $server swoole_server对象
     * @param $task_id      任务id
     * @param $data     任务返回的数据
     */
    public function onFinish(swoole_server $server, $task_id, $data)
    {
        echo 'Task is end' . PHP_EOL;
    }
}

new Server();