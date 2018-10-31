<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/28
 * Time: 20:02
 */

$http = new swoole_http_server('0.0.0.0',8812);

$http->set([
    'enable_static_handler' => true,
    'document_root' => "/usr/local/nginx/html/swoole/thinkphp/public/static",
]);
$http->on('WorkerStart',function (swoole_server $server,$worker_id){
    define('APP_PATH', __DIR__ . '/../application/');

    require __DIR__ . '/../thinkphp/start.php';
    //require __DIR__ . '/../thinkphp/base.php';
});
$http->on('request',function ($request,$response){
    $content = [
        'date' => date("Ymd H:i:s"),
        'get' => $request->get,
        'post' => $request->post,
        'header' => $request->header,
    ];
    if(!empty($_GET)){
        unset($_GET);
        $_GET = null;
    }
    if(!empty($_POST)){
        unset($_POST);
        $_POST = null;
    }
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
    if(isset($request->header)){
        foreach ($request->header as $k => $v){
            $_SERVER[strtoupper($k)] = $v;
        }
    }

    //开启缓冲区,输出的内容暂时不会到浏览器中 并且可以放在header前;
    ob_start();
    try {
        think\Container::get('app', [APP_PATH])
            ->run()
            ->send();
        echo 'action-'.request()->action();
        $res = ob_get_contents();
        ob_end_clean();
    } catch (Exception $e) {

    }
    $response->header("Content-type", "text/html;charset=UTF-8");
    $response->end($res);

});

$http->start();