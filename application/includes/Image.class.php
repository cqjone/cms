<?php
/**
*图片类:背景图和水印图都在指定的目录中
*/
class Image{
	private $filePath;
	public function __construct($_filePath='uploads/'){
		if(!file_exists($_filePath)){
			mkdir($_filePath,0777);
		}
		$this->filePath=rtrim($_filePath,"/")."/";
	}
	/**
	 * 根据传进来的图片，获取图片的宽、高、类型
	 * @param array $_fileName
	 * @return array $imageInfo:包含图片的属性的数组
	 *   */
	public function getInfo($_fileName){
		$data=getimagesize($this->filePath.$_fileName);
		$imageInfo['width']=$data[0];
		$imageInfo['height']=$data[1];
		$imageInfo['type']=$data[2];
		return $imageInfo;
	}
	/**
	 * 根据传入的图片的不同类型，生成相应的图片资源；
	 * @param string $_fileName
	 * @param array $_imgInfo
	 * @return boolean|resource  */
	private function getImageResource($_fileName,$_imgInfo){
		switch($_imgInfo['type']){
			case 1:
				$image=imagecreatefromgif($this->filePath.$_fileName);
				break;
			case 2:
				$image=imagecreatefromjpeg($this->filePath.$_fileName);
				break;
			case 3:
				$image=imagecreatefrompng($this->filePath.$_fileName);
				break;
			default:
				return false;
		}
		return $image;
	}
	/**
	 * 等比例的获取新尺寸 
	 * @param int $_width
	 * @param int $_height
	 * @param array $_imgInfo  */
	private function getNewSize($_width,$_height,$_imgInfo){
		$size['width']=$_imgInfo['width'];
		$size['height']=$_imgInfo['height'];		
		if($_width<$_imgInfo['width']){
			$size['width']=$_width;
		}
		if($_height<$_imgInfo['height']){
			$size['height']=$_height;
		}
		//等比例的公式
		if($_imgInfo['width']*$size['width']>$_imgInfo['height']*$size['height']){
			$size['height']=round($_imgInfo['height']*$size['width']/$_imgInfo['width']);
		}else{
			$size['width']=round($_imgInfo['width']*$size['height']/$_imgInfo['height']);
		}
		return $size;
	}
	/**
	 * 根据传入的图片的类型，生成相应的新的图片资源，并且导出相应格式的新图片;
	 * 
	 * $_imgInfo['type']:1是gif，2是jpg，3是png；
	 * @param resource $_newImage
	 * @param string $_newFileName
	 * @param array $_imgInfo  
	 * @return string $_newFileName:返回缩略图的名字;
	 * */
	private function outputNewImage($_newImageResource,$_newFileName,$_imgInfo){
		switch ($_imgInfo['type']){
			case 1:
				imagegif($_newImageResource,$this->filePath.$_newFileName);
				break;
			case 2:
				imagejpeg($_newImageResource,$this->filePath.$_newFileName);
				break;
			case 3:
				imagepng($_newImageResource,$this->filePath.$_newFileName);
				break;				
		}
		imagedestroy($_newImageResource);
		return $_newFileName;
	}
	/**
	 *生成经过等比例缩放的新图片资源；
	 * @param 原图的图片资源 $_sourceImage
	 * @param array $_size
	 * @param array $_imgInfo  */
	private function resizeImage($_sourceImageResource,$_size,$_imgInfo){
		$newImageResource=imagecreatetruecolor($_size['width'], $_size['height']);
		imagecopyresized($newImageResource,$_sourceImageResource,0, 0, 0, 0, $_size['width'],$_size['height'],$_imgInfo['width'], $_imgInfo['height']);
		imagedestroy($_sourceImageResource);
		return $newImageResource;
	}
	/**
	 * 生成缩略图
	 * @param string $_fileName:源文件图片名
	 * @param int $_width：缩略图的宽
	 * @param int $_height：缩略图的高
	 * @param string $prefix：缩略图的前缀
	 *   */
	public function thumb($_fileName,$_width,$_height,$prefix="only_"){
		//根据图片名，获取图片的宽、高、类型
		$imageInfo=$this->getInfo($_fileName);
		//根据图片的信息，生产源图片的图片资源
		$sourceImageResource=$this->getImageResource($_fileName,$imageInfo);
		//生成新图片的尺寸
		$size=$this->getNewSize($_width, $_height,$imageInfo);
		//生成新的图片资源
		$newImageResource=$this->resizeImage($sourceImageResource,$size,$imageInfo);
		//根据原图的类型，输出相应的图片
		echo $this->outputNewImage($newImageResource, $prefix.$_fileName, $imageInfo);
	}
	/**
	 * 对图片缩放
	 * @param string $_destionFileName
	 * @param number $_rate  */
	public function scale($_destionFileName,$_rate=0.5){
		$prefix=date("YmdHis");
		$imageInfo=$this->getInfo($_destionFileName);
		$sourceImageResource=$this->getImageResource($_destionFileName,$imageInfo);
		$size['width']=$imageInfo['width']*$_rate;
		$size['height']=$imageInfo['height']*$_rate;		
		$newImageResource=$this->resizeImage($sourceImageResource,$size,$imageInfo);
		echo $this->outputNewImage($newImageResource, $prefix.$_destionFileName, $imageInfo);
	}
	/**
	 *修剪图片 
	 * 
	 * @param string $_destionFileName
	 * @param int $trim_x:修剪的x坐标
	 * @param int $trim_y
	 * @param int $trim_width
	 * @param int $trim_height  */
	public function trim($_destionFileName,$trim_x,$trim_y,$trim_width,$trim_height){
		$prefix=date("YmdHis");
		$imageInfo=$this->getInfo($_destionFileName);
		$sourceImageResource=$this->getImageResource($_destionFileName,$imageInfo);
		$newImageSource=imagecreatetruecolor($trim_width, $trim_height);
		imagecopyresampled($newImageSource, $sourceImageResource, 0, 0,$trim_x, $trim_y, $trim_width, $trim_height,$trim_width, $trim_height);
		$this->outputNewImage($newImageSource, $prefix.$_destionFileName, $imageInfo);
	}
	/**
	 * 旋转图片
	 * @param string $_destionFileName
	 * @param int $_angle
	 * @param int $_bgColor  */
	public function rotate($_destionFileName,$_angle,$_bgColor){
		$prefix=date("YmdHis");
		$imageInfo=$this->getInfo($_destionFileName);
		$sourceImageResource=$this->getImageResource($_destionFileName,$imageInfo);
		$newImageSource=imagerotate($sourceImageResource, $_angle, $_bgColor);
		$this->outputNewImage($newImageSource, $prefix.$_destionFileName, $imageInfo);
	}
	/**
	 * 图片翻转，默认沿x轴旋转，既垂直翻转。
	 * @param string $_destionFileName
	 * @param string $_direction  */
	public function flip($_destionFileName,$_direction="x"){
		$prefix=date("YmdHis");
		$imageInfo=$this->getInfo($_destionFileName);
		$sourceImageResource=$this->getImageResource($_destionFileName,$imageInfo);
		$newImageSource=imagecreatetruecolor($imageInfo["width"], $imageInfo['height']);
		if ($_direction=="y"){
			for ($i = 0; $i < $imageInfo["width"]; $i++) {
				imagecopy($newImageSource, $sourceImageResource, $imageInfo["width"]-$i-1, 0, $i, 0, 1, $imageInfo['height']);
			}
		}else{
			for ($i = 0; $i < $imageInfo['height']; $i++) {
				imagecopy($newImageSource, $sourceImageResource, 0, $imageInfo['height']-$i-1, 0, $i, $imageInfo["width"], 1);
			}
		}
		$this->outputNewImage($newImageSource, $prefix.$_destionFileName, $imageInfo);
	}
	/**
	 * 对图片进行锐化
	 * @param string $_destionFileName
	 * @param int $_degree  */
	public function sharp($_destionFileName,$_degree){
		$prefix=date("YmdHis");
		$imageInfo=$this->getInfo($_destionFileName);
		$sourceImageResource=$this->getImageResource($_destionFileName,$imageInfo);
		$newImageSource=imagecreatetruecolor($imageInfo["width"], $imageInfo['height']);
		for ($i = 1; $i < $imageInfo['width']; $i++) {
			for ($j = 1; $j < $imageInfo['height']; $j++) {
				$b_c1 = imagecolorsforindex($sourceImageResource, imagecolorat($sourceImageResource, $i-1, $j-1));
				$b_c2 = imagecolorsforindex($sourceImageResource, imagecolorat($sourceImageResource, $i, $j));		
				$r = intval($b_c2["red"]+$_degree*($b_c2["red"]-$b_c1["red"]));
				$g = intval($b_c2["green"]+$_degree*($b_c2["green"]-$b_c1["green"]));
				$b = intval($b_c2["blue"]+$_degree*($b_c2["blue"]-$b_c1["blue"]));				
				$r = min(255,max($r,0));
				$g = min(255,max($g,0));
				$b = min(255,max($b,0));		
				if (($d_clr = imagecolorexact($newImageSource, $r, $g, $b))==-1){
					$d_clr = imagecolorexact($newImageSource, $r, $g, $b);
				}		
				imagesetpixel($newImageSource, $i, $j, $d_clr);
			}
		}
		$this->outputNewImage($newImageSource, $prefix.$_destionFileName, $imageInfo);
	}
	/**
	 * 复制图片
	 * @param string $_destionFileName  */
	public function copy($_destionFileName){
		$prefix=date("YmdHis");
		$imageInfo=$this->getInfo($_destionFileName);
		$sourceImageResource=$this->getImageResource($_destionFileName,$imageInfo);
		$newImageResource=$this->resizeImage($sourceImageResource, $imageInfo, $imageInfo);
		echo $this->outputNewImage($newImageResource, $prefix.$_destionFileName, $imageInfo);		
	}
	/**
	 * 添加水印；
	 * @param string $_groundName:背景图文件名
	 * @param string $_waterName：水印图文件名
	 * @param number $_waterMarkPos：水印的位置
	 * @param string $prefix
	 * @return boolean|Ambigous <string, string>  */	
	public function waterMark($_groundName,$_waterName,$_waterMarkPos=9,$prefix='kong_'){
		//判断背景图和水印图片都存在;
		if(file_exists($this->filePath.$_groundName)&&(file_exists($this->filePath.$_waterName))){
			$groundInfo=$this->getInfo($_groundName);
			$waterInfo=$this->getInfo($_waterName);
			if(!$pos=$this->position($groundInfo, $waterInfo, $_waterMarkPos)){
				echo "水印图片添加不成功";
				return false;
			}
			$groundImageResource=$this->getImageResource($_groundName, $groundInfo);
			$waterImageResource=$this->getImageResource($_waterName, $waterInfo);
			$groundImageResource=$this->addWaterMarkImage($groundImageResource, $waterImageResource, $pos, $waterInfo);
			echo $this->outputNewImage($groundImageResource, $prefix.$_groundName,$groundInfo);
		}else{
			echo "图片或水印图片不存在";
			return false;
		}
	}
	/**
	 * 为背景图添加水印图片
	 * @param Resource $_groundImageResource
	 * @param Resource $_waterMarkResource
	 * @param array $_pos
	 * @param array  $_waterInfo
	 * @return resource|false;
	 *   */
	private function addWaterMarkImage($_groundImageResource,$_waterMarkResource,$_pos,$_waterInfo){
		imagecopy($_groundImageResource, $_waterMarkResource, $_pos['posX'], $_pos['posY'],0, 0, $_waterInfo['width'], $_waterInfo['height']);
		imagedestroy($_waterMarkResource);
		return $_groundImageResource;
	}
	/**
	 * 调整水印图片在背景图的位置；
	 * 
	 * @param array $_groundInfo
	 * @param array $_waterInfo
	 * @param int $_waterPos  
	 * @return array;
	 * */
	private  function position($_groundInfo,$_waterInfo,$_waterPos){		if($_groundInfo['width']<$_waterInfo['width']||($_groundInfo['height']<$_waterInfo['height'])){
			echo "水印图片不能大于背景图";
			return false;
		}
		switch ($_waterPos){
			case 1:
				$posX=0;
				$posY=0;
				break;
			case 2:
				$posX=($_groundInfo['width']-$_waterInfo['width'])/2;
				$posY=0;
				break;
			case 3:
				$posX=$_groundInfo['width']-$_waterInfo['width'];
				$posY=0;
				break;
			case 4:
				$posX=0;
				$posY=($_groundInfo['height']-$_waterInfo['height'])/2;
				break;
			case 5:
				$posX=($_groundInfo['width']-$_waterInfo['width'])/2;
				$posY=($_groundInfo['height']-$_waterInfo['height'])/2;
				break;
			case 6:
				$posX=$_groundInfo['width']-$_waterInfo['width'];
				$posY=($_groundInfo['height']-$_waterInfo['height'])/2;
				break;
			case 7:
				$posX=0;
				$posY=$_groundInfo['height']-$_waterInfo['height'];
				break;
			case 8:
				$posX=($_groundInfo['width']-$_waterInfo['width'])/2;
				$posY=$_groundInfo['height']-$_waterInfo['height'];
				break;
			case 9:
				$posX=$_groundInfo['width']-$_waterInfo['width'];
				$posY=$_groundInfo['height']-$_waterInfo['height'];
				break;
			default:
				echo "水印图片的位置代码不对";
				return false;
		}
		return array('posX'=>$posX,'posY'=>$posY);		
	}
}
?>
