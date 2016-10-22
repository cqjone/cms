<?php
/**
 * 上传第六版：多文件上传
 * 1.统一的错误处理
 * 2.自定义input的name值;
 * 3.setNewName()新文件名;
 * 4.iconv():处理中文，把utf8字符集转换成gb2312字符集
 * 5.删除目录处理
 * 6.throwError()统一抛错：内置错误和自定义错误
 * 7.构造方法的参数为数组
 * 8.createDir()：创建子目录；
 * @author Administrator
 *  */
class M_Transfer{
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
    private $allowType=array("png","jpg","gif","html");
    private $path="uploads";
    private $errorNum;
    private $aaa;
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
    public function getError(){
        return $this->errorMsg;
    }
    private function throwError($_errorNum){
        $str=null;
        switch ($_errorNum){
            case 0:
                $str="上传成功！";
                break;
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
            default:
                $str="上传发生未知错误";
                break;
        }
        return $str;
    }
    private function upoadedFileInfo($_fieldName){
        $this->tmpName=$_FILES[$_fieldName]["tmp_name"];
        $this->originalName=$_FILES[$_fieldName]["name"];
        $this->size=$_FILES[$_fieldName]['size'];
        $this->errorNum=$_FILES[$_fieldName]['error'];
    }
    private function setNewName($_i){
        $this->path=rtrim($this->path,"/")."/";
        if(!file_exists($this->path)){
            //mkdir($this->path,07777);
            $this->createDir($this->path);
        }
        $this->path=rtrim($this->path,"/")."/";
        if($this->isRandom){
            $this->newName=date("YmdHis").rand(100,999).".".$this->type;
        }else {
            $this->newName=$this->originalName[$_i];
        }
    }
    public function getNewName(){
        return $this->aaa;
    }
    private function createDir($_dir){
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
    public function upload($_fieldName){
        $this->upoadedFileInfo($_fieldName);
        //echo $_FILES['pic']['name'][1];
        //echo throwError(9);
        for($i=0;$i<count($this->originalName);$i++){
            if($this->errorNum[$i]){
                $this->errorMsg=$this->throwError($this->errorNum[$i]);
                return false;
            }
            //根据.打散成一个数组
            $arr=explode(".", $this->originalName[$i]);
            //取数组的最后一个元素：上传文件的扩展名
            //所以得扩展名转换为小写;
            $this->type=strtolower($arr[count($arr)-1]);
            //echo $type."<br>";
            //判断类型
            if(!in_array($this->type, $this->allowType)){
                $this->errorMsg.=$this->originalName[$i].$this->throwError(8);
                continue;
            }
            //判断大小
            if($this->size[$i]>$this->maxSize){
                //exit("over 2160");
                $this->errorMsg.=$this->originalName[$i].$this->throwError(9);
                continue;
            }
            $this->setNewName($i);
            //echo move_uploaded_file($this->tmpName[$i], "uploads/".$this->originalName[$i]);
            if(!move_uploaded_file($this->tmpName[$i], $this->path.$this->newName)){
               continue; 
            }else{
                //$this->errorMsg.=$this->originalName[$i].$this->throwError(0);
                $this->aaa.=$this->newName."<br>";
            }
        }
    }
}
?>
