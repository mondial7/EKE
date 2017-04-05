<?php

/**
 * Log class
 */
class EKELog {

	/**
	 * @var string
	 */
	private $log_target,
					$log_data;
	/**
	 * @var string log filename
	 */
	private $log_filename = 'log.txt';

	/**
	 * @var string log directory
	 */
	private $log_directory = CORE_DIR . '/';

	/**
	 * @var matrix array logs[]
	 */
	private $logs = [];

	function __construct() {

		// Open log file
		$this->loadLogs();

	}

	/**
	 * Load logs array
	 *
	 * @return void
	 */
	private function loadLogs(){

		$log_path = $this->log_directory . $this->log_filename;

		if (file_exists($log_path)) {

			$contents = file_get_contents($log_path);
			$this->logs = json_decode($contents, true);

		}

	}

  /**
   * Set target field
   *
   * @param string
   * @return void
	 */
	public function setTarget( $target ){

		$this->log_target = $target;

	}

  /**
   * Set data field
   *
   * @param array
   * @return void
   */
	public function setData( $data ){

		$this->log_data = json_encode( $data );

	}

  /**
   * Store target and data
	 *
   * @param string (optional)
   * @param array (optional)
   * @return boolean
   */
	public function save( $target = null, $data = null ){

		if ($target) {
			$this->setTarget($target);
		}
		if ($data) {
			$this->setData($data);
		}

    global $userLogged;

    if ($userLogged) {

        $user_logged = 1;

    } else {

        $user_logged = 0;

    }

		$log = [
						'target' => $this->log_target,
		 				'data' => $this->log_data,
						'url' => $_SERVER['REQUEST_URI'],
						'ip' => $_SERVER['REMOTE_ADDR'],
						'user_logged' => $user_logged
					 ];

    return $this->updateLogs($log);

	}

	/**
	 * Store new log on file
	 *
	 * @todo now the method is prone to cuncurrency issues and data loss
	 *
	 * @param array log
	 * @return boolean
	 */
	private function updateLogs($log){

		// Open log file
		$this->loadLogs();

		// Append new log
		$this->logs[] = $log;

		// Push new contents on the file
		return $this->pushLogs();

	}

	/**
	 * Override the file with current logs contents
	 *
	 * @return boolean
	 */
	private function pushLogs(){

		$log_path = $this->log_directory . $this->log_filename;

		return file_put_contents($log_path, json_encode($this->logs));

	}

}
