<?php

/**
 * @todo add class description
 */
abstract Class EKEApiController {

    /**
     * Response
     * Usually a json or html string
     *
     * @var string
     */
    protected $response = null;

    /**
     * Default response bad request
     *
     * @var string
     */
    protected $ERR_BAD_REQUEST = '{"error":"bad request"}';

    /**
     * Default response error
     *
     * @var string
     */
    protected $ERROR = '{"error":"abc"}';

    /**
     * Default response success
     *
     * @var string
     */
    protected $STATUS_OK = '{"status":"OK"}';

    /**
     * Execute api logic
     * It is supposed to be overriden by any child class
     *
     * @return void
     */
    protected function run() {

      $this->response = "";
      return $this;

    }

    /**
     * Format error message as json
     *
     * @param string
     * @return string
     */
    protected function error($msg) {

      return "{\"error\":\"$msg\"}";

    }

    /**
     * Print response
     */
    public function answer() {

      echo $this->response;

    }

}
