<?php
if (!empty($data['res'])) {
print '<div class="content"><p>'.$data['res'].'</p></div>';
include_once APPROOT.DS."view".DS."js_back.html";
} else {
    /*
    print '<pre>';
    print_r($data['serv']);
    print '</pre>';
    */
    if (!empty($data['serv'])) {
        print '<form method="post" action="" id="zapis_usluga_form" class="form_zapis_usluga">
                <div class="choice" id="services_choice">
                    <div class="zapis_usluga page_buttons">';
                        foreach ($data['serv'] as $page => $cat_arr) {
                            print '<button type="button" class="buttons zapis_usluga_buttons" id="'.translit_to_lat(sanitize($page)).'">'.$page.'</button>';
                        }
            print ' </div>
                    <div class="zapis_usluga zapis_usluga_spisok">';
                        foreach ($data['serv'] as $page => $cat_arr) {
                            print ' <div class="uslugi display_none" id="div'.translit_to_lat(sanitize($page)).'" >';
                                        foreach ($cat_arr as $cat_name => $serv_arr) {
                                            print '<div class="text_left ">';
                                            /*
                                            if ($cat_name !== 'page_serv') {
                                                foreach ($serv_arr as $serv_name => $serv_duration) {
                                                    $id = translit_to_lat(sanitize($serv_name))."plus".(int)$serv_duration;
                                                    print '<label class="custom-checkbox back">
                                                                <input type="checkbox" name="usluga[]" value="'.$page.'-'.$cat_name.'-'.$serv_name.'-'.$serv_duration.'" id="'.$id.'" />
                                                                <span>'.$cat_name.': '.$serv_name.'</span>
                                                            </label>';
                                                }
                                            } elseif ($cat_name == 'page_serv')  {
                                                foreach ($serv_arr as $serv_name => $serv_duration) {
                                                    $id = translit_to_lat(sanitize($serv_name))."plus".(int)$serv_duration;
                                                    $cat_name = "page_serv";
                                                    print '<label class="custom-checkbox back">
                                                                <input type="checkbox" name="usluga[]" value="'.$page.'-'.$cat_name.'-'.$serv_name.'-'.$serv_duration.'" id="'.$id.'" />
                                                                <span>'.$serv_name.'</span>
                                                            </label>';
                                                }
                                            }
                                            */
                                            if ($cat_name !== 'page_serv') {
                                                foreach ($serv_arr as $serv_name => $serv_duration) {
                                                    $id = translit_to_lat(sanitize($serv_name))."plus".$serv_duration;
                                                    list($price, $duration) = explode('-', $serv_duration);
                                                    print '<label class="custom-checkbox back">
                                                                <input type="radio" name="usluga" value="'.$page.'plus'.$cat_name.'plus'.$serv_name.'plus'.$serv_duration.'" id="'.$id.'" />
                                                                <span>'.$cat_name.': '.$serv_name . ', ' . $price . ' руб.</span>
                                                            </label>';
                                                }
                                            } elseif ($cat_name == 'page_serv')  {
                                                foreach ($serv_arr as $serv_name => $serv_duration) {
                                                    $id = translit_to_lat(sanitize($serv_name))."plus".(int)$serv_duration;
                                                    list($price, $duration) = explode('-', $serv_duration);
                                                    $cat_name = "page_serv";
                                                    print '<label class="custom-checkbox back">
                                                                <input type="radio" name="usluga" value="'.$page.'plus'.$cat_name.'plus'.$serv_name.'plus'.$serv_duration.'" id="'.$id.'" />
                                                                <span>'.$serv_name . ', ' . $price . ' руб.</span>
                                                            </label>';
                                                }
                                            }
                                            print '</div>';
                                        }
                            print ' </div>';
                        }
            print ' </div>
                </div>';

            print ' <div class="choice display_none" id="master_choice">
                        <h3 class="back shad rad pad margin_rlb1">Выберите специалиста</h3>
                        <div class="radio-group flex">';
                            foreach ($data['masters'] as $key => $master) {
                                echo '
                                <article class="main_section_article radio" data-value="' . $master['id'] . '">
                                    <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">
                                    <img src="' . $master['img'] . '" alt="Фото ' . $master['master_fam'] . '" class="main_section_article_imgdiv_img" />
                                    </div>

                                    <div class="main_section_article_content">
                                        <h3 id="master_name">' . $master['master_name'] . ' ' . $master['master_fam'] . '</h3>
                                        <span>
                                        ' . $master["spec"].'
                                        </span>
                                    </div>
                                </article>
                                ';
                            }
                            print '<input type="hidden" id="master" name="master" />
                        </div>
                    </div>';

            print '<div class="choice display_none margin_bottom_1rem" id="time_choice"></div>';

            print '<div class="choice display_none" id="give_a_phone"></div>';

        print '</form>';

        print ' <div class="choice display_none" id="zapis_end"></div>
                <div class="zapis_usluga margin_rlb1">
                    <button type="button" class="buttons" id="button_back" value="" disabled >Назад</button>
                    <button type="button" class="buttons" id="button_next" value="" disabled >Далее</button>
                </div>';
    } else {
        print '<div class="content"><p>Список услуг пуст.</p></div>';
    }
include_once APPROOT.DS."view".DS."back_home.html";
}
?>

