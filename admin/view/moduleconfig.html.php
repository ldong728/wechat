<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>模块设置</title>
</head>

<body>
<form action="?moduleConfig=<?php echo $_GET['moduleConfig']?>&updateConfig=1"method="post">
<?php
$i=0;
foreach ( $config as $k=>$v) {
//        $v=urlencode($v);
        echo "<p>$configInf[$i]:<input type=\"$inputType[$i]\"name=\"$k\"value=\"$v\"/>";
        $i++;
    }


?>
    <input type="submit"value="确认修改"/>
</form>
</body>
</html>