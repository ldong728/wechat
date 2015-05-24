<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="utf-8"/>
    <script src="../jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="../stylesheet/style.css">
    <script>
        $(document).ready(function () {

        });
    </script>
</head>
<body>
<h4>用户登录</h4>
<form action="index.php" method="post">
    <label>用户名<input type="text" name="user_name"/></label></br>
    <label>密码<input type="password" name="password"/></label></br>
    <input type="hidden" name="login" value="1"/>
    <input type="submit" value="登录"/>
    <a href="index.php?signup=1">注册新用户</a>
</form>
</body>