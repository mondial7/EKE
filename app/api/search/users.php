<?php

/**
 * Class: Users
 * /api/search/users/
 *
 */
class Users extends EKEApiController {

  /**
   * Main method, automatically run
   */
  public function run(){

    require MODELS_DIR . '/Search.php';

    if ($users = (new Search())->getUsers()) {

      $this->response = $users;

    } else {

      $this->response = $this->error('no users found');

    }

  	return $this;

  }


}
