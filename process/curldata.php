<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/10/23
 * Time: 20:54
 */
    $ch = curl_init();
    // set url
    curl_setopt($ch, CURLOPT_URL, "www.baidu.com");
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);
    //echo output
    echo $output;