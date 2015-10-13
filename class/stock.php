<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/8/26
 * Time: 17:30
 */


include_once $mypath . '/class/interfaceHandler.php';


class stock {
    private $interface;


    public function __construct($weixinId){
        $this->interface=new interfaceHandler($weixinId);

    }


    public function getStockInf($stockId){
        $inf=$this->interface->sendGet('http://hq.sinajs.cn/list='.$stockId);
        $index=strcspn($inf,'=');
        $inf=substr($inf,$index+1);
//        wxlog($inf);
        return $inf;

    }


}