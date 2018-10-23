<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/23
 * Time: 15:08
 */
$redis =new swoole_redis;
$redis->__construct($options = ['password' => 'wocaosy321']);
$redis->connect('127.0.0.1',6379,function (swoole_redis $redisC , $result){
    var_dump($result);
    $redisC->set('ssgg',123,function (swoole_redis $red,$result){
        var_dump($result);
    });
});