<script type="text/javascript">
$(function() {
  //for page choice
  // Найти все узлы TD
  var page_buttons=$("#services_choice > .page_buttons > .zapis_usluga_buttons");
  // Добавить событие щелчка для всех TD
  page_buttons.click(function() {
    var button_id = $(this).prop('id');
    $('.uslugi').each(function (index, value){
    let page_id = $(this).prop('id');
    if (page_id == 'div'+button_id) {
      $("#div"+button_id).toggle();
    } else {
      $(this).hide();
    }
    });
  });

  //for reload page unchecked services_choice
  $('#services_choice input[type="checkbox"]').each(function(){
    if ( $(this).prop('checked') )
    {
      $(this).prop('checked', false);
    }
  });

  //if checked any service
  /*
  $('#services_choice input[type="checkbox"]').on('change', function(){
      if ( $('#services_choice input[type="checkbox"]:checked').length > 0 )
      {
        $('#button_next').val('master_next').prop('disabled', false);
      }
  });
*/
  $('#services_choice input[type="radio"]').on('change', function(){
      if ( $('#services_choice input[type="radio"]:checked').length > 0 )
      {
        $('#button_next').val('master_next').prop('disabled', false);
      }
  });

  $('#button_next').click(function(){
    //if ( $('#services_choice input:checkbox:checked').length > 0 && $(this).val() == 'master_next')
    if ( $('#services_choice input:radio:checked').length > 0 && $(this).val() == 'master_next')
    {
      $('#services_choice').hide();
      $('#master_choice').show();
      $(this).val('time_next');
      $('#button_back').val('services_choice').prop('disabled', false);
    }
    else if ( $('#master_choice #master').val() && $(this).val() == 'time_next' )
    {
      $('#master_choice').hide();
      $('#time_choice').show();
      $(this).val('phone_next');
      $('#button_back').val('master_choice');
      //console.log($('#master_choice #master').val());
      //SEND POST['master'] to pages/files/datetime-sql-query.php
      let master = $('#master_choice #master').val();
      $.ajax({
    		url: '<?php echo URLROOT; ?>/app/models/appoint_appointment.php',
            //url: '<?php //echo URLROOT; ?>/appoint/',
    		method: 'post',
    		dataType: 'html',
    		//data: 'master=' + master,
            data: {master: master},
/*
            complete : function(){  alert(this.url) },
            success: function(data){
            console.dir(data);
            },
            error: function (jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect. Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found (404).');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error (500).');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error. ' + jqXHR.responseText);
            }
            }
*/
            success: function(data){
                $('#time_choice').html('<h3 class="back shad rad pad margin_rlb1">Выберите дату и время</h3>'+data);
                //$("#t" + $(".dat:checked").val()).show();
                $("#t" + $(".dat:checked").prop('id')).show();
                //for time_choice
                $(".dat").change(function(){
                    $(".master_times").hide();
                    $("#t" + $(this).prop('id')).show();
                    $('.master_datetime input[name="time"]').each(function(){
                        if ($(this).attr('checked', true)) {
                        $(this).attr('checked', false);
                        }
                    });
                });
    		}
    	});
    }
    else if ( $('#time_choice input[name="time"]:checked').length && $(this).val() == 'phone_next' )
    {
      //alert($('.master_datetime input[name="time"]:checked').val());
      $('#time_choice').hide();
      $('#give_a_phone').show();
      $(this).val('end_next');
      $('#button_back').val('time_choice');
      let master = $('#master_choice #master').val();
      let date = $('#time_choice input[type="radio"][name="date"]:checked').val();
      let time = $('#time_choice input[type="radio"][name="time"]:checked').val();
      $.ajax({
    		url: '<?php echo URLROOT; ?>/app/models/appoint_phone.php',
    		method: 'post',
    		dataType: 'html',
    		data: {'master': master, 'date': date, 'time': time},
    		success: function(data){
    			$('#give_a_phone').html(data);
                $('body').find('.number').each(function(){
                        $(this).mask("+7 999 999 99 99",{autoclear: false});
                });
                /*
                $('#give_a_phone').append('\<script src\=\"js\/jquery-3\.6\.0\.min\.js\"\>\<\/script\>\
                                    \<script src\=\"js\/jquery\.maskedinput\.min\.js\"\>\<\/script\>\
                                    \<script src\=\"js\/form-recall-mask\.js\"\>\<\/script\>');
                //console.log(data);
                */
    		}
    	});
    }
    else if ( $('#give_a_phone input[name="zapis_phone_number"]').val() && $(this).val() == 'end_next' )
    {
      $('#give_a_phone').hide();
      $('#zapis_end').show();
      $('#button_back').val('give_a_phone');
      $(this).val('zapis_sql').html('Записаться!');
      //$('form#zapis_usluga_form').hide();

      let client_name = $('form#zapis_usluga_form input[name="zapis_name"]').val();
      $('#zapis_end').show().addClass('back shad rad pad margin_rlb1').html('<h3>'+client_name+' </h3>\
                                  <p id="zap_na">Вы записываетесь на:</p>\
                                  <div class="table_body" >\
                                  ');
      /*
      $('#services_choice input:checkbox:checked').each(function(){
        let serv_arr = $(this).val().split('-');
        if (serv_arr[1] != 'page_serv') {
          var cn = serv_arr[1]+': ';
        }else {
          cn = '';
        }
      $('#zapis_end').append('<div class="table_row">\
                                    '+serv_arr[0]+', '+cn+' '+serv_arr[2]+'\
                                </div>');
      });
      */
      let serv = $('#services_choice input:radio:checked').val().split('plus');
      if (serv[1] != 'page_serv') {
        var cn = serv[1]+': ';
      }else {
        cn = '';
      }
      let price = serv[3].split('-');
      $('#zapis_end').append('  <div class="table_row">\
                                    <div class="table_cell" style="text-align:right;">'+serv[0]+', '+cn.toLowerCase()+' '+serv[2].toLowerCase()+'</div>\
                                    <div class="table_cell">'+price[0]+' руб.</div>\
                                </div>');

      //let master_data = $('#master_choice #master').val().split('#');
      let master_data = $('#master_name').html();
      $('#zapis_end').append('  <div class="table_row">\
                                    <div class="table_cell" style="text-align:right;">Мастер: </div>\
                                    <div class="table_cell">'+master_data+'</div>\
                                </div>');

      let dayweek_date = $('#time_choice input[type="radio"][name="date"]:checked').val();
      let day_date_arr = dayweek_date.split(/\s{1}/);
      let date_0 = day_date_arr[1].split('-');
      let date = date_0[2]+'.'+date_0[1]+'.'+date_0[0]+', '+day_date_arr[0];
      let time = $('#time_choice input[type="radio"][name="time"]:checked').val();
      $('#zapis_end').append('<div class="table_row">\
                                <div class="table_cell" style="text-align:right;">Дата,<br /> время:</div>\
                                <div class="table_cell">'+date+'<br />'+time+'</div>\
                              </div>');
      let phone = $('#give_a_phone input[type="tel"]').val().replace(/ /g, '\u00a0');
      $('#zapis_end').append('<div class="table_row">\
                                <div class="table_cell" style="text-align:right;">Ваш номер:</div>\
                                <div class="table_cell">'+phone+' </div>\
                              </div>');

    }
    else if ( $(this).val() == 'zapis_sql' )
    {
      $.ajax({
    		url: 'pages/files/zapis_sql.php',
    		method: 'post',
    		dataType: 'html',
    		data: $('form#zapis_usluga_form').serialize(),
    		success: function(data){
          $('#zapis_end').html(data);
          $('#button_back, #button_next').hide();
          //console.log(data);
    		}
    	});
    }
    else
    {
      alert('Сделайте выбор, пожалуйста.');
    }
  });

  $('#button_back').click(function() {
    let choice_div_id = $(this).val();
    $('.choice').each(function(){
      if ( $(this).prop('id') == choice_div_id )
      {
        $('#'+choice_div_id).show();
        if ( $(this).prop('id') == 'services_choice') {
          $('#button_next').val('master_next');
          $('#button_back').prop('disabled', true);
        } else if ($(this).prop('id') == 'master_choice') {
          $('#button_next').val('time_next');
          $('#button_back').val('services_choice');
        }else if ($(this).prop('id') == 'time_choice') {
          $('#button_next').val('phone_next');
          $('#button_back').val('master_choice');
          let master = $('#master_choice #master').val();
          $.ajax({
            url: '<?php echo URLROOT; ?>/app/models/appoint_appointment.php',
    		method: 'post',
    		dataType: 'html',
            data: {master: master},
            success: function(data){
                $('#time_choice').html('<h3 class="back shad rad pad margin_rlb1">Выберите дату и время</h3>'+data);
                //$("#t" + $(".dat:checked").val()).show();
                $("#t" + $(".dat:checked").prop('id')).show();
                //for time_choice
                $(".dat").change(function(){
                    $(".master_times").hide();
                    $("#t" + $(this).prop('id')).show();
                    $('.master_datetime input[name="time"]').each(function(){
                        if ($(this).attr('checked', true)) {
                        $(this).attr('checked', false);
                        }
                    });
                });
    		}
    	});
        }else if ($(this).prop('id') == 'give_a_phone') {
          $('#button_back').val('time_choice');
          $('#button_next').val('end_next').html('Далее');
        } /* else if ($(this).prop('id') == 'zapis_end') {
          $('#button_back').val('give_a_phone');
          //$('#button_next').val('zapis_sql');
        } */
      } else {
        $(this).hide();
      }
    });
  });

//for master_choice
  $('.radio-group .radio').click(function(){
      $(this).parent().find('.radio').removeClass('selected');
      $(this).addClass('selected');
      var val = $(this).attr('data-value');
      $(this).parent().find('#master').val(val);
  });

  $('form#zapis_usluga_form').on('reset', function(){
    $('#zapis_usluga_form_go').prop('disabled', true);
    $('#res').prop('disabled', true);
    });

});
</script>
