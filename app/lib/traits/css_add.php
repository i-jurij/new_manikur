<?php
namespace App\Lib\Traits;

trait Css_add
{
    /*
    //glob
    public function css_add($path_to_css = DS.'public'.DS.'css'.DS.'first') 
    {
        $pattern = '/*.{php,css}';
        $css_files = array();
        foreach(glob($path_to_css . $pattern , GLOB_BRACE) as $file) {
            if (strpos($file, 'normalize')) {
                if (!empty($css_files[0])) {
                    $css_files[] = $css_files[0];
                    $css_files[0] = $file;
                } else {
                    $css_files[0] = $file;
                }   
            } else {
                $css_files[] = $file;
            }
        }
        return $css_files;
    }
    */
    /*
    //opendir readdir closedir
    public function css_add($path_to_css = DS.'public'.DS.'css'.DS.'first') 
    {
        $css_files = array();
        if ($handle = opendir($path_to_css)) {
            while (false !== ($entry = readdir($handle))) {
                $ext = pathinfo($entry, PATHINFO_EXTENSION);
                if ( $entry != "." && $entry != ".." && ( is_file($path_to_css.DS.$entry) && ($ext === 'css' or $ext === 'php') ) ) {
                    if (strpos($entry, 'normalize')) {
                        if (!empty($css_files[0])) {
                            $css_files[] = $css_files[0];
                            $css_files[0] = $path_to_css.DS.$entry;
                        } else {
                            $css_files[0] = $path_to_css.DS.$entry;
                        }   
                    } else {
                        $css_files[] = $path_to_css.DS.$entry;
                    }
                    
                }
            }
            closedir($handle);
        }
        return $css_files;
    }
    */

    //scandir
    public function css_add($path_to_css = DS.'public'.DS.'css'.DS.'first') 
    {
        $css_files = array();
        if (file_exists($path_to_css)) {
            $f = scandir($path_to_css);
            foreach ($f as $file){
                if(preg_match('/\.(css)/', $file)){
                    if (strpos($file, 'normalize')) {
                        if (!empty($css_files[0])) {
                            $css_files[] = $css_files[0];
                            $css_files[0] = $path_to_css.DS.$file;
                        } else {
                            $css_files[0] = $path_to_css.DS.$file;
                        }   
                    } else {
                        $css_files[] = $path_to_css.DS.$file;
                    }
                }
            }
        }
        return $css_files;
    }
}
?>