<?php
namespace App\Models;
use \App\Lib\Registry;
class Price extends Home
{
    public function get_data($path)
	{
		$this->data['nav'] = Registry::get('nav');

		if ( null !== Registry::get('page_db_data') ) {
			$this->data['page_db_data'] = Registry::get('page_db_data');
		} else {
			$this->db_query();
		}
		//add css for head in template
		$this->data['css'] = $this->css_add('public'.DS.'css'.DS.'first');

		$i = 0;
		foreach ($this->db->db->select("contacts", ["contacts_type", "contacts_data"]) as $value) {
			if ($value['contacts_type'] === 'tlf' && !empty($value['contacts_data'])) {
				$this->data['tlf'.$i] = $value['contacts_data'];
				$i++;
			} elseif (!empty($value['contacts_data'])) {
				$this->data[$value['contacts_type']] = $value['contacts_data'];
			}
		}

        //get page of services list from db
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
            "price"
        ], [
            "category_id[!]" => null
        ]);

        $data['page_serv'] = $this->db->db->select("services", [
            "id",
            "page_id",
            "service_name",
            "price"
        ], [
            "category_id" => null
        ]);

        foreach ($data['service_page'] as $page) {
            foreach ($data['page_cats'] as $cat) {
                if ($cat['page_id'] === $page['page_id']) {
                    foreach ($data['page_cats_serv'] as $cat_serv) {
                        if ($cat_serv['category_id'] === $cat['id']) {
                            $this->data['serv'][$page['page_alias'].'#'.$page['page_title']][$cat['category_name']][$cat_serv['service_name']] = $cat_serv['price'];
                        }
                    }
                }
            }
            foreach ($data['page_serv'] as $serv) {
                if ($serv['page_id'] === $page['page_id']) {
                    $this->data['serv'][$page['page_title']]['page_serv'][$serv['service_name']] = $serv['price'];
                }
            }
        }
		return $this->data;
	}
}
