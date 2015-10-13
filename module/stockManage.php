<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/8/26
 * Time: 20:21
 */

// 配置信息json：    {"config":{"kfKeyWord":"客服","evaluate":"1"},"inputType":["text","checkbox"],"configInf":["开启客服模式的关键词","开启客服评分模式"]}
include_once $mypath."/class/stock.php";
$myStock=new stock($weixinId);

if(isset($msg['Content'])) {
    $inf=$myStock->getStockInf($msg['Content']);
    $weixin->replytext($inf);
    exit;


}