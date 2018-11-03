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

class Send{
    public function index(){
        $phoneNum = input('get.phone_num/d');


        //手机号校验
        if(empty($phoneNum)){
            return Util::show(config('code.error'),'手机号不能为空');
        }
        if(preg_match(config('pattern.phoneNum'),$phoneNum)){
            return Util::show(config('code.success'),'发送成功');
        }else{
            return Util::show(config('code.error'),'手机号格式不正确');
        }

        //生成随机验证码
        $code = rand(1000,9999);

    }
}