<div class="map">
<?php
if (!empty($data['page_db_data'][0]['page_content']))
{    
    // ADD check to responsible map (if isset answer)
    print $data['page_db_data'][0]['page_content'];
}
elseif ( is_readable(IMGDIR.DS.'map'.DS.'map.jpg') )
{
  echo '<img src="'.URLROOT.'/public/imgs/map/map.jpg" alt="" class="mapp"/>';
}
include_once APPROOT.DS."view".DS."back_home.html";
?>

</div>

<script>
$(function(){
  let ifr = $('.map > iframe');
  if ( ifr.length ) {
    $('.map > iframe').css({width: '', height: ''}).addClass('mapp');
  }
});

</script>

