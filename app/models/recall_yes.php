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

        $this->data['res'] = '';

        $table = "client_phone_numbers";
        try
        {
        //delete rows older than 1 month
        $time = date("Y-m-d H:i:s", strtotime("-1 month"));
        $this->db->db->delete("client_phone_numbers", [ "date_time[<]" => $time ]);

        //vybor otvetov na zapros perezvonit
        $sql = "SELECT * FROM $table WHERE recall = 1 ORDER BY date_time DESC";
        $result = $this->db->db->pdo->query($sql);
            while($row = $result->fetch()){
            $this->data['res'] .= '<article class="adm_recall_article ">
                                        <div class="">' . $row["date_time"] . '</div>
                                        <div class="">
                                            <p>' . $row["phone_number"] . '</p>
                                            <p>' . $row["name"] . '</p>
                                            <p>' . $row["send"]  . '</p>
                                        </div>
                                    </article>'
                                    . PHP_EOL;
            }
        $npdo = null;
        }
        catch (\PDOException $e)
        {
            $this->data['res'] .= '<article class="adm_recall_article ">Connection failed: ' . $e->getMessage() . '</article><br />';
        }

	}
}
