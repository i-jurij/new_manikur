<?php
namespace App\Models;
use \App\Lib\Registry;
use \App\Lib\Upload;

class Redaktor_uslug extends Home
{
    use \App\Lib\Traits\Delete_files;

    protected function db_query()
	{
		//add data for head in template
		if ($this->db->db->has($this->table, ["page_alias" => $this->page])) {
		$this->data['page_db_data'] = $this->db->db->select($this->table, "*", ["page_alias" => $this->page]);
		}
		if (!empty($this->data['page_db_data'])) {
			Registry::set('page_db_data', $this->data['page_db_data']);
		}
        $this->data['service_page'] = $this->db->db->select($this->table, [
            "page_id",
            "page_alias",
            "page_title",
        ], [
            "page_publish[!]" => null
        ]);
	}

    public function go() {
        if ( !empty($_POST['page_for_edit']) && !empty($_POST['action']) ) {
            $this->data['page_id'] = test_input($_POST['page_for_edit']);
            $this->data['action'] = test_input($_POST['action']);
            if ($this->data['action'] === 'cats_add') {
                $this->data['name'] = "Добавить категории";
            } elseif ($this->data['action'] === 'serv_add') {
                $this->data['name'] = "Добавить услуги";
            } elseif ($this->data['action'] === 'cats_del') {
                $this->data['name'] = "Удалить категории";
            } elseif ($this->data['action'] === 'serv_del') {
                $this->data['name'] = "Удалить услуги";
            }

            $this->data['page_cats'] = $this->db->db->select("serv_categories", [
                "id",
                "category_name",
                "category_img"
            ], [
                "page_id" => $this->data['page_id']
            ]);

            $this->data['page_cats_serv'] = $this->db->db->select("services", [
                "id",
                "category_id",
                "service_name"
            ], [
                "page_id" => $this->data['page_id'],
                "category_id[!]" => null
            ]);

            $this->data['page_serv'] = $this->db->db->select("services", [
                "id",
                "service_name",
                "service_img"
            ], [
                "page_id" => $this->data['page_id'],
                "category_id" => null
            ]);

        } elseif (!empty($_POST['cats_name'])) {
            $this->data['name'] = "Добавить категории";
            $this->data['res'] = '';
            $post = array_map('test_input', $_POST['cats_name']);
            if (!empty($_POST['page_id'])) {
                $page_id = test_input($_POST['page_id']);
                //PROCESSING $_FILES
                $load = new Upload;
                if ($load->isset_data()) {
                    foreach ($load->files as $input => $input_array) {
                        foreach ($input_array as $key => $file) {
                            // SET the vars for class
                            if ($input === 'cats_img') {
                                $load->default_vars();
                                $load->create_dir = true; // let create dest dir if not exists
                                $load->dest_dir = IMGDIR.DS.'categories'.DS.$page_id;
                                $load->tmp_dir = PUBLICROOT.DS.'tmp';
                                $load->file_size = 3*1024*1024; //3MB
                                $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                                $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                                $load->new_file_name = $post[$key];
                                $load->processing = ['resizeToBestFit' => ['1240', '1024']];
                                $load->replace_old_file = true;
                            }
                            // PROCESSING DATA
                            if ($load->execute($input_array, $key, $file)) {
                                if (!empty($load->message)) { $this->data['res'] .= $load->message; }
                                // sql insert
                                $cat_name = $post[$key];
                                $cat_img = 'categories'.DS.$page_id.DS.$load->name.'.jpg';
                                $iscat = $this->db->db->has("serv_categories", [
                                    "AND" => [
                                        "OR" => [
                                            "category_name" => $cat_name,
                                            "category_img" => $cat_img
                                        ],
                                        "page_id" => $page_id
                                    ]
                                ]);
                                if ($iscat) {
                                    $this->data['res'] .= 'Категория с таким именем "'.$cat_name.'" уже существует в базе.<br />';
                                } else {
                                    $sql = $this->db->db->insert("serv_categories", [
                                        "page_id" => $page_id,
                                        "category_img" => $cat_img,
                                        "category_name" => $cat_name
                                    ]);
                                    if ($sql->rowCount() > 0) {
                                        $this->data['res'] .= 'Данные категории "'.$cat_name.'" внесены в базу.<br />';
                                    } else {
                                        $this->data['res'] .= 'Ошибка! Данные категории "'.$cat_name.'" НЕ внесены в базу.<br />';
                                    }
                                }
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
                } else {
                    $this->data['res'] .= 'Фото для загрузки не были выбраны.<br />';
                }
            } else {
                $this->data['res'] = "Не выбрана страница для редактирования.";
            }
        } elseif (!empty($_POST['serv_name'])) {

        }  elseif (!empty($_POST['cat_del'])) {
            $this->data['name'] = "Удалить категории";
            $this->data['res'] = '';
            foreach ($_POST['cat_del'] as $value) {
                if (!empty($value)) {
                    $ar = explode('#', test_input($value));
                    $id_ar[] = $ar[0];
                    // del img
                    if (self::del_file(IMGDIR.DS.$ar[1]) === true) {
                        $this->data['res'] .= 'Изображение "'.$ar[1].'" было удалено.<br />';
                    } else {
                        $this->data['res'] .= self::del_file(IMGDIR.DS.$ar[1]).'<br />';
                    }
                    // sql del
                    $sql = $this->db->db->delete("serv_categories", ["id" => $ar[0]]);
                    if ($sql->rowCount() > 0 ) {
                        $this->data['res'] .= 'Данные категории "'.$ar[2].'" удалены из базы.<br />';
                    } else {
                        $this->data['res'] .= 'Данные категории "'.$ar[2].'" НЕ удалены или не существуют в базе.<br />';
                    }
                } else {
                    $this->data['res'] .= "Пустые входные данные.<br />";
                }
            }

        } elseif (!empty($_POST['serv_del'])) {

        } else {

        }
        return $this->data;
    }
}
