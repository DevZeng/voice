<?php

if (!function_exists('getWarehouseId')){
    function getWarehouseId($key)
    {
        $data = Redis::get($key);
        if (empty($data)){
            return false;
        }
        $data = unserialize($data);
        return $data['warehouse_id'];
    }
}

if (!function_exists('getUserId')){
    function getUserId($key)
    {
        $data = Redis::get($key);
        if (empty($data)){
            return false;
        }
        $data = unserialize($data);
        return $data['user_id'];
    }
}
if(!function_exists('setUrl')){
    function setUrl($path,$secure=false)
    {
        if ($secure){
            return 'https://'.env('assets').'/'.$path;
        }else{
            return 'http://'.env('assets').'/'.$path;
        }
    }
}
if(!function_exists('getTime')){
    function getTime($date)
    {
        $time = strtotime($date);
        $time = time()-$time;
        if ($time<60*60){
            $minutes = intval(floor($time/60));
            if($minutes==0){
                $minutes=1;
            }
            return $minutes.'分钟前';
        }else if($time>60*60&&$time<60*60*24){
            $hour = intval(floor($time/(60*60)));
            return $hour.'小时前';
        }else if ($time>60*60*24){
            $days = intval(floor($time/(60*60*24)));
            if ($days>365){
                $year = intval(floor($days/365));
                return $year.'年前';
            }elseif($days<365&&$days>30){
                $month = intval(floor($days/30));
                return $month.'月前';
            }
            return $days.'天前';
        }else{
            return '1分钟前';
        }
    }
}
if (!function_exists('buildCommentsTree')){
    function buildCommentsTree($data,$top=0,$button=0,$tree=[])
    {
        $length = count($data);
        for ($i=0;$i<$length;$i++){
            if ($data[$i]['id']==$top){
                $top = $data[$i]['comment_id'];
                array_unshift($tree,$data[$i]);
                buildCommentsTree($data,$top,$button,$tree);
            }elseif ($data[$i]['comment_id']==$button){
                $button =$data[$i]['id'];
                array_push($tree,$data[$i]);
                buildCommentsTree($data,$top,$button,$tree);
            }
        }
        return $tree;

    }
}
function getNode($data,$node)
{
    for ($i=0;$i<count($data);$i++){
        if ($data[$i]['id']==$node){
            return $data[$i];
        }
    }
}