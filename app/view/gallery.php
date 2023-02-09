<div class="gallery">
<?php
$photo_link = "https://disk.yandex.ru/d/PldT5ChpcRCVRg";
//var for gallery.php
$directory = PUBLICROOT.DS.'imgs/gallery';    // Папка с изображениями
$pattern = '#z*.(jpg|png|jpeg|webm*)#';
//$width = 460;
//$height = 320;

echo simpleGallery_fancybox($directory, $pattern, $width = '', $height = '');

if (!empty($photo_link)): $photo_link_name = explode('.', parse_url($photo_link, PHP_URL_HOST));
?>
<p class="zapis_usluga back shad rad pad mar">
    Больше снимков можно посмотреть в
    <a href="<?php if (isset($photo_link)): echo $photo_link; endif; ?>" >
        <?php if (isset($photo_link_name)): echo strtoupper($photo_link_name[0]); endif; ?>
    </a>
</p>
<?php
endif;
include_once APPROOT.DS."view".DS."back_home.html";
?>
</div>
