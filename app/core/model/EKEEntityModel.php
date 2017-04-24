<?php

/**
 * EntityModel class
 *
 * @todo add class description
 */
abstract class EKEEntityModel extends EKEModel {

    /**
     * Save current object properties status
     * Used in Entity kind models
     *
     * @var boolean
     */
    protected $valid = true;

    /**
     * Array of properties of the object
     * Instatiated in the constructor
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Clean html text input
     *
     * @param string dirty text
     * @param int quote's option
     * @param string encoding
     * @return string cleaned text
     *
     */
    protected function cleanText($text, $quote = ENT_QUOTES, $encoding = 'utf-8') {

      $text_ = $text;

      $text = htmlspecialchars(strip_tags($text), $quote, $encoding);

      if (!empty(trim($text_)) && empty(trim($text))) {

        $this->valid = false;

      }

      return $text;

    }

    /**
     * Validate a date
     *
     * @param string date
     * @return string date
     */
    protected function validateDate($date) {

        if (!strtotime($date)) {

            $this->valid = false;

        }

        return $date;

    }

    /**
     * Check if input is valid email format
     *
     * @param string
     * @return boolean
     */
    protected function parseEmail($email) {

      // ...

    }

    /**
     * Check if input is valid url format
     *
     * @param string
     * @return boolean
     */
    protected function parseUrl($url) {

      // ...

    }

    /**
     * Check if the article values are correct
     *
     * @return boolean
     */
    public function isValid() {

      return $this->valid;

    }
    
    /**
     * Load article data from array 
     *
     * @param array
     * @return Entity Object
     */
    public function loadFromArray($data) {

      foreach ($data as $key => $value) {
        
        $key = $this->toCamel($key);

        $this->{'set' . $key}($value);

      }

      return $this;

    }

    /**
     * Generate an associative array with Entity data
     *
     * @return array
     */
    public function toArray() {

      $result_ = [];

      foreach ($this->properties as $value) {
        
        $camel_value = $this->toCamel($value);

        $result_[$value] = $this->{'get' . $camel_value}();

      }

      return $result_;

    }

    /**
     * Convert string to camel case
     *
     * @param string
     * @return string
     */
    private function toCamel($str){

        $str = str_replace("_", "", $str);

        return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");

    }

}
