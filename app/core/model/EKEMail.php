<?php

/**
 * Model class handles emails
 * 
 */
class EKEMail {

    /**
     * Mail parameters
     *
     * @var string
     */
    private $object,
            $message,
            $headers;

    /**
     * Mail parameter
     *
     * @var array
     */
    private $to;

    /**
     * Addresses of mail failures
     *
     * @var array
     */
    private $missed;

    /**
     * Send the mail
     * In dirty mode the receivers are attached
     * to the email as bcc and only one email is sent.
     * Email are sent sequentially otherwise
     * 
     * @param boolean quick&dirty send mode
     * @param boolean is a group email
     * @return boolean sent status
     * 
     */
    public function send($dirty = false, $is_group = false){

        $n = count($this->to);

        if ($n<=0) {

            $sent = false;

        } else if ($is_group) {

            $to = $this->to[0];

            for ($i = 0; $i < $n; $i++) {

                $to .= ',' . $this->$to;
            
            }

            $sent = mail($to, 
                         $this->object,
                         $this->message,
                         $this->headers);
            
            if (!$sent) {

                foreach (explode(",", $to) as $address) {
                    
                    $this->addMissed($address);

                }

            }

        } else {

            if ($dirty) {

                for ($i = 0; $i < $n; $i++) {

                    $this->addHeader('Bcc: ' . $addr);
                
                }

                // reset variables
                $this->to = ['info@startuppuccino.com'];
                $n = 1;

            }

            for ($i = 0; $i < $n; $i++) { 

                $sent = mail($this->to[$i], 
                             $this->object,
                             $this->message,
                             $this->headers);
                
                if (!$sent) {

                    $this->addMissed($this->to[$i]);

                }

            }

        }

        return $sent;

    }

    /**
     * Add new receiver
     *
     * @todo add validation of mail
     *
     * @return void
     */
    public function addAddress($email){

        $this->to[] = $email;

    }

    /**
     * Append new header
     *
     * @return void
     */
    public function addHeader($header){

        if ($this->headers != '') {

            $this->headers .= "\r\n";
        
        }

        $this->headers .= $header;

    }

    /**
     * Add From Header
     *
     * @param string
     * @return void
     */
    public function setFrom($sender){

        $this->addHeader("From: " . $sender);

    }

    /**
     * Fill array of email addresses
     * of emails not sent
     * N.B. this overrides previously set parameters
     * 
     * @return void
     */
    public function setMail($to, $object='', $message='', $headers=''){

        $this->to = [$to];
        $this->object = $object;
        $this->message = $message;
        $this->headers = $headers;

    }
    
    /**
     * Set email message
     *
     * @param string
     * @return void
     */
    public function setMessage($message){

        $this->message = $message;

    }

    /**
     * Set email object
     *
     * @param string
     * @return void
     */
    public function setObject($object){

        $this->object = $object;

    }

    /**
     * Add item to list of address
     * with errors in sending the email
     *
     * @param 
     * @return void
     */
    private function addMissed($email){

        $this->missed[] = $email;

    }

    /**
     * Get the missed email addresses
     *
     * @return array
     */
    public function getMissed(){

        return $this->missed;

    }

}