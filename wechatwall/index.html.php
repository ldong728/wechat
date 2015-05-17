<!DOCTYPE html>
<html lang = "cn">
<head>
    <meta charset = "utf-8"/>
    <!--<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>-->
    <script src="../jquery.js"></script>
    <script>
        var myCanvas;
        var myContext;
//        var testCount=0;
//        var lineType=0;
//        var lastPoint;
//        var mousePressed;
//        var points = Array();
//        var lines = Array();
//        //        var drawActions = Array();
//        var chars = Array();
//        var currentX=0;
//        var currentY=0;
        $(document).ready(function(){
            initCanvas();
            setInterval(reflashMsg,3000);
//            setInterval(test,3);
        });
        function initCanvas(){
            myCanvas=$('#drawing').get(0);
            myContext=myCanvas.getContext('2d');
            myContext.fillStyle="#000000";
            myContext.strokeStyle="#000000";
            myContext.font = "bold 20px Arial";
            myContext.textAlign = "left";
            myContext.textBaseline = "middle";
        }

        function getNewMsg(value){
//            var ajaxData;
            $.post('ajax.php',value,function(data){    //json传输
                displayMsg(data);


            });
//            return ajaxData;
        }
        function displayMsg(msgList){
//            test(msgList);
            var list = eval(msgList);
            $.each(list,function(id,value){
               var icon=new Image();
                icon.src=value['user_icon'];
                var x=rnd(10,1000);
                var y=rnd(10,800);
                myContext.drawImage(icon,x,y,50,50);
                myContext.fillText(value.content,x+55,y+25);

            });
        }
        function reflashMsg(){
            var par = {'msgNum':10,'stopTime':3}
            var data = getNewMsg(par);
//            test('json:');
//            displayMsg(data);
        }
        function test(str){
            var x=rnd(10,1440);
            var y=rnd(10,700);
            myContext.fillText(str,x,y);
        }
        function rnd(start, end){
            return Math.floor(Math.random() * (end - start) + start);
        }
    </script>
</head>
<body>

<canvas id="drawing" width="1440" height="700">当你看到这行字的时候，说明你电脑的系统已经太老了，装个新系统吧，换个新浏览器也行</canvas>
<p>微信墙</p>
<div id="temp"></div>
</body>
</html>