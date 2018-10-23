<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/22
 * Time: 20:51
 */
class AsyncMysql{
    public $db = "";
    public $config = "";
    public function __construct()
    {
        $this->db = new swoole_mysql();
        $this->config = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 'wocaosy321',
            'database' => 'swoole',
            'charset' => 'utf8', //指定字符集
            'timeout' => 2,  // 可选：连接超时时间（非查询超时时间），默认为SW_MYSQL_CONNECT_TIMEOUT（1.0）
        ];
    }
    public function add(){}
    public function query(){}
    public function delete(){}
    public function update(){}

    /**
     * 測試mysql
     * @param $id
     * @param $username
     */
    public function execute($id, $username){
        $this->db->connect($this->config,function ($db,$r) use($id,$username){
            if($r === false){
                var_dump($db->connect_error);
            }
            $sql = "select * from test where id=1";
            $upd = "update test set user_name='".$username."'where id=".$id;
            $db->query($upd,function ($db, $res){
                if($res === false){
                    var_dump($db->error);
                }elseif ($res === true){
                    //數據庫的 影響行數
                    var_dump($db->affected_rows);

                }else{
                    var_dump($res);
                }
                $db->close();
            });
        });
        return true;
    }
}
$obj = new AsyncMysql();
$flag = $obj->execute(1,'ajsonx');
var_dump($flag).PHP_EOL;
echo "start".PHP_EOL;
//使用異步mysql增加訪問量操作時
//邏輯是 用戶請求->async_mysql 訪問量+1 -> 頁面呈現。 如何顯示給用戶？(mysql返回true,但還未執行,是先+1還是＋完1之後再顯示)