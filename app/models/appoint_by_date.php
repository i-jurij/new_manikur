<?php
namespace App\Models;
use \App\Lib\Registry;

class Appoint_by_date extends Home
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
        $this->data['masters'] = $this->get_data_masters();
        foreach ($this->data['masters'] as $master) {
            $key = $master['master_name'] . ' ' . $master['sec_name'] . ' ' . $master['master_fam'];
            $this->data['app'][$key] = $this->get_data_app($master['id']);
        }
    }

    public function get_data_masters()
    {
      $masters = $this->db->db->select("masters", "*", [ "OR" => [ "data_uvoln" => "",  "data_uvoln[=]" => null ] ]);
      return $masters;
    }

    protected function get_data_app($master_id){
        $app = $this->db->db->select("app_to_".$master_id, [
                "den",
                "denned",
                "vremia",
                "usluga",
                "name_client",
                "tlf_client"
        ], [
            "OR" => [
                "tlf_client[!]" => "",
                "tlf_client[!]" => null
            ],
            "ORDER" => [
                "vremia" => "ASC",
            ]
        ]);
        return $app;
    }
}
