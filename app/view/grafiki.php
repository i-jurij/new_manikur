<script type="text/javascript">
$(function() {
  //submit master form
  $('input[type="radio"]').on('click',function(){
    $('form#grafiki-master').submit();
  });
});

function print() {
  var darkCSS = '<link rel=\"stylesheet\" type=\"text/css\" href=\"<?php echo URLROOT; ?>/public/css/first/dark.css\"/>';
  var lightCSS = '<link rel=\"stylesheet\" type=\"text/css\" href=\"<?php echo URLROOT; ?>/public/css/first/light.css\"/>';
    var printCSS = '<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/first/style.css" type="text/css" />';
    var printName = $('#result').html();
    var windowPrint = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0');
    windowPrint.document.write(darkCSS);
    windowPrint.document.write(lightCSS);
    windowPrint.document.write(printCSS);
    windowPrint.document.write(printName);
    windowPrint.document.close();
    windowPrint.focus();
    windowPrint.print();
    windowPrint.close();
}
</script>

 <div class="content">
    <p class="" id="p_pro">Показать / скрыть справку</p>
    <div class="display_none text_left margintb1" style="max-width:60rem;" id="pro">
        <p>Запланированные выходные дни или часы в графике отмечены цветом.</p>
        <p>Чтобы добавить <b>выходной день</b>:</p>
        <ul>
            <li>нажмите на дату.</li>
        </ul>
        <p>Чтобы добавить <b>отдельное время отдыха или перерыва:</b>:</p>
        <ul>
            <li>нажмите на ячейку на пересечении нужного дня и времени.</li>
        </ul>
        <p>Воскресенья отмечать не нужно, по умолчанию они отключены для записи клиентов (но это можно изменить, задав переменные для класса appointment).</p>
        <p>Нажмите кнопку Готово, чтобы сохранить изменения.</p>
    </div>
</div>
<div class="content" >
<?php
if (!empty($data['res'])) {
    print '<p>'.$data['res'].'</p>';
}
// 2 - view calendar after choice master
elseif (!empty($data['idd'])  ) {
    $idd = $data['idd'];
    $md = $data['md'];
    $year = $data['year'];
    $num_month = $data['num_month'];
    $date = $data['date'];
    $vyh = $data['vyh'];

    print '<div class="mod" id="result">
                    <br />'.$data['first_name'].' '.$data['sec_name'].' '.$data['last_name'].'
                    <a class="buttons zap_print" href="#null" onclick="print()"><img src="'.URLROOT.'/public/imgs/printer.png" alt="Печать"></a>
                    <a class="buttons zap_close" href="'.URLROOT.'/grafiki/">X</a>';
    include APPROOT.DS."lib".DS."grafiki_form_for_include.php";
    print '</div>';
    $idd = $data['idd'];
    $idd = $data['idd'];
    unset($idd, $md, $year, $num_month, $date, $vyh); $data = [];
}
// 2+ - смена месяцев в grafiki-grafiki-form.php
elseif (!empty($data['gidd']) ) {
    $idd = $data['gidd'];
    $md = $data['gmd'];
    $year = $data['gyear'];
    $num_month = $data['gnum_month'];
    $date = $data['gdate'];
    $vyh = $data['gvyh'];

    print '<div class="mod" id="result">
                    <br />'.$data['gfirst_name'].' '.$data['gsec_name'].' '.$data['glast_name'].'
                    <a class="buttons zap_print" href="#null" onclick="print()"><img src="'.URLROOT.'/public/imgs/printer.png" alt="Печать"></a>
                    <a class="buttons zap_close" href="'.URLROOT.'/grafiki/">X</a>';
    include APPROOT.DS."lib".DS."grafiki_form_for_include.php";
    print '</div>';
    unset($idd, $md, $year, $num_month, $date, $vyh); $data = [];
}
// 1 - master choice
else {
    print '<form action="'.URLROOT.'/grafiki/graf/" method="post" name="grafiki-master" id="grafiki-master" class="pad form_radio_btn">';
        foreach ($data['masters'] as $ress) {
            echo '
                  <label class="">
                  <input type="radio" name="master" id="'.$ress['id'].'" value="'.$ress['id'].'#'.$ress['master_name'].'$'.$ress['sec_name'].'$'.$ress['master_fam'].'#'.$ress['master_phone_number'].'" />
                   <span>'
                    .$ress['master_name'].' '.$ress['sec_name'].' '.$ress['master_fam'].'<br />'.$ress['master_phone_number'].
                  '</span>
                  </label>
                ';
        }
    print '</form>';
    $data = []; $ress = [];
    }
  ?>
</div>
