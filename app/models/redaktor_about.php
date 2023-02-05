<?php
namespace App\Models;
use \App\Lib\Registry;
use \App\Lib\Upload;
class Redaktor_about extends Home
{
    use \App\Lib\Traits\Delete_files;
    use \App\Lib\Traits\File_find;
    protected function db_query() 
	{
		//add data for head in template
		if ($this->db->db->has($this->table, ["page_alias" => $this->page])) {
		$this->data['page_db_data'] = $this->db->db->select($this->table, "*", ["page_alias" => $this->page]);
		}
		if (!empty($this->data['page_db_data'])) {
			Registry::set('page_db_data', $this->data['page_db_data']);
		}
        $this->data['about'] = $this->db->db->select("about", "*");
	}

    public function change() {
        //name point for menu navigation
        $this->data['name'] = 'Данные обработаны';
        $this->data['res'] = '';
        if (!empty($_POST['about_title']) and !empty($_FILES['about_img']) and !empty($_POST['about_text'])) {
            $title = [];
            $content = [];
            if (!empty($_POST['about_title']))
            {
                foreach ($_POST['about_title'] as $value)
                {
                  $title[] = mb_ucfirst(test_input($value), 'UTF-8');
                }
            }
            if (!empty($_POST['about_text']))
            {
                foreach ($_POST['about_text'] as $value)
                {
                  $content[] = mb_ucfirst(htmlspecialchars($value), 'UTF-8');
                }
            }
          
            $load = new Upload;
            if ($load->isset_data()) {
                $path_to_img = IMGDIR.DS.'about';
                foreach ($load->files as $input => $input_array) {
                    foreach ($input_array as $key => $file) {
                        $load->default_vars();
                        // SET the vars for class
                        if ($input === 'about_img') {
                            $load->dest_dir = $path_to_img;
                            $load->create_dir = true;
                            $load->tmp_dir = PUBLICROOT.DS.'tmp';
                            $load->file_size = 3*1024*1024; //3MB
                            $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                            $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                            $load->new_file_name = [ $title[$key], 'noindex']; // name will be transformed as sanitize(translit_ostslav_to_lat($title[$key]))
                            $load->processing = ['resizeToBestFit' => ['640', '480']];
                            $load->replace_old_file = true;
                        }
                        // PROCESSING DATA
                        if ($load->execute($input_array, $key, $file)) {
                            if (!empty($load->message)) { $this->data['res'] .= $load->message; }
                            //sql insert
                            $pti = URLROOT.DS.'public'.DS.'imgs'.DS.'about'.DS.sanitize(translit_ostslav_to_lat($title[$key])).'.jpg'; // to match the file name after upload
                            if ($this->db->db->has("about", ["article_title" => "$title[$key]"])) {
                                $this->data['res'] .= 'Карточка с таким названием "'.$title[$key].'" уже существует.<br />';
                            } else {
                                $res = $this->db->db->insert("about", [
                                    "article_title" => $title[$key],
                                    "article_content" => $content[$key],
                                    "article_image" => $pti
                                ]);
                                if ($res->rowCount() > 0)
                                {
                                    $this->data['res'] .= 'Карточка "'.$title[$key].'" добавлена в таблицу бд.<br />';
                                }
                                if (!empty($res->error))
                                {
                                    $this->data['res'] .= $res->error;
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
            }
        } elseif (!empty($_POST['about_del'])) {
            foreach ($_POST['about_del'] as $value)
            {
                list($id, $pathbefore) = explode('iiiii', $value);
                $path = str_replace('slashslash', '/', str_replace('punktpunkt', '.', $pathbefore));
                //print $path.'<br />';
                // del image
                if ( self::del_file(IMGDIR.DS.'about'.DS.pathinfo($path, PATHINFO_BASENAME)) === true ) {
                    $this->data['res'] .= 'Фото '.pathinfo($path, PATHINFO_BASENAME).' удалено.<br />';
                }
                else {
                    if ( self::del_file(find_by_filename(IMGDIR.DS.'about'.DS, pathinfo($path, PATHINFO_FILENAME))) === true ) {
                        $this->data['res'] .= 'Фото '.pathinfo($path, PATHINFO_BASENAME).' удалено.<br />';
                    } else {
                        $this->data['res'] .= self::del_file(find_by_filename(IMGDIR.DS.'about'.DS, pathinfo($path, PATHINFO_FILENAME))).'<br />';
                    }
                }
                $ids[] = $id;
            }
            // sql delete
            $del = $this->db->db->delete("about", ["id" => $ids]);
            if ($del->rowCount() > 0 ) {
                $this->data['res'] .= 'Данные были удалены из таблицы бд.<br />';
            } else {
                if (!empty($del->error)) { $this->data['res'] .= $del->error.'<br />'; }
            }
            
        } else {
            $this->data['res'] = 'Data is empty. Submit the form again.';
        }
        return $this->data;
    }
}
