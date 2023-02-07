<?php
namespace App\Models;
use \App\Lib\Upload;
class Redaktor_map extends Home
{
    public function go(){
        $this->data['name'] = "Сделано";
        $this->data['res'] = '';

        if ( isset($_POST['map_iframe']) || !empty($_FILES['map_img'])) {
            if (isset($_POST['map_iframe']) && $_POST['map_iframe'] != '') {
                $map_iframe = mb_substr($_POST['map_iframe'],0,600);
                
                $map = $this->db->db->update("pages", ["page_content" => $map_iframe], ["page_alias" => "map"]);
            
                if ($map->rowCount() > 0) {
                    $this->data['res'] .= "Ссылка на карту обновлена.<br />";
                } else {
                    $this->data['res'] .= "ОШИБКА! Ссылка на карту не удалось обновить.<br />";
                }
            }

            if (!empty($_FILES['map_img']) && empty($_FILES['map_img']['error'])) {
                //PROCESSING $_FILES
                $load = new Upload;
                if ($load->isset_data()) {
                    foreach ($load->files as $input => $input_array) {
                        foreach ($input_array as $key => $file) {
                            // SET the vars for class
                            if ($input === 'map_img') {
                                $load->dest_dir = IMGDIR.DS.'map';
                                $load->create_dir = true;
                                $load->tmp_dir = PUBLICROOT.DS.'tmp';
                                $load->file_size = 3*1024*1024; //3MB
                                $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                                $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                                $load->new_file_name = 'map';
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
            } else {
                $this->data['res'] .= 'Файл изображения не загружался.';
            }
            

        } else {
            $this->data['res'] .= 'Отправлена пустая форма.';
        }
        return $this->data;
    }
}
