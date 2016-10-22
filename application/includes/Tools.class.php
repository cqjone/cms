<?php
/**
 * 工具类
 * final类，不能被继承,
 * 基于Bootstrap、tools.css、tools.js
 *  */
final class Tools{
/**
 * 重定向函数，必须配合Tools.js和Tools.css
 * @param string $_msg:要显示的信息
 * @param string $_url:要跳转的路径
 * @param number $_t:重定向需要的时间，默认是3秒钟;
 * @param number $_flag:1是成功,0是失败
 * */
	public static function Redirect($_msg,$_url,$_flag=1,$_t=13){
    	if($_flag==1){
       	 $color="green;";
    	}else if($_flag==0){
        	$color="red;";
    	}
    	echo "<div id='Redirect' data-countdown=".$_t." data-url=".$_url.">";
    	echo "<span style='color:".$color."'>".$_msg."</span>";
    	echo "&nbsp;<span id='timer'>".$_t."</span>";
    	echo "</div>";
}
	/**
	 * 修剪字符串，如果有剩余字符就显示省略号。
	 * @param string $_str
	 * @param number $_length
	 * @param string $_suffix
	 * @param number $_start
	 * @return string  */
	public static function subString($_str,$_length,$_suffix="...",$_start=0){
		//mb_substr();修剪多字节的字符串;
		$num=mb_strlen($_str);
		//判断修剪字符的启示值与字符长度的关系;
		if($_start<($num-1)){
			if($num<$_length){
				return mb_substr($_str, $_start,$_length,"utf8");
			}else{
				return mb_substr($_str, $_start,$_length,"utf8").$_suffix;
			}		
		}elseif($_start==($num-1)){
			echo "";			
		}else{
			return "<span style='color:red'>开始的索引值大于字符串的长度</span>";
		}				
	}	
/**
 * 过滤字符串、数组、对象
 * 
 * @param mixed $_content  
 * @return mixed;
 * */
public static function filter($_content){
    if(is_string($_content)){
        $_content=htmlspecialchars($_content);
    }else if(is_array($_content)){
        foreach ($_content as $key=>$value){
            $_content[$key]=htmlspecialchars($value);
        }
    }else if(is_object($_content)){
        foreach ($_content as $key=>$value){
            $_content->$key=htmlspecialchars($value);
        }
    }else{
        echo "filter()参数类型错误！";
    }
    return $_content;
}
/**
 * 反过滤：恢复到过滤前的字符串、数组、对象
 *
 * @param mixed $_content  
 * @return mixed;
 * */
public static function filter_decode($_content){
    if(is_string($_content)){
        $_content=htmlspecialchars_decode($_content);
    }else if(is_array($_content)){
        foreach ($_content as $key=>$value){
            $_content[$key]=htmlspecialchars_decode($value);
        }
    }else if(is_object($_content)){
        foreach ($_content as $key=>$value){
            $_content->$key=htmlspecialchars_decode($value);
        }
    }else{
        echo "filter()参数类型错误！";
    }
    return $_content;
}	
/**
	 * 检测数据是否为空<br>
	 * 修剪掉空格后的字符长度为0；
	 * @param string $_data
	 * @return boolean  */
	public static function isNull($_data){
		if(mb_strlen(trim($_data),"utf8")==0){
			return true;
		}
		return false;
	}
	/**
	 * 检测数据是否是数字或数字类型的字符串
	 * @param number string number $_data
	 * @return boolean  */
	public static function isNumber($_data){
		if(!is_numeric($_data)){
			return true;
		}
		return false;
	}
	/**
	 * 检测两条数据是否相等
	 * @param string $_firstData
	 * @param string $_secondData
	 * @return boolean  */
	public static function isEqual($_firstData,$_secondData){
		if(trim($_firstData)!=trim($_secondData)){
			return true;
		}
		return false;
	}
	/**
     * 验证字符串的长度范围
     *
     * @param string $_data
     * @param int $_minLength:最小值
     * @param int $_maxLength：最大值;
     * @return boolean  不符合要求返回true*/
    public static function range($_data,$_minLength=6,$_maxLength=12){
        if(is_int($_minLength)&&is_int($_maxLength)&&$_minLength>0&&$_maxLength>0){
            if(mb_strlen(trim($_data),"utf8")<$_minLength||mb_strlen(trim($_data),"utf8")>$_maxLength){
                return true;
            }
        }else{
            exit("range()长度参数错误");
        }
        return false;
    }
	/**
	 * 验证邮箱
	 * @param string $_data
	 * @return boolean  */
	public static function isEmail($_data){		$pattern="/^[a-z0-9]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]+[-_]?[a-z0-9]+)+[\.][a-z]{2,4}([\.][a-z]{2})?$/i";
		if(!preg_match($pattern, $_data)){
			return true;
		}
		return false;
	}
	/**
	 * 检测session值是否存在
	 * @param string $_data
	 * @return boolean  */
	static public function isSession($_data){
		if(!isset($_SESSION[$_data])){
			return true;
		}
		return false;
	}
	/**
	 * 销毁session
	 * @param string $_data
	 * @return boolean  */
	public static function destroySession($_data){
		unset($_SESSION[$_data]);
		return true;
	}
	/**
     * 判断是否有权限
     *
     * @param string $_data:传进来的权限值;
     * @return boolean 没有权限，返回TRUE，有权限返回FALSE；
     * */
    public static function hasPermission($_data){
        if(!strstr($_SESSION['permission'],$_data)){
            return true;
        }
        return false;
    }
    /**
     * 循环创建目录
     * @param string $_dir:要创建的目录名
     * @return boolean;
     * */
    public static function createDir($_dir){
        if(!is_dir($_dir)){
            if(!self::createDir(dirname($_dir))){
                return false;
            }
            if(!mkdir($_dir,0777)){
            return false;
            }
        }
        return true;
    }
	/**
     * 输出变量simple description
     *
     * dump方法中传递的参数区分为字符串、数组、数字、对象、资源
     *
     * @method dump()：输出变量
     * @param mixed $_content：要输出的内容
     *  */
	public static function dump($_content){
	    if($_content){
    	    	if(is_string($_content)){
                echo "<pre>";
                var_dump($_content);
                echo "</pre>";
            }else if(is_numeric($_content)){
                echo "<pre>";
                var_dump($_content);
                echo "</pre>";
            }else if(is_array($_content)){
                echo "<pre>";
                print_r($_content);
                echo "</pre>";
            }else if(is_object($_content)){
                echo "<pre>";
                var_dump($_content);
                echo "</pre>";
            }else if(is_resource($_content)){
                echo "<pre>";
                var_dump($_content);
                echo "</pre>";
            }else{
                exit("dump()参数不是字符串、数字、数组、对象、资源");
            }	        
	    }else{
	        echo "dump参数为空";
	    }		
	}
}
?>
