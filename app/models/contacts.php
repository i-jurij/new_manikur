<?php
namespace App\Models;
use \App\Lib\Registry;

class Contacts extends Home
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
        $this->data['cont'] = $this->db->db->select("contacts", ["id", "contacts_type", "contacts_data"]);
	} 

    public function go() {
        $this->data['name'] = 'Сделано';
        $this->data['res'] = '';
        $res = [];
        if (!empty($_POST)) {
            foreach ($_POST['contacts'] as $key => $value) {
                if (!empty($value)) {
                    $dataid = explode('plusplus', $_POST['id'][$key]);
                    $sql = $this->db->db->update("contacts", 
                        [ "contacts_data" => test_input($value) ], 
                        [ "id" => $dataid['1']]
                    );
                    if ($sql->rowCount() > 0) {
                        $this->data['res'] .= 'Контакт "'.$dataid['0'].'" изменен на "'.test_input($value).'".<br />';
                    } else {
                        $this->data['res'] .= 'Ошибка! Данные для изменения контакта "'.$dataid['0'].'" не внесены в базу.<br />';
                    }
                } else {
                    $t = true;
                }
            }

            if (!empty($_POST['contacts_value'])) {
                if ($this->db->db->has("contacts", ["contacts_data" => test_input($_POST['contacts_value'])])) {
                    $this->data['res'] .= 'Контакт "'.test_input($_POST['contacts_name']).'" => "'.test_input($_POST['contacts_value']).'" уже существует в базе данных.<br />';
                } else {
                    $sql = $this->db->db->insert("contacts", 
                    [   "contacts_type" => test_input($_POST['contacts_name']),
                        "contacts_data" => test_input($_POST['contacts_value']) ]
                    );
                    if ($sql->rowCount() > 0) {
                        $this->data['res'] .= 'Контакт "'.test_input($_POST['contacts_name']).'" => "'.test_input($_POST['contacts_value']).'" внесен в базу.<br />';
                    } else {
                        $this->data['res'] .= 'Ошибка! Данные для контакта "'.test_input($_POST['contacts_name']).'" не внесены в базу.<br />';
                    }
                }
            }

            if ($t && empty($_POST['contacts_value'])) {
                $this->data['res'] .= 'Данные для изменения или добавления контактов не передавались.';
            }
        } else {
            $this->data['res'] = 'Данные не передавались.';
        }
        return $this->data;
    }
}
