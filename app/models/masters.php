<?php
namespace App\Models;

use \App\Lib\Upload;

class Masters extends Home
{
	use \App\Lib\Traits\Delete_files ;

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

	public function set_name($prefix, $id)
	{	
		$name = $prefix.'_' . $id;
		return $name;
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
					$tablename = $this->set_name('app_to', $master_data['id']);
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
			
			foreach ($delmas as $master) {
				$this->data['res'] .= '<p>Мастер <b>'.$master['master_name'].' '.$master['master_fam'].'</b>:</p>';
				//delete photo
				$name = $this->set_name('master_photo', $master['id']);
				$path = IMGDIR.DS.'masters'.DS;
				$basename = find_by_filename($path, $name);
				if ($basename === false) {
					$this->data['res'] .= 'Фото мастера не найдено для удаления.<br />';
				} else {
					if ($this->del_file($path.$basename) === true) {
						$this->data['res'] .= 'Фото мастера удалено.<br />';
					} else {
						$this->data['res'] .= $this->del_file($path.$basename).'<br />';
					}
				}
				//del from table masters in db
				$del = $this->db->db->delete("masters", ["id" => $master['id']]);
				if ($del->rowCount() > 0) {
					$this->data['res'] .= 'Данные из таблицы "masters" удалены.<br />';
				} else {
					$this->data['res'] = 'Внимание! Данные из таблицы "masters" не удалены.<br />';
				}
				//del appointment table "appto_nametlf"
				$table = $this->set_name('app_to', $master['id']); 
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
		if (!empty($_POST['master'])) {
			// change form
			$id = test_input($_POST['master']);
			$this->data['change'] = $this->db->db->get("masters", "*", ["id" => $id]);
		} elseif (!empty($_POST['master_f']) or !empty($_POST['master_pn']) or !empty($_POST['master_spec']) or !empty($_POST['data_uvoln'])) {
			$this->data['res'] = '';
			//запись в бд и вывод инфо о внесении изменений
			//меняем фамилию, если изменилась
			if (isset($_POST['master_f']) and $_POST['master_f'] != '') {
				//echo "Обновлено строк: $affectedRowsNumber";
				$mf = test_input($_POST['master_f']);
				$id = test_input($_POST['m_id']);
				$res = $this->db->db->update("masters", ["master_fam" => $mf], ["id" => $id]);
				if ($res->rowCount() > 0) {
					$this->data['res'] .= '<p>Фамилия изменена на' . '<br /> ' . $mf. '</p>';
				} else {
					$this->data['res'] .= '<p>'.$this->db->db->error.'.</p>';
				}
			}
			
			//меняем номер, если изменился
			if (isset($_POST['master_pn']) and $_POST['master_pn'] != '') {
				$mpn = test_input($_POST['master_pn']);
				$id = test_input($_POST['m_id']);
				$res = $this->db->db->update("masters", ["master_phone_number" => $mpn], ["id" => $id]);
				if ($res->rowCount() > 0) {
					$this->data['res'] .= '<p>Номер изменен на' . '<br /> ' . $mpn. '</p>';
				} else {
					$this->data['res'] .= '<p>'.$this->db->db->error.'.</p>';
				}
			}
			//меняем специальность, если изменилась
			if (isset($_POST['master_spec']) and $_POST['master_spec'] != '') {
				$spec = test_input($_POST['master_spec']);
				$id = test_input($_POST['m_id']);
				$res = $this->db->db->update("masters", ["spec" => $spec], ["id" => $id]);
				if ($res->rowCount() > 0) {
					$this->data['res'] .= '<p>Специальность изменена на' . '<br /> ' . $spec. '</p>';
				} else {
					$this->data['res'] .= '<p>'.$this->db->db->error.'.</p>';
				}
			}
			//добавляем дату увольнения
			if (isset($_POST['data_uvoln']) and $_POST['data_uvoln'] != '') {
				$du = test_input($_POST['data_uvoln']);
				$id = test_input($_POST['m_id']);
				$res = $this->db->db->update("masters", ["data_uvoln" => $du], ["id" => $id]);
				if ($res->rowCount() > 0) {
					$this->data['res'] .= '<p>Дата увольнения: ' . '<br /> ' . $du. '</p>';
				} else {
					$this->data['res'] .= '<p>'.$this->db->db->error.'.</p>';
				}
			}
		} else {
			//$this->data['master'] = $this->db->db->select("masters", "*");
			$this->data['master'] = $this->get_data_masters();
			$this->data['uv_master'] = $this->get_data_uvoleny_masters();
		}
		return $this->data;
	}

    public function change_photo($path)
	{			
		$this->data['name'] = 'Изменить фото';
        //name point for menu navigation
		if (!empty($_POST['change_photo'])) {
			$this->data['res'] = '';
			list($master_fam, $id) = explode('_',  test_input($_POST['change_photo']));
			$photoname = $this->set_name('master_photo', $id);
			//PROCESSING $_FILES
			$load = new Upload;
			if ($load->isset_data()) {
				foreach ($load->files as $input => $input_array) {					
					foreach ($input_array as $key => $file) {
						// SET the vars for class
						if ($input === 'photom') {
							$load->dest_dir = IMGDIR.DS.'masters';
							$load->create_dir = true;
							$load->tmp_dir = PUBLICROOT.DS.'tmp';
							$load->file_size = 1*1000*1024; //1MB
							$load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
							$load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
							$load->new_file_name = $photoname;
							$load->processing = ['resizeToBestFit' => ['640', '480']];
							$load->replace_old_file = true;
						}
						// PROCESSING DATA
						if ($load->execute($input_array, $key, $file)) { 
							if (!empty($load->message)) { $this->data['res'] .= $load->message; }
						} else { 
							if (!empty($load->error)) { $this->data['res'] .= $load->error; } 
							continue; 
						}
						//CLEAR TMP FOLDER
						if (!$load->del_files_in_dir($load->tmp_dir)) { 
							if (!empty($load->error)) { $this->data['res'] .= $load->error; } 
						}
					}
				}
			}
		} elseif (!empty($_POST['master'])) { 
			//step 2 - form for change photo with input name = "change_photo"
			$this->data['change_photo'] = test_input($_POST['master']);
		} else {
			//step 1 - data for form for choose master with input name = "change_photo_form"
			$this->data['choose_master'] = $this->get_data_masters();
		}
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
				$this->data['res'] = '<p>Мастер снова работает (по крайней мере на сайте).</p> Сегодняшняя дата внесена как дата приема.';
			} else {
				$this->data['res'] = 'Внимание! Изменения не внесены в таблицу "masters".';
			}
		} else {
			// out form with list of dismissed masters
			$data = $this->get_data_uvoleny_masters();
			if (empty($data)) {
				$this->data['uv_mastera'] = "Данных об уволенных мастерах нет.";
			} else {
				$this->data['uv_mastera'] = $data;
			}
		}
		
		return $this->data;
	}
}
