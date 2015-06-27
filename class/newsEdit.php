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
    private $description;
    private $titleImgUrl;




    public function __construct($newsId){
        $this->newsId=$newsId;
        $this->uploadImg();
        $this->exeContent();
    }
    private function exeContent(){
        $title='';
        $title_url='';
        $description='';
        if(isset($_POST['newsEdit'])){
            if(isset($_FILES['titlePic'])){
                $config = array(
                    "savePath" => imgPath ,             //存储文件夹
                    "maxSize" => maxSize,                   //允许的文件最大尺寸，单位KB
                    "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )  //允许的文件格式
                );
                $titleUp=new uploader($_SESSION['weixinId'],$this->newsId,'titlePic',$config);
                $inf=$titleUp->getFileInfo();
                $this->addImgToNews($this->newsId, $inf['md5']);
                $title_url=$inf['url'];
            }
            $title=$_POST['title'];
            $description=$_POST['description'];

            $id=pdoInsert('news_tbl',array('news_id'=>$this->newsId,'weixin_id'=>$_SESSION['weixinId'],'title'=>$title,'description'=>$description,
                'content'=>addslashes($_POST['newsEdit']),'pic_url'=>$title_url,'update_time'=>time()));
            $this->activeNewsImgLink($this->newsId);
            unset($_SESSION['temp_name']);


        }
    }

    /**
     * 删除没有用到的图片，更新数据库
     * @param $news_id
     */
    private function activeNewsImgLink($news_id){
        pdoUpdate('news_img_tbl',array('in_use'=>1),array('news_id'=>$news_id));
        $sql='delete from news_img_tbl where in_use=0 and weixin_id="'.$_SESSION['weixinId'].'"';
        exeNew($sql);
        $query=pdoQuery('img_tbl',array('url'),array('weixin_id'=>$_SESSION['weixinId']),' and md5 not in (select img_id from news_img_tbl)');
        $delList=$query->fetchAll();
        foreach ($delList as $row) {
            unlink($row['url']);
        }
        $sql='delete from img_tbl where weixin_id="'.$_SESSION['weixinId'].'"  and md5 not in (select img_id from news_img_tbl)';
        exeNew($sql);
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

                $up = new uploader($_SESSION['weixinId'], $this->newsId, 'upfile', $config);
//                $type = $_REQUEST['type'];
                $callback = $_GET['callback'];

                $info = $up->getFileInfo();
                $this->addImgToNews($this->newsId, $info['md5']);
                /**
                 * 返回数据
                 */
                if ($callback) {
                    echo '<script>' . $callback . '(' . json_encode($info) . ')</script>';
                } else {
                    echo json_encode($info);

                }

        }
    }
    /**
     * 链接图文信息与图片,存入‘news_img_tbl’
     * @param $news_id
     * @param $md5
     */
    private function addImgToNews($news_id,$md5){
        pdoInsert('news_img_tbl',array('weixin_id'=>$_SESSION['weixinId'],'news_id'=>$news_id,'img_id'=>$md5),'ignore');
    }

    public function addNews(){
        $this->uploadImg();
        $this->exeContent();
    }
//    public function clearNewsImg(){
//        $sql='delete from news_img_tbl where news_id not in (select news_id from news_tbl where weixin_id='.$_SESSION['weixinId'].')';
//        exeNew($sql);
//        $query=pdoQuery('img_tbl',array('url'),array('weixin_id'=>$_SESSION['weixinId']),' and news_id not in (select news_id from news_tbl where weixin_id='.$_SESSION['weixinId'].')');
//        $delList=$query->fetchAll();
////        $sql='delete from img_tbl where md5 not in (select md5 )'
//    }

}
