<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/29
 * Time: 9:37
 */
//include_once 'interfaceHandler.php';

$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
class jokeMaker {
//    private $joke;

    public function __construct(){
//        wxlog('jokeconstruct');
        $this->reflashSource();
}
    private function reflashSource(){
        $getTime=json_decode(file_get_contents($GLOBALS['mypath'] .'/tmpfiles/jock_time.dat'),true);
        if(date('d',time())!=date('d',$getTime['gettedTime'])){
            $mhand=new interfaceHandler();
            $jock=$mhand->getByCurl('http://api.laifudao.com/open/xiaohua.json');
            file_put_contents($GLOBALS['mypath'] .'/tmpfiles/jock.dat',$jock);
            wxlog('getJockOnline');
            $temp=json_encode(array('gettedTime'=>time()));
            file_put_contents($GLOBALS['mypath'] .'/tmpfiles/jock_time.dat',$temp);
            $this->initJokeNum($jock);
        }
    }
    public function getJoke(){
        $jockList=json_decode(file_get_contents($GLOBALS['mypath'] .'/tmpfiles/jock.dat'),true);
//        $offset=mt_rand(0,count($jockList));
        $offset=$this->flashJokeList();
        if($offset<0){
            $content='客官，今天的笑话讲完了，明天请赶早[微笑]';

        }else {
            $content = str_ireplace('<br/><br/>', "\n", $jockList[$offset]['content']);

        }
        return $content;
    }
    private function flashJokeList(){
        $num=file_get_contents($GLOBALS['mypath'] .'/tmpfiles/jock_num.dat');
//        wxlog('jokeNum:'.$num);
        if($num>0){
            file_put_contents($GLOBALS['mypath'] .'/tmpfiles/jock_num.dat',$num-1);
            return $num-1;
        }else{
            return -1;
        }

    }
    private function initJokeNum($source){
       $temp=json_decode($source,true);
        $num=count($temp);
        file_put_contents($GLOBALS['mypath'] .'/tmpfiles/jock_num.dat',$num);

    }



} 