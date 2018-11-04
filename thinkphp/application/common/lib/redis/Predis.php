<?php
/**
 * 重写 redis 适配自己的业务开发
 * 单例模式 一个类只有一个实例化对象
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/4
 * Time: 17:23
 */
namespace app\common\lib\redis;

class Predis{
    public $redis = "";

    /**
     * 单例模式的对象
     * @var null
     */
    private static $_instance = null ;

    public static function getInstance(){
        if(empty(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 构建redis
     * Predis constructor.
     * @throws Exception
     */
    private function __construct()
    {
        $this->redis = new \Redis();
        //有可能连接失败,需要做判断 (考虑各种场景)
        try{
            $this->redis->connect(config('redis.host'),config('redis.port'),config('redis.timeout'));
        }catch (\Exception $e){
            throw new Exception('redis connect fail');
        }
        $this->redis->auth(config('redis.auth'));
    }

    /**
     * set
     * 之前的set不能接收数组,转换成string
     * 1.考虑数组可以存什么.
     * 2.考虑如果set重复的key该怎么操作.(无报错,会重新赋值.)
     * @param $key
     * @param $value
     * @param int $time
     * @return bool
     */
    public function set($key,$value,$time = 0){

        if(is_array($value)){
            $value = json_encode($value);
        }
        //为负为0都不行
        if(!$time){
            return $this->redis->set($key,$time);
        }
        return $this->redis->setex($key, $time, $value);
    }

    /**
     * get
     * @param $key
     * @return bool|string
     */
    public function get($key){
        if(!$key){
            return '';
        }
        return $this->redis->get($key);
    }
}