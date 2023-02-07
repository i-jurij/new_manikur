<?php
namespace App\Lib\Traits;

trait Put_content_to_file
{
    public function put($file, $content, $dir_permissions = 0755, $append = false) {
        $param = ($append) ? 'FILE_APPEND | LOCK_EX' : 'LOCK_EX';
            
        if (file_exists($file)) {
            if (is_file($file)) {
                $mes =  'ERROR! <br />File "'.$file.'" exists.<br />';
            } elseif (is_dir($file)) {
                $mes =  'ERROR! <br />"'.$file.'" is existed directory.<br />';
            }
        } else {
            $path_part = pathinfo($file);
            $dir = $path_part['dirname'];
            if ( is_dir($dir) ) {
                if ( !is_writable($dir) && !chmod($dir, $dir_permissions) ) {
                    $mes =   'ERROR!<br /> Cannot change the mode of dir "'.$dir.'".';
                } else {
                    if (file_put_contents($file, $content, $param) === false) {
                        $mes =  'ERROR!<br /> Cannot created file "'.$file.'".<br />';
                    } else {
                        $mes = true;
                    }
                }
            } else {
                $mes = 'ERROR! <br />"'.$dir.'" is not a directory.<br />';
            }
        }
        return $mes;
    }
    /**
     * @param string $file - - path to txt file
     * @param string $new_string
     * @param int $num_string - number of string for replace
     * 
     */
    function replace_string($file, $new_string, int $num_string = 0)
    {
        $array = file( $file );
        if($array)
        {
        $array[$num_string] = $new_string . "\n";
        }
        if (!is_writable($file)) {
        return false;
        }
        if (file_put_contents( $file, $array, LOCK_EX) === false) {
        return false;
        } else {
        return true;
        }
    }
}
?>