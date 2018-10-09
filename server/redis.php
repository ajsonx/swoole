<?php

$redis = new swoole_redis;

$redis->connect('127.0.0.1',6379,function(swoole_redis $redis,$result)
{
	echo "connect".PHP_EOL;
	var_dump($result);
	$redis->get("sw",function(swoole_redis $redis , $result){
		var_dump($result);
		$redis->close();
	});
});

