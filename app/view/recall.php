<div class="">
<?php
if (!empty($data['res'])) {
    print $data['res'];
    include_once APPROOT.DS."view".DS."js_back.html";
} else {
?>
    <script type="text/javascript">
    function guidGenerator() {
        var S4 = function() {
           return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
        };
        return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
    }
    
    $(function() {
      //create form
      $('.flex_top').append('<form action="<?php print URLROOT.DS.'recall'.DS.'rec'; ?>" method="post" class="form-recall" id="recall_one">\
           <div class="form-recall-main">\
             <div class="form-recall-main-section">\
               <div class=" flex">\
                 <input type="text" placeholder="Ваше имя" name="name" id="name" maxlength="50" />\
                 <input name="last_name" type="text" placeholder="Ваша фамилия" id="last_name" maxlength="50">\
                 <input type="tel" name="phone_number"  id="number" class="number"\
                   title="Формат: +7 999 999 99 99" placeholder="+7 ___ ___ __ __"\
                   minlength="6" maxlength="17"\
                   pattern="(\\+?7|8)?\\s?[\(]{0,1}?\\d{3}[\\)]{0,1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?"\
                   style=""\
                   required />\
                 <div id="error"><small></small></div>\
                 <textarea placeholder="Что вас интересует?" name="send"  id="send" maxlength="300"></textarea>\
               </div>\
             </div>\
    \
             <div class="back shad rad pad margin_bottom_1rem capcha">\
               <div class="imgs div_center" style="width:21rem;"></div>\
             </div>\
    \
             <div class="form-recall-main-section">\
                 <button class="buttons form-recall-submit">Отправить</button>\
                 <button class="buttons form-recall-reset" type="reset">Очистить</button>\
             </div>\
             <div class="clear"></div>\
           </div>\
    \
           <div class="form-recall-main">\
             <p class="pers">\
               Отправляя данную форму, вы даете согласие на\
               <br>\
               <a href="<?php echo URLROOT; ?>/persinfo/">\
                 обработку персональных данных\
               </a>\
             </p>\
           </div>\
      </form>');
    
      var uniqids = [];
      for (var i = 0; i < 6; i++)
      {
        //random id generated
        uniqids[i] = guidGenerator();
      }
      //choice random id from ids array
      var truee = uniqids[Math.floor(Math.random()*uniqids.length)];
    
      var strings = [];
      var imgs = [];
      for (var i = 0; i < uniqids.length; i++)
      {
        let ii = i+1;
        let imgpath = '<?php echo URLROOT.DS.'public'.DS.'imgs'.DS.''.DS.'captcha_imgs'.DS; ?>';
        imgs[uniqids[i]] = '<img src="'+imgpath+ii+'.jpg" style="width:5rem;" />';
        //console.log(imgs[uniqids[i]]);
        strings[i] = '<input id="captcha_'+uniqids[i]+'" class="captcha" name="dada" value="'+ii+'" type="radio" />\
        <label class="captcha_img" for="captcha_'+uniqids[i]+'">\
        <img src="'+imgpath+ii+'.jpg" id="img_'+uniqids[i]+'"/>\
        </label>';
      }
    
      $('.capcha .imgs').before('<div><p>Выберите, пожалуйста, среди других этот рисунок:</p>\
                        <p>'+imgs[truee]+'</p></div>');
    
      for (var i = 0; i < strings.length; i++)
      {
        $('.capcha .imgs').append(strings[i]);
      }
    
      $("#img_"+truee).addClass('access');
    
      $('.capcha .imgs').after('<p><small>После выбора рисунка нажмите Отправить.</small></p>');
    
      $('div.form-recall-submit').click(function(){
        let check = $("#captcha_"+truee).is(':checked');
        if ($('#number').val())
        {
          if ( check == true )
          {
            $('form#recall_one').submit();
          }
          else
          {
            alert('Выберите, пожалуйста, соответствующий рисунок :)');
          }
        }
        else
        {
          alert('Вы забыли ввести номер телефона :)');
        }
      });

      $("form#recall_one").on("submit", function(event){
        //event.preventDefault();
        var dataar = $("form#recall_one").serialize();
        $.ajax({
          url: '<?php echo URLROOT.DS.'app'.DS.'lib'.DS.'mail_send.php'; ?>',
          method: 'post',
          dataType: 'html',
          data: dataar,
          success: function(data){
            //$(".flex_top").append(data);
            //console.log(data);
          }
        });
      });
    });
    
    </script>
<?php
include_once APPROOT.DS."view".DS."back_home.html";
}
?>
</div>
