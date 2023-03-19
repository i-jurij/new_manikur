<?php
namespace App\Models;
use \App\Lib\Registry;

class Appoint extends Home
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

		$data['service_page'] = $this->db->db->select($this->table, [
            "page_id",
            "page_alias",
            "page_title"
        ], [
            "page_publish[!]" => null
        ]);

        $data['page_cats'] = $this->db->db->select("serv_categories", [
            "id",
            "page_id",
            "category_name"
        ]);

        $data['page_cats_serv'] = $this->db->db->select("services", [
            "id",
            "page_id",
            "category_id",
            "service_name",
            "price",
            "duration"
        ], [
            "category_id[!]" => null
        ]);

        $data['page_serv'] = $this->db->db->select("services", [
            "id",
            "page_id",
            "service_name",
            "price",
            "duration"
        ], [
            "category_id" => null
        ]);

        foreach ($data['service_page'] as $page) {
            foreach ($data['page_cats'] as $cat) {
                if ($cat['page_id'] === $page["page_id"]) {
                    foreach ($data['page_cats_serv'] as $cat_serv) {
                        if ($cat_serv['category_id'] === $cat['id']) {
                            $this->data['serv'][$page["page_title"]][$cat['category_name']][$cat_serv['service_name']] = $cat_serv['price'] . '-' . $cat_serv['duration'];
                        }
                    }
                }
            }
            foreach ($data['page_serv'] as $serv) {
                if ($serv['page_id'] === $page["page_id"]) {
                    $this->data['serv'][$page["page_title"]]['page_serv'][$serv['service_name']] = $serv['price'] . '-' . $serv['duration'];
                }
            }
        }

        $this->data['masters'] = $this->db->db->select("masters", "*", [ "OR" => [ "data_uvoln" => "",  "data_uvoln[=]" => null ] ]);
        foreach ($this->data['masters'] as $key => $result_row) {
            // выбор фото по фамилии и номеру
            $dir_imgs = IMGDIR.DS.'masters';
            $fotoname = 'master_photo_'.$result_row['id'];
            $this->data['masters'][$key]['img'] = (find_by_filename($dir_imgs, $fotoname)) ? URLROOT.DS.'public'.DS.'imgs'.DS.'masters'.DS.find_by_filename($dir_imgs, $fotoname) : $img =  URLROOT.DS.'public'.DS.'imgs'.DS.'ddd.jpg';
        }
        unset($master);
	}
}
