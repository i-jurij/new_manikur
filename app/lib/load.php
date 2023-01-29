<?php
// Load Config
require_once $rootpath.DIRECTORY_SEPARATOR.'app/configs/config.php';
require_once $rootpath.DIRECTORY_SEPARATOR.'app/lib/function/func.php';
//Loading Libraries
/*
spl_autoload_register(function($className){
    if (file_exists(APPROOT.DIRECTORY_SEPARATOR.'lib/'.mb_strtolower($className).'.php')) {
        require_once APPROOT.DIRECTORY_SEPARATOR.'lib/'.mb_strtolower($className).'.php';
    }
    elseif (file_exists(APPROOT.DIRECTORY_SEPARATOR.'services/'.mb_strtolower($className).'.php')) {
        require_once APPROOT.DIRECTORY_SEPARATOR.'services/'.mb_strtolower($className).'.php';
    } 
});
*/
spl_autoload_register();
