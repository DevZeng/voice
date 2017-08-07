<?php

namespace App\Http\Controllers;

use App\Models\OAuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    //
    public function login()
    {
        return view('auth.login');
    }
    public function doLogin()
    {
        $username = Input::get('username');
        $password = Input::get('password');
        if (Auth::attempt(['name'=>$username,'password'=>$password],true)){
            $warehouses = Auth::user()->warehouses()->get();
            Session::put('warehouse_id',$warehouses[0]->id);
            Session::put('warehouse_name',$warehouses[0]->name);
            return redirect('/home');
        }else{
            return redirect()->back()->with('status','用户名或密码错误');
        };
    }
    public function userList()
    {
        $username = Input::get('username');
        if (!empty($username)){
            $users = OAuthUser::where('warehouse_id','=',\session('warehouse_id'))->where('nickname','like','%'.$username.'%')->paginate(20);
        }else{
            $users = OAuthUser::where('warehouse_id','=',\session('warehouse_id'))->paginate(20);
        }
        $warehouses = Auth::user()->warehouses()->get();
        return view('userList',['warehouses'=>$warehouses,'users'=>$users]);
    }
}
