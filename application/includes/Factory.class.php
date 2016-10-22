<?php
class Factory{
	static private $obj=null;
	//获取地址栏中查询字符串的a的值
	static public function getAction(){
		if(isset($_GET['a']) &&!empty($_GET['a'])){
			return $_GET['a'];
		}
		return "home";
	}
	//根据地址栏中a的值，返回aAction的对象的实例(contronller)
	static public function setAction(){
		$a=self::getAction();
		if(file_exists(ROOT_PATH.'/application/controllers/'.$a."Action.class.php")){
			//a=index
			//self::$obj=new indexAction();
			eval('self::$obj=new '.$a.'Action();');
			return self::$obj;
		}else{
			exit($a."Action控制器不存在");
		}
	}
	//根据地址栏中a的值，返回aModel()的对象的实例;
	static public function setModel(){
		$a=self::getAction();
		if(file_exists(ROOT_PATH.'/application/models/'.$a."Model.class.php")){
			eval('self::$obj=new '.$a.'Model();');
			return self::$obj;
		}
	}
}
?>