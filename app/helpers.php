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
    function setUrl($path)
    {
        return env('assets').'/'.$path;
    }
}
if(!function_exists('getTime')){
    function getTime($date)
    {
        $time = time();
        $time = time()-1;
        $days = intval(floor($time/86400));
        if ($days<=30){
            if($days==0){
                return ($days+1).'天';
            }
            return $days.'天';
        }else if($days>30&&$days<90){
            $day = $days%30;
            if ($day==0){
                intval(floor($days/30)).'个月';
            }
            return intval(floor($days/30)).'个月'.$day.'天';
        }else if ($days>90&&$days<365){
            return intval(floor($days/30)).'个月';
        }else{
            $month = intval(floor(($days%365)/30));
            if ($month==0){
                return intval(floor($days/365)).'岁';
            }
            return intval(floor($days/365)).'岁'.$month.'个月';
        }
    }
}