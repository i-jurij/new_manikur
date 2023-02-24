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

            $logs = filesindir(ROOT.DS.'log');
            if (!empty($logs)) {
                foreach ($logs as $file) {
                        $f = pathinfo($file, PATHINFO_FILENAME);
                        if (filesize($file) > 10*1024*1024) {
                              $this->data['logs'][] = "Файл $f больше 10МБ.";
                        } else {
                              $f .= ' - ' . human_filesize(filesize($file), $decimals = 2) .'B';
                              $this->data['logs'][] = $f.'#'.$file;
                        }
                  }
            }

            if (!empty($_POST['log']) && is_string($_POST['log'])) {
                  if (self::clear_file(test_input($_POST['log']), 40)) {
                        $this->data['res'] = "Файл очищен.";
                  } else {
                        $this->data['res'] = "Файл не очищены или не созданы.";
                  }
            }

            return $this->data;
      }
}
