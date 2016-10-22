<?php
class adAction extends Action{
	public function main(){
		
		
		
	}
	public function add(){
		$this->smarty->assign("add",true);
		$this->smarty->display("admin/ad.html");
	}
}
?>