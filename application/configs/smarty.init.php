<?php
class main{
	public static function init(){
		session_start();
		header("Content-Type:text/html;charset=utf-8");
		//echo dirname(__FILE__);
		//网站物理路径
		define("ROOT_PATH",substr(dirname(__FILE__), 0,-20));
		include ROOT_PATH.'/application/configs/config.php';
		//网络目录
		define("WEBSITE",$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		//var_dump($_SERVER['HTTP_HOST']);
		//var_dump($_SERVER['REQUEST_URI']);
		//echo WEBSITE;
		function autoloader($_className){
			if(substr($_className,-5)=='Model'){
				include ROOT_PATH."/application/models/".$_className.'.class.php';
			}else if(substr($_className,-6)=='Action'){
				include ROOT_PATH."/application/controllers/".$_className.'.class.php';
			}else{
				include ROOT_PATH."/application/includes/".$_className.'.class.php';
			}
		}
		spl_autoload_register('autoloader');
		date_default_timezone_set("PRC");
		error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE ^ E_WARNING);
		include ROOT_PATH.'/libs/Smarty.class.php';
		////////////////////////////////////////
		Factory::setAction()->run();
	}
}
main::init();
?>