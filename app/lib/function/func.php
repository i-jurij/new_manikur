<?php
function getOutput ($file) {
  ob_start();
  include $file;
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function files_in_dir($path, $ext) 
{
  $files = array();
  if (file_exists($path)) {
    $f = scandir($path);
    foreach ($f as $file){
      if(preg_match("/\.($ext)/", $file)) {
        $files[] = $file;
      }
    }
  }
  return $files;
}

function menu($data) 
{
  //page list from db
  if (!empty($data['page_list'])) {
    $res = array_column($data['page_list'], 'page_alias', 'page_h1');//get pages array: 'page_h1' => 'page_alias'
  }
  //url path from rout and controller
  if(!empty($data['nav']) && isset($res)){
    if (is_array($data['nav'])) {
      foreach ($data['nav'] as $value) {
        $ress[$value] = array_search($value, $res);//get array 'nav = page_alias' => 'page_h1'
      }
    } 			
  }
  //set empty value for main pages 'home' and 'admin'
  if(!empty($data['page_db_data'][0])){
    $ress[$data['page_db_data'][0]['page_alias']] = $data['page_db_data'][0]['page_h1'];
    if ($data['page_db_data'][0]['page_alias'] == 'home' or $data['page_db_data'][0]['page_alias'] == 'adm') {
      $nav = '';
    } else {
      $nav = '<a href="'.URLROOT.'/adm">Главная</a>';
    }
  }
//get full path for links
  if (!empty($ress)) {
    $prevk = '';
    foreach ($ress as $key => $value) {
      if (empty($value)) {
        $value = (!empty($data['name'])) ? $data['name'] : $key;
      }
      if (!empty($prevk)) {
        $nav .= ' / <a href="'.URLROOT.$prevk.DS.$key.'">'.$value.'</a>';
        $prevk .= DS.$key;
      } else {
        if (empty($nav)) {
          $nav = '<a href="'.URLROOT.DS.$key.'">'.$value.'</a>';
        } else {
          $nav .= ' / <a href="'.URLROOT.DS.$key.'">'.$value.'</a>';
        }
        $prevk .= DS.$key;
      }
    }
  }
  return (isset($nav)) ? $nav : '';
}

function sanitize($filename) {
  // remove HTML tags
  $filename = strip_tags($filename);
  // remove non-breaking spaces
  $filename = preg_replace("#\x{00a0}#siu", ' ', $filename);
  // remove illegal file system characters
  $filename = str_replace(array_map('chr', range(0, 31)), '', $filename);
  // remove dangerous characters for file names
  $chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "’", "%20",
                 "+", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", "^", chr(0));
  $filename = str_replace($chars, '_', $filename);
  // remove break/tabs/return carriage
  $filename = preg_replace('/[\r\n\t -]+/', '_', $filename);
  // convert some special letters
  $convert = array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss',
                   'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u');
  $filename = strtr($filename, $convert);
  // remove foreign accents by converting to HTML entities, and then remove the code
  $filename = html_entity_decode( $filename, ENT_QUOTES, "utf-8" );
  $filename = htmlentities($filename, ENT_QUOTES, "utf-8");
  $filename = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $filename);
  // clean up, and remove repetitions
  $filename = preg_replace('/_+/', '_', $filename);
  $filename = preg_replace(array('/ +/', '/-+/'), '_', $filename);
  $filename = preg_replace(array('/-*\.-*/', '/\.{2,}/'), '.', $filename);
  // cut to 255 characters
  //$filename = substr($data, 0, 255);
  // remove bad characters at start and end
  $filename = trim($filename, '.-_');
  return $filename;
}

function mb_ucfirst($string, $encoding)
{
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, null, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

?>