<div class="gallery">
<?php
/**
   * @param string $directory = 'gallery_img';    // Папка с изображениями
   * @param string $pattern = '#z*.(jpg|png|jpeg|webm*)#'; // паттерн отбора изображений
   * @param string $width = 100; //ширина в пикселях
   * @param string $height = 100; //высота в пикселях
   * @return string or null
   */
  function simpleGallery_fancybox ($directory, $pattern, $width = '', $height = '') {
    $x = explode('/', URLROOT);
    $x = array_pop($x);
    $iterator = new FilesystemIterator($directory);
    $filter = new RegexIterator($iterator, $pattern);
    $gallery = '';
    foreach ($filter as $name) {
      $nameww = explode($x, str_replace(' ', '%20', $name));
      $nameww = array_pop($nameww);
      $nameww = URLROOT.$nameww;
      $namefn = pathinfo($name,PATHINFO_FILENAME);
      $w = (!empty($width)) ? 'width="' . $width . '"' : '';
      $h = (!empty($height)) ? 'height="' . $height . '"' : '';
      $gallery .= '<a data-fancybox="gallery" class="gallery_a" href="' . $nameww . '" title="'.$namefn.'">
                    <img class="rounded" src="' . $nameww .'" alt="'.$namefn.'" '.$w.' '.$h.'  />
                  </a>';
    }
    return (!empty($gallery)) ? $gallery : null;
  }

$photo_link = "https://disk.yandex.ru/d/PldT5ChpcRCVRg";
//var for gallery.php
$directory = PUBLICROOT.DS.'imgs/gallery';    // Папка с изображениями
$pattern = '#z*.(jpg|png|jpeg|webm*)#';
//$width = 460;
//$height = 320;

echo simpleGallery_fancybox($directory, $pattern, $width = '', $height = '');

if (!empty($photo_link)): $photo_link_name = explode('.', parse_url($photo_link, PHP_URL_HOST));
endif;
include_once APPROOT.DS."view".DS."back_home.html";
?>
</div>
<p class="zapis_usluga back shad rad pad mar">
    Больше снимков можно посмотреть в
    <a href="<?php if (isset($photo_link)): echo $photo_link; endif; ?>" >
        <?php if (isset($photo_link_name)): echo strtoupper($photo_link_name[0]); endif; ?>
    </a>
</p>
