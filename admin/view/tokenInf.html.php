<?php $token=$GLOBALS['token'] ?>
<br/>
<br/>
<div>
    <span>URL(服务器地址):</span><?php echo 'http://'.$_SERVER[HTTP_HOST].'/wechat/index.php?id='.$_SESSION['weixinId']; ?><br/>
    <span>Token(令牌):</span><?php echo $token; ?>

</div>