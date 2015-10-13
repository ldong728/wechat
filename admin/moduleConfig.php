<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/6/11
 * Time: 9:57
 */
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath . '/contrller/serveManager.php';
session_start();
if (isset($_SESSION['login']) && $_SESSION['login']) {
    if (isset($_GET['modulemenu'])) {
//        echo "ok!!";
//        exit;
        $query = pdoQuery('duty_tbl', array('duty'), array('weixin_id' => $_SESSION['weixinId']), ' limit 1');
        $row = $query->fetch();
        $duty = json_decode($row['duty'], true);
        $query = pdoQuery('module_tbl', null, array('weixin_id' => $_SESSION['weixinId']), ' or private=0 limit 20');
        foreach ($query as $row) {
            $menu[] = array('name' => $row['name'], 'inf' => $row['inf'], 'path' => $row['path'], 'menu_inf' => $row['menu_inf'],
                'price' => $row['price'], 'uni' => $row['uni'], 'selected' => (array_search($row['path'], $duty['dutyContent']) > -1 ? 1 : 0));
        }
        printView('/admin/view/moduleselect.html.php','模式选择');
        exit;

    }


    if (isset($_POST['moduleset'])) {
        $setSql = 'insert ignore module_config_tbl set weixin_id="' . $_SESSION['weixinId'] . '",module_path=:path,config=(select default_config from module_tbl where path=:path)';
        $configExe = $pdo->prepare($setSql);

        if (isset($_POST['mulmodule'])) {
            for ($i = 0; $i < count($_POST['mulmodule']); $i++) {
                $modules[] = $_POST['mulmodule'][$i];
                $configExe->bindValue(':path', $_POST['mulmodule'][$i]);
                $configExe->execute();
            }
        }
        if (isset($_POST['unimodule'])) {
            $modules[] = $_POST['unimodule'];
            $configExe->bindValue(':path', $_POST['unimodule']);
            $configExe->execute();
            $menuInf = pdoQuery('module_tbl', array('menu_inf'), array('path' => $_POST['unimodule']), ' limit 1');
            $menu = $menuInf->fetch();
            if ($menu['menu_inf'] != null) {
                deleteButton();
                creatButton($menu['menu_inf']);
            }
        }

        $prejson = array('dutyContent' => $modules);
        $json = json_encode($prejson);
        $json = addslashes($json);
        $sql = 'update duty_tbl set duty="' . $json . '" where weixin_id="' . $_SESSION['weixinId'] . '"';
//        echo $sql;
        exeNew($sql);
        header('location: index.php');
        exit;

    }
    if (isset($_GET['moduleInf'])) {
        switch ($_GET['moduleInf']) {
            case 'module/defaultModule.php': {
                header('location: autoReply.php?auto_reply=1');
                break;
            }
            case 'module/wechatWall.php':{
                header('location: ../wechatwall?owner='.$_SESSION['weixinId']);
                                             break;
            }


        }
        exit;

    }

    if (isset($_GET['moduleConfig'])) {

        $where = array('module_path' => urldecode($_GET['moduleConfig']), 'weixin_id' => $_SESSION['weixinId']);//用户模块参数在表中的位置
        $query = pdoQuery('module_config_tbl', null, $where, 'limit 1');//获取用户模块参数
        if ($row = $query->fetch()) {
//            echo $row['config'];
            $configList = json_decode($row['config'], true);//获取config列的json数据，并转换为数组，
//            printInf($configList);
            //以下为设置中有多选框的处理方法
            if (isset($_GET['updateConfig'])) {//更改设置
                $unchecked=array_diff_key($configList['config'],$_POST);//比较递交的config数组和数据库中的config数组，返回差异数组
                if(count($unchecked)>0){//如果差异数组大于0，说明递交的config数组中有未选中的选项
                    foreach ($unchecked as $k=>$v) {
                        $unchecked[$k]=0;//遍历差异数组，将所有值改为0，以表示没有选中
                    }

                }
//                $configList['config']=array_intersect_key($configList['config'],$_POST);
                $configList['config']=array_merge($configList['config'],$_POST,$unchecked);//融合数组，并用差异数组中的值覆盖前者
                                    $jsonData = addslashes(json_encode($configList,JSON_UNESCAPED_UNICODE));//添加转义符，以便保存入mysql数据库
                    pdoUpdate('module_config_tbl', array('config' => $jsonData), $where);

//                foreach ($_POST as $k => $v) {
//                    $configList['config'][$k] = $v;
//                    $jsonData = addslashes(json_encode($configList));
//                    pdoUpdate('module_config_tbl', array('config' => $jsonData), $where);
//                }

                header('location: ../');
                exit;
            }
            $configInf = $configList['configInf'];
            $inputType = $configList['inputType'];
            $config = $configList['config'];
            printView('/admin/view/moduleconfig.html.php','模块设置');
            exit;
        } else {
            echo " no config info";
            exit;
        }

    }


}