<?php
namespace App\Models;
use \App\Lib\Registry;
class Price_edit extends Home
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
		$this->data['service_page'] = $this->db->db->select($this->table, [
            "page_id",
            "page_alias",
            "page_title"
        ], [
            "page_publish[!]" => null
        ]);
		return $this->data;
	}

    public function edit() {
        $this->data['name'] = 'Изменить расценки';
        if (!empty($_POST['page'])) {
            $arr = explode("#", test_input($_POST['page']));
            $this->data['page_id'] = $arr[0];
            $this->data['page_title'] = $arr[1];

            $data['page_cats'] = $this->db->db->select("serv_categories", [
                "id",
                "category_name"
            ], [
                "page_id" => $this->data['page_id']
            ]);

            $data['page_cats_serv'] = $this->db->db->select("services", [
                "id",
                "category_id",
                "service_name",
                "price"
            ], [
                "page_id" => $this->data['page_id'],
                "category_id[!]" => null
            ]);

            $data['page_serv'] = $this->db->db->select("services", [
                "id",
                "page_id",
                "service_name",
                "price"
            ], [
                "page_id" => $this->data['page_id'],
                "category_id" => null
            ]);

            foreach ($data['page_cats'] as $cat) {
                foreach ($data['page_cats_serv'] as $cat_serv) {
                    if ($cat_serv['category_id'] === $cat['id']) {
                        $this->data['serv'][$cat['category_name']][$cat_serv['service_name']] = $cat_serv['id'].'#'.$cat_serv['price'];
                    }
                }
            }
            foreach ($data['page_serv'] as $serv) {
                $this->data['serv']['page_serv'][$serv['service_name']] = $serv['id'].'#'.$serv['price'];
            }
        } else {
            $this->data['res'] = "Отсутствуют входные данные.";
        }
        return $this->data;
    }

    public function change() {
        $this->data['name'] = 'Изменить расценки';
        $this->data['res'] = '';
        if (!empty($_POST['serv_id'])) {
            $i = 0;
            foreach ($_POST['serv_id'] as $id => $price) {
                $re = "/^-?(?:\d+|\d*\.\d+|\d*\,\d+)$/";
                if (preg_match($re, $price)) {
                    $price_end = $price;
                } else {
                    $price_end = '';
                }
                //$this->data['res'] .= $id.' - '.$price.'<br />';
                $res = $this->db->db->update("services", [
                    "price" => $price_end
                ], [
                    "id" => test_input($id)
                ]);
                if ($res->rowCount() > 0) $i++;
            }
            if ($i = count($_POST['serv_id'])) {
                $this->data['res'] = "Все изменения внесены в базу.";
            } else {
                $this->data['res'] = "ВНИМАНИЕ! Изменения внесены в базу c ошибками.";
            }
        } else {
            $this->data['res'] = "Отсутствуют входные данные.";
        }
        return $this->data;
    }
}
