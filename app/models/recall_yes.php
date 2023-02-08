<?php
namespace App\Models;
use \App\Lib\Registry;
class Recall_yes extends Home
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

        $this->data['res'] = [];

        $table = "client_phone_numbers";
        try
        {
        //delete rows older than 1 month
        $time = date("Y-m-d H:i:s", strtotime("-1 month"));
        $this->db->db->delete("client_phone_numbers", [ "date_time[<]" => $time ]);

        //vybor otvetov na zapros perezvonit
        $sql = "SELECT * FROM $table WHERE recall = 1 ORDER BY date_time DESC";
        $result = $this->db->db->pdo->query($sql);
        if (!empty($result)) {
            $this->data['res'] = $result->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $this->data['res'] = "Журнал звонков пуст.";
        }
        $result = null;
        }
        catch (\PDOException $e)
        {
            $this->data['res'] .= '<article class="adm_recall_article ">Connection failed: ' . $e->getMessage() . '</article><br />';
        }
	}

    function clear() {
        $this->data['name'] = "Очистить журнал";
        $result = $this->db->db->delete('client_phone_numbers', ["recall" => 1]);
        if ($result->rowCount() > 0) {
            $this->data['res'] = "Журнал очищен.";
        } else {
            $this->data['res'] = "Журнал не очищен или пуст.";
        }
        return $this->data;
    }
}
