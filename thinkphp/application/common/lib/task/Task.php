<?php
/**
 * 分发异步任务
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/6
 * Time: 19:42
 */
namespace app\common\lib\task;
use app\common\lib\ali\Sms;
use app\common\lib\redis\Predis;
use app\common\lib\Prefix;
use app\common\lib\Util;
class Task{

    public function sendSms($data){
        try{
            $res = Sms::sendSms($data['phone'],$data['code']);
        }catch (\Exception $e){
            echo $e->getMessage();
            return false;
        }

        if($res->Code === 'OK'){
            Predis::getInstance()->set(Prefix::smsKey($data['phone']),$data['code'],config('redis.out_time'));
        }else{
            return false;
        }
        return  true;
    }
}