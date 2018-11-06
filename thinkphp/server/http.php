<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/4
 * Time: 21:46
 */
class Http{
    CONST HOST = '0.0.0.0';
    CONST PORT = 8812;

    public $http = null;
    public function __construct()
    {
        $this->http = new swoole_http_server(self::HOST,self::PORT);
        $this->http->set(
            [
                'worker_num' => 4,
                'task_worker_num' => 4,
                'enable_static_handler' => true,
                'document_root' => '/usr/local/nginx/html/swoole/thinkphp/public/static',
            ]
        );

        $this->http->on('workerstart',[$this,'onWorkerStart']);
        $this->http->on('request',[$this,'onRequest']);
        $this->http->on('task',[$this,'onTask']);
        $this->http->on('finish',[$this,'onFinish']);
        $this->http->on('close',[$this,'onClose']);

        $this->http->start();
    }

    /**
     * http开启 加载thinkphp文件
     * @param $server
     * @param $worker_id
     */
    public function onWorkerStart($server , $worker_id){
        //echo "worker start".PHP_EOL;
        define('APP_PATH', __DIR__ . '/../application/');
        require __DIR__ . '/../thinkphp/start.php';
    }

    /**
     * @param $request
     * @param $response
     */
    public function onRequest($request , $response){
        //echo "request start".PHP_EOL;
        if(!empty($_GET)){
            unset($_GET);
            $_GET = null;
        }
        if(!empty($_POST)){
            unset($_POST);
            $_POST = null;
        }
        $_SERVER = [];
        if(isset($request->server)){
            foreach ($request->server as $k => $v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if(isset($request->get)){
            foreach ($request->get as $k => $v){
                $_GET[$k] = $v;
            }
        }
        if(isset($request->post)){
            foreach ($request->post as $k => $v){
                $_POST[$k] = $v;
            }
        }

        $_POST['http_server'] = $this->http;

        if(isset($request->header)){
            foreach ($request->header as $k => $v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        //
        /**
         * 1.swoole_http_server接受客户端每一次请求 ↑↑↑↑↑↑
         * 2.加载tp5框架,获取控制器返回的数据
         * 3.swoole_http_server写入到浏览器中
         * 开启缓冲区,输出的内容暂时不会到浏览器中 并且可以放在header前;
         */
        ob_start();
        try {
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
            //echo 'action-'.request()->action();
            $res = ob_get_contents();
            ob_end_clean();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $response->header("Content-type", "text/html;charset=UTF-8");
        $response->end($res);
    }

    /**
     * @param $server
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($server, $taskId, $workerId, $data){
        //echo "onTask start";
        $obj = new app\common\lib\task\Task;
        if(empty($data['method'])){
            echo "Method Not Found";
            return;
        }
        //echo "Method Found";
        $method = $data['method'];
    }

    /**
     * @param $server
     * @param $taskId
     * @param $data
     */
    public function onFinish($server, $taskId, $data){

    }

    /**
     * @param $http
     * @param $fd
     */
    public function onClose($http, $fd){

    }
}

new Http();