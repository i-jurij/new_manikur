<div class="content">
    <form action="<?php echo URLROOT.'/redaktor_uslug/go/'; ?>" method="post" enctype="multipart/form-data" class="" id="change_page_form">
<?php
if (!empty($data['res'])) {
    print '<p>'; print_r($data['res']); print '</p>';
    $dn = 'display_none';
} elseif (!empty($data['page_id'])) {
    if ($data['action'] === 'cats_add') {
        print ' <div class="zapis_usluga" id="cats_add">
                    <input type="hidden" name="page_id" value="'.$data['page_id'].'" />
                    <p class="">Выберите изображение, название и описание категории, нажмите Далее</p>
                    <div class="about_form back shad rad pad mar display_inline_block" id="cats0">
                        <label class="input-file">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                            <input type="file" id="fcats0" name="cats_img[]" accept=".jpg,.jpeg,.png, .webp, image/jpeg, image/pjpeg, image/png, image/webp" />
                            <span >Изображение категории весом до 3Мб</span>
                            <p id="fileSizefcats0" ></p>
                        </label>
                        <label ><p>Введите название категории (до 100 символов)</p>
                            <p>
                            <input type="text" name="cats_name[]" placeholder="Название категории" maxlength="100" required />
                            </p>
                        </label>
                    </div>
                    <div class="mar " id="">
                        <button class="buttons add" type="button" value="cats" onclick="add(this);">Добавить еще</button>
                    </div>
                </div>
            ';
    } elseif ($data['action'] === 'serv_add') {

    } elseif ($data['action'] === 'cats_del') {
        print '<div class="zapis_usluga">
                <p class="">Выберите категории для удаления, нажмите Далее</p>';
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

    }
} else {
    //вывод списка страниц
    if (isset($data['service_page'])) {
        print '<div class="form_radio_btn margin_bottom_1rem" style="width:85%;">
                    <p class="">Выберите страницу для редактирования:</p>';
        foreach ($data['service_page'] as $value)
        {
            echo '<label>
                    <input type="radio" name="page_for_edit" value="' . $value['page_id'] . '" required />
                        <span>' . $value['page_title'] . '</span>
                    </label>
            ';
        }
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
    let name = '';
    let desc = '';
        let shoose = $(el).prop("value");

        if (shoose == "cats") {
            name = "категории";
        } else if (shoose == "serv") {
            name = "услуги";
            desc ='<label ><p>Описание '+name+' (до 500 символов)</p>\
                        <p>\
                        <textarea name="'+shoose+'_desc[]" placeholder="Описание '+name+'" maxlength="500"></textarea>\
                        </p>\
                    </label>' ;
        }
        var id = parseInt($("div#"+shoose+"_add").find(".about_form:last").attr("id").slice(4))+1;
        $("div#"+shoose+'_add').append('<div class="about_form back shad rad pad mar display_inline_block display_none" id="'+shoose+id+'">\
                <label class="input-file">\
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />\
                <input type="file" id="f'+shoose+id+'" name="'+shoose+'_img[]" accept=".jpg,.jpeg,.png, .webp, image/jpeg, image/pjpeg, image/png, image/webp" />\
                <span >Изображение '+name+' весом до 3Мб</span>\
                <p id="fileSizef'+shoose+id+'" ></p>\
                </label>\
                <label ><p>Введите название '+name+' (до 100 символов)</p>\
                        <p>\
                        <input type="text" name="'+shoose+'_name[]" placeholder="Название '+name+'" maxlength="100" required />\
                        </p>\
                    </label>\
                    '+desc+'\
            </div>');
    };
$(function(){

    $('div#page_choice > label > input').on('click', function(){
        let choice = $(this).prop("value");
    });

    $('div#action_choice > button').on('click', function(){
        $('div#action_choice').hide();
        //$('div#form_buttons').show();
        const object = $('div#'+this.id+'d');
        $('div.zapis_usluga').remove();
        object.insertBefore('div#form_buttons');
        $('div#'+this.id+'d').removeClass('display_none');
    });

    const TDEL = $('#cat_view');
    if (TDEL) {
        TDEL.on('click', function(e) {
            $('#cat_list').toggle();
            if (TDEL.textContent.includes('Показать') ) {
                TDEL.innerText = 'Выбрать категорию';
            } else {
                TDEL.innerText = 'Показать категории';
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
                    //$('#fileSize').html(file.name+' - '+file.size/1024+' KB');
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

});

</script>
