<?php
namespace App\Models;

use App\Lib\Sql_col_names;
use App\Lib\Upload;

class Create_delete_page extends Adm
{
    use \App\Lib\Traits\Delete_files;
    use \App\Lib\Traits\File_find;

	protected function db_query() 
	{
		$this->data['page_db_data'] = array(array("page_alias" => "create_delete_page", 
                                                "page_title" => "Страницы",
                                                "page_meta_description" => "Создать или удалить страницу",
                                                "page_robots" => "NOINDEX, NOFOLLOW",
                                                "page_h1" => "Страницы",
                                                "page_access" => "admin"));
	}

    public function create()
	{	
        //VALIDATE PAGE DATA for SQL DB
        $this->data['name'] = 'Создать';
        if (filter_has_var(INPUT_POST, 'page_alias') && !empty($_POST['page_alias'])) {
            $this->data['res'] = null;

            foreach ($_POST as $key => $value) {
                if ( $key === 'page_alias') {
                    if (!preg_match("/^[a-zA-Zа-яА-ЯёЁ0-9-_]{1,100}$/", $value)) {
                        $this->data['res'] .= 'ERROR!<br />Page_alias "'.$value.'" не соответствует шаблону.<br />';
                        $end = true;
                    }
                    $aliases = $this->db->db->select($this->table, "page_alias");
                    if (in_array($value, $aliases)) {
                        $this->data['res'] .= 'ERROR!<br />Page_alias "'.$value.'" уже существует. Придумайте другой.<br />';
                        $end = true;
                    } 
                }

                if (($key === 'page_templates' || $key === 'page_title' || $key === 'page_robots' || $key === 'page_h1' ) 
                    && strlen($value) > 100 ) 
                {
                    $this->data['res'] .= 'ERROR!<br />'.$key.' "'.mb_substr($value, 0, 30).'..." слишком длинный.<br />';
                    $end = true;
                }
                if ($key !== 'MAX_FILE_SIZE') {
                    $post[$key] = (!empty($value)) ? $value: null;  
                }
            }

            if ( isset($end) && $end === true ) {
                goto end;
            }
            
            if ( isset($post) ) {
                //INSERT DATA INTO SQL TABLE
                $res = $this->db->db->insert($this->table, $post);
                if ($res->rowCount()) {
                    $this->data['res'] .= 'Page data has been inserted to db.<br />';
                } else {
                    $this->data['res'] .= 'ERROR! <br />Page DATA has NOT been INSERTED to db.<br />';
                    goto end;
                }

                //CREATE CONTROLLER, MODEL, VIEW
                $filename = $post['page_alias'].'.php';
                if (function_exists('mb_ucfirst')){
                    $classname = mb_ucfirst($post['page_alias'], 'UTF-8');
                }

                $models = [ '<?php'.PHP_EOL, 'namespace App\Models;'.PHP_EOL, 'class '.$classname.' extends Home'.PHP_EOL, '{'.PHP_EOL, '}'.PHP_EOL ];
                $view = [ '<div class="content">'.PHP_EOL, $filename.PHP_EOL, '</div>'.PHP_EOL ];
                if (!empty($post['page_admin']) && $post['page_admin'] == 1 ) {
                    $controllers = [ '<?php'.PHP_EOL, 'namespace App\Controllers;'.PHP_EOL, 'class '.$classname.' extends Adm'.PHP_EOL, '{'.PHP_EOL, '}'.PHP_EOL ];
                } else {
                    $controllers = [ '<?php'.PHP_EOL, 'namespace App\Controllers;'.PHP_EOL, 'class '.$classname.' extends Home'.PHP_EOL, '{'.PHP_EOL, '}'.PHP_EOL ];
                }
                $file_array = ['controllers', 'models', 'view'];
                foreach ($file_array as $value) {
                    if (function_exists('mb_ucfirst')){
                        $name = mb_ucfirst($value, 'UTF-8');
                        if ( substr($name, -1) === 's' ) {
                            $name = substr($name, 0, -1);
                        }     
                    }
                    if (file_exists(APPROOT.DS.$value.DS.$filename)) {
                        $this->data['res'] .= 'ERROR! <br />Dir or file "'.APPROOT.DS.$value.DS.$filename.'" exists.<br />';
                        goto end;
                    } else {
                        if ( is_dir(APPROOT.DS.$value) ) {
                            if ( !is_writable(APPROOT.DS.$value) && !chmod(APPROOT.DS.$value, 0755) ) {
                                $this->data['res'] .=  'ERROR!<br /> Cannot change the mode of dir "'.APPROOT.DS.$value.'".';
                                goto end;
                            } else {
                                if (file_put_contents(APPROOT.DS.$value.DS.$filename, $$value, LOCK_EX) === false) {
                                    $this->data['res'] .= 'ERROR!<br /> Cannot created file "'.APPROOT.DS.$value.DS.$filename.'".<br />';
                                    goto end;
                                } else {
                                    $this->data['res'] .= 'SUCCESS!<br />'.$name.' "'.$filename.'" has been created <br />'; //in dir "'.APPROOT.DS.$value.'".<br />';
                                }
                            }
                        } else {
                            $this->data['res'] .= 'ERROR! <br />"'.APPROOT.DS.$value.DS.$filename.'" is not a directory.<br />';
                            goto end;
                        }
                    }
                }
                //PROCESSING $_FILES
                $load = new Upload;
                if ($load->isset_data()) {
                    foreach ($load->files as $input => $input_array) {
                        //print_r($input_array); print '<br />';
                        print 'Input "'.$input.'":<br />';
                        
                        foreach ($input_array as $key => $file) {
                            if (!empty($file['name'])) {
                                if (mb_strlen($file['name'], 'UTF-8') < 101) {
                                    $name = $file['name'];
                                } else {
                                    $name = mb_strimwidth($file['name'], 0, 48, "...") . mb_substr($file['name'], -48, null, 'UTF-8'); 
                                }
                                print 'Name "'.$name.'":<br />';
                            }
                            // SET the vars for class
                            $load->create_dir = false; // let create dest dir if not exists
                            if ($input === 'template') {
                                $load->dest_dir = PUBLICROOT.DS.'templates'; // where upload file after postprocessing
                                $load->file_size = 3*100*1024; //300KB - size for upload files = MAX_FILE_SIZE from html
                                $load->file_mimetype = ['text/php', 'text/html', 'text/x-php', 'application/x-httpd-php', 'application/php', 'application/x-php', 'application/x-httpd-php-source' ]; // allowed mime-types for upload file
                                $load->file_ext = ['.php', 'html']; // allowed extension for upload file
                                $load->new_file_name = $post['page_alias']; // new name of upload file
                                $load->replace_old_file = false; // replace old file in dest dir with new upload file with same name
                                $load->processing = []; // method and parameters for class imageresize
                            }
                            if ($input === 'picture') {
                                $load->default_vars();
                                $load->dest_dir = PUBLICROOT.DS.'imgs/pages';
                                $load->tmp_dir = PUBLICROOT.DS.'tmp';
                                $load->file_size = 1*1000*1024; //1MB
                                $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                                $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                                $load->new_file_name = $post['page_alias'];
                                $load->processing = ['resizeToBestFit' => ['1024', '640']];
                                $load->replace_old_file = false;
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
            }
            end:
            //OUTPUT MESSAGE if isset error
            if (strpos($this->data['res'], 'RROR') or strpos($this->data['res'], 'rror')) {
                $this->data['res'] .= 'ATTENTION!<br /> If there are errors, delete page and enter all page data again<br />';
            } 
            unset($post, $aliases, $value, $key, $end, $res, $filename, $model, $controller, $view );
        } else {
            $colnames = (new Sql_col_names($this->db, $this->table))->res;
            $this->data['colname'] = $colnames;
            unset($q, $colnames );
        }
        return $this->data;
	}

    public function delete()
	{	
        $this->data['name'] = 'Удалить';
        $this->data['res'] = null;
        if (filter_has_var(INPUT_POST, 'delete_page')) {
            if (is_array($_POST['delete_page'])) {
                foreach ($_POST['delete_page'] as $value) {
                    if (!empty($value)) {
                        $postdel[] = htmlentities($value);
                    }
                }
                if (!empty($postdel)) {
                    //delete data from db
                    $res = $this->db->db->delete($this->table, ["page_alias" => $postdel]);
                    if ($res->rowCount() > 0) {
                        $this->data['res'] .= 'Pages data has been deleted from db table.<br />';
                    } else {
                        $this->data['res'] .= 'ERROR!<br /> The data has NOT been DELETED from database.<br />'.$this->db->db->error;
                    }                
                    foreach ($postdel as $val) {
                        //delete controller, model, view (except adm, home)
                        $file_array = ['controllers', 'models', 'view'];
                        foreach ($file_array as $va) {
                            $path = APPROOT.DS.$va.DS.$val.'.php';
                            if (function_exists('mb_ucfirst')){
                                $name = mb_ucfirst($va, 'UTF-8');
                                if ( substr($name, -1) === 's' ) {
                                    $name = substr($name, 0, -1);
                                }     
                            }
                            if (self::del_file($path) === true) {
                                $this->data['res'] .= $name.' "'.$value.'" has been deleted.<br />';
                            } else {
                                $this->data['res'] .= self::del_file($path).'<br />';
                            }
                        }
                        unset($path);
                        //delete page img
                        $path = PUBLICROOT.DS.'imgs'.DS.'pages'; 
                        if ( self::find_by_filename($path, $val) === false ) {
                            $this->data['res'] .= 'WARNING! Image named "'.$val.'" was not found for deletion.<br />';
                        } else {
                            $file_for_del = self::find_by_filename($path, $val);
                            if (self::del_file($file_for_del)) {
                                $this->data['res'] .= 'Image "'.$val.'" has been deleted.<br />';
                            } else {
                                $this->data['res'] .= 'ERROR!<br />Image "'.$val.'" has not been deleted.<br />';
                            }
                        }
                        //delete template (except adm_templ.php and templ.php if is the only one)
                        unset($path);
                        //delete page img
                        $path = PUBLICROOT.DS.'templates'; 
                        if ($val != 'adm_templ' || $val != 'templ') {
                            if ( self::find_by_filename($path, $val) === false ) {
                                $this->data['res'] .= 'WARNING! Template named "'.$val.'" was not found for deletion.<br />';
                            } else {
                                $file_for_del = self::find_by_filename($path, $val);
                                if (self::del_file($file_for_del)) {
                                    $this->data['res'] .= 'Template "'.$val.'" has been deleted.<br />';
                                } else {
                                    $this->data['res'] .= 'ERROR!<br />Template "'.$val.'" has not been deleted.<br />';
                                }
                            }
                        } else {
                            $this->data['res'] .= 'WARNING! We need to replace "adm_teml.php" or "templ.php", otherwise everything will break.<br />';
                        }
                    }
                } else {
                    $this->data['res'] .= 'ERROR!<br /> Empty array of pages for delete.<br />';
                }
            } else { 
                $this->data['res'] .= 'ERROR! Data is not array.<br />';
            }
            unset($value, $val, $va, $res, $path, $files, $k, $v);
        } 
        //step 1 - list of pages and templates
        else {
            $pagename = $this->db->db->select($this->table, ["page_alias", "page_title", "page_admin"]);
            //print_r($pagename);
            foreach ($pagename as $page) {
                if ($page['page_alias'] != 'home' && $page['page_alias'] != 'adm' ) {
                    if (!empty($page['page_admin'])) {
                        $this->data['adm_pagename'][] = $page;
                    } else {
                        $this->data['pagename'][] = $page;
                    }
                }
            }
            //list of files in templates dir
            $this->data['templates_list'] = files_in_dir(PUBLICROOT.DS.'templates', 'php');
        }
        return $this->data;
	}
}
