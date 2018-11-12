<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/11/12
 * Time: 8:47
 */
namespace app\admin\controller;

use think\Controller;

class Live extends Controller {

    public function index(){
        return $this->fetch();
    }

    public function push(){

        $_POST['ws_server']->push(2,json_encode(request()->get()));
    }
}