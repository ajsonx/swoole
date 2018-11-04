<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/3
 * Time: 16:00
 */
namespace app\index\controller;
use app\common\lib\ali\sms;
use app\common\lib\Util;
use app\common\lib\Redis;
class Send{
    public function index(){
        /**
         * ThinkPHP适配swoole,由于swoole进程长连接,get数据是上一次的请求数据,需要修改TP源代码
         */
        $phoneNum = input('get.phone_num/d');
        //1.手机号校验
        if(empty($phoneNum)){
            return Util::show(config('code.error'),'手机号不能为空');
        }
        if(!preg_match(config('pattern.phoneNum'),$phoneNum)){
            return Util::show(config('code.error'),'手机号格式不正确');
        }
        //2.生成随机验证码
        $code = rand(1000,9999);
        try{
            $res = Sms::sendSms($phoneNum,$code);
        }catch (\Exception $e){
            $e->getMessage();
            Util::show(config('code.error'),'内部错误,请稍后再试');
        }

        /**
         * 3.存入redis中
         * 为什么用到协程?
         * 协程最大的功能就是提高了单个进程接受请求的能力，进而提高了总体高并发的能力。Qps
         */
        if($res->Code === 'OK'){
            $redis = new \Swoole\Coroutine\Redis();
            $redis->connect(config('redis.host'),config('redis.port'));
            $redis->auth(config('redis.auth'));
            $redis->set(Redis::smsKey($phoneNum),$code,config('redis.timeout'));
            return  Util::show(config('code.success'),'验证码发送成功');
        }else{
            return Util::show(config('code.error'),'验证码发送失败,请稍后再试');
        }
    }
}