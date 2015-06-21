<?php $menu = $GLOBALS["menu"]?>
<form action="?" method="post">
    <table id="module_table" border="1">
        <?php foreach ($menu as $row): ?>
            <tr>
                <td>
                    <?php
                    $checked = ($row['selected'] == 1 ? 'checked' : '');
                    if ($row['uni'] == 1) {
                        echo '<input type="radio" name="unimodule" value="' . $row['path'] . '" ' . $checked . '/>';
                    } else {
                        echo '<input type="checkbox"name="mulmodule[]"value="' . $row['path'] . '" ' . $checked . '/>';
                    }
                    ?>
                </td>
                <td>
                    <a href="?moduleInf=<?php echo urlencode($row['path'])?>"><?php echo $row['name'] ?></a>
                </td>
                <td>
                    <?php echo $row['inf'] ?>
                </td>
                <td>
                    <?php if($row['menu_inf']==null) echo '无自定义菜单' ?>
                </td>
                <td>
                    <a href="?moduleConfig=<?php echo urlencode($row['path'])?>">设定</a>
                </td>

            </tr>
        <?php endforeach ?>


    </table>
    <input type="hidden" name="moduleset"/>
    <p class="button"><button>确定</button></p>


</form>

