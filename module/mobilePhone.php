<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/5/26
 * Time: 17:12
 */
wxlog('include mobilePhone ok');
if(count($msg['content'])<10&&count($msg['content'])>0){
    include_once $mypath.'/class/mobilePhoneQuery.php';
    $phoneQuery= new mobilePhoneQuery();
    $response=$phoneQuery->getPrice($msg['content']);
    if($response!='以上报价由慈溪兄弟数码提供，仅供参考，详情请咨询店家,电话：4000-120-188'){
//        $response= $weixin->prepareTextMsg($msg['from'],$msg['me'],$response);
//        echo $response;
        $weixin->replytext($response);
        exit;
    };
}
