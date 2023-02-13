

<?php
if (!empty($data['res'])) {
    print '<div class="content"><p>';
    print_r($data['res']);
    print '</p></div>';
} else {
    // menu
    ?>
      <div class="">
        <div class="margin_bottom_1rem choice">
        <button type="button" class="buttons" id="aboutadd" >Добавить записи</button>
        <button type="button" class="buttons" id="aboutdel" >Удалить записи</button>
        </div>

        <form action="<?php echo URLROOT.DS.'redaktor_about'.DS.'change'.DS; ?>" method="post" name="about_edit" id="about_edit" class="display_none" enctype="multipart/form-data">
        <div class="zapis_usluga" >
            <div class="" id="inputs">
              <p class="back shad rad pad mar">Выберите изображение, название и текст для новой карточки страницы</p>
              <div class="about_form back shad rad pad mar display_inline_block" id="inp0">
                  <label class="input-file">
                      <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                      <input type="file" id="f0" name="about_img[]" accept=".jpg,.jpeg,.png, image/jpeg, image/pjpeg, image/png" />
                      <span >Выберите фото весом до 3Мб</span>
                      <p id="fileSizef0" ></p>
                  </label>
                  <label ><p>Введите название (до 50 символов)</p>
                      <p>
                      <input type="text" name="about_title[]" placeholder="Название" maxlength="50" />
                      </p>
                  </label>
                  <label ><p>Введите текст (до 500 символов)</p>
                      <p>
                      <textarea name="about_text[]" placeholder="Текст" maxlength="500" ></textarea>
                      </p>
                  </label>
              </div>
            </div>
            <div class="mar" id="aaf">
              <button class="buttons" type="button" >Добавить еще</button>
            </div>
        </div>

        <div class="zapis_usluga display_none" id="about_art_del">
            <p class=" back shad rad pad mar">Выберите карточки для удаления, нажмите Готово</p>
            <?php
                foreach ($data['about'] as $art)
                {
                    $imgname = pathinfo($art['article_image'], PATHINFO_FILENAME);
                    $imgext = pathinfo($art['article_image'], PATHINFO_EXTENSION);
                    $iddel = $art['id'].'_'.$imgname.'_'.$imgext;

                    echo '<article class="main_section_article" id="'.$iddel.'">
                            <div class="main_section_article_imgdiv">
                            <img src="'.$art['article_image'].'" alt="Фото '.$art['article_title'].'" class="main_section_article_imgdiv_img" />
                            </div>
                            <div class="main_section_article_content">
                                <h3>'.$art['article_title'].'</h3>
                                <span>'.$art['article_content'].'</span><br />
                                <br />
                            </div>
                        </article>';
                }
            ?>
        </div>

        <div class="">
            <div class="zapis_usluga margin_bottom_1rem" >
            <a href="<?php echo URLROOT.DS.'redaktor_about/'; ?>" class="buttons">Назад</a>
            <button class="buttons" form="about_edit" type="reset" >Очистить</button>
            <button class="buttons" type="submit" form="about_edit">Готово</button>
            </div>
        </div>
        </form>
    </div>
<?php
}

?>
<script>
$(function(){

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

  $('div#aaf > button').on('click', function(){
    var id = parseInt($("div#inputs").find(".about_form:last").attr("id").slice(3))+1;
    $("div#inputs").append('<div class="about_form back shad rad pad mar display_inline_block display_none" id="inp'+id+'">\
            <label class="input-file">\
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />\
              <input type="file" id="f'+id+'" name="about_img[]" accept=".jpg,.jpeg,.png, .webp, image/jpeg, image/pjpeg, image/png, image/webp" required />\
              <span>Выберите фото весом до 3Мб</span>\
              <p id="fileSizef'+id+'" ></p>\
            </label>\
            <label ><p>Введите название (до 50 символов)</p>\
              <p>\
              <input type="text" name="about_title[]" placeholder="Название карточки" maxlength="50" required />\
              </p>\
            </label>\
            <label ><p>Введите текст (до 500 символов)</p>\
              <p>\
              <textarea name="about_text[]" placeholder="Текст карточки" maxlength="500" required ></textarea>\
              </p>\
            </label>\
        </div>');
  });

  $(".main_section_article").on({
    click: function() {
      let id = this.id;
      console.log(id);
      if ( $('#ch'+id).val() ) {
        $('#ch'+id).remove();
        $(this).removeClass('selected');
      }else {
        $("div#inputs").append('<input type="hidden" name="about_del[]" value="'+id+'" id="ch'+id+'" />');
        $(this).addClass('selected');
      }
    }
  });

  $('form#about_edit').on('change', function(){
    $("[type='file']").each(function(){
        let file = this.files[0];
        let size = 3*1024*1024; //3MB
        $(this).next().html(file.name);
        if (file.size > size) {
            $('#fileSize'+this.id).css("color","red").html('ERROR! Image size > 3MB');
        } else {
            //$('#fileSize').html(file.name+' - '+file.size/1024+' KB');
        }
    });
  });

  $('form#about_edit').on('reset', function(){
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
