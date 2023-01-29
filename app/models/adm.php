<?php
namespace App\Models;

class Adm extends Home
{
	protected function db_query() 
	{
		$this->data['page_db_data'] = [
            ["page_alias" => "adm",
            "page_title" => "Управление сайтом",
            "page_meta_description" => "Управление сайтом",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Управление сайтом",
            "page_access" => "user"
            ]];
	}
}
