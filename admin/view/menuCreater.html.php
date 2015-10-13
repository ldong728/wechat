<script>



    $(document).ready(function () {
        $('#topButton1').append("添加按钮(jquery Work)");
        $('#topButton1').click(function(){
            $('#topButton1').empty();
            $('#topButton1').append('<input class="creatButton"type="text"id="buttonName"value="请输入按钮名称（8个英文或4个中文）">');

        });
        $('.creatButton').change(function(){
            $('#tempDiv').append("creat");

        })


    });


</script>

<div>
    <p class="button"id="topButton1"></p>
    <p class="button"id="topButton2"></p>
    <p class="button"id="topButton3"></p>
    <div id="tempDiv"></div>


</div>

<div>
    <form action="consle.php?menuManage=1"method="post">
    <textarea name="menuInfo"></textarea>
        <p class="button"><button>确定</button></p>

    </form>

</div>