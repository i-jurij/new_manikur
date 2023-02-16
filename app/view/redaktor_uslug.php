<div class="content">
    <form action="<?php echo URLROOT.'/redaktor_uslug/go/'; ?>" method="post" enctype="multipart/form-data" class="" id="change_page_form">
<?php
if (!empty($data['res'])) {
    print '<p>'; print_r($data['res']); print '</p>';
    $dn = 'display_none';
} elseif (!empty($data['page_id'])) {
        print '<p class=""><b>Страница "'.$data['page_title'].'":</b></p>';
    if ($data['action'] === 'cats_add') {
        print ' <div class="">
                    <div class="" id="cats_add">
                        <input type="hidden" name="page_id" value="'.$data['page_id'].'#'.$data['page_title'].'" />
                        <p class="">Выберите изображение, название и описание категории, нажмите Далее</p>
                        <div class="about_form back shad rad pad mar display_inline_block" id="cats0">
                            <label class="input-file">
                                <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                                <input type="file" id="fcats0" name="cats_img[]" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" required />
                                <span >Изображение категории весом до 3Мб</span>
                                <p id="fileSizefcats0" ></p>
                            </label>
                            <label ><p>Введите название категории (до 100 символов)</p>
                                <p>
                                <input type="text" name="cats_name[]" placeholder="Название категории" maxlength="100" required />
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="mar " id="">
                        <button class="buttons add" type="button" value="cats" onclick="add(this);">Добавить еще</button>
                    </div>
                </div>';
    } elseif ($data['action'] === 'serv_add') {
        print ' <p><small> За один раз можно добавить услуги только для одной категории или услуги вне категорий для страницы.<br />
                        Для добавления услуг в категории - нажмите "Показать категории", выберите категорию, далее добавляйте услуги.<br />
                        Для добавления услуг вне категорий - сразу вводите данные для услуг.<br />
                </small></p>';
        print '<div class=""><p class="buttons" id="cats_view">Показать категории</p>
                    <p class="display_none" id="cats_list">';
                        if (!empty($data['page_cats'])) {
                            foreach ($data['page_cats'] as $cat) //foreach cat ids from table
                            {
                                echo '  <label class="display_inline_block buttons ">
                                            <input type="radio" name="cat_id" value="'.$cat['id'].'#'.$cat['category_name'].'">
                                            <span>'.$cat['category_name'].'</span>
                                        </label>';
                            }
                        } else {
                            print 'Список категорий пуст.<br />';
                        }
        print '     </p>
                </div>';
        print ' <div class="">
                    <div class="" id="serv_add">
                        <input type="hidden" name="page_id" value="'.$data['page_id'].'#'.$data['page_title'].'" />
                        <p class="">Выберите изображение, название и описание услуги, нажмите Далее</p>
                        <div class="about_form back shad rad pad mar display_inline_block" id="serv0">
                            <label class="input-file">
                                <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                                <input type="file" id="fserv0" name="serv_img[]" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" />
                                <span >Изображение услуги весом до 3Мб</span>
                                <p id="fileSizefserv0" ></p>
                            </label>
                            <label ><p>Введите название услуги (до 100 символов)</p>
                                <p>
                                <input type="text" name="serv_name[]" placeholder="Название услуги" maxlength="100" required />
                                </p>
                            </label>
                            <label class="textarea"><p>Описание услуги (до 500 символов)</p>
                                <p>
                                <textarea name="serv_desc[]" placeholder="Описание услуги" maxlength="500"></textarea>
                                </p>
                            </label>
                            <label ><p>Прайс (цифры, до 10 символов)</p>
                                <p>
                                <input type="number" name="price[]" placeholder="10000" min="0" max="1000000000" step="0.1" title="Только цифры" required />
                                </p>
                            </label>
                            <label ><p>Длительность в минутах (2-3 цифры)</p>
                                <p>
                                    <input type="number" name="duration[]" placeholder="60" min="10" max="480" step="5" title="Только цифры" required />
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="mar " id="">
                            <button class="buttons add" type="button" value="serv" onclick="add(this);">Добавить еще</button>
                    </div>
                </div>    ';
    } elseif ($data['action'] === 'cats_del') {
        print '<div class="">
                <p class="">Выберите категории для удаления, нажмите Далее</p>
                <input type="hidden" name="page_id" value="'.$data['page_id'].'" />';

                if (!empty($data['page_cats'])) {
                    foreach ($data['page_cats'] as $cat) //foreach cat ids from table
                    {
                        echo '  <label class="checkbox-btn">
                                    <input type="checkbox" name="cat_del[]" value="'.$cat['id'].'#'.$cat['category_img'].'#'.$cat['category_name'].'">
                                    <span>'.$cat['category_name'].'</span>
                                </label>';
                    }
                } else {
                    print 'Список категорий пуст.<br />';
                }
        print '</div>';

    } elseif ($data['action'] === 'serv_del') {
        print ' <div class="">
                    <p class="mb2"><b>Услуги в категориях:</b></p>
                    <div class="" style="align-items:stretch;" id="cat_serv_del_p">';
                    if (!empty($data['page_cats'])) {
                        foreach ($data['page_cats'] as $value) {
                            print '<div class="mb2">
                                    <p class="">Категория <b>"'.$value['category_name'].'"</b>:</p>';
                            if (!empty($data['page_cats_serv'])) {
                                $cs = '';
                                foreach ($data['page_cats_serv'] as $serv) {
                                    if ($serv['category_id'] === $value['id']) {
                                        $cs .= '  <label class="display_inline_block margin_bottom_1rem shad rad pad">
                                                    <input type="checkbox" name="serv_del[]" value="'.$serv['id'].'#'.$serv['service_name'].'#'.$serv['page_id'].'#'.$serv['category_id'].'">
                                                    <span>'.$serv['service_name'].'</span>
                                                </label>';
                                    }
                                }
                                if (!empty($cs)) {
                                    print $cs;
                                } else {
                                    print 'Список услуг пуст.<br />';
                                }
                            } else {
                                print 'Список услуг пуст.<br />';
                            }
                            print '</div>';
                        }
                    } else {
                        print 'Список категорий пуст.<br />';
                    }
        print '     </div>
                </div>
                <div class="mb2">
                    <p class=""><b>Услуги вне категорий:</b></p>
                    <div class="" id="page_serv_del_p">';
                    if (!empty($data['page_serv'])) {
                        foreach ($data['page_serv'] as $pserv) {
                            echo '  <label class="display_inline_block margin_bottom_1rem shad rad pad">
                                        <input type="checkbox" name="serv_del[]" value="'.$pserv['id'].'#'.$pserv['service_name'].'#'.$pserv['page_id'].'#'.$pserv['service_img'].'">
                                        <span>'.$pserv['service_name'].'</span>
                                    </label>';
                        }
                    } else {
                        print 'Список услуг пуст.<br />';
                    }
        print '     </div>
                </div>';

    }
} else {
    //вывод списка страниц
    if (isset($data['service_page'])) {
        print '<div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
                    <p class="">Выберите страницу для редактирования:</p>';
        foreach ($data['service_page'] as $value)
        {
            echo '  <label>
                        <input type="radio" name="page_for_edit" value="' . $value['page_id'] . '#'. $value['page_title'] . '" required />
                        <span>' . $value['page_title'] . '</span>
                    </label>';
        }
        print '</div>';
    }
    print ' <div class="form_radio_btn margin_bottom_1rem">
                <p>Выберите действие:</p>
                <label>
                    <input type="radio" name="action" value="cats_add" required/>
                    <span>Добавить категории</span>
                </label>
                <label>
                    <input type="radio" name="action" value="cats_del" required/>
                    <span>Удалить категории</span>
                </label>
                <label>
                    <input type="radio" name="action" value="serv_add" required/>
                    <span>Добавить услуги</span>
                </label>
                <label>
                    <input type="radio" name="action" value="serv_del" required/>
                    <span>Удалить услуги</span>
                </label>
            </div>';
}
//include_once APPROOT.DS."view".DS."js_back.html";
?>
        <div class="margintb1 <?php echo $dn; ?>" id="form_buttons" >
            <?php
            /*
            if (!empty($data['res']) || !empty($data['page_id'])) {
                print '<button class="buttons" onclick="history.back();">Назад</button>';
            } else {
                print '<a href="'.URLROOT.'/adm/" class="buttons">Назад</a>';
            }
            */
            ?>
            <button type="submit" name="submit" class="buttons" form="change_page_form" />Далее</button>
            <input type="reset" class="buttons" form="change_page_form" value="Сбросить" />
        </div>
    </form>
