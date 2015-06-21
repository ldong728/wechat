
<?php $token = $GLOBALS['token'] ?>

<br/>
<br/>
<div>
<?php echo 'http://'.$_SERVER[HTTP_HOST].'/wechat/index.php?id='.$_SESSION['weixinId']; ?><br/>
    <?php echo $token; ?>

</div>
<br/>
<br/>
<div>
    <a href="moduleConfig.php?modulemenu=1">模块选择与设置</a><br/><br/><br/>
    <a href="consle.php?menu=1">'自定义按钮</a><br/><br/><br/>
    <a href="/wechatwall/index.php?owner=<?php echo $_SESSION['weixinId']; ?>"> '微信墙</a><br/><br/><br/>
    <a href="consle.php?modultest=1">'测试</a><br/><br/><br/>
    <a href="../consle.php?getContact=1">'获取获奖者地址</a><br/><br/><br/>
    <a href="../consle.php?edit_gallery=1">'编辑照片墙</a><br/><br/><br/>
    <a href="../consle.php?delete_button=1">'删除自定义按钮</a><br/><br/><br/>
    <a href="../consle.php?logout=1">退出登录</a>
</div>


<div>
<!--    <a href="autoReply.php?auto_reply=1">自动回复设置</a>-->
</div>


<div>
<!--    <a href="consle.php?del_guess_tbl">清除歌曲竞猜结果</a>(一旦点击所有的结果将清空，慎点！)-->
</div>
