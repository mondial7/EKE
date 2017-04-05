<?php

/**
 * @todo Secure cookie with https and httponly parameters
 */
class Cookie {

    /**
     * @var string perma login key
     */
    private static $permalogin;

    /**
     * Initialize static variables
     * @return void
     */
    public static function init(){

      self::$permalogin = md5($_SERVER['REMOTE_ADDR'] . '_log');

    }

    /**
     * Add new cookie variable
     *
     * @param mixed key
     * @param mixed value
     * @param int days (optional)
     * @param string path (optional)
     * @return string cookie token
     */
    public static function add($key, $value, $days = 90, $path = "/"){

        setcookie($key, $value, time() + (86400 * $days), "/");

        return $value;

    }

    /**
     * Delete cookie
     *
     * @param string key
     * @return boolean
     */
    public static function remove($key){

        return setcookie($key, "", time() - 3600, "/");

    }

    /**
     * Key exists
     *
     * @param string key
     * @return boolean
     */
    public static function exists($key){

        return isset($_COOKIE[$key]);

    }

    /**
     * Get cookie variable
     *
     * @param string key
     * @return mixed
     */
    public static function get($key){

        // Sintax valid since Php > 7.0
        // use
        // isset($_SESSION[$key]) ? $_SESSION[$key] : null
        // for previous versions
        return $_COOKIE[$key] ?? null;

    }


    /**
     * Add permanent login cookie ("Stay logged in")
     *
     * @return sting cookie token
     */
    public static function addPermaLogin(){

        return self::add(self::$permalogin, self::generateToken(Session::get('id')), 120);

    }


    /**
     * Get permanent login cookie
     *
     * @return string
     */
    public static function getPermaLogin(){

        return self::get(self::$permalogin);

    }


    /**
     * Delete perma login cookie
     *
     * @return boolean
     */
    public static function removePermaLogin(){

        return self::remove(self::$permalogin);

    }


    /**
     * Generate a new cookie token
     *
     * the cookie is generated as the hash
     * of the account id, time and user_agent
     *
     * @param int account id
     * @return string md5 hash
     */
    private static function generateToken($id) {

      return md5( $id . time() . $_SERVER['HTTP_USER_AGENT'] );

    }

}
/**
 * Initialize the static variables
 */
Cookie::init();
