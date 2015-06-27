<?php $config=$GLOBALS["config"];
$configInf=$GLOBALS["configInf"];
$inputType=$GLOBALS["inputType"]
?>
<form action="?moduleConfig=<?php echo $_GET['moduleConfig']?>&updateConfig=1"method="post">
<?php
$i=0;
foreach ( $config as $k=>$v) {
        $extra = '';
        $value=$v;
        if($inputType[$i]=='checkbox'){//如果输入方式为多选框，则根据值来判定是否选中
            $value=1;
            $extra=($v==1?'checked=1':'');
        }
        $value=($inputType[$i]=='checkbox'? 1:$v);
        echo "<p>$configInf[$i]:<input type=\"$inputType[$i]\"name=\"$k\"value=\"$value\"$extra/>";
        $i++;
    }


?>
    <br/><p class="button"><button>确认修改</button></p>
</form>
