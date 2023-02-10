<?php
namespace App\Models;
use \App\Lib\Registry;

class Redaktor_uslug extends Home
{
    protected function db_query()
	{
		//add data for head in template
		if ($this->db->db->has($this->table, ["page_alias" => $this->page])) {
		$this->data['page_db_data'] = $this->db->db->select($this->table, "*", ["page_alias" => $this->page]);
		}
		if (!empty($this->data['page_db_data'])) {
			Registry::set('page_db_data', $this->data['page_db_data']);
		}
        $this->data['service_page'] = $this->db->db->select($this->table, [
            "page_id",
            "page_alias",
            "page_title",
        ], [
            "page_publish[!]" => null
        ]);
	}
}
