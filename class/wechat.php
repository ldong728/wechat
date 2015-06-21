<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/17
 * Time: 8:31
 */

//define("TOKEN", "godlee");

class wechat
{
    public $weixinId='';
    public $msg;
    public function __construct($wxid){
        $this->weixinId=$wxid;
}

    public function valid()  //微信服务器验证配置用
    {
//        wxlog('valid start');
        if (isset($_GET['echostr'])) {
            $echoStr = $_GET["echostr"];
            if ($this->checkSignature()) {
                echo $echoStr;
                exit;
            }
        }
    }




    public function receiverFilter_o()
    {

            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            if (!empty($postStr)) {
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $msg['from'] = $postObj->FromUserName;
                $msg['me'] = $postObj->ToUserName;
                $msg['type'] = $postObj->MsgType;
                $msg['msgId'] = $postObj->MsgId;
//                wxlog('basic msginf ok,type: '.$msg['type']);
                switch ($msg['type']) {
                    case 'text': {
                        $msg['content'] = trim($postObj->Content);
                        break;
                    }
                    case 'image': {
                        $msg['PicUrl'] = $postObj->PicUrl;
//                        wxlog('geturl' . $msg['PicUrl']);
                        $msg['MediaId'] = $postObj->MediaId;
//                        wxlog('getMediaId:' . $msg['MediaId']);
                        break;
                    }
                    case 'voice': {
                        $msg['MediaId'] = $postObj->MediaId;
                        $msg['Format'] = $postObj->Format;
                        break;
                    }
                    case 'video': {
                        $msg['MediaId'] = $postObj->MediaId;
                        $msg['ThumbMediaId'] = $postObj->ThumbMediaId;
                        break;
                    }
                    case 'shortvideo': {
                        $msg['MediaId'] = $postObj->MediaId;
                        $msg['ThumbMediaId'] = $postObj->ThumbMediaId;
                        break;
                    }
                    case 'event': {
                        $msg['Event'] = $postObj->Event;
                        $msg['EventKey'] = $postObj->EventKey;

                        break;
                    }


                }
            }
            $this->msg=$msg;
            wxlog(json_encode($msg));
            return $msg;

//        }
//        echo 'error';
//        exit;

    }
    public function receiverFilter()
    {

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msg['from'] = $postObj->FromUserName;
            $msg['me'] = $postObj->ToUserName;
            $msg['content'] = trim($postObj->Content);
            foreach ($postObj->children() as $child) {
//                wxlog($child);
                $msg[$child->getName()]=(string)$child;
            }
            $this->msg=$msg;
            return $msg;
        }
    }

    public function prepareTextMsg($sentTo, $me, $content)
    {
        $con=array('MsgType'=>'text','Content'=>$content);
        $resultStr=$this->prepareMsg($con);
        return $resultStr;
    }
    public function prepareToKFMsg($sentTo,$me){
        $resultStr=$this->prepareMsg(array('MsgType'=>'transfer_customer_service'));
        return $resultStr;
    }

    public function replyMsg(array $content){
        $replyStr=$this->prepareMsg($content);
        echo $replyStr;
    }

    private function prepareMsg(array $content){
        $textTpl = '<?xml version="1.0" encoding="utf-8"?><xml>
							<ToUserName><![CDATA['.$this->msg['FromUserName'].']]></ToUserName>
							<FromUserName><![CDATA['.$this->msg['ToUserName'].']]></FromUserName>
							<CreateTime>'.time().'</CreateTime>
							</xml>';
        $xml=new SimpleXMLElement($textTpl);
        $this->arrayToXml($xml,$content);
        $replyStr=$xml->asXML();
        return $replyStr;
    }
    private function arrayToXml(SimpleXMLElement $xml, array $array){
        foreach ($array as $k => $v) {
            if(is_array($v)){
                arrayToXml($xml->addChild($k),$v);
            }else{
                $xml->addChild($k,$v);
            }
        }
        return $xml;

    }
    public function replytext($response){
        $content=array('MsgType'=>'text','Content'=>$response);
        $this->replyMsg($content);
    }

    public function prepareNewsMsg($sentTo,$me,$newsJson){
        $time=time();
        $data=json_decode($newsJson,true);
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>".count($data['news_item'])."</ArticleCount>
                            <Articles>
							";
        $textTitle = sprintf($textTpl, $sentTo, $me, $time);

        $textTpl="<item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                        </item>
                        ";
        foreach ($data['news_item'] as $row) {
            $title=(isset($row['title'])? $row['title'] : '无标题');
            $description=(isset($row['digest'])? $row['digest']:'');
            $picUrl=(isset($row['cover_url'])?$row['cover_url']: '');
            $url=(isset($row['url'])?$row['url']:'');
            $url=(isset($row['content_url'])?$row['content_url'] : $url);
            $content=sprintf($textTpl,$title,$description,$picUrl,$url);
            $textTitle=$textTitle.$content;
        }
        $textTitle=$textTitle."</Articles></xml>";
//        wxlog($textTitle);
        return $textTitle;

    }


    private function checkSignature()
    {

        // you must define TOKEN by yourself
//        if (!defined("TOKEN")) {
//            throw new Exception('TOKEN is not defined!');
//        }
        $query=pdoQuery('user_tbl',array('token'),array('weixin_id'=>$this->weixinId),'limit 1');
        $row=$query->fetch();
        $token=$row['token'];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function validMsg()
    {
        if ($this->checkSignature()) return true;
        else return false;
    }

}