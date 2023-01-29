<?php
namespace App\Controllers;

use App\Lib\Registry;

class Home
{
	protected $param = [];
    protected $model;
	protected $view;
	protected $table = "pages";
	
	function __construct()
	{
		$this->view = new \App\Lib\View();
	}

	public function index($path = [])
    {		
		$arr = explode('\\', static::class);
		$class = array_pop($arr);
		$full_name_class = '\App\Models\\'.$class;
		if (class_exists($full_name_class)) {
			$this->model = new $full_name_class($this->table, strtolower($class));//parameters - tables and page for db query
			if (!empty($path[0]) && method_exists($this->model, $path[0])) {
				$method = $path[0];
				$nav = \App\Lib\Registry::get('nav');
				array_push($nav, array_shift($path));
				\App\Lib\Registry::set('nav', $nav);
				//$data = ($this->model)->$method($path);
				$data = array_merge($this->model->get_data($path), $this->model->$method($path));
			} else {
				$data = $this->model->get_data($path);
			}
		}
		$this->view->generate(APPROOT.DS.'view/'.mb_strtolower($class).'.php', $data);
    }
}