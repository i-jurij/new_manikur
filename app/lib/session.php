<?php
/*
* example of using a class:
* $session = new Session();
* $session->start();
* if( !$session->has('counter') ) { $session->set('counter', 0); }
* $counter = $session->get('counter');
* $session->set('counter', ++$counter);
* $response->getBody()->write("Вы посетили сайт $counter раз/a");
* // массовая установка значений
* $session->setArray(['one' => 1, 'two' => 2, 'three' => 3]);
*/
namespace App\Lib;

class Session
{
    private $cookieTime;
    // Время устаревания сессии в секундах
    static protected $_sessionTime = 30*60;

    // задаем время жизни сессионных кук
    public function __construct(string $cookieTime = '+7 days') {
        $this->cookieTime = strtotime($cookieTime);
        if (session_status() !== PHP_SESSION_ACTIVE) {
        }   
    }

    static public function start($cache = 'nocache')
    {
      // Если сессия еще не запущена
      if (session_status() !== PHP_SESSION_ACTIVE)
      {
        session_cache_limiter($cache);//nocache, public, private, private_no_expire
        //session_save_path("S:\Server\session");
        session_start();
        // Устанавливаем время отсчета для удаления сессии
        $_SESSION["deleted_time"] = time();
      }
      // Не используем слишком старые идентификаторы сессии
      if (!empty($_SESSION["deleted_time"]) && $_SESSION["deleted_time"] < (time() - static::$_sessionTime))
      {
        session_destroy();
        static::sessionRegenerateId(true);
      }
    }

    static public function sessionRegenerateId($deleteOldSession = FALSE)
    {
        // Устанавливаем время отсчета для удаления сессии
        $_SESSION["deleted_time"] = time();
        // Обновляем идентификатор сессии
        session_regenerate_id($deleteOldSession);
    }

    /**
     * Проверяем сессию на наличие в ней переменной c заданным именем
     */
    public function has($name)
    {
        static::start();
        return isset($_SESSION[$name]);
    }
    /**
     * Устанавливаем сессию с именем $name и значением $value
     * 
     * @param $name
     * @param $value
     */
    public function set($name, $value) {
        static::start();
        if ($name && $value) { $_SESSION[$name] = $value; } 
        session_write_close();
    }
    /**
     * Когда мы хотим сохранить в сессии сразу много значений - используем массив
     *
     * @param $vars
     */
    public function setArray(array $vars)
    {
        static::start();
        foreach($vars as $name => $value) {
            $this->set($name, $value);
            session_write_close();
        }
    }
    /**
     * Получаем значение сессий
     *
     * @param $name
     * @return mixed
     */
    public function get($name) {
        static::start();
        return (!empty($_SESSION[$name])) ? $_SESSION[$name] : false;
    }
    /** 
    * Если вызвать $this->flash со строковым параметром, то она сохранит эту строку в сессии, 
    * а если вызвать без параметров, то возвратит сохранённое сообщение 
    *
    * @param $message - string or null
    */
    public function flash(?string $message = null)
    {
        static::start();
        if ($message) { $_SESSION['flash'] = $message; } 
        else { if (!empty($_SESSION['flash'])) { return $_SESSION['flash']; } }
    }

    /**
     * 
     * @param $name - Уничтожаем $name
     */
    public function destroy($name) {
        static::start();
        unset($_SESSION[$name]);
        session_write_close();
    }
    /**
     * Полностью очищаем все данные пользователя
     */
    public function destroyAll() {
        static::start();
        // Если есть активная сессия, удаляем куки сессии,
        setcookie(session_name(), session_id(), time()-60*60*24);
        $_SESSION = array(); //Очищаем сессию
        session_destroy();
    }
    /**
     * Устанавливаем куки  
     *
     * @param $name
     * @param $value
     */
    public function setCookie($name, $value) {
        setcookie($name, $value, $this->cookieTime);
    }
    /**
     * Получаем куки
     *
     * @param $name
     * @return mixed
     */
    public function getCookie($name) {
        return $_COOKIE[$name];
    }
    /**
     * @param $name Удалаяем
     */
    public function removeCookie($name) {
        setcookie($name, null);
    }
}
