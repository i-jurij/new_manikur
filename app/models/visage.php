<?php
namespace App\Models;
use \App\Lib\Registry;
class Visage extends Home
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

        $page_id = $this->data['page_db_data']['0']['page_id'];
        // get cat and serv data from db
        if ($this->db->db->has("serv_categories", ["page_id" => $page_id])) {
            $this->data['cat'] = $this->db->db->select("serv_categories", "*", ["page_id" => $page_id]);
        } else {
            $this->data['cat'] = [];
        }

        if ($this->db->db->has("services", ["page_id" => $page_id])) {
            $this->data['serv'] = $this->db->db->select("services", "*", ["page_id" => $page_id]);
            // min price for common categories
            $min_price = [];
            foreach ($this->data['cat'] as $cat) {
                $sql = "SELECT * FROM `services`
                WHERE price = ( SELECT MIN(price) FROM `services` WHERE (category_id = :cdp AND page_id = :pid))";
                $ccf = $this->db->db->pdo->prepare($sql);
                $ccf->bindParam(':cdp', $cat['id']);
                $ccf->bindParam(':pid', $page_id);
                $ccf->execute();
                if ($rccf = $ccf->fetch(\PDO::FETCH_LAZY))
                {
                    $min_price[$cat['category_name']] = $rccf->price;
                }
            }
            asort($min_price, SORT_NATURAL);
            $this->data['min_price'] = $min_price;
        } else {
            $this->data['serv'] = [];
            $this->data['min_price'] = [];
        }
	}
}