</div>

<script>

function add(el) {
    let shoose = $(el).prop("value");
    var id = parseInt($("div#"+shoose+"_add").find(".about_form:last").attr("id").slice(4))+1;

    let file = '';
    let name = '';
    let desc = '';
    let price = '';
    let duration = '';

    if (shoose == "cats") {
        name = "категории";
        file = '<label class="input-file">\
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />\
                    <input type="file" id="f'+shoose+id+'" name="'+shoose+'_img[]" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" required/>\
                    <span >Изображение '+name+' весом до 3Мб</span>\
                    <p id="fileSizef'+shoose+id+'"></p>\
                </label>';
    } else if (shoose == "serv") {
        file = '<label class="input-file">\
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />\
                    <input type="file" id="f'+shoose+id+'" name="'+shoose+'_img[]" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" required/>\
                    <span >Изображение '+name+' весом до 3Мб</span>\
                    <p id="fileSizef'+shoose+id+'"></p>\
                </label>';
        name = "услуги";
        desc = '<label class="textarea"><p>Описание '+name+' (до 500 символов)</p>\
                    <p>\
                        <textarea name="'+shoose+'_desc[]" placeholder="Описание '+name+'" maxlength="500"></textarea>\
                    </p>\
                </label>' ;
        price = '   <label ><p>Прайс (цифры, до 10 символов)</p>\
                        <p>\
                            <input type="number" name="price[]" placeholder="10000" min="0" max="1000000" step="0.1" title="Только цифры" required />\
                        </p>\
                    </label>';
        duration = '   <label ><p>Длительность в минутах (2-3 цифры)</p>\
                        <p>\
                            <input type="number" name="duration[]" placeholder="60" min="10" max="480" step="5" title="Только цифры" required />\
                        </p>\
                    </label>';
        if ($('#cats_view').text() == 'Скрыть категории') {
            file = ''; desc = '';
        }
    }
    // add fields for input
    $("div#"+shoose+'_add').append('<div class="about_form back shad rad pad mar display_inline_block display_none" id="'+shoose+id+'">\
        '+file+'\
        <label ><p>Введите название '+name+' (до 100 символов)</p>\
            <p>\
                <input type="text" name="'+shoose+'_name[]" placeholder="Название '+name+'" maxlength="100" required />\
            </p>\
        </label>\
        '+desc+'\
        '+price+'\
        '+duration+'\
    </div>');
 };

