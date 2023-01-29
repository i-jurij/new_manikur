<?php
namespace App\Lib;

class View
{
	/*
	$content_view - виды отображающие контент страниц;
	$template_view - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы. Обычно заполняется в модели.
	*/
	public function generate($content_view, $data, $template_view = TEMPLATEROOT.DS.'templ.php')
	{
		if (!empty($template_view)) {
			include $template_view;
		}
	}
}