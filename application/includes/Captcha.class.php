<?php
/**
 * 验证码类
 * 
 * @author kong
 *  */
class Captcha{
    private $width;//验证码的宽
    private $height;//验证码的高   
    private $image;//图片资源
    private $noiseNum;//干扰元素的数量
    private $captchaCode;//干扰元素的字符
    private $errorMsg;//错误信息;
    private $codeNum;
    private $config=array('level'=>10,"isNoise"=>true,'simple'=>true);
    public function __construct($_width=80,$_height=20,$_codeNum=4){
        $this->width=$_width;
        $this->height=$_height;  
        $this->codeNum=$_codeNum;    
        //验证字符是由createCaptcha()方法生成;
        $this->captchaCode=$this->createCaptcha();           
    }  
    /**
     * 重新设置config的值;
     *
     * @param string $_key
     * @param string $_value  */
     public function setConfig($_config){
         if($_config){
             if(is_array($_config)&&count($_config)!=0){
                 foreach ($_config as $_key=>$_value){
                     //判断传进来的下标是否在config中
                     if(array_key_exists($_key,$this->config)){
                         $this->config[$_key]=$_value;
                     }else{
                 $this->errorMsg="<span >setConfig()方法的参数的下标不正确</span>";
                     }
                 }
             }else{
                 $this->errorMsg="<span >setConfig()的参数为非空数组</span>";
             }
         }else{
             $this->errorMsg="<span >setConfig()的参数不能为空</span>";
         }             
     }
    /**
     * 返回错误信息 
     * @return string  */
    public function getMsg(){
        return $this->errorMsg;
    } 
    /**
     * 返回验证码
     * @return string  */
    public function getCaptcha(){
        return $this->captchaCode;
    }
    /**
     * 输出验证码图片
     * @param string $_fontFile  */
    public function showCaptcha($_fontFile='georgia.ttf'){
        //创建图片
        $this->createImage();
        //设置噪音:点和弧线
        $this->setNoise();
        //输出验证码字符
        $this->outputText($_fontFile);
        //输出图片
        $this->outputImage();
    }    
    /**
     * 根据传进来的尺寸创建真彩色图片,默认是80*20
     **/
    private function createImage(){
        //创建真彩色图片
        $this->image=imagecreatetruecolor($this->width, $this->height);
        if($this->config['simple']){
            $this->config['isNoise']=false;
            //设置背景色
            $bgColor=imagecolorallocate($this->image,255,255,255);            
        }else{
            //设置背景色
            $bgColor=imagecolorallocate($this->image, rand(200,255),rand(200,255),rand(200,255));            
        }
        imagefill($this->image,0,0,$bgColor);
        //设置边框颜色$borderColor=imagecolorallocate($this->image,rand(0,200),rand(0,200),rand(0,200));
        //画一个没有填充的四边行imagerectangle($this->image,0,0,$this->width-1,$this->height-1,$borderColor);
    }
    /**
     * 输出png图片
     *   */
    private function outputImage(){
        //header("Content-Type:image/png);
        imagepng($this->image);
    }
    /**
     * 生成验证字符;
     * @return string  */
    private function createCaptcha(){
        $str="23456789qwertyuipkjhgfdsazxcvbnmQWERTYUIPLKJHGFDSAZXCVBNM";
        $code="";
        for($i=0;$i<$this->codeNum;$i++){
            //echo $i."<br>";
            $code.=$str[rand(0, strlen($str)-1)];
        }
        return $code;
    }
    private function outputText($_fontFile){
        for($i=0;$i<$this->codeNum;$i++){
            $fontColor=imagecolorallocate($this->image,rand(0,100),rand(0,100),rand(0,100));
            if ($_fontFile==''){                
                $fontSize=rand(3,5);
                $x=floor($this->width/$this->codeNum)*$i+3;
                $y=rand(0,$this->height-15);
                imagechar($this->image,$fontSize, $x, $y,$this->captchaCode[$i],$fontColor);
            }else{
              $fontSize=rand(10,16);
              $angle=rand(-30,30);  
              $x=floor($this->width-8)/($this->codeNum)*$i+8;
              $y=rand($fontSize+5,$this->height-2);
              imagettftext($this->image,$fontSize,$angle, $x, $y,$fontColor,$_fontFile,$this->captchaCode[$i]);
            }
        }
    }
    /**
     * 设置噪音:点和弧线;
     *   */
    private function setNoise(){
        if($this->config['isNoise']){
            //设置像素点;
           for($i=0;$i<$this->config['level']*10;$i++){
               $color=imagecolorallocate($this->image,rand(0,255),rand(0,255),rand(0,255));
               //设置像素点;
               imagesetpixel($this->image,rand(1,$this->width-2),rand(1,$this->height-2), $color);
           }
           for($j=0;$j<$this->config['level'];$j++){
               $color=imagecolorallocate($this->image,rand(0,100),rand(0,100),rand(0,100));
        //$image, $cx:圆心坐标, $cy:圆心坐标, $width, $height, $start:开始的角度, $end：结束的角度, $color               
               imagearc($this->image,rand(-10,$this->width),rand(-10,$this->height),
               rand(30,300),rand(20,200),55,44, $color);
           }
        }
    }
}
?>
