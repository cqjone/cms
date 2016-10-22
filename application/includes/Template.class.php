<?php
/**
 * 重写Smarty
 * @author Administrator
 *  */
class Template extends Smarty{
	static private $instance;
	static public function getInstance(){
		if(!self::$instance instanceof self){
			self::$instance=new self();
		}
		return self::$instance;
	}
	public function __construct(){
		parent::__construct();
		$this->setConfig();
	}
	private function setConfig(){
		//设置视图目录
		$this->template_dir=ROOT_PATH."/application/views";
		//设置编译目录,随时可以删除;
		$this->compile_dir=ROOT_PATH."/application/runtime";
	}
}
?>