<?php

/**
  * Upload class
  *
  * Upload files to the server
  *
  */
class Upload extends EKEModel {

    /**
     * @var string
     */
    private $dir,
            $name,
            $path,
            $temp_name;

    /**
     * @var boolean
     */
    private $replace = false;

    /**
     * Update new file path
     *
     * @return void
     */
    private function updatePath(){

        $this->path = $this->dir . $this->name;
    
    }

    /**
     * Automatically rename the file in order not to replace the existing one
     * 
     * @todo to implement
     */
    private function rename(){

        // Define the new name
        // ... [NOT YET IMPLEMENTED] ...
        $new_name = $this->name;
        // Set the renamed file name
        $this->setFileName($name_);
    
    }

    /**
     * Filename exists
     *
     * @param string
     * @return boolean
     */
    private function filename_exists($file_){
        
        foreach (scandir($this->dir) as $file) {
        
            if ($this->file_basename($file) == $this->file_basename($file_)) {

                return true;

            }
        
        }
        
        return false;

    }
    

    /**
     * Delete file based on file basename
     *
     * @param string
     * @return void
     */
    private function delete_file($file_){

        foreach (scandir($this->dir) as $file) {
        
            if ($this->file_basename($file) == $this->file_basename($file_)) {
        
                unlink($this->dir.$file);
        
            }
        
        }

    }


    /**
     * Get file basename (without extension)
     */
    private function file_basename($file){

        return basename($file, pathinfo($file, PATHINFO_EXTENSION));
    
    }


    /**
     * Set the upload directory
     *
     * @param string
     */
    public function setDir($dir_){

        $this->dir = $dir_;
        $this->updatePath();
    
    }


    /**
     * Set the file name
     *
     * @param string
     */
    public function setFileName($name_){

        $this->name = $name_;
        $this->updatePath();
    
    }


    /**
     * Set the upload directory
     *
     * @param string
     */
    public function setTemporaryName($name_){

        $this->temp_name = $name_;
    
    }


    /**
     * Set the upload directory
     *
     * @param boolean
     */
    public function setReplace($bool_){
    
        $this->replace = $bool_;
    
    }


    /**
     * Upload function to save the data on server
     *
     * @return boolean
     */
    public function store(){
        
        // Check if a file with same name already exists
        if($this->filename_exists($this->name)){

            if($this->replace){
            
                // Remove existing file
                $this->delete_file($this->name);
            
            } else {
            
                // Rename the new file name
                $this->rename();
            
            }

        }

        // Store the uploaded file
        return move_uploaded_file($this->temp_name, $this->path);
    
    }

}