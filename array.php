<?php
	/**
	* 数组
	**/
	//count()函数统计数组长度。数组下标从0开始
	$arr = array(1,2,3,4);
	for($i = 0 ; $i < count($arr); $i++){
		echo $arr[$i]."\n";
	}
	$relation_arr = array(1=>'2');
	//使用关联数组的键，必须带引号
	echo $relation_arr['1']."\n";
	//关联数组key值一定为字符或字符串，value值可以写为字符或者数值类型
	if($relation_arr['1']===2){
		echo "YES\n";	
	}
	/**
	* foreach循环  $要循环的数组 as 键 => 值
	**/
	foreach($arr as $x){
		echo $x.' ';
	}echo "\n";
	foreach($relation_arr as $key=>$value){
		echo $x.'====>'.$value;	
	}
	/**
	* 各种排序方法
	* sort()升序排序 数字根据大小，字符根据首字母字典序排序
	* rsort()降序排序
	* asort()根据关联数组的值 升序排序  用于索引数组根据值排序
	* ksort()根据关联数组的键 升序排序  用于索引数组时根据下标排序
	* arsort()根据关联数组的值，降序排序
	* krsort()根据关联数组的键，降序排序
	*
	**/
	//双引号单引号都能解析字符串
	$strArr = array('a','b','z','c','sort','array','zzzz');
	$znArr = array("你","我是你爹","嗯冲");
//	sort($strArr);
	sort($znArr);
//	print_r($strArr);print_r($znArr);
	ksort($strArr);		
	print_r($strArr);

