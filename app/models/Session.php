<?php

class Session {


    /**
     * Start new session
     *
     * @return void
     */
    public static function init(){
        
        session_start();

    }

    /**
     * End session
     *
     * @return void
     */
    public static function end(){
            
        session_unset();

        // Delete session cookie
        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );

        }

        session_destroy();

    }

    /**
     * Add new session variable 
     *
     * @param mixed key
     * @param mixed value
     * @return void
     */
    public static function add($key, $value){
        
        $_SESSION[$key] = $value;

    }

    /**
     * Add multiple items
     *
     * @param array [key(mixed)=>value(mixed)]
     * @return void
     */
    public static function addArray($data){
        
        foreach ($data as $key => $value) {
            self::add($key, $value);
        }

    }

    /**
     * Key exists
     *
     * @param mixed key
     * @return boolean
     */
    public static function exists($key){
        
        return isset($_SESSION[$key]);

    }

    /**
     * Get session variable
     *
     * @param mixed key
     * @param mixed (optional) default return value
     * @return mixed
     */
    public static function get($key, $value = null){
        
        // Sintax valid since Php > 7.0
        // use 
        // isset($_SESSION[$key]) ? $_SESSION[$key] : null
        // for previous versions
        return $_SESSION[$key] ?? $value;

    }

    /**
     * Check if a value exists and is equal to given one
     *
     * @param mixed key
     * @param mixed value
     * @return boolean
     */
    public static function is($key, $value){
        
        return self::get($key) === $value;

    }

}