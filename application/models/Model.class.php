<?php
class Model{
	private $db;
	public function __construct(){
		try{
			$this->db=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PWD);
		}catch(PDOException $e){
			exit($e->getMessage());
		}
		$this->db->query("set names utf8");
	}
	/**
	 * 获取所有的数据
	 * @param string $_sql  */
	protected function getAllData($_sql){
		$result=$this->db->query($_sql);
		$data=$result->fetchAll(PDO::FETCH_OBJ);
		return $data;
	}
	/**
	 * 获取第一条数据
	 * @param string $_sql
	 * @return Ambigous <>  */
	protected function getFirstData($_sql){
		$result=$this->db->query($_sql);
		$data=$result->fetchAll(PDO::FETCH_OBJ);
		return $data[0];
	}
	/**
	 * 获取数据的总记录数
	 * @param string $_sql
	 * @return number  */
	protected function getTotal($_sql){
		$result=$this->db->query($_sql);
		$total=$result->rowCount();
		return $total;
	}
	/**
	 * 获取一条数据
	 * @param string $_sql
	 * @return mixed  */
	protected function getOneData($_sql){
		$result=$this->db->query($_sql);
		$data=$result->fetchObject();
		return $data;
	}
	/**
	 * c:create;u:update;d:delete;
	 * @param string $_sql
	 * @return number  */
	protected function cud($_sql){
		$result=$this->db->exec($_sql);
		return $result;
	}
	/**
	 * 执行多条sql语句
	 * @param string $_sql
	 * @return boolean  */
	protected function multi_query($_sql){
		$result=$this->db->exec($_sql);
		return true;
	}
	/**
	 * 获取表即将增加的数据的id
	 * @param string $_tableName  */
	protected function Auto_Increment($_tableName){
		$_sql="show table status like '".$_tableName."'";
		$result=$this->db->query($_sql);
		$data=$result->fetchObject();
		return $data->Auto_increment;
	}
}
?>