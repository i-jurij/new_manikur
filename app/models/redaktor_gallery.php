<?php
namespace App\Models;
use \App\Lib\Upload;

class Redaktor_gallery extends Home
{
    use \App\Lib\Traits\Delete_files;

    public function go() {
        //name point for menu navigation
        $this->data['name'] = 'Сделано';
        $this->data['res'] = '';
        if (!empty($_FILES['gallery_add']) || !empty($_POST['gallery_del']) || !empty($_POST['photo_link'])) {
            //PROCESSING $_FILES
            $load = new Upload;
            if ($load->isset_data()) {
                foreach ($load->files as $input => $input_array) {
                    //print_r($input_array); print '<br />';
                    $this->data['res'] .= 'Input "'.$input.'":<br />';

                    foreach ($input_array as $key => $file) {
                        if (!empty($file['name'])) {
                            if (mb_strlen($file['name'], 'UTF-8') < 101) {
                                $name = $file['name'];
                            } else {
                                $name = mb_strimwidth($file['name'], 0, 48, "...") . mb_substr($file['name'], -48, null, 'UTF-8');
                            }
                            $this->data['res'] .= '<br />Name "'.$name.'":<br />';
                        }
                        // SET the vars for class
                        if ($input === 'gallery_add') {
                            $load->default_vars();
                            $load->create_dir = true; // let create dest dir if not exists
                            $load->dest_dir = PUBLICROOT.DS.'imgs/gallery';
                            $load->tmp_dir = PUBLICROOT.DS.'tmp';
                            $load->file_size = 3*1024*1024; //3MB
                            $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png'];
                            $load->file_ext = ['.jpg', '.jpeg', '.png'];
                            $load->new_file_name = '';
                            $load->processing = ['resizeToBestFit' => ['1240', '1024']];
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
            } else {
                $this->data['res'] .= 'Фото для загрузки не были выбраны.<br />';
            }
            if (!empty($_POST['gallery_del'])) {
                foreach ($_POST['gallery_del'] as $value) {
                    if (self::del_file(PUBLICROOT.DS.'imgs'.DS.'gallery'.DS.$value) === true) {
                        $this->data['res'] .= 'Фото '.$value.' удалено.<br />';
                    } else {
                        $this->data['res'] .= self::del_file($value).'<br />';
                    }
                }
            }
            if (!empty($_POST['photo_link'])) {
                $photo_link = htmlentities($_POST['photo_link']);
                $file = APPROOT.DS.'view'.DS.'gallery.php';
                if (getResponseCode($photo_link)) {
                    $new_string = '$photo_link = "'.test_input($_POST['photo_link']).'";';
                } else {
                    $this->data['res'] .= 'Введена неправильная ссылка на фотоальбом.<br />';
                }

                if (!empty($new_string) && replace_string($file, $new_string, 1)) {
                    $this->data['res'] .= 'Ссылка на фотоальбом изменена на '.$photo_link.'<br />';
                } else {
                    $this->data['res'] .= 'Ссылка на фотольбом НЕ изменена. Проверьте права доступа к файлу app/view/gallery.php.<br />';
                }
            }
        } else {
            $this->data['res'] .= 'Отправлена пустая форма.<br />';
        }
        return $this->data;
    }
}
