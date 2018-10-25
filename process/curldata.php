<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/23
 * Time: 20:54
 */

class curldata{

    public function __construct($str,$i)
    {
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $str);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //响应过期时间设置
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
        //返回301 Move Permanentl 永久迁移.是因为遇到要跳转的页面.设置为直接抓取跳转的地址.
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);

        $output = curl_exec($ch);
        swoole_async_writefile(__DIR__."/../../{$str}.html",$output,function ($filename){

        });
        curl_close($ch);
    }
}