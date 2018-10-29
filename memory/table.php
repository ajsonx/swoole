<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/25
 * Time: 22:49
 */

//2的N次方
$table = new swoole_table(1024);
//表增加一列
$table->column('id',swoole_table::TYPE_INT);
//String不设置最大长度默认为 0
$table->column('name',swoole_table::TYPE_STRING,44);
$table->column('age',swoole_table::TYPE_INT);

$table->create();
//唯一标识$key,数据的key,相同的$key对应同一行数据,如果set同一个key,会覆盖上一次的数据
$table->set('goer',['id'=> 1,'name'=>'goer','age'=>'20']);
//可以通过数组形式设置以及获取
$table['gs'] = [
    'id' => '1',
    'name' => 'gs',
    'age' =>28,
];
$table->incr('gs','id',1);
$table->decr('goer','age','3');
$table->del('goer','age');
print_r($table['gs']);
print_r($table->get('goer'));