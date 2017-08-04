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