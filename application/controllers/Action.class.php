<?php
class Action{
	protected $smarty=null;
	protected $model=null;
	public function __construct(){
		//把smarty对象赋给smarty属性
		$this->smarty=Template::getInstance();
		//把模型对象赋给model属性;a=index new indexModel();
		$this->model=Factory::setModel();
	}
	protected function page($_total){
		$page=new Page($_total);
		$this->model->limit=$page->limit;
		//$this->smarty->assign('num',$page->page);
		$this->smarty->assign("page",$page->display());
	}
	//根据地址栏中的m值，调用相应的方法
	public function run(){
		$method=isset($_GET['m'])?$_GET['m']:"main";
		method_exists($this,$method)?eval('$this->'.$method."();"):$this->main();
	}
}
?>