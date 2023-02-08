<?php
namespace App\Models;

class Adm extends Home
{
      use \App\Lib\Traits\Clear_file;

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
      public function clear() {
            $this->data['name'] = "Чистка логов";
            if (self::clearfile(ROOT.DS.'log', 40)) {
                  $this->data['res'] = "Логи очищены.";
            } else {
                  $this->data['res'] = "Логи не очищены или не созданы.";
            }
            return $this->data;
      }
}
