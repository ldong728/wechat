<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/17
 * Time: 8:31
 */

define("TOKEN", "godlee");

class wechat
{
    public function valid()  //微信服务器验证配置用
    {
//        wxlog('valid start');
        if (isset($_GET['echostr'])) {
            wxlog('echostr: '.$_GET['echostr']);
            $echoStr = $_GET["echostr"];
            if ($this->checkSignature()) {
                echo $echoStr;
                exit;
            }
        }
    }




    public function receiverFilter()
    {
        if ($this->validMsg()) {
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            if (!empty($postStr)) {
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $msg['from'] = $postObj->FromUserName;
                $msg['me'] = $postObj->ToUserName;
                $msg['type'] = $postObj->MsgType;
                $msg['msgId'] = $postObj->MsgId;
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
            return $msg;
        }
        echo 'error';
        exit;

    }

    public function prepareTextMsg($sentTo, $me, $content)
    {
        $time = time();
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
        $resultStr = sprintf($textTpl, $sentTo, $me, $time, $content);
        return $resultStr;
    }
    public function prepareToKFMsg($sentTo,$me){
        $time = time();
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[transfer_customer_service]]></MsgType>
							</xml>";
        $resultStr = sprintf($textTpl, $sentTo, $me, $time);
        return $resultStr;
    }


    private function checkSignature()
    {

        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
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