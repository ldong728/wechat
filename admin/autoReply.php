<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/6/3
 * Time: 22:17
 */

$mypath = $_SERVER['DOCUMENT_ROOT'] . '/wechat';
include_once $mypath . '/includes/magicquotes.inc.php';
include_once $mypath . '/includes/db.inc.php';
include_once $mypath . '/includes/helpers.inc.php';
include_once $mypath . '/contrller/serveManager.php';
session_start();
if (isset($_SESSION['login']) && $_SESSION['login']) {
    if (isset($_GET['auto_reply'])) {
        if (isset($_GET['reply_type'])) {
            $mediaList = getMediaList($_GET['reply_type'], 0);
            foreach ($mediaList['item'] as $row) {
                $allList[] = json_encode($row, JSON_UNESCAPED_UNICODE);
            }

        }
        if (isset($_POST['content'])) {
            $_POST['key_word'] = trim($_POST['key_word']);
            $key = ($_POST['key_word'] == '' ? '.' : preg_replace('/,|，/', '\|', $_POST['key_word']));
            $content = addslashes($_POST['content']);
            switch ($_POST['type']) {
                case 'news': {
                    $postjsondata = json_encode(array('media_id' => $_POST['content']));
                    $content = getMedia($postjsondata);
                    $content = addslashes($content);
                    break;
                }

            }
            pdoInsert('default_reply_tbl', array('weixin_id' => $_SESSION['weixinId'], 'reply_type' => $_POST['type'],
                'key_word' => $key, 'content' => $content), ' ON DUPLICATE KEY UPDATE content="' . $content . '"');
            header('location: ?auto_reply=1');

        }
        if (isset($_GET['deleteAutoReply'])) {
            $sql = 'delete from default_reply_tbl where weixin_id="' . $_SESSION['weixinId'] . '" and id=' . $_GET['deleteAutoReply'];
            $pdo->exec($sql);
            header('location: ?auto_reply=1');

        }

        $query = pdoQuery('default_reply_tbl', null, array('weixin_id' => $_SESSION['weixinId']), null);
        printView('/admin/view/autoreply.html.php','自动回复设置');
    }
    if(isset($_GET['getDefultReply'])){
        reflashAutoReply();
        header('location: ?auto_reply=1');
    }
}