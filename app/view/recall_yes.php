<div class="content">
    <p class="">
<?php
if (is_string($data['res'])) {
    print $data['res'];
} elseif (is_array($data['res'])) {
    print '<div class="mar"><a href="'.URLROOT.'/recall_yes/clear/" class="buttons" >Очистить журнал</a></div>';
    foreach ($data['res'] as $value) {
        $date = new DateTimeImmutable($value['date_time']);
        $data = $date->format('d.m.Y');
        $time = $date->format('H:i');
        print '<p class="text_left">'.$data.' '.$time.', имя: '.$value['name'].', номер: '.$value['phone_number'].', сообщение: '.$value['send'].'</p>';
    }
} else {
    print 'Журнал звонков пуст или произошла ошибка в чтении данных из базы.';
}
?>
    </p>
</div>
