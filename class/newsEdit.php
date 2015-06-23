<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/6/23
 * Time: 12:11
 */
define('imgPath','../user_img');
define('maxSize',1000);

include_once $mypath . '/class/uploader.php';

class newsEdit {
    private $newsId;



    public function __construct($newsId){
        $this->newsId=$newsId;
        $this->exeContent();
        $this->uploadImg();

    }
    private function exeContent(){
        if(isset($_POST['newsEdit'])){
            $id=pdoInsert('news_tbl',array('md5'=>$this->newsId,'weixin_id'=>$_SESSION['weixinId'],'content'=>addslashes($_POST['newsEdit']),'update_time'=>time()));
        }
    }
    private function uploadImg(){
        if(isset($_POST['upfile'])||$_FILES['upfile']){
            header("Content-Type:text/html;charset=utf-8");
            error_reporting( E_ERROR | E_WARNING );
            $config = array(
                "savePath" => imgPath ,             //存储文件夹
                "maxSize" => maxSize,                   //允许的文件最大尺寸，单位KB
                "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )  //允许的文件格式
            );
            $up = new uploader($_SESSION['weixinId'],$this->newsId, "upfile" , $config );
            $type = $_REQUEST['type'];
            $callback=$_GET['callback'];

            $info = $up->getFileInfo();
            wxlog(json_encode($info));
            /**
             * 返回数据
             */
            if($callback) {
                echo '<script>'.$callback.'('.json_encode($info).')</script>';
            } else {
                echo json_encode($info);

            }


        }
    }
    public function addNews(){


    }
    public function delNewsImg(){

    }

}
