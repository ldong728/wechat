<?php $config=$GLOBALS["config"];
$configInf=$GLOBALS["configInf"];
$inputType=$GLOBALS["inputType"]
?>
<form action="?moduleConfig=<?php echo $_GET['moduleConfig']?>&updateConfig=1"method="post">
<?php
$i=0;
foreach ( $config as $k=>$v) {
//        $v=urlencode($v);
        echo "<p>$configInf[$i]:<input type=\"$inputType[$i]\"name=\"$k\"value=\"$v\"/>";
        $i++;
    }


?>
    <br/><p class="button"><button>确认修改</button></p>
</form>
