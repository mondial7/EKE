<?php

/**
 * Model class parent of all models
 */
abstract Class EKEModel {


    /**
     * Database connection
     *
     * @var Object MySQLi
     */
    private $db_instance = null;

    /**
     * ORM instance
     *
     * @var Object EKE_ORM
     */
    protected $db = null;

    /**
     * LogModel instance
     *
     * @var Object EKELog
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
            $db_instance =  EKEDB::getInstance();

            // Create a new connection
            $this->db_instance = $db_instance->connect();

            // Instantiate ORM
            require_once CORE_DIR . '/database/EKE_ORM.php';
            $this->db = new EKE_ORM($this->db_instance);

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
        return (isset($this->db_instance) && $this->db_instance->close());

    }

}
