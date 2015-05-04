<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>照片墙</title>
</head>

<body>
<a href="index.php?page_up=1&page_offset=<?php echo $page_index;?>">上一页</a>
<a href="index.php?page_down=1&page_offset=<?php echo $page_index;?>">下一页</a>
<br/>
<?php foreach ($query as $row): ?>
<img src="<?php echo '../'.$row['file_path']; ?>"alt="image"/>

<?php endforeach ?>
<br/>

<a href="index.php?page_up=1&page_offset=<?php echo $page_index;?>">上一页</a>
<a href="index.php?page_down=1&page_offset=<?php echo $page_index;?>">下一页</a>

</body>
</html>