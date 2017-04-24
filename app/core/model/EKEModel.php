<?php

/**
 * Model class parent of all models
 */
abstract Class EKEModel {


    /**
     * Database connection
     *
     * @var Object mysqli
     */
    protected $db = null;

    /**
     * LogModel instance
     *
     * @var Object LogModel
     */
    protected $LOG = null;


    function __construct() {

        // Instantiaze LogModel
        $this->LOG = new EKELog();

    }


    function __destruct() {

    	$this->LOG = null;

    }


    /**
     * Declare a new connection to the database
     *
     * @return void
     *
     */
    protected function connectDB(){

        // Prevent to open multiple connections
        if (!isset($this->db)) {

            // Get instance from db class
            $db_instance =  DB::getInstance();

            // Create a new connection
            $this->db = $db_instance->connect();

        }

    }

    /**
     * Close db connection
     *
     * @return boolean
     *
     */
    protected function closeDB(){

        // Close database connection if exists
        return (isset($this->db) && $this->db->close());

    }

}
