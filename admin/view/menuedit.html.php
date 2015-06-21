<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>自定义菜单</title>
</head>

<body>
<?php foreach ($buttonInf as $button): ?>
    <p><?php echo $button['name'] ?></p>
    <?php
    if (count($button['sub_button']) > 0) {
        echo '<p>子菜单:</p>';
        foreach ($button['sub_button']['list'] as $subbutton) {
            foreach ($subbutton as $k => $v) {
                echo  '---- '.$k . ':  ' . $v . '</p>';
            }
        }
    }else{
        foreach ($button as $k=>$v) {
            echo $k . ':  ' . $v . '</p>';
        }
    }
    ?>
<?php endforeach ?>
</body>
</html>
