<div class="content">
    <p class="">
<?php
if (is_string($data['res'])) {
    print $data['res'];
} elseif (is_array($data['res'])) {
    print '<div class="mar"><a href="'.URLROOT.'/recall_yes/clear/" class="buttons" >Очистить журнал</a></div>';
    ?>
    <table class="table">
        <colgroup>
          <col width="5%">
          <col width="15%">
          <col width="15%">
          <col width="15%">
          <col width="50%">
        </colgroup>
        <thead>
          <tr>
            <th>№</th>
            <th>Дата, время</th>
            <th>Номер</th>
            <th>Имя</th>
            <th>Сообщение</th>
          </tr>
        </thead>
        <tbody>
       <?php
       $i = 1;
        foreach ($data['res'] as $value) {
            $date = new DateTimeImmutable($value['date_time']);
            $data = $date->format('d.m.Y');
            $time = $date->format('H:i');
            ?>
            <tr>
            <td><?php echo $i; ?></td>
            <td style="text-align:left"><?php echo $data.' '.$time; ?></td>
            <td style="text-align:left; white-space: nowrap;"><?php echo $value['phone_number']; ?></td>
            <td style="text-align:left"><?php echo $value['name']; ?></td>
            <td style="text-align:left"><?php echo $value['send']; ?></td>
            </tr>
            <?php
            $i++;
        }
        ?>
      </tbody>
     </table>
     <?php
} else {
    print 'Журнал звонков пуст или произошла ошибка в чтении данных из базы.';
}
?>
    </p>
</div>
