<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;
class Index extends BaseController
{
    /**
     * 显示后台首页
     */

    public function index()
    {
    	$this->assign('title','帮你想');
        return $this->fetch();
    }
    /**
     * 显示后台欢迎页面
     */
    public function welcome()
    {
        $num = Db::name('login_log')->where('user_id','=',Session::get('user.id'))->field('count(id)')->find();
        $this->assign('id',$num['count(id)']);
    	$this->assign('title','帮你想');
        return $this->fetch('welcome');
    }
}