$(function(){
    const TDEL = $('#cats_view');
    if (TDEL) {
        TDEL.on('click', function(e) {
            $('#cats_list').toggle();
            if (TDEL.text() == 'Показать категории' ) {
                TDEL.text('Скрыть категории');
                $('.input-file').hide();
                $('.textarea').hide();
            } else {
                TDEL.text('Показать категории');
                $('.input-file').show();
                $('.textarea').show();
            }
        });
    }

    $('form#change_page_form').on('change', function(){
        $("[type='file']").each(function(){
            let files = this.files;
            if (files.length > 0) {
                let file = this.files[0];
                let size = 3*1024*1024; //3MB
                $(this).next().html(file.name);
                if (file.size > size) {
                    $('#fileSize'+this.id).css("color","red").html('ERROR! Image size > 3MB');
                } else {
                    //$('#fileSize'+this.id).css("color","").html(file.name+' - '+Math.round(file.size/1000)+' KB');
                    $('#fileSize'+this.id).html('');
                }
            }
        });
    });

    $('form#change_page_form').on('reset', function(){
        //$('form#about_edit').get(0).reset();
        $('.about_form').each(function (i) {
            $('.about_form').slice(1).remove();
        });
        $("[type='file']").each(function(){
            let file = 'Выберите фото весом до 3Мб';
            $(this).next().html(file);
            $('#fileSize'+this.id).html('');
        });
    });

    $('button[type="submit"]').on('click', function(e){
        //$('form#about_edit').get(0).reset();
        let inp = $('input[name="serv_del[]"]:checkbox').length;
        if (inp > 0 ) {
            var count = $('input[name="serv_del[]"]:checkbox:checked').length;
            if ( count > 0 ) {
                $('#change_page_form').submit();
            } else {
                e.preventDefault();
            }
        }
    });

});

</script>
