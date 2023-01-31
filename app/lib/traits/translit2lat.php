<?php
namespace App\Lib\Traits;

trait Translit2lat {
      /**
     * replaces all Cyrillic letters with Latin
     * @param string $var
     * @return string
     */
    function translit_ostslav_to_lat($textcyr) {
      $cyr = ['Ц','ц', 'а','б','в','ў','г','ґ','д','е','є','ё','ж','з','и','ï','й','к','л','м','н','о','п', 'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я', 'А','Б','В','Ў','Г','Ґ','Д','Е','Є','Ё','Ж','З','И','Ї','Й','К','Л','М','Н','О','П', 'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
      ];
      $lat = ['C','c', 'a','b','v','w','g','g','d','e','ye','io','zh','z','i','yi','y','k','l','m','n','o','p', 'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya', 'A','B','V','W','G','G','D','E','Ye','Io','Zh','Z','I','Yi','Y','K','L','M','N','O','P', 'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
      ];
      $textlat = str_replace($cyr, $lat, $textcyr);
      return $textlat;
  }
   /**
   * replaces all letters with Latin ASCII
   * @param string $var
   * @return string
   */
  function translit_to_lat($text) {
      $res = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $text));
      return $res;
  }
}
?>