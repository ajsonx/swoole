<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/22
 * Time: 15:10
 */
$content = date("Y")."321";
swoole_async_write(__DIR__."/2.log",$content,-1,function ($filename){
    echo "write OK.\n";
});
swoole_async_writefile(__DIR__."/3.log",$content,function ($filename){
    echo "OK.\n";
},FILE_APPEND);//file_put_contents;