<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/9
 * Time: 20:41
 */

class Ws {
    CONST HOST = '127.0.0.1';
    CONST PORT = 8812;
    public $ws = null;

    /**
     * ws服务构造函数
     * Ws constructor.
     */
    public function __construct(){
        $this->ws = new swoole_websocket_server(HOST,PORT);
        $this->ws->set(
            [
                'enable_static_handler' => true,
                'document_root' => "/root/swoole_test/html",
            ]
        );

        //添加监听事件
        $this->ws->on("open",[$this,'onOpen']);
        $this->ws->on("message",[$this,'onMessage']);
        $this->ws->on("close",[$this,'onClose']);

        //开启服务
        $this->ws->start();
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request 是一个Http请求对象，包含了客户端发来的握手请求信息GET参数、Cookie、Http头信息等
     */
    public function onOpen($ws,$request){
        var_dump($request->id);
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame 是swoole_websocket_frame对象，包含了客户端发来的数据帧信息
     */
    public function onMessage($ws,$frame){
        echo "push-message:{$frame->data}\n";

        //发送消息给客户端 ->push(int $fd客户端id, $data要发送的数据, int $opcode = 1数据格式默认文本, bool $finish = true发送成功状态);
        $ws->push($frame->fd,"server-push:".data("Ymd h:m:s"));
    }
    public function onClose($ws,$fd){
        echo "client {$fd} close";
    }
}