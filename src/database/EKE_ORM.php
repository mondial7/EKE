<?php

class EKE_ORM {

	/**
	 * @var object MySQLi
	 */
	private $db = null;

	/**
	 * @var object EKEQuery
	 */
  private $query = null;

	/**
	 * @var string
	 */
	private $table;

	/**
	 * @var matrix
	 */
	private $parameters_whitelist = [];

	/**
	 * @var array [string]
	 */
	private $tables_whitelist = [];


	function __construct($db_instance) {

		// Define DB instance
		$this->db = $db_instance;

		// Load tables whitelist
		$this->loadTablesWhitelist();

		// Instantiaze the Query
		require_once CORE_DIR . '/database/EKEQuery.php';
		$this->query = new EKEQuery($this->parameters_whitelist);

	}

	/**
	 * Define the main table
	 * and return the instance
	 * for inline further operations
	 *
	 * @param string [table name]
	 * @return Object EKE_ORM
	 */
	public function in($table) {

		if ($this->validateTable($table)) {

			$this->table = $table;
			$this->query->setTable($table);

			return $this;

		}

		return null;

	}

	/**
	 * Set query limit
	 *
	 * @param int
	 * @return Object EKE_ORM
	 */
	public function limit($limit) {

		$this->query->setLimit($limit);

		return $this;

	}

	/**
	 * Validate the table
	 * Whitelist check
	 *
	 * @param string
	 * @return boolean
	 */
	private function validateTable($table) {

		return in_array($table, $this->tables_whitelist);

	}

	/**
	 * Load tables whitelist
	 *
	 * @return array [string]
	 */
	private function loadTablesWhitelist() {

		global $DB_TABLES_LIST;

		foreach ($DB_TABLES_LIST as $key => $value) {

			$this->tables_whitelist[] = $key;
			$this->parameters_whitelist[] = $value;

		}

	}

	/**
	 * Dirty query
	 *
	 * @param string query
	 * @param array parameters
	 */
	public function directQuery($query, $params) {

		return $this->query->dirty($query, $params);

	}

	/**
	 * Set correct query fields
	 *
	 * @param array
	 */
	public function filter($fields) {

		$this->query->setFields($fields);

		return $this;

	}

	/**
	 * Search among the table records - Selection queries
	 *
	 * Search among tables records with the values contained into the parameters.
	 * Options provides options to the query, like sorting, max number of records, ...
	 *
	 * @param array
	 * @param array
	 * @param array
	 * @return array
	 */
	public function search($options = null) {

		$this->query->setParameters($options);
		$this->query->setType('select');

		return $this->query->build()->run($this->db);

	}

	/**
	 * Add into the table records - Insertion queries
	 *
	 * Add into the table records with the values contained into the parameters.
	 *
	 * @param array
	 * @param array
	 * @return array
	 */
	public function add($tables, $parameters) {

		// Check that tables belongs to the accessible tables and that also the parameters are the filterable ones

	}

	/**
	 * Remove the table records - Deletion queries
	 *
	 * Remove the table records with the values contained into the parameters
	 *
	 * @param array
	 * @param array
	 * @return array
	 */
	public function remove($tables, $parameters) {

		// Check that tables belongs to the accessible tables and that also the parameters are the filterable ones

	}

}
