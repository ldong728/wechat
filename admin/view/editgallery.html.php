<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>照片墙设定</title>
</head>
<body>
<?php foreach ($query as $row):?>
    <div>
        <img src="../<?php echo $row['file_path']?>"alt="<?php echo $row['file_path']?>"width="50"height="50"/>
        <a href="../consle.php?delete_image=1&file_path=<?php echo $row['media_id']?>">删除</a>
    </div>

<?php endforeach?>

</body>
</html>