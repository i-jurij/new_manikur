<?php
if (!empty($data['res'])) {
    print $data['res'];
}
elseif ( !empty($data['category']) ) {
  echo 'ccc';
} elseif ( !empty($data['service']) ) {
    echo 'sss';
} else {
    //вывод списка страниц
    if (isset($data['service_page'])) {
        ?>
        <div class="content">
        <form action="" method="post" class="" id="change_page_form">
            <div class="form_radio_btn" style="width:85%;" id="page_choice">
                <p class="">Выберите страницу для редактирования:</p>
                <?php
                foreach ($data['service_page'] as $value)
                {
                    echo '<label class="" for="' . $value ['page_id'] . '">
                            <input type="radio" name="page_for_edit" value="' . $value['page_id'] . '" />
                            <span>' . $value['page_title'] . '</span>
                        </label>
                        ';
                }
                ?>
            </div>

            <div class="form_radio_btn" style="width:85%;" id="action_choice">
                <p class="">Выберите действие:</p>
                <label for="add_cat">
                    <input type="radio" name="action_choice" value="add_usl" />
                    <span>Добавить категории</span>
                </label>
                <label for="del_cat">
                    <input type="radio" name="action_choice" value="del_usl" />
                    <span>Удалить категории</span>
                </label>
                <label for="add_usl">
                    <input type="radio" name="action_choice" value="add_usl" />
                    <span>Добавить услуги</span>
                </label>
                <label for="del_usl">
                    <input type="radio" name="action_choice" id="del_usl" value="del_usl" />
                    <span>Удалить услуги</span>
                </label>
            </div>

            <br />js show form after shoose<br />

            <div class="zapis_usluga" >
                <div class="" id="cats_add">
                    <p class=" back shad rad pad mar">Выберите изображение, название и описание категории, нажмите Далее</p>
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
                        <label ><p>Описание категории (до 500 символов)</p>
                            <p>
                            <textarea name="cats_desc[]" placeholder="Описание категории" maxlength="500"></textarea>
                            </p>
                        </label>
                    </div>
                </div>
                <div class="mar " id="">
                    <button class="buttons add" type="button" value="cats">Добавить еще</button>
                </div>
            </div>

            del cat: list of cats for choosed page<br />
            <div class="zapis_usluga" id="cats_del">
                <p class=" back shad rad pad mar">Выберите категории для удаления, нажмите Далее</p>
                <?php
                    foreach ($data['page_cats'] as $cat) //foreach cat ids from table
                    {
                        echo '  <label class="">
                                    <input type="checkbox" name="cat_del[]" value="'.$cat['id'].'">
                                    <span>'.$cat['category_name'].'</span>
                                </label>';
                    }
                ?>
            </div>

            add serv: list of cats for choosed page and form for serv add (cat can be or not added in form)<br />
            <div class="zapis_usluga" >
                <div class="" id="serv_add">
                    <p class=" back shad rad pad mar">Выберите изображение, название и описание услуги, нажмите Далее</p>
                    <div class="about_form back shad rad pad mar display_inline_block" id="serv0">
                        <label class="input-file">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                            <input type="file" id="fserv0" name="serv_img[]" accept=".jpg,.jpeg,.png, .webp, image/jpeg, image/pjpeg, image/png, image/webp" />
                            <span >Изображение услуги весом до 3Мб</span>
                            <p id="fileSizefserv0" ></p>
                        </label>
                        <label ><p>Введите название услуги (до 100 символов)</p>
                            <p>
                            <input type="text" name="serv[]" placeholder="Название категории" maxlength="100" required />
                            </p>
                        </label>
                        <label ><p>Описание услуги (до 500 символов)</p>
                            <p>
                            <textarea name="serv_desc[]" placeholder="Описание категории" maxlength="500"></textarea>
                            </p>
                        </label>
                    </div>
                </div>
                <div class="mar " id="">
                    <button class="buttons add" type="button" value="serv">Добавить еще</button>
                </div>
            </div>
            del serv: list of serv into cat and list other serv<br />
            <div class="zapis_usluga" id="serv_del">
                <p class=" back shad rad pad mar">Выберите услуги для удаления, нажмите Далее</p>
                <?php
                    foreach ($data['page_cats_serv'] as $cat_name => $cat_serv) //foreach cat ids from table
                    {
                        print $cat_name;
                        foreach ($cat_serv as $serv) //foreach cat ids from table
                        {
                            echo '  <label class="">
                                        <input type="checkbox" name="cat_serv_del[]" value="'.$serv['id'].'">
                                        <span>'.$serv['serv_name'].'</span>
                                    </label>';
                        }
                    }
                    foreach ($data['page_serv'] as $serv) //foreach cat ids from table
                    {
                        echo '  <label class="">
                                    <input type="checkbox" name="serv_del[]" value="'.$serv['id'].'">
                                    <span>'.$serv['serv_name'].'</span>
                                </label>';
                    }
                ?>
            </div>

            <div class="margintb1">
                <a href="" class="buttons" onclick="document.referrer ? window.location = document.referrer : history.back();">Назад</a>
                <button type="submit" name="submit" class="buttons" form="change_page_form" value="change" />Далее</button>
                <input type="reset" class="buttons" form="change_page_form" value="Сбросить" />
            </div>
        </form>
        </div>
        <?php
    } else {
        print '<div class="content"><p>Список страниц пуст.</p></div>';
    }
}
include_once APPROOT.DS."view".DS."js_back.html";
?>

<script>
$(function(){
/*
  $('.choice > .buttons').on('click', function(){
    $('.choice').hide();
    $('form#about_edit').show();
    if (this.id == 'aboutadd') {
      $("#f0, [type='text'], textarea").prop('required', true);
    }
    else if (this.id == 'aboutdel') {
      $('div#aaf').remove();
      $('div.about_form').remove();
      $('#about_art_del').show();
    }
  });
*/
    $('div#page_choice > input[type="radio"]').on('click', function(){
        let choice = $(this).prop("value");
        console.log(choice);
    });


    $('.add').on('click', function(){
        let shoose = $(this).prop("value");

        if (shoose == "cats") {
            name = "категории";
        } else if (shoose == "serv") {
            name = "услуги";
        }
        var id = parseInt($("div#"+shoose+"_add").find(".about_form:last").attr("id").slice(4))+1;
        $("div#"+shoose+'_add').append('<div class="about_form back shad rad pad mar display_inline_block display_none" id="'+shoose+id+'">\
                <label class="input-file">\
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />\
                <input type="file" id="f'+shoose+id+'" name="'+shoose+'_img[]" accept=".jpg,.jpeg,.png, .webp, image/jpeg, image/pjpeg, image/png, image/webp" required />\
                <span >Изображение '+name+' весом до 3Мб</span>\
                <p id="fileSizef'+shoose+id+'" ></p>\
                </label>\
                <label ><p>Введите название '+name+' (до 100 символов)</p>\
                        <p>\
                        <input type="text" name="'+shoose+'_name[]" placeholder="Название '+name+'" maxlength="100" required />\
                        </p>\
                    </label>\
                    <label ><p>Описание '+name+' (до 500 символов)</p>\
                        <p>\
                        <textarea name="'+shoose+'_desc[]" placeholder="Описание '+name+'" maxlength="500"></textarea>\
                        </p>\
                    </label>\
            </div>');
    });

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
