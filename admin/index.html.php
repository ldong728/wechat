<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>控制台</title>
</head>

<body>
<div>
    <a href="consle.php?upload=1&type=image">上传临时图片</a>
    <a href="consle.php?upload=1&type=voice">上传临时语音</a>
    <a href="consle.php?upload=1&type=video">上传临时视频</a>
    <a href="consle.php?upload=1&type=thumb">上传临时缩略图</a>
    <a href="consle.php?upload=1&type=12">上传错误图片测试</a>
</div>
<div>
    <a href="consle.php?kfManage=1">客服管理</a>
</div>

<br/>
<br/>
<div>
<?php echo 'http://'.$_SERVER[HTTP_HOST].'/wechat/index.php?id='.$_SESSION['weixinId']; ?><br/>
    <?php echo $token; ?>

</div>
<br/>
<br/>
<div>
    <a href="moduleConfig.php?modulemenu=1">模块选择与设置</a>
</div>

<div>
    <a href="consle.php?menu=1">自定义按钮</a>
</div>
<div>
    <a href="../wechatwall/index.php?owner=<?php echo $_SESSION['weixinId']; ?>"> 微信墙</a>
</div>
<div>
    <a href="consle.php?modultest=1">测试</a>

</div>
<div>
<!--    <a href="autoReply.php?auto_reply=1">自动回复设置</a>-->
</div>
<br/>
<br/>
<div><a href="consle.php?getContact=1">获取获奖者地址</a></div>

<div>
<!--    <a href="consle.php?del_guess_tbl">清除歌曲竞猜结果</a>(一旦点击所有的结果将清空，慎点！)-->
</div>

<div>
   <a href="consle.php?edit_gallery=1"> 编辑照片墙</a>
</div>

<br/>
<br/>
<div>
    <a href="consle.php?delete_button=1">删除自定义按钮</a>
</div>
<br/>
<br/>
<div>
    <a href="consle.php?create_button=1">建立自定义按钮</a>
</div>
<div>

</div>

<div>
    <a href="consle.php?logout=1">退出登录</a>
</div>



</body>

</html>