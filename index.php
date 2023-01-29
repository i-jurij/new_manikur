<?php
$rootpath = __DIR__;
require_once $rootpath.DIRECTORY_SEPARATOR.'app/lib/load.php';
// Init Core Library
$init  = new App\Lib\Rout($rootpath);
/*
use App\Lib\Registry;
$content_view = Registry::get("content_view");
$data = Registry::get("data");
$template_view = Registry::get("template_view");
if (!empty($template_view)) {
    include $template_view;
}
Registry::clean();
*/
?>
