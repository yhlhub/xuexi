<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Index extends Controller
{
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->redis = new \Redis();
    //     $this->redis->connect('127.0.0.1', 6379);
    // }
    public function index()
    {
    	// $view = new View();
     //    return $view->fetch();
     	return $this->fetch();
    }
    public function user()
    {
        header('Content-Type:text/html; charset= utf-8');
    	if(request()->IsPost()){
    		$name = input('name');
            $addtime = date("Y-m-d H:i:s");
    		// $file = request()->file('image');
    		// $info = $file->move(ROOT_PATH . 'public' .DS. 'uploads');
      //       $end = $info->getFilename();
            $data=[
                'user'=>$name,
                'addtime'=>$addtime,
                // 'image'=>$end,
            ];
           Db::name('user')->insert($data);
         
    	}  
    }
}
