<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>客服管理</title>
</head>

<body>
<?php foreach ($dataArray['kf_list'] as $row):?>
<p><?php
    foreach ($row as $k=>$v) {
        echo $k .': '.$v.'<br/>';
    }

    ?></p>


<?php endforeach ?>


</body>
</html>