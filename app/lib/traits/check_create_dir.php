<?php
namespace App\Lib\Traits;

trait Check_create_dir
{
    function check_or_create_dir($dir, $permissions, $create) {
        if (file_exists($dir)) {
            if (is_dir($dir)) {
                if ( !is_writable($dir) && !chmod($dir, $permissions) ) {
                    $mes = 'ERROR! Cannot change the mode of dir "'.$dir.'".';
                } else {
                    $mes = true;
                }
            } else {
                $mes = 'ERROR! "'.$dir.'" is file.';
            }
        } else {
            # create dir if $create_dir = true or message - dir not exists
            if ($create) {
                if (mkdir($dir, $permissions, true)) {
                    $mes = true;
                } else {
                    $mes = 'ERROR! Failed to create directory "'.$dir.'".';
                }
            } else {
                $mes = 'ERROR! "'.$dir.'" not exists and $create = false.';
            }
        }
        return $mes;
    }
}
?>