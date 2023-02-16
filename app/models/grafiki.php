<?php
namespace App\Models;
use \App\Lib\Registry;

class Grafiki extends Home
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
	}

    public function get_data_masters()
	{
		$masters = $this->db->db->select("masters", "*", [ "OR" => [ "data_uvoln" => "",  "data_uvoln[=]" => null ] ]);
		return $masters;
	}

	public function set_name($prefix, $id)
	{
		$name = $prefix.'_' . $id;
		return $name;
	}

    public function graf()
	{
        $this->data['name'] = 'График мастера';
        $this->data['res'] = '';
        $this->data['from_graf'] = "";
        if (!empty($_POST['master']) and !isset($_POST['daytime']) and !isset($_POST['date']) and !isset($_POST['deldate']) and !isset($_POST['deltime'])) {
            try {
            list($idd, $md) = explode('#', htmlentities($_POST['master']));
            $this->data['idd'] = $idd;
            $this->data['md'] = $md;
            $tablename = $this->set_name('app_to', $idd);

            list($fn, $sn, $ln) = explode('$', $md);
            $this->data['first_name'] = $fn;
            $this->data['sec_name'] = $sn;
            $this->data['last_name'] = $ln;

            $this->data['year'] = date('Y');
            $this->data['num_month'] = date('m');
            $this->data['date'] = null;
            /*
            $id = "`".str_replace("`","``",$tablename)."`";
            $sql2 = "SELECT den, vremia FROM $id WHERE (YEAR(den) = :year AND MONTH(den) = :mon) AND (tlf_client = '' or tlf_client IS NULL)"; // mariadb
            //$sql2 = "SELECT den, vremia FROM $id WHERE ( strftime('%Y', den) = :year AND strftime('%m', den) = :mon) AND (tlf_client = '' or tlf_client IS NULL)"; // sqlite
            $stmt2 = $this->db->db->pdo->prepare($sql2);
            $stmt2->bindParam(':year', $this->data['year']);
            $stmt2->bindParam(':mon', $this->data['num_month']);
            $stmt2->execute();
            $vyh = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
            $stmt2 = null;
            */
            $svyh = $this->db->db->select($tablename, ["den", "vremia"], [
                "OR" => [
                        "tlf_client" => "",
                        "tlf_client" => null
                        ]
                ]
            );
            foreach ($svyh as $value) {
                if (date('Y', strtotime($value['den'])) == $this->data['year'] && date('m', strtotime($value['den'])) == $this->data['num_month']) {
                    $vyh[] = $value;
                }
            }

            $this->data['vyh'] = $vyh;
            } catch(\PDOException $e) {
                $this->data['res'] .= $e->getMessage();
            }
        } //смена месяцев в grafiki-grafiki-form.php
        elseif (isset($_GET['id']) and isset($_GET['md']) and isset($_GET['num_month']) and isset($_GET['year'])) {
          try {
            $this->data['gidd'] = htmlentities($_GET['id']);
            $tablename = $this->set_name('app_to', $this->data['gidd']);
            $this->data['gmd'] = htmlentities($_GET['md']);
            list($fn, $sn, $ln) = explode('$', $this->data['gmd']);
            $this->data['gfirst_name'] = $fn;
            $this->data['gsec_name'] = $sn;
            $this->data['glast_name'] = $ln;
            $this->data['gnum_month'] = $_GET['num_month'];
            $this->data['gyear'] = $_GET['year'];
            $this->data['gdate'] = $this->data['gyear'].'-'.$this->data['gnum_month'].'-01';
            $gsvyh = $this->db->db->select($tablename, ["den", "vremia"], [
              "OR" => [
                      "tlf_client" => "",
                      "tlf_client" => null
                      ]
              ]
            );
            foreach ($gsvyh as $val) {
              if (date('Y', strtotime($val['den'])) == $this->data['gyear'] && date('m', strtotime($val['den'])) == $this->data['gnum_month']) {
                  $gvyh[] = $val;
              }
            }
            $this->data['gvyh'] = (!empty($gvyh)) ? $gvyh : [];
          } catch(\PDOException $e) {
                $this->data['res'] .= $e->getMessage();
          }
        } // 3 - получаем date + times из grafiki-grafiki.php
        elseif ((isset($_POST['date']) && !empty($_POST["date"])) or (isset($_POST['daytime']) && !empty($_POST["daytime"]))
                or (isset($_POST['deldate']) && !empty($_POST["deldate"])) or (isset($_POST['deltime']) and !empty($_POST["deltime"]))) {
          if (isset($_POST['id']) && !empty($_POST["id"])) {
            $idd = htmlentities($_POST['id']);
            $tablename = $this->set_name('app_to', $idd);
          }
          //echo $idd."<br />";
          if (isset($_POST['master_name']) and !empty($_POST["master_name"])) {
            list($fn, $sn, $ln) = explode('$', htmlentities($_POST["master_name"]));
          }

          if (isset($_POST['date']) && !empty($_POST["date"])) {
            foreach ($_POST['date'] as $value) {
               $result_date[] = htmlentities($value);
            }
            //print_r($result_date);
          }

          if (isset($_POST['daytime']) && !empty($_POST["daytime"])) {
            foreach ($_POST['daytime'] as $val) {
              $result_daytime[] = htmlentities($val);
            }
            //print_r($result_daytime);
          }

          if (isset($_POST['deldate']) && !empty($_POST["deldate"])) {
            foreach ($_POST['deldate'] as $va) {
              $result_deldate[] = htmlentities($va);
            }
          }

          if (isset($_POST['deltime']) and !empty($_POST["deltime"])) {
            foreach ($_POST['deltime'] as $v) {
              $result_deltime[] = htmlentities($v);
            }
          }
          $id = "`".str_replace("`","``",$tablename)."`";
          //del from id master tables rest day or times if choice
          if (isset($result_deldate))
          {
            $sql = "DELETE FROM $id WHERE den = :den AND (tlf_client = '' OR tlf_client IS NULL)";
            $stmt = $this->db->db->pdo->prepare($sql);
            foreach ($result_deldate as $deldate)
            {
                $stmt->bindParam(':den', $deldate);
                $stmt->execute();
                if ($stmt->rowCount()) {
                    $this->data['res'] .= '<p>Выходной день "'.$deldate.'" мастера <b>'.$fn.' '.$sn.' '.$ln.'</b> удален из графика.</p>';
                } else {
                    $this->data['res'] .= '<p>ВНИМАНИЕ! Выходной день "'.$deldate.'" мастера <b>'.$fn.' '.$sn.' '.$ln.'</b> НЕ удален из графика.</p>';
                }
            }
          }
          if (isset($result_deltime))
          {
            $sql = "DELETE FROM $id WHERE den = :den AND vremia = :vr AND (tlf_client = '' OR tlf_client IS NULL)";
            $stmt = $this->db->db->pdo->prepare($sql);
            foreach ($result_deltime as $deltime)
            {
              $daytime = explode('_', $deltime);
              $tim = str_replace('-', ':', $daytime[1]);
              $stmt->bindParam(':den', $daytime[0]);
              $stmt->bindParam(':vr', $tim);
              $stmt->execute();
            }
            if ($stmt->rowCount()) {
                $this->data['res'] .= '<p>Выходные часы мастера <b>'.$fn.' '.$sn.' '.$ln.'</b> удалены из графика.</p>';
            }
          }

          //add to id master table rest days end hours
          $sql = "INSERT INTO $id (den, vremia) VALUES (?, ?)";
          $stmt = $this->db->db->pdo->prepare($sql);

          if (isset($result_date))
          {
    //        $times = array('09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00') ;
            foreach ($result_date as $value)
            {
                $val = NULL;
                $dt = [$value, $val];
                $stmt->execute($dt);
                if ($stmt->rowCount()) {
                    $this->data['res'] .= '<p>Выходной день "'.$value.'" мастера <b>'.$fn.' '.$sn.' '.$ln.'</b> внесен в график.</p>';
                } else {
                    $this->data['res'] .= '<p>ВНИМАНИЕ! Выходной день "'.$value.'" мастера <b>'.$fn.' '.$sn.' '.$ln.'</b> НЕ внесен в график.</p>';
                }
            }
          }

          if (isset($result_daytime))
          {
            foreach ($result_daytime as $va)
            {
                //$dt2[] = explode('_', $va);
                $dt2 = explode('_', $va);
                $tim = str_replace('-', ':', $dt2[1]);
                $resarray = [$dt2[0], $tim];
                $stmt->execute($resarray);

            }
            if ($stmt->rowCount()) {
                $this->data['res'] .= '<p>Выходные часы мастера <b>'.$fn.' '.$sn.' '.$ln.'</b> внесены в график.</p>';
            }
          }
          $stmt = null;
        }

		return $this->data;
	}
}
