<?php
namespace App\Models;
use \App\Lib\Registry;
class Recall_no extends Home
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
        try {
            //delete rows older than 1 month
            $time = date("Y-m-d H:i:s", strtotime("-1 month"));
            $this->db->db->delete("client_phone_numbers", [ "date_time[<]" => $time ]);
            
            $sql = "SELECT * FROM `client_phone_numbers` WHERE (recall = '' or recall IS NULL) ORDER BY date_time ASC";
            $result = $this->db->db->pdo->query($sql);
              while($row = $result->fetch(\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_NEXT)){
                $date = new \DateTime($row["date_time"]);
                $this->data['res'] .= '<article class="adm_recall_article ">
                                        <div class="">' . $date->format('H:i d.m.Y') . '</div>
                                            <div class="">
                                            <p>' . $row["phone_number"] . '</p>
                                            <p>' . $row["name"] . '</p>
                                            <p>' . $row["send"]  . '</p>
                                                <p>
                                                    <label ><input type="checkbox" name="date_time[]" value="' . $row["date_time"] . '" /> Выбрать</label>
                                                </p>
                                            </div>
                                        </article>'
                                        . PHP_EOL;
              }
          //ставим галочку, если перезвонили, изменяем recall на 1 в бд
            if( isset($_POST['submit']) and isset($_POST['date_time']) and $_POST['date_time'] != '' ) {
              foreach ($_POST['date_time'] as $value) {
                $sql1 = "UPDATE `client_phone_numbers` SET recall=1 WHERE date_time = :date_time";
                $stm = $this->db->db->pdo->prepare($sql1);
                $dt = htmlentities($value);
                $stm->bindParam(':date_time', $dt, \PDO::PARAM_STR);
                $stm->execute();
              }
              header("Location: ".$_SERVER['REQUEST_URI']);
              exit;
            }
            $result = null; $stm = null;
          } catch (\PDOException $e) {
                $this->data['res'] .= '<article class="adm_recall_article ">Connection failed: ' . $e->getMessage() . '</article><br />';
          }
	}
}
