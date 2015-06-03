<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>自动回复设置</title>
</head>

<body>
<?php $i = 1;
foreach ($query as $row): ?>
    <p><?php echo '第' . $i . '名: ' . $row['contact'];
        $i++ ?>  </p>


<?php endforeach ?>


</body>
</html>