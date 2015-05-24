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
    <label>appId：<input type="text"name="app_id"id="app_id"size="16"> </label><br/>
    <label>app secret：<input type="text"name="app_secret"id="app_secret"size="32"> </label><br/>
    <lavel>weixin Id：<input type="text"name="weixin_id"id="weixin_id"size="16">(公众号设置中，最下面注册信息里的“原始ID”)</lavel><br/>
    <label> </label>
    <input type="hidden"id="nameReady"name="nameReady"/>
    <input type="submit"id="submitButton" value="注册"/>
</form>

</body>