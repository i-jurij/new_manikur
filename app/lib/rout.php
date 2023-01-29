<?php
namespace App\Lib;
/**
*  App Rout class
* Parse URL and loads controller from app/controllers
* URL FORMAT may be /controller/method_for_controller_or_model/params/...?name=string&name2=string2
* if method not exists for controller - he may used as model method
* rout loaded controller and put other data from url to controllers input datas array
* where 'path' - a piece of url after /method/... before ? if exists method for controller
* or where 'path' - a piece of url after /controller/... before ? if method for controller not exists
* 'get_query' - after ?
* and may be 'post_query' - from html POST
*/
class Rout
{
    protected $controller;
    protected $method = 'index';
    protected $param = [];

    public function __construct($siterootpath)
    {
        $dbinit = '\App\Lib\\'.DBINITNAME;
        $db_create = new $dbinit;
        $db_create->create_tables();
        $url_arr = $this->url_to_arr($siterootpath);
        Registry::remove('nav');
        //if (empty($url_arr) or !file_exists(APPROOT.DS.'controllers'.DS.$url_arr[0].'.php')) 
        if (empty($url_arr) or !class_exists("\App\\Controllers\\".ucwords($url_arr[0]))) 
        {
            $contr = '\App\Controllers\Home';
            $this->controller = new $contr;
            $nav[] = 'home';
            $url_arr = [];
        }
        else 
        {
            $contr = "\App\\Controllers\\".ucwords($url_arr[0]);
            $this->controller = new $contr;
            //unset($url_arr[0]);
            $nav[] = array_shift($url_arr);
            
            if (isset($url_arr[0]) && method_exists($contr, $url_arr[0])) 
            {
                $this->method = $url_arr[0];
                //unset($url_arr[1]);
                $nav[] = array_shift($url_arr);
            }

            if (!empty($url_arr) && array_filter($url_arr, 'is_string') === $url_arr)
            {
                $this->param['path'] = $url_arr;
            }

            if (isset($params) && !empty($params)) 
            {
                $this->param = $params;
            }
        }
        Registry::set('nav', $nav);
        call_user_func_array([$this->controller,$this->method], $this->param);
    }

    public function url_to_arr($siterootpath)
    {
        if (isset($_SERVER['REQUEST_URI'])) 
        {
            $request_url = rtrim($_SERVER['REQUEST_URI'], '/');
            $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
            $request_url = strtok($request_url, '?');
            $request_url = trim($request_url, " \/");
            $res_path = explode('/', $request_url);
            //если на сервере многосайтовость - уберем из пути корневую папку сайта и переиндексируем
            $root = explode('/', $siterootpath);
            $res_path = array_values(array_diff($res_path, array(array_pop($root))));

            return $res_path;
        }
    }
}
