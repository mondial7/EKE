<?php

/**
 * PasswordUtility class
 *
 * Utilities for passwords management
 *
 */
class PasswordUtility extends EKEModel {

    /**
     * Hash a give password and 
     * return the hashed string
     *
     * @param string plain-password
     * @return string hashed-password
     */
    public static function hash($password){
        
        return password_hash($password, PASSWORD_DEFAULT);

    }

    /**
     * Match hashed password with plain text
     *
     * @param string plain-password
     * @param string hashed-password
     * @return boolean
     */
    public static function match($plain, $hash){
        
        return password_verify($plain, $hash);

    }

}