<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="utf-8"/>
</head>
<body>

<form action="index.php" method="post">
    <label>key1<input type="text" name="rootName"/></label></br>
    <label>key2<input type="password" name="rootPwd"/></label></br>
    <input type="hidden" name="login" value="1"/>
    <input type="submit" value="确定"/>
    <a href="index.php?signup=1">注册新用户</a>
</form>
</body>