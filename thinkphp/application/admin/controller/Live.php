<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/12
 * Time: 8:47
 */
namespace app\admin\controller;

use app\common\lib\Util;
use think\Controller;

//到时候要基础Base控制器
class Live extends Controller {

    public function index(){
        return $this->fetch();
    }

    public function push(){
        if(empty($_GET)){
            Util::show(config('code.error'),"没有数据");
        }
        if(empty($_GET['team_id'])){
            Util::show(config('code.error'),"请输入id");
        }
        //客户端token
        // => mysql
        $team = [
            1 => [
                'name' => '马刺',
                'logo' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/Grizzlies_nba_logo_128px_509957_easyicon.net.png',
                'image' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/cf34ce05e1ed38be369adac53dd03920.png',
            ],
            2 => [
                'name' => '热火',
                'logo' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/Heat_nba_logo_128px_509959_easyicon.net.png',
                'image' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/cf34ce05e1ed38be369adac53dd03920.png',
            ],
            3 => [
                'name' => '灰熊',
                'logo' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/Spurs_nba_logo_128px_509975_easyicon.net.png',
                'image' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/cf34ce05e1ed38be369adac53dd03920.png',
            ],
            4 => [
                'name' => '小牛',
                'logo' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/Heat_nba_logo_128px_509959_easyicon.net.png',
                'image' => 'http://goer-app.oss-cn-qingdao.aliyuncs.com/nba/cf34ce05e1ed38be369adac53dd03920.png',
            ],
        ];
        $data = [
            'type' => intval($_GET['type']),
            'title' => $team[$_GET['team_id']]['name'],
            'logo' => $team[$_GET['team_id']]['logo'],
            'content' => $_GET['content'],
            'image' => $team[$_GET['team_id']]['image'],
        ];

        $client = \app\common\lib\redis\Predis::getInstance()
            ->sMember('live');

        try{foreach ($client as $fd) {
            //可能会出现服务端关闭时客户端关闭 redis数据清楚出现操作异常
            $_POST['ws_server']->push($fd, json_encode($data));
        }
        }catch (\Exception $e){
            //不能写echo 会传给前端,导致jQuery获取不到正常数据
            //echo $e->getMessage().PHP_EOL;
        }finally{
            $d = [
                'status' => 1,
            ];
            return json_encode($d);
        }
    }
}