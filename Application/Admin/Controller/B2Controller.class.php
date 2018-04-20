<?php
namespace Admin\Controller;
class B2Controller extends CommonController
{
    //普通方法
   //public function B22(){
   //     $a = new B1Controller;
   //     echo  $a->B11();
   // }

    //A函数
    public function B22(){
        $a=A("B1");
        echo $a->B11();
    }

}