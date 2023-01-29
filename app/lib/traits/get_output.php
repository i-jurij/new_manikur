<?php
namespace App\Lib\Traits;

trait Get_output 
{
    public function getOutput ($file) {
        ob_start();
        include $file;
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
      }
}
