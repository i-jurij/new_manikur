<?php
namespace App\Models;

class Adm extends Home
{
      use \App\Lib\Traits\Clear_logs;

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
            if (self::clear_logs(ROOT.DS.'log', 40)) {
                  $this->data['res'] = "Логи очищены";
            } else {
                  $this->data['res'] = "Ошибка! Логи не очищены";
            }
            return $this->data;
      }
}
