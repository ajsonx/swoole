<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/9
 * Time: 20:41
 */

class Ws {
    CONST HOST = "0.0.0.0";
    CONST PORT = 8812;
    public $ws = null;

    /**
     * ws服务构造函数
     * Ws constructor.
     */
    public function __construct(){
        $this->ws = new swoole_websocket_server("0.0.0.0",8812);
	    $this->ws->set(
            [
                'worker_num' => 2,
                'task_worker_num' => 4,
                'enable_static_handler' => true,
            ]
        );
	
        //添加监听事件
        $this->ws->on("open",[$this,'onOpen']);
        $this->ws->on("message",[$this,'onMessage']);
        $this->ws->on("task",[$this,'onTask']);
        $this->ws->on("finish",[$this,'onFinish']);
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
        var_dump($request);
        if($request->fd == 1){
            //毫秒定時器 沒兩秒執行一次
            swoole_timer_tick(2000,function ($timer_id){
                 echo "2s times：{$timer_id}\n";
            });
        }
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame 是swoole_websocket_frame对象，包含了客户端发来的数据帧信息 $frame->data
     */
    public function onMessage($ws,$frame){
        echo "push-message:{$frame->data}\n";
        $data = [
            'task' => 1,
            'fd' => $frame->fd,
        ];
        //異步定時器，與下面的任務同時進行
        swoole_timer_after(5000,function ()use ($ws,$frame){
             echo "5s after\n";
             //發送消息給客戶端
             $ws->push($frame->fd,"server-time-after:");
        });

        //投放異步任務給task
        $ws->task($data);
        //发送消息给客户端 自帶的 ->push(int $fd客户端id, $data要发送的数据, int $opcode = 1数据格式默认文本, bool $finish = true发送成功状态);
        $ws->push($frame->fd,"server-push:".date("Ymd h:m:s"));
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($serv,$taskId,$workerId,$data){
        print_r($data);
        //耗時場景
        sleep(10);
        $to = "577429696@qq.com";
        $subject = "Test mail";
        $message = "Hello,I'm from";
        //CentOs 7下發送郵件失敗 報錯：/usr/sbin/sendmail: error while loading shared libraries: libmysqlclient.so.18: cannot open shared object file: No such file or directory
        //安裝mysql時自帶libmysqlclient.so.20 在/usr/lib/下創建管關聯 ln /usr/local/mysql/lib/libmysqlclient.so.20.3.10  /usr/lib64/libmysqlclient.so.18
        //又提示send-mail: fatal: parameter inet_interfaces: no local interface found for ::1
        //將/etc/postfix/main.cf 下inet_interfaces =localhost改爲 =all
        //此時提示 mail()函數返回true，但未收到 懷疑postfix未啓動，運行 service postfix start 報錯 ......libmysqlclient_18' not found (required by /usr/sbin/postconf
        //重新安裝mysql運行庫，yum reinstall mysql-libs -y 成功，此時沒有報錯，但qq郵箱還是收不到
        //真他媽難 ， 相關資料太少 無法使用mail發送郵件 大概是因爲 攔截 php.ini smtp 和mail.cf 不會配置。。應該就差這點
        $t = mail($to,$subject,$message);
        var_dump($t);
        return "onTask finished：".date("Ymd h:m:s"); //告訴worker
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function onFinish($serv,$taskId,$data){
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}\n";
    }
    /**
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws,$fd){
        echo "client {$fd} close\n";
    }
}
$obj = new Ws();
