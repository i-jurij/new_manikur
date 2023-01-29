<?php
namespace App\Lib;

class Registry
{ 
	private static $_storage = array(); 
    private static $_lock = array();
    
    public static function set($key, $value)
    {   // Проверим блокировку элемента
        if(!isset(self::$_lock[$key]))
            self::$_storage[$key] = $value;
        else
            trigger_error('Variable '. $key .' is locked', E_USER_WARNING);
    }
    // Заносим ключ в черный список
    public static function lock($key)
    {
        self::$_lock[$key] = true;
    }    
    // Разблокируем элемент
    public static function unlock($key)
    {
        unset(self::$_lock[$key]);
    }
    // Этот метод не проверяет наличия значения у ключа и сразу его перезаписывает
    public static function change($key, $value)
    {
        self::$_storage[$key] = $value;
    }  
	/**
	 * Получение значения.
	 */
	public static function get($key, $default = null)
	{
		return (isset(self::$_storage[$key])) ? self::$_storage[$key] : $default;
	}
 
	/**
	 * Удаление.
	 */
	public static function remove($key)
	{
		unset(self::$_storage[$key]); 
		return true;
	}
 
	/**
	 * Очистка.
	 */
	public static function clean()
	{
		self::$_storage = array(); 
		return true;
	}
}
