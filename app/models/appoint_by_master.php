<?php
namespace App\Models;
use \App\Lib\Registry;
class Appoint_by_master extends Home
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
            $key = $master['master_name'] . ' ' . $master['sec_name'] . ' ' . $master['master_fam'] . '<br />'. phone_number_view($master['master_phone_number']);
            $app[$key] = $this->get_data_app($master['id']);
        }
        foreach ($app as $master => $value) {
            foreach ($value as $val) {
                list($year, $month, $day) = explode('-', $val['den']);
                $date =  $day.'.'.$month.'.'.$year.', '.$val['denned'];
                $time = $val['vremia'];
                $name = ($val['name_client'] != '&hellip;') ? $val['name_client'].', ' : '';
                $this->data['app'][$master][$date][$time] = $val['usluga'].' руб.<br />'.$name.$val['tlf_client'];
            }
        }
    }

    public function get_data_masters()
    {
      $masters = $this->db->db->select("masters", ["id", "master_name", "sec_name", "master_fam", "master_phone_number"], [ "OR" => [ "data_uvoln" => "",  "data_uvoln[=]" => null ] ]);
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
            "AND" => [
                "OR" => [
                    "tlf_client[!]" => "",
                    "tlf_client[!]" => null
                ],
                "den[>]" => date('Y-m-d')
            ],
            "ORDER" => [
                "den" => "ASC",
                "vremia" => "ASC"
            ]
        ]);
        return $app;
    }


}
