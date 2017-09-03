<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
class Login extends Controller
{
	
	public function index()
	{
		$this->assign('title','帮你想-后台登录');
		return $this->fetch('login');
	}

	public function login($code='')
	{
		if(request()->isPost())
		{
			//验证登录信息是否正确
			$data = [
				'username'=>trim(input('username')),
				'password'=>trim(input('password')),
			];
			//验证规则
			$validate = \think\Loader::validate('User');
			if(!$validate->check($data)){
				header("Content-Type: text/html; charset=utf-8");
			   $this->error($validate->getError()); die;
			}
			//判断验证码是否正确
			// $captcha = new \think\captcha\Captcha();
			// if(!$captcha->check($code)){
			//  	$this->error('验证码错误');
			// }
			$user = Db::name('admin_user')->where('username','=',$data['username'])->find();
			if($user){
				if(md5($data['password']) == $user['password']){
					//更新用户信息
					Db::name('admin_user')
				    ->where('id','=',$user['id'])
				    ->update([
				        'login_time'=>date('Y-m-d H:i:s'),
						'user_ip'=>$_SERVER["REMOTE_ADDR"],
				    ]);
				    //把新一条登录信息插入到登录日志
					$login_message = [
						'user_id'=>$user['id'],
						'login_time'=>date('Y-m-d H:i:s'),
						'ip'=>$_SERVER["REMOTE_ADDR"],
					];
					Db::name('login_log')->insert($login_message);
					Session::set('user',$user);
					$this->success('登录成功','Index/index');
				}else {
					$this->error('密码错误');
				}
			}else {
				$this->error('用户不存在');
			}
			
		}
	}
	public function logout()
	{
		Session::delete('username',null);
		return $this->success('退出成功','Login/index');
	}
}