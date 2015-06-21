<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>控制台</title>
</head>


<body>

<div>
    <form action="../consle.php" method="post"enctype="multipart/form-data">
        上传文件<input type="file"name="loadIn"/>
        <input type="hidden"name="type"value="<?php echo $_GET['type']?>"/>
        <input type="submit"value="确定"/>


    </form>
</div>



</body>
</html>