<?php
namespace App\Models;
class Masters extends Home
{
    public function get_data_masters()
	{	
		$masters = $this->db->db->select("masters", "*", [ "OR" => [ "data_uvoln" => "",  "data_uvoln[=]" => null ] ]);
		return $masters;
	}

    public function get_data_uvoleny_masters()
	{	
		$masters = $this->db->db->select("masters", "*", \App\Lib\Medoo::raw('WHERE LENGTH(<data_uvoln>) > 1'));
		return $masters;
	}

    public function add_form($path)
	{			
        $this->data['name'] = 'Добавить';
        $this->data['add_form'] = 1;
		return $this->data;
	}

    public function add($path)
	{			
        //name point for menu navigation
        $this->data['name'] = 'Добавлено';
		//добавление мастера второй шаг, если есть пост - запись в бд
		if ( !empty($_POST['master_fam']) and !empty($_POST['master_phone_number']) )
		{
			// добавление мастера начало
			$master_name = test_input($_POST["master_name"]);
			$sec_name = test_input($_POST["sec_name"]);
			$master_fam = test_input($_POST["master_fam"]);
			//$master_phone_number = test_input($_POST["master_phone_number"]);
			$master_phone_number = phone_number_to_db($_POST["master_phone_number"]);
			$spec = test_input($_POST["spec"]);
			$data_priema=date('Y-m-d') ; // this to get current date as text .
			if (empty($sec_name))
			{
			  $sec_name = "";
			}
			if (empty($data_uvoln))
			{
			  $data_uvoln = null;
			}

			//----- work with db ---------------------------------------------------------
	  
			//проверим, что такого мастера еще не было и запишем в бд
			if ($getmaster = $this->db->db->get("masters", "master_fam", ["master_phone_number" => $master_phone_number])) {
				$this->data['res'] = 'Мастер "'.$getmaster.'" с номером телефона "'.phone_number_view($master_phone_number).'" уже существует в базе данных.';
			} else {
				$sql = "INSERT INTO masters (master_name, sec_name, master_fam, master_phone_number, spec, data_priema, data_uvoln) VALUES (?, ?, ?, ?, ?, ?, ?)";
				$mstmt = $this->db->db->pdo->prepare($sql);
				// через массив передаем значения параметрам по позиции
				// $mstmt->execute(array($master_name, $sec_name, $master_fam, $master_phone_number, $spec, $data_priema, $data_uvoln));
				$mstmt->bindParam(1, $master_name, \PDO::PARAM_STR);
				$mstmt->bindParam(2, $sec_name, \PDO::PARAM_STR);
				$mstmt->bindParam(3, $master_fam, \PDO::PARAM_STR);
				$mstmt->bindParam(4, $master_phone_number, \PDO::PARAM_STR);
				$mstmt->bindParam(5, $spec, \PDO::PARAM_STR);
				$mstmt->bindParam(6, $data_priema);
				$mstmt->bindParam(7, $data_uvoln);
				$mstmt->execute();

				if ($mstmt->rowCount() > 0) {
					$this->data['res'] = '<div class="">
											<p class="centr">Мастер ' . $master_name . ' ' . $master_fam . ' добавлен</p>
											<div class="table_body" style="max-width: 50rem;">
											<div class="table_row">
												<span class="table_cell text_right">Номер телефона:</span>
												<span class="table_cell">'
												. phone_number_view($master_phone_number) .
												'</span>
											</div>

											<div class="table_row">
												<span class="table_cell text_right">Специальность:</span>
												<span class="table_cell">'
												. $spec .
											'</span>
											</div>

											</div>
										</div>' ;
					//узнаем id мастера в таблице masters
					$master_data = $this->db->db->get("masters", ["id", "master_fam"], ["master_phone_number" => $master_phone_number]);
					//создаем таблицу в бд на каждого мастера с занятыми датой временем и данные на клиента (имя, тлф)
					$tablename = 'appto_' . translit_to_lat(sanitize($master_data['master_fam'])) . '_' . $master_data['id'];
					//$date = date('Y-m-d', strtotime(date('now')));
					$this->db->db->create($tablename, [
						"ID" => [
							"INTEGER PRIMARY KEY",
							"AUTOINCREMENT",
							"NOT NULL"
						],
						"den" => [
							"TEXT"
						],
						"vremia" => [
							"VARCHAR(10)"
						],
						"denned" => [
							"VARCHAR(10)"
						],
						"usluga" => [
							"VARCHAR(300)"
						],
						"name_client" => [
							"VARCHAR(100)"
						],
						"tlf_client" => [
							"VARCHAR(30)"
						],
						"dt" => [
							"TEXT"
						],
					]);
					$this->data['res'] .= '<p class="centr">Таблица для формирования графика мастера добавлена.</p>
											<p class="centr">ОБЯЗАТЕЛЬНО ДОБАВЬТЕ ФОТО мастера в "Изменить данные".</p>';
				} else {
					$this->data['res'] = 'Ошибка! Данные не записаны в базу.';
				}
			}
		}
        //$this->data['res'] = 'Добавлено';
		return $this->data;
	}

