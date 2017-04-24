<?php

// Inlcude db constants (connection credentials, table names)
require_once APP_DIR . '/configs/EKEDB_config.php';

/**
 *
 * Connect to database
 *
 * Configurations are placed outsite the core,
 * in the models directory (see the require above)
 *
 */
class EKEDB {

    /**
     * Database class instance
     *
     * @var Object DB
     */
    private static $_instance = null;

    /**
     * Database connection object
     *
     * @var Object mysqli
     */
    private $db = null;

    /**
     * Connecting to database
     *
     * Create new instance of mysqli connection
     */
    private function __construct() {

        // Create a new connection to mysql database
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    }

    /**
     * Get class instance
     *
     * @return Object DB
     *
     */
    public static function getInstance() {

        if (!isset(self::$_instance)) {
            self::$_instance = new EKEDB();
        }
        return self::$_instance;

    }

    /**
     * Get database connection
     *
     * @return obj database connection
     *
     */
    public function connect() {

        // return database handler
        return $this->db;

    }

}
