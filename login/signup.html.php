<!DOCTYPE html>
<html lang = "cn">
<head>
    <meta charset = "utf-8"/>
    <script src="../jquery.js"></script>
    <script src="signUpScript.js"></script>
    <link rel="stylesheet" type="text/css" href="../stylesheet/style.css">

</head>

<body>


<h2 class="topbar">新用户注册</h2>
<form action="index.php" method="post">
    <label>请输入用户名：<input type="text" name="name"id="name"/><span id="usernameInf"></span></label></br>
    <label>请输入密码：<input type="password"name="password"id="password1"/></label></br>
    <label>请再次输入密码：<input type="password"id="password2"name="password2"/><span id="passwordInf"></span></label></br>
    <input type="hidden"id="nameReady"name="nameReady"/>
    <input type="submit"id="submitButton" value="注册"/>
</form>

</body>