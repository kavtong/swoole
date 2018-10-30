<?php
/**
 * Description: swoole定时器的使用
 * User: yongStar
 * Date: 2018/10/30
 * Time: 22:45
 * Email:kavtong@163.com
 */
// 永久定时器的使用
// 用户的自定义数据
$userData = array('userData');
// 定时器传到函数里面的参数
$params = array('param');
swoole_timer_tick(1000, function ($timer_id, $params) use ($userData) {
    var_dump($userData, $params);
    echo 'Time is doing:' . PHP_EOL;
}, $params);

// 仅执行一次的定时器的使用 after定时器的回调函数不接受任何参数，可以通过闭包方式传递参数

swoole_timer_after(1000, function () {
    echo 'Time just do once' . PHP_EOL;
});