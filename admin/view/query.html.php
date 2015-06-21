
<?php $i = 1;
foreach ($query as $row): ?>
    <p><?php echo '第' . $i . '名: ' . $row['contact'];
        $i++ ?>  </p>


<?php endforeach ?>

