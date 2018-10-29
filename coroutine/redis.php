<?php
/**
 * 协程
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/28
 * Time: 10:37
 */
$http = new swoole_http_server('0.0.0.0',8813);
$http->on('request',function ($request,$response){
    $redis = new Swoole\Coroutine\Redis();
    $res = $redis->connect('127.0.0.1',6379);
    //先连接客户端再验证密码
    $redis->auth('wocaosy321');
    //该方法不适用于Coroutine/Redis
    //$redis->__construct($options = ['password' => 'wocaosy321']);
    $value = $redis->get($request->get['a']);
    //输出错误信息
    //var_dump($redis->errMsg);
    $response->header("Content-Type","text/plain");
    $response->end($value);
});
$http->start();