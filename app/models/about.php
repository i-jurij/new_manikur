<?php
namespace App\Models;
use \App\Lib\Registry;
class About extends Home
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
        $this->data['res'] = $this->db->db->select("about", "*");
        $this->data['masters'] = $this->db->db->select("masters", "*", [ "OR" => [ "data_uvoln" => "",  "data_uvoln[=]" => null ] ]);
        //print_r($stmt);
	}
}
