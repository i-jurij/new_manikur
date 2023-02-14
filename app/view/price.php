<div class="content"><p>Стоимость услуг в нерабочее время или дни, а также при выезде - договорная.</p></div>

<div class=" margin_rlb1">
<?php
foreach ($data['serv'] as $page => $cat_arr) {
    $i = 1;
    ?>
    <div class="back shad rad pad margin_rlb1 price" id="<?php echo $page; ?>">
        <table class="table">
            <caption class=""><h2><?php echo $page; ?></h2></caption>
            <colgroup>
            <col width="10%">
            <col width="65%">
            <col width="25%">
            </colgroup>
            <thead>
            <tr>
                <th>№</th>
                <th>Услуга</th>
                <th>Цена, руб.</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($cat_arr as $cat => $serv)
            {
                if ($cat !== 'page_serv') {
                    ?>
                    <tr><td colspan="3"><h3><?php echo $cat; ?></h3></td></tr>
                    <?php
                    foreach ($serv as $name => $value) {
                        ?>
                        <tr>
                        <td><?php echo $i; ?></td>
                        <td style="text-align:left"><?php echo $name; ?></td>
                        <td><?php echo $value; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                } else {
                    ?>
                    <tr><td colspan="3"><h3>Другие услуги</h3></td></tr>
                    <?php
                    foreach ($serv as $name => $value) {
                        ?>
                        <tr>
                        <td><?php echo $i; ?></td>
                        <td style="text-align:left"><?php echo $name; ?></td>
                        <td><?php echo $value; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
            }
            ?>
        </tbody>
        </table>
    </div>
<?php
}
?>
</div>
