<?php
/**
 * page第七版
 * 1.传每页显示的数据条数
 * 2.通过地址栏传值
 * 3.添加display方法，可以显示：首页，末页，上一页，下一页,当前页/总页数
 * 4.__set(),__get()
 * 5.handlePage()处理页面范围
 * 6.pageList();当前页，当前页之前和之后
 * 7.first(),end()出现省略号
 * 8.jump()数字范围;select()下拉
 * 9.setConfig()修改配置
 * 10.统一的错误处理
 * 11.dispay()的参数以数组的形式传递
 * 12.reWrite()重写URL
 * @author Administrator
 *  */
class Page{
    private $limit;
    //每页数据条数
    private $listRows;
    private $page;
    //总记录数
    private $total;
    //总页数
    private $pageNum;
    private $num;
    private $errorMsg;
    private $url;
    private $config=array('prev'=>"上一页","next"=>"下一页");
    public function __construct($_total,$_listRows=5,$_num=3){
        $this->num=$_num;
        $this->total=$_total;
        $this->listRows=$_listRows;
        //如果地址栏传了page就等于这个值，每页传就等于1;
        //$this->page=!empty($_GET['page'])?$_GET['page']:1;
        //获取总页数
        $this->pageNum=ceil($this->total/$this->listRows);
        $this->handlePage();
        //$sql="select * from user limit ($page-1)*$listRows,$listRows";
        $this->limit=" limit ".($this->page-1)*$this->listRows.",".$this->listRows;
        $this->url=$this->reWrite();
    }
    public function __set($_value,$_key){
        $this->$_key=$_value;
    }
    public function __get($_key){
        return $this->$_key;
    }
    public function getError(){
        return $this->errorMsg;
    }
    private function reWrite(){
        $newURL=null;
        //获取path/queryString:/php3/oop/getData.php?action=show&id=8
        $url=$_SERVER['REQUEST_URI'];
        //echo "<hr>".$url."<hr>";
        //parse_url:解析路径，返回路径的组件，有query下标的是查询字符串;
        /*array(2) {
         ["path"]=>
         string(21) "/php3/oop/getData.php"
         ["query"]=>
         string(16) "action=show&id=8"
         }
         */
        $parseURL=parse_url($url);
        //echo "<pre>";
        //var_dump($parseURL);
        //echo "</pre>";
        if(isset($parseURL['query'])){
            //把查询字符解析到数组中;
            /**
            * array(2) {
            ["action"]=>
            string(4) "show"
            ["page"]=>
            string(1) "2"
            }
            **/
            parse_str($parseURL['query'],$query);
            //echo "<pre>";
            //var_dump($query);
            //echo "</pre>";
            //销毁page元素
            unset($query['page']);
            //http_build_query:重新生成查询字符串;
            $newURL=$parseURL['path']."?".http_build_query($query);
            //echo "<hr>".$newURL."<hr>";
        }else{
            $newURL=$parseURL['path']."?";
        }
        return $newURL;
    }
    
    /**
     * 重新设置config的值;
     *
     * @param array $_config;
     *  */
    public function setConfig($_config){
        if($_config){
            if(is_array($_config)&&count($_config)!=0){
                foreach ($_config as $_key=>$_value){
                    //判断传进来的下标是否在config中
                    if(array_key_exists($_key,$this->config)){
                        $this->config[$_key]=$_value;
                    }else{
                        $this->errorMsg="<span>setConfig()方法的参数的下标不正确</span>";
                    }
                }
            }else{
                $this->errorMsg="<span>setConfig()的参数为非空数组</span>";
            }
        }else{
            $this->errorMsg="<span>setConfig()的参数不能为空</span>";
        }
    }
    private function pageList(){
        $prev=null;
        $next=null;
        //当前页减
        for($i=$this->num;$i>=1;$i--){
            if($this->page-$i<1){
                continue;
            }else{
                $prev.="<li><a href='".$this->url."&page=".($this->page-$i)."'>".($this->page-$i)."</a></li>";
            }
        }
        //当前页;
        $present="<li class='present'>".$this->page."</li>";
        //当前页加
        for($j=1;$j<=$this->num;$j++){
            if($this->page+$j<=$this->pageNum){
                $next.="<li><a href='".$this->url."&page=".($this->page+$j)."'>".($this->page+$j)."</a></li>";
            }else{
                break;
            }
        }
        return $prev.$present.$next;
    }
    /**
     * 当前页
     * @return string  */
    private function present(){
        return "<li class='present'>".$this->page."</li>";
    }
    
