<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="utf-8"/>
<!--    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>-->
    <script src="../jquery.js"></script>
    <script>
        var FONT_SIZE = 20;
        var ICON_SIZE = 50;
        var TEXT_LEGNTH=10;
        var MARGINS=20; //文字与背景框的边距
        var backGround;
        var myCanvas;
        var myContext;
        var currentId = -1;
        var width;
        var height;
        $(document).ready(function () {
            initCanvas();
            backGround=new Image();
            backGround.src='resource/background.png';
                setInterval(reflashMsg, 3000);
        });
        $(window).resize(initCanvas);
        function resizeCanvas(){
            myCanvas.width=window.innerWidth;
            myCanvas.height=window.innerHeight;
            width=window.innerWidth;
            height=window.innerHeight;
            myContext = myCanvas.getContext('2d');
            myContext.fillStyle = "#000000";
            myContext.strokeStyle = "#000000";
            myContext.font = "bold "+FONT_SIZE+"px Arial";
            myContext.textAlign = "left";
            myContext.textBaseline = "middle";
        }
        function initCanvas() {
            myCanvas = $('#drawing').get(0);
            resizeCanvas();
        }

        function getNewMsg(value) {
            $.post('ajax.php', value, function (data) {    //json传输
                displayMsg(data);
            });
        }
        function displayMsg(msgList) {
            var list = eval(msgList);
            $.each(list, function (id, value) {
                var formedText=cutText(value.content);
                var bgwidth = (formedText.length>1? FONT_SIZE*TEXT_LEGNTH+MARGINS*2: formedText[0].length*FONT_SIZE+MARGINS*2);
                var bgheight = formedText.length*(FONT_SIZE+5)+MARGINS;
                var x = rnd(10, width-bgwidth-ICON_SIZE);
                var y = rnd(10, height-bgheight);
                if(currentId<value.id)currentId = value.id;
                var icon = new Image();
                icon.src = value.user_icon;

                myContext.drawImage(backGround,x+ICON_SIZE,y,bgwidth,bgheight);
                drawFormatedText(formedText,x+ICON_SIZE+15,y+FONT_SIZE);
                icon.onload = function () {
                    myContext.drawImage(icon, x, y, ICON_SIZE,ICON_SIZE);
                }
            });
        }
        function drawFormatedText(textArray,x,y){
            for(i in textArray){
                myContext.fillText(textArray[i],x,i*(FONT_SIZE+5)+y);
            }
        }
        function cutText(str){
            var length=str.length;
            var time=length/TEXT_LEGNTH;
            var textarray=new Array();
            if(time<1){
                textarray.push(str);
                return textarray;
            }
              else{
                for(var x=0;x<time;x++){
                    textarray.push(str.substring(x*TEXT_LEGNTH,(x+1)*TEXT_LEGNTH));
                }
            }
            return textarray;
        }
        function reflashMsg() {
            var par = {'msgNum': 10, 'currentId': currentId,'owner':'<?php echo $_GET['owner']?>'}
            getNewMsg(par);
        }
        function test(str) {
            var x = rnd(10, 1440);
            var y = rnd(10, 700);
            myContext.fillText(str, x, y);
        }
        function rnd(start, end) {
            return Math.floor(Math.random() * (end - start) + start);
        }
    </script>
</head>
<body>
<canvas id="drawing" width="1024" height="768">当你看到这行字的时候，说明你电脑的系统已经太老了，装个新系统吧，换个新浏览器也行</canvas>

<!--<canvas id="drawing" style=" height: 100%;width: 100%;margin: 0;padding: 0;">当你看到这行字的时候，说明你电脑的系统已经太老了，装个新系统吧，换个新浏览器也行</canvas>-->
<p>微信墙</p>

<div id="temp"></div>
</body>
</html>