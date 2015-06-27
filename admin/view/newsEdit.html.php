<link href="../uedit/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="../uedit/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../uedit/umeditor.min.js"></script>
<script type="text/javascript" src="../uedit/lang/zh-cn/zh-cn.js"></script>
<form action="consle.php?newsedit=1"method="post"enctype="multipart/form-data">
    图文信息标题：<input type="text"name="title"value="请在这里输入标题"><br/>
    请上传一张图片作为封面<input type="file"name="titlePic"/><br/>
    <textarea name="description"cols="30"rows="5">请输入摘要</textarea>


    <script type="text/plain" id="myEditor"name="newsEdit" style="width:1000px;height:240px;">
    <p>请在这里编辑内容</p>
</script>
    <p class="button"><button>确定</button></p>
</form>
<script type="text/javascript">
    var um = UM.getEditor('myEditor');
</script>