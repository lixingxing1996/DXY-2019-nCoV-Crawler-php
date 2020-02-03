<?php


$json_path = dirname(__FILE__).'/json/';
$info_json =  file_get_contents($json_path.'info.json');
$info  = json_decode($info_json, TRUE);
$name = $_GET['json'];

if(in_array($name,['overall','area','news'])){
    $json =  file_get_contents($json_path.$info['modifyTime'].'/'.$name.'.json');
    echo $json;
}else{
    header("Location: index.html");
    exit();
}



