

<div>
    <a href="?menuManage=1&menuInfo=1">获取菜单信息</a>
</div>

<div>
    <a href="?menuManage=1&menuCreate=1">创建菜单</a>
</div>

<div>
<!--    --><?php
//        if(isset($))
//    ?>

</div>
<div>
    <?php
        if(isset($GLOBALS['buttonInf'])){
            $inf=json_decode($GLOBALS['buttonInf'],true);
            echo '自定义菜单数据：';
            echo $GLOBALS['buttonInf'];
        }else{
        }

    ?>
</div>

