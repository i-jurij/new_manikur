<!doctype html>
<?php $db = (!empty($data['page_db_data']['0'])) ?  $data['page_db_data']['0'] : null; ?>
<html lang="<?php echo $a = (isset($db['html_lang']) and !empty($db['html_lang'])) ? htmlspecialchars($db['html_lang']) : 'ru'; ?>">
<head>
  <meta charset="<?php echo $b = (isset($db['charset']) and !empty($db['charset'])) ? htmlspecialchars($db['charset']) : 'utf-8' ; ?>">
  <meta name="referrer" content="origin-when-cross-origin">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">

  <title>
    <?php 
      echo $c = (isset($db['page_title']) and !empty($db['page_title'])) ? htmlspecialchars($db['page_title']) : 'Title of page';
    ?>
  </title>
  <meta name="description" content="<?php echo $d = (isset($db['page_meta_description']) and !empty($db['page_meta_description'])) ? htmlspecialchars($db['page_meta_description']) : 'Description of page'; ?>">
  <META NAME="keywords" CONTENT="<?php echo $e = (isset($db['page_meta_keywords']) and !empty($db['page_meta_keywords'])) ? htmlspecialchars($db['page_meta_keywords']) : 'Keywords of page'; ?>">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <META NAME="Robots" CONTENT="<?php echo $f = (isset($db['robots']) and !empty($db['robots'])) ? htmlspecialchars($db['robots']) : 'INDEX, FOLLOW'; ?>">
  <meta name="author" content="I-Jurij">
  <?php 
  if (!empty($data['css'])) {
    if (is_array($data['css'])) {
      foreach ($data['css'] as $css) {
        echo '<link rel="stylesheet" type="text/css" href="'.URLROOT.DS.htmlspecialchars($css).'" />'; 
      }
    } elseif (is_string($data['css'])) {
      echo '<link rel="stylesheet" type="text/css" href="'.URLROOT.DS.htmlspecialchars($data['css']).'" />'; 
    }
  }
  ?>
  <link rel="icon" href="<?php echo URLROOT.DS; ?>public/imgs/favicon.png" />
  <?php 
    foreach (files_in_dir(PUBLICROOT.DS.'js'.DS.'core', 'js') as $value) {
      print '<script type="text/javascript" src="'.URLROOT.DS.'public'.DS.'js'.DS.'core'.DS.$value.'"></script>';
    }
  ?>
</head>
<body>
  <div class="wrapper">
    <header class="he stickyheader flex">
      <?php include PUBLICROOT.DS.'templates'.DS.'contacts.php'; ?>
    </header>

    <div class="main ">
      <section class="main_section">
        <div class="flex flex_top">
          <div class="content title">
            <h2><?php echo $c = (isset($db['page_h1']) and !empty($db['page_h1'])) ? htmlspecialchars($db['page_h1']) : 'Page title';?></h2>
          </div>
          <?php 
            if (!empty($data[0]['page_content'])) {
              print htmlspecialchars($data[0]['page_content']);
            }
            if ( (new SplFileInfo($content_view))->isReadable() ) {
              include $content_view; 
            } elseif (is_string($content_view)) {
              print $content_view;
            }
          ?>
        </div>
      </section>
    </div>

    <footer class="foot">
      <div class="foot_div">
          <?php include PUBLICROOT.DS.'templates'.DS.'contacts.php'; ?>
      </div>
      <div class="foot_div">
        <?php echo "2022 - " . date('Y') . PHP_EOL; ?>
      </div>
    </footer>
  </div>

  <?php 
    foreach (files_in_dir(PUBLICROOT.DS.'js'.DS.'other', 'js') as $value) {
      print '<script type="text/javascript" defer src="'.URLROOT.DS.'public'.DS.'js'.DS.'other'.DS.$value.'"></script>';
    }
  ?>
  <!-- <script type="text/jsx" src="public/js/fancybox.umd.js"></script> -->
  </body>
</html>