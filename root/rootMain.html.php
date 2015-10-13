<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="utf-8"/>
</head>
<body>
<?php
foreach ($query as $row) {
    $name=$row['name'];
    $weixinId=$row['weixin_id'];

    echo "<a href=\"index.php?userName=$name&weixinId=$weixinId\">$name</a><br/>";
}


?>

</body>