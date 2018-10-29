<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/23
 * Time: 19:00
 */
require_once "curldata.php";
echo date("Ymd H:i:s").PHP_EOL;
$work = [];
$urls = [
    'www.hao123.com',
    'www.baidu.com',
    'www.sina.com',
    '39.108.115.17',
    'www.weibo.com',
];
for($i=0;$i<5;$i++){
    $process = new swoole_process(function (swoole_process $worker)use ($urls,$i){
        new curldata($urls[$i],$i);
        echo "success{$i}";
    },true);
    $pid = $process->start();
    $work[$pid] = $process;
    //echo $work[$i];

}
//管道里要是没东西 会卡在read() 用foreach无法判断到达最后一个
foreach ($work as $v){
    echo $v->read()."这是管道".PHP_EOL;
}
/**
 * swoole_process::wait()
 * 回收结束运行的子进程
 * 'code' => 0, 'pid' => 15001, 'signal' => 15
 * 操作成功会返回一个数组包含子进程的PID、退出状态码、被哪种信号KILL
 */
while($ret = $process->wait()){
    //var_dump($ret);
    echo "PID={$ret['pid']}\n";
};


echo date("Ymd H:i:s").PHP_EOL;