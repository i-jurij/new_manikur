<?php

namespace App\Lib;

class Auth
{
    use \App\Lib\Traits\Reject;
    protected $session;
    protected $medoo;
    
    function __construct()
    {
        $this->session = new \App\Lib\Session();
		$this->session::start();
        $this->medoo = new \App\Lib\Db_init_sqlite;
    }

    public function login() 
    {
        if ( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) )
        {
            if (strlen(filter_has_var( INPUT_POST, "login" )) < 256 && filter_has_var( INPUT_POST, "password" ) ) 
            { 
                $args = array('login' => FILTER_SANITIZE_SPECIAL_CHARS, 'password' => FILTER_SANITIZE_SPECIAL_CHARS);
                $post_inputs = filter_input_array(INPUT_POST, $args);
                $inp_login = $post_inputs["login"];
                $inp_password = $post_inputs["password"];
                
                $db = $this->medoo->db;
                $res = $db->select("users", ["password", "status"], [
                    "username" => $inp_login
                ]);
                $password = (isset($res[0]['password'])) ? $res[0]['password'] : '';
                $status = (isset($res[0]['status'])) ? $res[0]['status'] : '';

                if (empty($password)) {
                    $this->reject_login();
                    $this->session->flash('<span style="color:red;">Неправильный логин или пароль.</span>');
                    include APPROOT.DS.'view'.DS.'login.php';
                    die;
                }
            }

            //Если логин и пароль введены правильно
            if ( isset($status) && password_verify($inp_password, $password) )
            { 
                if (password_needs_rehash($password, PASSWORD_DEFAULT)) 
                {
                    $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $db->update("users", ["password" => $newHash], ["username" => $inp_login]);
                }
                $this->session->set("user_name", $inp_login); //Записываем в сессию логин пользователя
                $this->session->set("status", password_hash($status, PASSWORD_DEFAULT)); //Записываем в сессию статус пользователя: admin, mode, user

                //disable caching
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Cache-Control: post-check=0, pre-check=0', FALSE);
                header('Pragma: no-cache');
                //load current page
                header('Location: '.CURRENT_PAGE_LOCATION);
                die;
            }

            if ( filter_has_var( INPUT_POST, "password" ) && $this->check_auth() === false ) {
                $this->reject_login();
                $this->session->flash('<span style="color:red;">Неправильный логин или пароль.</span>');
                include APPROOT.DS.'view'.DS.'login.php';
                die;
            }

            include APPROOT.DS.'view'.DS.'login.php';
            die;
        }
        else {
            header('HTTP/1.0 403 Forbidden');
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
            echo '<html xmlns="http://www.w3.org/1999/xhtml">';
            echo '<head>';
            echo '<title>403 Forbidden</title>';
            echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
            echo '</head>';
            echo '<body>';
            echo '<h1 style="text-align:center">403 Forbidden</h1>';
            echo '<p style="background:#ccc;border:solid 1px #aaa;margin:30px auto;padding:20px;text-align:center;max-width:700px">';
            echo 'Вход на страницу напрямую из сети запрещен.<br />';
            echo 'Войдите, пожалуйста, с главной страницы сайта.';
            echo '</p>';
            echo '</body>';
            echo '</html>';
            //$session->destroy('counter'); $session->destroyAll();
            exit;
        }
    }
    
    public function check_auth()
    {
        return ($this->session->get('user_name') != false) ? true : false;
    }

    public function out() 
    {
        $this->session->destroyAll();
    }
}