	public function delete_form($path)
	{			
        $this->data['name'] = 'Удалить';
		$this->data['delete_form'] = '	<p class="">
										<span>Данные о мастере УДАЛЯЮТСЯ БЕЗВОЗВРАТНО!</span><br />
										<span>Не удаляйте без необходимости.</span>
									</p>
									<form action="'.URLROOT.'/masters/delete" id="delmast" method="post" class="mar_pad" >
										<p><b>Список работающих мастеров</b></p>
										<div>';
		if ($masters = $this->get_data_masters()) {
			foreach ($masters as $master) {
				$deldata = base64_encode(serialize($master));
				$this->data['delete_form'] .= '		<label class="checkbox-btn" >
												<input class="" type="checkbox" name="mastk[]" value="' . $deldata . '" />
												<span>' . $master['master_name'] . ' ' . $master['master_fam'] . '<br />' . $master['master_phone_number'] . '</span>
											</label>
										';
			}
		} else {
			$this->data['delete_form'] .= 'Данные о мастерах не получены.';
		}
		$this->data['delete_form'] .= '			</div>
										<p><b>Список уволенных мастеров</b><p>
										<div>';
		if ($uvoleny_masters = $this->get_data_uvoleny_masters()) {
			foreach ($uvoleny_masters as $uv_master) {
				$deldatau = base64_encode(serialize($uv_master));
				$this->data['delete_form'] .= '		<label class="checkbox-btn" >
												<input class="" type="checkbox" name="mastk[]" value="' . $deldatau . '" />
												<span>' . $uv_master['master_name'] . ' ' . $uv_master['master_fam'] . '<br />' . $uv_master['master_phone_number'] . '</span>
											</label>
										';
			}
		} else {
			$this->data['delete_form'] .= 'Данные об уволенных мастерах не получены.';
		}
		$this->data['delete_form'] .= '			</div>
									</form>
									<div class="margin05 ">
										<button type="submit" class="buttons" form="delmast">Удалить мастера</button>
										<button type="reset" class="buttons" form="delmast">Сбросить</button>
									</div>
							';
		return $this->data;
	}

	public function delete($path)
	{			
        //name point for menu navigation
        $this->data['name'] = 'Удалено';
		$this->data['res'] = 'Данные для удаления отсутствуют.';
		if (!empty($_POST['mastk'])) {
			$this->data['res'] = '';
			$del = array_map('base64_decode', $_POST['mastk']);
			$delmas = array_map('unserialize', $del);
			//delete photo

			//del from masters
			foreach ($delmas as $master) {
				$this->data['res'] .= '<p>Мастер <b>'.$master['master_name'].' '.$master['master_fam'].'</b>:</p>';
				$del = $this->db->db->delete("masters", ["id" => $master['id']]);
				if ($del->rowCount() > 0) {
					$this->data['res'] .= 'Данные из таблицы "masters" удалены.<br />';
				} else {
					$this->data['res'] = 'Внимание! Данные из таблицы "masters" не удалены.<br />';
				}
				//del appointment table "appto_nametlf"
				$table = 'appto_' . translit_to_lat(sanitize($master['master_fam'])).'_'.$master['id']; 
				//print $table; print '<br />';
				$drop = $this->db->db->drop($table);
				if ($drop->rowCount()) {
					$this->data['res'] .= 'Таблица записей удалена.<br />';
				} else {
					$this->data['res'] .= 'Внимание!  Таблица записей не удалена.<br />';
				}
			}
		}
		return $this->data;
	}

	public function change($path)
	{			
        //name point for menu navigation
        $this->data['name'] = 'Изменить данные';
		$this->data['masters'] = $this->db->db->select("masters", "*");
		return $this->data;
	}

    public function change_photo($path)
	{			
        //name point for menu navigation
        $this->data['name'] = 'Изменить фото';
		return $this->data;
	}

    public function uv_mastera($path)
	{			
        //name point for menu navigation
        $this->data['name'] = 'Уволенные';
		if (!empty($_POST['recover'])) {
			//del datu uvolnenija, add to data priema novoe znachenie - vosstanavlivajem na rabote
			$date = date('Y-m-d', strtotime(date('now')));
			$id = test_input($_POST['recover']);
			$res = $this->db->db->update("masters", ["data_uvoln" => null, "data_priema" => $date], ["id" => $id]);
			if ($res->rowCount()) {
				$this->data['res'] = 'Дата увольнения удалена из таблицы. Сегодняшняя дата внесена как дата приема.';
			} else {
				$this->data['res'] = 'Внимание! Изменения не внесены в таблицу "masters".';
			}
		} else {
			// out form with list of dismissed masters
			$this->data['uv_mastera'] = $this->get_data_uvoleny_masters();
		}
		
		return $this->data;
	}
}
