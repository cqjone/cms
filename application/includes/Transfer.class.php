<?php
/**
 * 上传第五版
 * 1.统一的错误处理
 * 2.自定义input的name值;
 * 3.setNewName()新文件名;
 * 4.iconv():处理中文，把utf8字符集转换成gb2312字符集
 * 5.删除目录处理
 * 6.throwError()统一抛错：内置错误和自定义错误
 * 7.构造方法的参数为数组
 * @author Administrator
 *  */
class Transfer{
    private $errorMsg;
    private $isRandom=true;
    //上传文件的原来名称
    private $originalName;
    //上传文件的临时文件名
    private $tmpName;
    //上传文件的新文件名
    private $newName;
    //上传文件的大小
    private $size;
    //上传文件的类型
    private $type;
    private $maxSize=1000000;
    private $allowType=array("png","jpg","gif");
    private $path="uploads";
    private $errorNum;
    private $fieldName="icon";
    public function __construct($_parameter=array()){
        foreach ($_parameter as $key=>$value){
            //$key转换为小写
            //$key=strtolower($key);
            if(array_key_exists($key,get_class_vars(get_class($this)))){
                //echo "yes";
                //continue;
                $this->$key=$value;
            }else{
                $this->errorMsg=$this->throwError(11);
            }
        }
    }
    private function throwError($_errorNum){
        $str=null;
        switch ($_errorNum){
            case 1:
                $str="上传文件超过php.ini的最大值";
                break;
            case 2:
                $str="上传文件超过表单允许的最大值";
                break;
            case 3:
                $str="上传文件信息不完整";
                break;
            case 4:
                $str="没有文件上传";
                break;
            case 5:
                $str="上传文件的大小为0字节";
                break;
            case 6:
                $str="临时文件没有生成";
                break;
            case 7:
                $str="文件写入失败";
                break;
            ////自定义错误号
            case 8:
                $str="文件类型错误";
                break;
            case 9:
                $str="文件大小超过了".$this->maxSize;
                break;
            case 10:
                $str="上传文件目录错误";
                break;
            case 11:
                $str="类的字段名错误";
                break;
           default:
                $str="上传发生未知错误";
                break;
        }
        return $str;
    }
    /**
     * 设置上传文件的新名称
     *   */
    private function setNewName(){
        if($this->isRandom){
            $this->newName=date("YmdHis").rand(100,999).".".$this->type;
        }else{
            $this->newName=$this->originalName;
        }
        //echo $this->newName;
    }
    private function uploadedFileInfo(){
        //iconv：处理中文文件名;
        $this->originalName=iconv("utf-8","gb2312", $_FILES[$this->fieldName]['name']);
        $arr=explode(".",$this->originalName);
        /* echo "<pre>";
        var_dump($arr);
        echo "</pre>"; */
        $this->type=$arr[count($arr)-1];
        $this->tmpName=$_FILES[$this->fieldName]['tmp_name'];
        $this->size=$_FILES[$this->fieldName]['size'];
        $this->errorNum=$_FILES[$this->fieldName]['error'];
        //echo $this->size."<br>";
    }
    public function getError(){
        return $this->errorMsg;
    }
    private function checkSize(){
        if($this->size>$this->maxSize){
            $this->errorMsg=$this->throwError(9);
            return false;
        }
        return true;
    }
    private function checkType(){
        //echo $this->type;
        if(!in_array($this->type,$this->allowType)){
            $this->errorMsg=$this->throwError(8);
            return false;
        }
        return true;
    }
    private function createDir($_dir){
        if(!is_dir($_dir)){
            if(!self::createDir(dirname($_dir))){
                return false;
            }
            //
            if(!mkdir($_dir,0777)){
                return false;
            }
        }
        return true;
    }
    private function checkPath(){
        if(empty($this->path)){
            $this->errorMsg=$this->throwError(10);
            return false;
        }
        if(!file_exists($this->path)){
            //mkdir($this->path);
            $this->createDir($this->path);
        }
        $this->path=rtrim($this->path,"/")."/";
        return true;
    }
    public function getNewName(){
        return $this->newName;
    }
    private function prevCheck(){
        //触发内置错误
        if($this->errorNum){
            $this->errorMsg=$this->throwError($this->errorNum);
            return false;
        }
        if(!$this->checkSize()){
            return false;
        }
        if(!$this->checkType()){
            return false;
        }
        if(!$this->checkPath()){
            return false;
        }        
        return true;
    }
    public function upload(){
        $this->uploadedFileInfo();
        $this->setNewName();
        //先执行预检测
        //不通过的话，返回false，
        if(!$this->prevCheck()){
            return false;
        } 
        //判断临时文件是否生成
        if(is_uploaded_file($_FILES[$this->fieldName]['tmp_name'])){
            //判断是否移动成功
            if(move_uploaded_file($_FILES[$this->fieldName]['tmp_name'], $this->path.$this->newName)){
                //$this->errorMsg="上传成功!";
                //echo iconv("gb2312","utf-8",$this->originalName);
                return true;
            }
        }
    }
}
?>