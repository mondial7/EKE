<?php

class ORMTry extends EKEModel {

  public function __construct() {

    parent::__construct();

    // Declare database connection
    $this->connectDB();

  }

  public function getResult(){

    return $this->db->in('eke__account')->filter(['id','username'])->search();

  }


}