    /**
     * 处理页数范围
     *
     * 页数小于1时等于1；页数大于最大值时等于最大值；
     *   */
    private function handlePage(){
        //$_GET['page']默认值为1;
        $this->page=!empty($_GET['page'])?$_GET['page']:1;
        if($this->page>$this->pageNum){
            $this->page=$this->pageNum;
        }
        if($this->page<1){
            $this->page=1;
        }
    }
    /**
     * 显示首页，如果当前页是1的话，每页链接，其它有链接
     * @return string $str;
     *   */
    /**
     * 首页
     * @return string */
    private function first(){
        $str=null;
        if($this->page==1){
            //$str="<li class='disabled'><span>首页</span></li>";
            $str=null;
        }else if($this->page>$this->num+2){
            $str="<li><a href='".$this->url."&page=1'>1</li><li>...</li>";
        }elseif($this->page>$this->num+1){
            $str="<li><a href='".$this->url."&page=1'>1</li>";
        }
        return $str;
    }
    private function jump(){
        $str=null;
        $str="<li class='next'><input id='pageValue' type='number' min=1 max=".($this->pageNum)." style='width:100%;text-align:center;' value='".$this->page."'></li>";
        return $str;
    }
    /**
     * 显示末页
     *
     * 如果到了最后一页，末页不可以点击;不到最后一页，末页可以点击
     *
     * @return string $str：返回字符串 */
    private function end(){
        $str=null;
        if($this->page==$this->pageNum){
            //$str.="<li class='disabled'><span>末页</span></li>";
            $str=null;
        }elseif($this->pageNum-$this->page>$this->num+1){
            $str="<li>...</li>
                <li><a href='".$this->url."&page=".($this->pageNum)."'>".($this->pageNum)."</a></li>";
        }elseif($this->pageNum-$this->page>$this->num){
            $str="<li><a href='".$this->url."&page=".($this->pageNum)."'>".($this->pageNum)."</a></li>";
        }
        return $str;
    }
    private function prev(){
        $str=null;
        if($this->page==1){
            $str="<li class='next'>".$this->config['prev']."</li>";
        }else{
            $str="<li class='next'><a href='".$this->url."&page=".($this->page-1)."'>".$this->config['prev']."</a></li>";
        }
        return $str;
    }
    private function next(){
        $str=null;
        if($this->page==$this->pageNum){
            $str="<li class='next'>".$this->config['next']."</li>";
        }else{
            $str="<li class='next'><a href='".$this->url."&page=".($this->page+1)."'>".$this->config['next']."</a></li>";
        }
        return $str;
    }
    /**
     * 页数的select跳转
     * @return string  */
    private function select(){
        $str=null;
        $str.="<li><select id='pageSelect'>";
        for($i=$this->num*2;$i>=1;$i--){
            if($this->page-$i<1){
                continue;
            }else{
                $str.="<option value='".($this->page-$i)."'>".
                    ($this->page-$i."/".$this->pageNum)."</option>";
            }
        }
        for($j=0;$j<=$this->num*2;$j++){
            if($this->page+$j<=$this->pageNum){
                if($this->page==($this->page+$j)){
                    $str.="<option value='".($this->page+$j)."' selected='selected'>"
                        .($this->page+$j."/".$this->pageNum)."</option>";
                }else{
                    $str.="<option value='".($this->page+$j)."'>".($this->page+$j."/".
                        $this->pageNum)."</option>";
                }
            }else{
                break;
            }
        }
        $str.="</select></li>";
        return $str;
    }
    
    public function display($_data=array(0,1,2,3,4,5,6)){
    //参数必须是数组并且不能为空        
        if(is_array($_data)&&count($_data)!=0){
            $str="<ul>";
            $ui[0]=$this->prev();
            $ui[1]=$this->first();
            $ui[2]=$this->pageList();
            $ui[3]=$this->end();
            $ui[4]=$this->next();
            $ui[5]=$this->select();
            $ui[6]=$this->jump();
            $data=array(0,1,2,3,4,5,6);
            //var_dump($ui); 
            foreach ($_data as $key=>$value){
                //下标不能超出范围并且元素只能是数字;
                if(in_array($value,$data) && is_int($value)){
                    $str.=$ui[$value];
                }else{
                    echo "<span class='text-danger'>display方法参数的下标错误</span>";
                }                               
            } 
            $str.="</ul>";          
        }else{
            echo "<span class='text-danger'>display方法传递的参数必须是非空数组</span>";
        }       
        
        return $str;
    }
}
?>