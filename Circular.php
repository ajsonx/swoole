<?php

	/**
	* PHP循环语句
	**/
	$i = 1;
	$sum = 1;
	do
	{
	  $sum *= $i;
	  $i++;
	}while($i<=10);//do-while中放循环条件 while放跳出条件
          
 	echo $sum;
	
	for($i=0;$i<=10;$i++){
	  echo $i.' ';
	}
	
	/**
	* foreach循环用于遍历数组。数组的代码中有写到
	**/
