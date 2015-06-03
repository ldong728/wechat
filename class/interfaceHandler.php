<?php

//测试号：
//define('appID','wx03393af10613da23');
//define('appsecret','40751854901cc489eddd055538224e8a');
//define('wexinId','gh_964192c927cb');
//个人公众号：
//define('appID','wx8426b63b5ce8c518');
//define('appsecret','451129432c9ebe8fb74d0ac1a3926e3e');
//define('wexinId','myBrainHole');
$mypath=$_SERVER['DOCUMENT_ROOT'] . '/wechat';
class interfaceHandler {
    public $currentToken='';
    public $gettedTime=0;
    public $expiresIn=7200;
    public $weixinId='';
    public function __construct($id){
        $this->weixinId=$id;
//        wxlog('handerConstructIdgetted:'.$id);
        $this->reflashAccessToken();
    }
    public function reflashAccessToken(){
        if($this->currentToken==''||$this->gettedTime==0) {
            $tokenFileData = file_get_contents($GLOBALS['mypath'] . '/tokens/'.$this->weixinId.'.token');
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
        include_once $GLOBALS['mypath'].'/includes/db.inc.php';
        $appinf=pdoQuery('user_tbl',null,array('weixin_id'=>$this->weixinId),null);
        $data = $appinf->fetch();
        $jsonToken=file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$data['app_id'].'&secret='.$data['app_secret']);
        $geted=json_decode($jsonToken,true);
        $geted['gettedTime']=time();
        $this->currentToken=$geted['access_token'];
        $this->gettedTime=$geted['gettedTime'];
        $this->expiresIn=$geted['expires_in'];
        $reJson=json_encode($geted);
        file_put_contents($GLOBALS['mypath'] . '/tokens/'.$this->weixinId.'.token',$reJson);
//        wxlog($reJson);
//        wxlog($GLOBALS['mypath'] . '/tokens/'.$this->weixinId.'.token');
        wxlog($this->weixinId.': getTokenOnLine');
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
    public function uploadFileByCurl($remote_server,$file,$exraInf=null){
        $remote_server=$this->replaceAccessToken($remote_server);
        $fields['media'] = '@'.$file;
        if($exraInf!=null){
            foreach ($exraInf as $k=>$v) {
                $fields[$k]=$v;
            }

        }
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