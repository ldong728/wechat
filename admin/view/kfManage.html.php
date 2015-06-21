
<?php foreach ($dataArray['kf_list'] as $row):?>
<p><?php
    foreach ($row as $k=>$v) {
        echo $k .': '.$v.'<br/>';
    }

    ?></p>


<?php endforeach ?>

