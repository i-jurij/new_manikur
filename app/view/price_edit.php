<div class="content">
<?php
if (!empty($data['res'])) {
    print '<p>'.$data['res'].'</p>';
} elseif (!empty($data['serv'])) {
    print ' <div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
                <p class="">В строке нужной услуги кликните по ячейке в колонке с ценой, введите данные, нажмите кнопку Сохранить.</p>
                <div class="price">
                    <form action="'.URLROOT.'/price_edit/change/" method="post" name="price_form" id="price_form" >
                        <table class="table price_form_table">
                            <caption class=""><b>'.$data['page_title'].'</b></caption>
                            <colgroup>
                                <col width="10%">
                                <col width="65%">
                                <col width="25%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>Услуга</th>
                                <th>Цена</th>
                            </tr>
                            </thead>
                            <tbody>';
                            $i = 1;
                            foreach ($data['serv'] as $cat_name => $serv_arr) {
                                if ($cat_name != 'page_serv') {
                                    print '<tr><td colspan="3">'.$cat_name.'</td></tr>';
                                    foreach ($serv_arr as $serv_name => $cat_serv_price) {
                                        $ar = explode('#', $cat_serv_price);
                                        $price = $ar[1];
                                        $id = $ar[0];
                                        print ' <tr>
                                                    <td>'.$i.'</td>
                                                    <td style="text-align:left">'.$serv_name.'</td>
                                                    <td class="td" id="serv_id['.$id.']">'.$price.'</td>
                                                </tr>';
                                        $i++;
                                    }

                                } else {
                                    print '<td colspan="3">Услуги вне категорий</td>';
                                    foreach ($serv_arr as $servv_name => $serv_price) {
                                        $sar = explode('#', $serv_price);
                                        $sprice = $sar[1];
                                        $sid = $sar[0];
                                        print ' <tr>
                                                    <td>'.$i.'</td>
                                                    <td style="text-align:left">'.$servv_name .'</td>
                                                    <td class="td" id="serv_id['.$sid.']">'.$sprice.'</td>
                                                </tr>';
                                    $i++;
                                     }
                                }
                            }
        print '             </tbody>
                        </table>
                        <div class="margintb1" id="form_buttons" >
                            <button type="submit" name="submit" class="buttons" form="price_form" />Далее</button>
                            <input type="reset" class="buttons" form="price_form" value="Сбросить" />
                        </div>
                    </form>
                </div>
            </div>';
} else {
    print ' <form action="'.URLROOT.'/price_edit/edit/" method="post" id="form_price_edit" >
                <div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
                    <p class="">Выберите страницу для редактирования расценок:</p>';
    foreach ($data['service_page'] as $value)
    {
        echo '      <label>
                        <input type="radio" name="page" value="' . $value['page_id'] . '#'. $value['page_title'] . '" required />
                        <span>' . $value['page_title'] . '</span>
                    </label>';
    }
    print '     </div>
                <div class="margintb1" id="form_price_edit_buttons" >
                    <button type="submit" name="submit" class="buttons" form="form_price_edit" />Далее</button>
                    <input type="reset" class="buttons" form="form_price_edit" value="Сбросить" />
                </div>
            </form>';
}
?>
</div>

<script>
$ (function () {// эквивалентна вкладке тела на странице плюс событие onload
                // Найти все узлы TD
        var tds=$(".price_form_table .td");
                // Добавить событие щелчка для всех TD
        tds.click(function(){
                        // Получить объект текущего клика
            var td=$(this);
                        // Удалите текущий текстовый контент TD
           var oldText=td.text();
           var idstr=td.attr('id');
                      // Создать текстовое поле, установите значение текстового поля сохранено значение
           var input=$('<input type="number" name="'+idstr+'" min="0" step="10" style="width:100%;" value="'+oldText+'" />');
                      // Установите содержимое текущего объекта TD для ввода
           td.html(input);
                      // Установите флажок Click события текстового поля
           input.click(function(){
               return false;
           });
                      // Установите стиль текстового поля
           input.css("border-width","0");
           //input.css("font-size","1rem");
           input.css("text-align","center");
                      // Установите ширину текстового поля, равная ширине TD
           input.width(td.width());
                      // Запустите полное событие выбора, когда текстовое поле получает фокус
           input.trigger("focus").trigger("select");
                      // вернуться к тексту, когда текстовое поле потеряло фокус
           input.blur(function(){
               var input_blur=$(this);
           });
        });

        // удаление полей ввода при нажатии кнопки сброса
        $("#price_form").on('reset', function(){
            let td = $('.td');
            td.each( function() {
                let inp = $(this).find('input');
                if ( inp.val() !== '' ) {
                    let price = inp.val();
                    inp.remove();
                    $(this).html(price);
                    //console.log(price+'\n');
                }
            });
        });

   });

</script>
