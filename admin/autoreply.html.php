<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>自动回复设置</title>
</head>

<body>
<form action="consle.php?auto_reply=1"method="post">


    关键字：<input type="text"name="key_word"/>
    <p><a href="consle.php?auto_reply=1">文本回复</a><a href="consle.php?auto_reply=1&reply_type=news">图文素材</a></p>
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
    <input type="submit"value="确定"/>
</form>


</body>
</html>