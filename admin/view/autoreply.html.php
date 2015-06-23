<?php $query=$GLOBALS["query"] ?>

<form action="autoReply.php?auto_reply=1"method="post">


    关键字：<input type="text"name="key_word"/>
    <p><a href="autoReply.php?auto_reply=1">文本回复</a><a href="autoReply.php?auto_reply=1&reply_type=news">图文素材</a></p>
    <div>
        <?php
            if(isset($mediaList)){
                echo '<input type="hidden"name="type"value="'.$_GET['reply_type'].'"/>';
                foreach ($allList as $row) {
                    $rowdata=json_decode($row,true);
                    echo'<p>'. $rowdata['content']['news_item'][0]['title'];
                    echo '<input type="radio"name="content"value="'.$rowdata['media_id'].'"/></>';
                }

            }else{
                echo'<input type="hidden"name="type"value="text"/>
                    <textarea name="content"cols="60"rows="10"></textarea>';
            }
        ?>
    </div>
    <p class="button"><button>确定</button></p>
    <br/>
</form>
<div>
    <a href="autoReply.php?getDefultReply=1">获取原始自动回复设置</a>
</div>
<table border="1">
    <tr>
        <td>关键词（“.”为默认回复）</td>
        <td>回复内容</td>
        <td>功能</td>
    </tr>
    <?php foreach ($query as $row):?>
    <tr>
        <td><?php echo $row['key_word']?></td>
        <td><?php echo $row['content']?></td>
        <td><a href="autoReply.php?auto_reply=1&deleteAutoReply=<?php echo $row['id']?>">删除</a></td>


    </tr>

    <?php endforeach?>



</table>
