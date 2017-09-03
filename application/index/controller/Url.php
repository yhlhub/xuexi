<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
/**
* 
*/
class Url extends Controller
{
	
	public function url()
	{
		return $this->fetch();
	}
	public function file_content()
	{
		$url = $_POST['url'];
		$conn =file_get_contents($url);
		$pattern="/<li><a title=\"(.*)\" target=\"_blank\" href=\"(.*)\">/iUs";
		preg_match_all($pattern, $conn, $arr);//匹配内容到arr数组
		var_dump($arr);exit;
		foreach ($arr[1] as $key => $value) {//二维数组[2]对应id和[1]刚好一样,利用起key     
		 	$u=$url.$arr[2][$key]; 
		 	$data = [
		 		'title'=>$value,
		 		'url'=>$u,
		 		'create_at'=>date('Y-m-d H:i:s'),
		 	];
		 	$sql = Db::name('caiji')->insert($data);
		}

	}
























	function preg_substr($start, $end, $str) // 正则截取函数      
	{   
		
	    $temp = preg_split($start, $str);     
	    $content = preg_split($end, $temp[1]);      
	    return $content[0];      
	} 
	function str_substr($start, $end, $str) // 字符串截取函数      
	{      
	    $temp = explode($start, $str, 2);      
	    $content = explode($end, $temp[1], 2);      
	    return $content[0];      
	}
	function writelog($str)
	{
		@unlink("log.txt");
		$open=fopen("log.txt","a" );
		fwrite($open,$str);
		fclose($open);
	}   
	function getImage($url, $filename='', $dirName, $fileType, $type=0)
	{
	    if($url == ''){return false;}
	    //获取文件原文件名
	    $defaultFileName = basename($url);
	    //获取文件类型
	    $suffix = substr(strrchr($url,'.'), 1);
	    if(!in_array($suffix, $fileType)){
	        return false;
	    }
	    //设置保存后的文件名
	    $filename = $filename == '' ? time().rand(0,9).'.'.$suffix : $defaultFileName;
	          
	    //获取远程文件资源
	    if($type){
	        $ch = curl_init();
	        $timeout = 5;
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	        $file = curl_exec($ch);
	        curl_close($ch);
	    }else{
	        ob_start();
	        readfile($url);
	        $file = ob_get_contents();
	        ob_end_clean();
	    }
	    //设置文件保存路径
	    $dirName = $dirName.'/'.date('Y', time()).'/'.date('m', time()).'/'.date('d',time()).'/';
	    if(!file_exists($dirName)){
	        mkdir($dirName, 0777, true);
	    }
	    //保存文件
	    $res = fopen($dirName.$filename,'a');
	    fwrite($res,$file);
	    fclose($res);
	    return $dirName.$filename;
	}
}
