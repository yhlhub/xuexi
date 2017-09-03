<?php
namespace app\admin\controller;
use app\admin\model\User;
use think\Controller;
use think\Request;
use think\Session;
use think\Db;
class BaseController extends Controller
{
    public $loginUser;

    protected $beforeActionList = [
        'beforeLoadAction',
    ];

    protected function beforeLoadAction()
    {
        $this->loadLoginUser();
        $this->checkHasLogin();
        $this->menu();
    }
    protected function loadLoginUser()
    {
        if(Session::get('user')){
            $user = User::get(Session::get('user.id'));
            if($user){
                $this->loginUser = $user; 
            }
        }
        return true;
    }
    protected function checkHasLogin()
    {
        if(!$this->loginUser){
            $this->error('请先登录','Login/index');
        }
    }
    protected function menu()
    {
        header('Content-Type:text/html; charset= utf-8');
        //顶级菜单
        $menu_list = array();

        $menu_list = Db::name('menu_list')
        ->where(['pid'=>0,'is_del'=>0])
        ->field('id,pid,title,cid,ico')
        ->select();

        //二级菜单
        foreach($menu_list as $k=>$v){
            foreach ($v as $k1 => $v1) {
                $menu_list[$k]['child'] = Db::name('menu_list')
                ->where(['is_del'=>0,'pid'=>$v['id']])
                ->order('id','asc')
                ->field('title,data_href,data_title')
                ->select();
            }   
        }
        $this->assign('menu_list',$menu_list);
    }
}
