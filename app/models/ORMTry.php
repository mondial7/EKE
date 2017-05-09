<?php

class ORMTry extends EKEModel {

  public function __construct() {

    parent::__construct();

    // Declare database connection
    $this->connectDB();

    // Define Accound DB table (apply immediately filters)
    $this->account = $this->db->in('eke__account')->filter(['id','username']);

  }

  public function getResult(){

    // retrieve all the accounts
    return $this->account->search();

  }


}
