<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/21
 * Time: 19:03
 */

/**
 * read分段讀取,size控制大小
 * $callback函数，可以通过return true/false，来控制是否继续读下一段内容。
 * readfile讀取整個文件
 */
$res = swoole_async_read(__DIR__."/1.txt",function ($filename,$content){
    echo "filename:".$filename.PHP_EOL;
    echo "contents:".$content.PHP_EOL;
    return false;
},$size = 8);
//返回true
var_dump($res);
echo "start".PHP_EOL;
//$content 這裏的變量名應該無所謂
$result = swoole_async_readfile(__DIR__."/1.txt",function($filename,$w){
    echo "file:".$filename.PHP_EOL;
    echo "content:".$w.PHP_EOL;
});
var_dump($result);
echo "start";