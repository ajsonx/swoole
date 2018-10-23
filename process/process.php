<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/23
 * Time: 16:00
 */
/**
创建swoole_process一定要带参数
$pro = new swoole_process(function (){});
$pro->__construct(function (swoole_process $worker){
    echo "321";
},false);
*/
$process = new swoole_process(function (swoole_process $worker){
    echo '123';
    var_dump($worker->pid);
},false);//開啓後echo變爲管道輸出 ,不仅仅是echo,好像一切输出都会进入管道
$process->start();
echo "pid:";