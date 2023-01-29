<?php
namespace App\Models;

class Change_pass extends Adm
{
    private array $users;
	protected function db_query() 
	{
		$this->data['page_db_data'] = array(array("page_alias" => "change_pass", 
                                                "page_title" => "Пользователи",
                                                "page_meta_description" => "Изменить явки и пароли",
                                                "page_robots" => "NOINDEX, NOFOLLOW",
                                                "page_h1" => "Пользователи",
                                                "page_access" => "admin"));
        $this->users = $this->db->db->select("users", ["username", "status"]);  
	}

    public function add($path)
	{	
        //name point for menu navigation
        $this->data['name'] = 'Добавить';
        if (strlen(filter_has_var( INPUT_POST, "reg_name" )) < 26 
            && strlen(filter_has_var( INPUT_POST, "reg_password")) < 121
            && filter_has_var( INPUT_POST, "reg_status" ) ) 
        {
            
            //$names = $this->db->db->select("users", "username");  
            $name = htmlentities( strip_tags( trim($_POST["reg_name"])));
            if (preg_match("/^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$/", $name)) {
                $nameisset = false;
                foreach ($this->users as $key => $value) {
                    if (in_array($name, $value)) { $nameisset = true; break; }
                }
                if ($nameisset) {
                    $this->data['res'] = 'ERROR!<br />Username exists.<br /> Такое имя уже существует.';
                } else {
                    $pass = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);
                    $status = htmlentities( strip_tags( trim($_POST['reg_status']) ) );
                    $sql_res = $this->db->db->insert("users", [
                                    [
                                        "username" => \App\Lib\Medoo::raw(':name', [':name' => $name]),
                                        "password" => \App\Lib\Medoo::raw(':pass', [':pass' => $pass]),
                                        "email" => "foo@bar.com",
                                        "email_status" => "0",
                                        "status" => \App\Lib\Medoo::raw(':status', [':status' => $status])
                                    ]
                                ]);
                    if ($sql_res->rowCount() > 0) {
                        $this->data['res'] = 'User added.<br />
                                                Пользователь добавлен.<br /> 
                                                Username is: "'.$name.'"<br />
                                                Status is: "'.$status.'"';
                    } else {
                        $this->data['res'] = 'ERROR!<br />Data NOT INSERT to database.';
                    }
                }
            } else {
                $this->data['res'] = 'ERROR!<br />Username is not valid.';
            }
        } else {
            $file = APPROOT.DS.'view'.DS.'registration.php';
            if (is_readable($file)) {
                $this->data['res'] = 'Save the username and password.<br />'.file_get_contents($file);
            }else {
                $this->data['res'] = 'ERROR!<br />Check for the file '.$file.' and access rights to it.';
            }             
        }
        return $this->data;
	} 

    public function delete($path)
	{	
        //name point for menu navigation
        $this->data['name'] = 'Удалить';
        if ( filter_has_var( INPUT_POST, "delete" ) ) 
        {
            $this->data['res'] = '';
            if (!empty($_POST['delete']) && is_array($_POST['delete'])) {
                foreach ($this->users as $user) {
                    if (in_array('admin', $user)) {
                        $admins[] = $user['username'];
                    }
                }

                foreach ($_POST['delete'] as $name) {
                   $postdel[] = htmlentities( strip_tags( trim($name)));
                }

                if ( !empty(array_diff($admins, $postdel)) ) {
                    $sql_res = $this->db->db->delete("users", array(
                        "username" => $postdel)
                    );
                    $del_user = '"'.implode('", "', $_POST['delete']).'"';
                    if ($sql_res->rowCount() > 0) {
                        $this->data['res'] = 'Users '.$del_user.'<br /> has been deleted from database.';
                    } else {
                        $this->data['res'] = 'ERROR!<br /> The data has NOT been DELETED from database.<br />'.$this->db->db->error;
                    }
                }
                else {
                    $this->data['res'] = "ERROR! You can't delete all users with administrator rights.";
                }

            } else {
                $this->data['res'] = 'ERROR! Data from $_POST is empty or not array.';
            }
        } else {
            foreach ($this->users as $un) {
                $this->data['users_del'][] = $un['username'];
            }
        }        
        return $this->data;
	} 

    public function change($path)
	{	
        //name point for menu navigation
        $this->data['name'] = 'Изменить';
        // step 2  - enter new data for users
        if ( filter_has_var( INPUT_POST, "change" ) ) 
        {
            //get data for js in view for validation on unic name
            foreach ($this->users as $un) {
                $this->data['users'][] = $un['username'];
            }
            
            $this->data['res'] = '';
            $change = [];
            if (!empty($_POST['change']) && is_array($_POST['change'])) {
                foreach ($_POST['change'] as $name) {
                    foreach ($this->users as $user) {
                        if ($name == $user['username']) {
                            $change[$user['username']] = $user['status'];
                            break;
                        }
                    }
                }
                $this->data['res'] = $change;
            } else {
                $this->data['res'] = 'ERROR! Data from $_POST is empty or not array.';
            }
        } 
        // step 3 - sql query for update data for users into db
        elseif (    (
                        filter_has_var( INPUT_POST, "change_name" ) 
                        || filter_has_var( INPUT_POST, "change_status" ) 
                        || filter_has_var( INPUT_POST, "change_pass")
                    ) 
                    && !filter_has_var( INPUT_POST, "change" )
                ) 
        {
            $st = false;
            if (filter_has_var( INPUT_POST, "change_name" ) && !empty($_POST['change_name']) && is_array($_POST['change_name'])) {
                foreach ($_POST['change_name'] as $key => $value) {
                    $name = htmlentities( strip_tags( trim($value)));
                    
                    foreach ($this->users as $val) {
                        if (in_array($name, $val)) { 
                            $this->data['res'] = 'Username "'.$name.'" is exists.<br /> Имя "'.$name.'" уже существует.'; 
                            goto end; 
                        }
                    }
                    //if name is changed
                    if ( $key !== $value && preg_match("/^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$/", $name)) {
                        $change_user[$key]['username'] = $name;
                    }
                }
            }
            if (filter_has_var( INPUT_POST, "change_status" ) && !empty($_POST['change_status']) && is_array($_POST['change_status'])) {
                foreach ($_POST['change_status'] as $ke => $valu) {
                    $status = htmlentities( strip_tags( trim($valu)));
                    
                    foreach ($this->users as $va) {
                        if ( $status != $va['status']) { $st = true; }
                    }
                    if ($st && preg_match("/^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$/", $status)) {
                        $change_user[$ke]['status'] = $status;
                    }
                }
            }
            if (filter_has_var( INPUT_POST, "change_pass") && !empty($_POST['change_pass']) && is_array($_POST['change_pass'])) {
                foreach ($_POST['change_pass'] as $k => $v) {
                    if (!empty($v)) {
                        $change_user[$k]['password'] = password_hash($v, PASSWORD_DEFAULT);
                    }
                }
            }

            if (!empty($change_user)) {
                foreach ($change_user as $username => $userarr) {
                    $sql_res = $this->db->db->update("users", $userarr, ["username" => $username]);
                }
                if ($sql_res->rowCount() > 0) {
                    $this->data['res'] = 'DATA has been UPDATED in database.';
                } else {
                    $this->data['res'] = 'ERROR!<br /> The data has been NOT UPDATED in database.';
                }
            } else {
                $this->data['res'] = 'Data has been not entered.<br />Данные не были введены.';
            }
        } 
        else 
        { //start position - choose users
            foreach ($this->users as $un) {
                $this->data['users_change'][] = $un['username'];
            }
        }        
        end:
        return $this->data;
	}
}