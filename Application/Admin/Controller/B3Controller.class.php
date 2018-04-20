<?php
namespace Admin\Controller;
class B3Controller extends CommonController{
    //R函数
    public function B33(){
        $r= R("B1/B11","B11");
        echo $r;
    }
}