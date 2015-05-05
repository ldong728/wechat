<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/17
 * Time: 8:31
 */

define("TOKEN", "xdsm1234");

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
//        echo 'connect ok';
    }


    public function responseMsg($con)
    {
        $rMsg = $this->receiveMsg();
        $respnseStr = $this->prepareMsg($rMsg['from'], $rMsg['me'], 'text', $con);
        if (!empty($rMsg['content'])) {
            echo $respnseStr;
        } else {
            echo '???';
        }
    }

    public function receiveMsgBack()
    {// 已过期
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msg['from'] = $postObj->FromUserName;
            $msg['me'] = $postObj->ToUserName;
            $msg['type'] = $postObj->MsgType;
            $msg['picUrl'] = $postObj->PicUrl;
            $msg['content'] = trim($postObj->Content);
            return $msg;
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

    public function prepareMsg($sentTo, $me, $msgType, $content)
    {
        $time = time();
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        $resultStr = sprintf($textTpl, $sentTo, $me, $time, $msgType, $content);
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

    public function responseMsgBackup($response)  //sample 已过时
    {
//        file_put_contents('log.txt','getTheMsg ',FILE_APPEND);
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            if (!empty($keyword)) {
                $msgType = "text";
                $contentStr = $response;
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            } else {
                echo "Input something...";
            }

        } else {
            echo "";
            exit;
        }
    }
}