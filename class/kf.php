<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/6/25
 * Time: 22:54
 */


include_once $mypath . '/class/interfaceHandler.php';
class kf {
    private $interface;


    public function __construct($weixinId){
        $this->interface=new interfaceHandler($weixinId);

    }

    public function getOnlineKf(){
        $str='https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?access_token=ACCESS_TOKEN';
        $inf=$this->interface->getByCurl($str);
        $onlineList=json_decode($inf,true);
        if(count($onlineList)<1)return false;
        $str='https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=ACCESS_TOKEN';
        $inf=$this->interface->getByCurl($str);
        $allList=json_decode($inf,true);
        foreach ($onlineList['kf_online_list'] as $row) {
            foreach ($allList['kf_list'] as $all) {
                if($row['kf_id']==$all['kf_id']){
                    $mylist[]=array('kf_account'=>$row['kf_account'],'kf_id'=>$row['kf_id'],'kf_nick'=>$all['kf_nick'],'accepted_case'=>$row['accepted_case']);
                    break;
                }
            }
        }
        return $mylist;

    }
    public function sendKfMsg($content,$open_id,$kf_account=null){
        $prejson=array('touser'=>$open_id,'msgtype'=>'text','text'=>array('content'=>$content));
        if(isset($kf_account))$prejson['customservice']=array('kf_account'=>$kf_account);
        $url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN';
        $ercode=$this->interface->postArrayAsJson($url,$prejson);
        return $ercode;

    }



} 