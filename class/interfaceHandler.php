<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/17
 * Time: 8:21
 */
define('appID','wx0fe280bfdea0083c');
define('appsecret','49e1178d8a8e6d4e840434c3e1c9bd5a');
define('wexinId','cxxdsm');
$mypath=$_SERVER['DOCUMENT_ROOT'] . '/xdsm';
class interfaceHandler {
    public $currentToken='';
    public $gettedTime=0;
    public $expiresIn=7200;
    public function __construct(){
        $this->reflashAccessToken();
    }
    public function reflashAccessToken(){
        if($this->currentToken==''||$this->gettedTime==0) {
            $tokenFileData = file_get_contents($GLOBALS['mypath'] . '/class/token.dat');
            $token = json_decode($tokenFileData,true);
            $this->currentToken=$token['access_token'];
            $this->gettedTime=$token['gettedTime'];
            $this->expiresIn=$token['expires_in'];
//            wxlog('gettedTime from file: '.$token['gettedTime']);
//            wxlog('expiresIn time from file:'.$token['expires_in']);
            //echo'read form file';
        }
        if($this->gettedTime+$this->expiresIn<time()-100){
//            wxlog('token timeout: '.(time()-($this->gettedTime+$this->expiresIn)));
            $this->getTokenOnLine();
            //echo 'read from onLine';
        }else{
//            wxlog('get token from file');
        }
    }
    public function getTokenOnLine(){
        $jsonToken=file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.appID.'&secret='.appsecret);
        $geted=json_decode($jsonToken,true);
        $geted['gettedTime']=time();
        $this->currentToken=$geted['access_token'];
        $this->gettedTime=$geted['gettedTime'];
        $this->expiresIn=$geted['expires_in'];
        $reJson=json_encode($geted);
        file_put_contents($GLOBALS['mypath'] . '/class/token.dat',$reJson);
        wxlog('getTokenOnLine');
    }
    public function sendPost($url, $request_data) {
        $url=$this->replaceAccessToken($url);
        $postdata = http_build_query($request_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
}
    public function sendGet($str){
        $str=$this->replaceAccessToken($str);
        $getted=file_get_contents($str);
        return $getted;
    }
    public function getByCurl($url) {
        $str=$this->replaceAccessToken($url);
//        wxlog($url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $str);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
    private function replaceAccessToken($url){
        $this->reflashAccessToken();
        $result= preg_replace('/ACCESS_TOKEN/',$this->currentToken,$url);
        return $result;
    }
    /*
     * $post_string = "a=abc&b=def";
     */
    public function postByCurl($remote_server, $post_string) {
        $remote_server=$this->replaceAccessToken($remote_server);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
}
    public function postJsonByCurl($remote_server,$json_string){
        $remote_server=$this->replaceAccessToken($remote_server);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_string))
        );
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function postArrayAsJson($remote_server,$sArray){
        $remote_server=$this->replaceAccessToken($remote_server);
        $jsonData=json_encode($sArray,JSON_UNESCAPED_UNICODE);
        $data=$this->postJsonByCurl($remote_server,$jsonData);
        return $data;
    }
    public function uploadFileByCurl($remote_server,$file){
        $remote_server=$this->replaceAccessToken($remote_server);
        $fields['media'] = '@'.$file;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$remote_server);
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data=curl_exec ($ch);
        curl_close ($ch);
        return $data;
//        return $data;

    }


} 