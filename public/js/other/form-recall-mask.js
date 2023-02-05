(function( $ ){

  var $body;

  $(document).ready(function(){
    $body = $('body');

    $body
      .find('.number').each(function(){
          $(this).mask("+7 999 999 99 99",{autoclear: false});
      });

    $body.on('keyup','.number',function(){
      var phone = $(this),
          phoneVal = phone.val(),
          form = $(this).parents('form');

      if ( (phoneVal.indexOf("_") != -1) || phoneVal == '' ) {
        form.find('.form-recall-submit').attr('disabled',true);
      } else {
        form.find('.form-recall-submit').removeAttr('disabled');
      }
    });

  });

})( jQuery );
