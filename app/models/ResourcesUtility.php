<?php

/**
 * ResourcesUtility class
 *
 * Utilities for file resources
 *
 */
class ResourcesUtility extends EKEModel {

    /**
     * @var @static int
     */
    private static $img = 2,
                   $zip = 3,
                   $pdf = 4,
                   $ppoint = 5,
                   $video = 6;

    /**
     * @var @static string
     */
    private static $img_ = "img",
                   $zip_ = "zip",
                   $pdf_ = "pdf",
                   $ppoint_ = "ppoint",
                   $video_ = "video";


    /**
     * Check if file is correct as pictures
     *
     * @param string
     * @return boolean
     */
    public static function isPicture($filename){
        
        return self::isValidType($filename, self::$img);

    }

    /**
     * Check if file is correct as compressed file
     *
     * @param string
     * @return boolean
     */
    public static function isCompressed($filename){
        
        return self::isValidType($filename, self::$zip);

    }

    /**
     * Check if file is correct as pdf file
     *
     * @param string
     * @return boolean
     */
    public static function isPDF($filename){
        
        return self::isValidType($filename, self::$pdf);

    }

    /**
     * Check if file is correct as power point presentation
     *
     * @param string
     * @return boolean
     */
    public static function isPowerPoint($filename){
        
        return self::isValidType($filename, self::$ppoint);

    }

    /**
     * Check if file is a video
     *
     * @param string
     * @return boolean
     */
    public static function isVideo($filename){
        
        return self::isValidType($filename, self::$video);

    }

    /**
     * Extract file MIME
     *
     * @param string
     * @return string
     */
    public static function fileMime($filename){

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        
        $real_mime = finfo_file($finfo, $filename);
        
        finfo_close($finfo);

        return $real_mime;

    }

    /**
     * Extract file extension
     *
     * @param string
     * @param string
     */
    public static function fileExt($filename){

        return pathinfo($filename, PATHINFO_EXTENSION);

    }

    /**
     * Checks for extenstions and mime-type
     *
     * @todo add check for file extension
     *
     * @param string filename|filepath
     * @param int|string type
     * @return boolean
     */
    private static function isValidType($filename, $type){

        switch ($type) {

            case self::$img:
            case self::$img_:
                
                $exts = ["jpg","jpeg","png","gif","JPEG","PNG","GIF","JPG","JFIF","jfif","svg"];
                $mimes = ["image/jpeg", "image/x-citrix-jpeg",
                          "image/png", "image/x-citrix-png", "image/x-png",
                          "image/gif", "image/pjpeg",
                          "image/svg+xml", "text/plain"];

                break;

            case self::$zip:
            case self::$zip_:
                

                $exts = ["zip","rar","7z"];
                $mimes = ["application/x-compressed", "application/x-zip-compressed", 
                          "application/zip", "multipart/x-zip", "application/x-rar-compressed",
                          "application/x-7z-compressed"];

                break;
            
            case self::$pdf:
            case self::$pdf_:
                

                $exts = ["pdf"];
                $mimes = ["application/pdf"];

                break;

            case self::$ppoint:
            case self::$ppoint_:
                

                $exts = ["ppt","pptx","pptm"];
                $mimes = ["application/mspowerpoint", "application/powerpoint", 
                          "application/vnd.ms-powerpoint", "application/x-mspowerpoint",
                          "application/vnd.openxmlformats-officedocument.presentationml.presentation",
                          "application/vnd.ms-powerpoint.presentation.macroenabled.12"];

                break;

            case self::$video:
            case self::$video_:
                

                $exts = ["mp4","mov","avi"];
                $mimes = ["video/mp4","video/quicktime","video/x-msvideo"];

                break;
            
            default:
                
                $exts = [];
                $mimes = [];

                break;
        
        }

        $ext_ = self::fileExt($filename);
        $mime_ = self::fileMime($filename);

        /**
         * @todo add check to file extension (while uploading)
         *
         * Example of how not to make it: pathinfo is returning 'tmp'
         * in_array(self::fileExt($filename), $exts)
         *
         * Currently patched allowing extension 'tmp'
         */        
        //return (($ext_ == 'tmp' || in_array($ext_, $exts)) && in_array($mime_, $mimes));
        return in_array($mime_, $mimes);

    }

    /**
     * Print out a file
     *
     * @param string file path
     * @param string type
     */
    public static function showFile($filepath, $filetype) {

        if (file_exists($filepath)) {

            // show file according to the type of resources
            if (self::isValidType($filepath, $filetype)) {

                if (isset($_SERVER['HTTP_RANGE'])) {

                    self::outputRange($filepath);

                } else {
                
                    self::outputFile($filepath);
                
                }

            } else {

                exit('Something went wrong :( ');

            }

        } else {

            exit('File not found.');

        }

    }


    /**
     * Output file data
     *
     * @param string file path
     * @return void
     */
    private static function outputFile($file) {

        header('Content-type: ' . self::fileMime($file));
        header('Content-Length: ' . filesize($file)); 
        header('Content-Disposition: inline; filename="' . basename($file) . '"'); 
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes'); 

        @readfile($file);

    }


    /**
     * Output a range of data of the file
     * used to stream video
     * ref: http://stackoverflow.com/a/15798140/5124589
     * 
     * @param string filepath
     * @return void
     */
    private static function outputRange($file) {

        /**
         * Better solution would have been just
         *
         * header("X-Sendfile: $file");
         *
         * see reference above
         */


        $size = filesize($file);

        $fm = @fopen($file,'rb');
 
        if (!$fm) {
        
          // You can also redirect here
          header ("HTTP/1.0 404 Not Found");
          die();
        
        }

        $begin = 0;
        $end = $size;

        if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
        
            $begin = intval($matches[0]);
    
            if (!empty($matches[1])) {
                $end = intval($matches[1]);
            }
    
        }
    
        if ($begin > 0 || $end < $size) {
            header('HTTP/1.0 206 Partial Content');
        } else {
            header('HTTP/1.0 200 OK');
        }

        header("Content-Type: " . self::fileMime($file));
        
        header("Accept-Ranges: bytes");
        header("Content-Length:" . ($end - $begin));
        header("Content-Disposition: inline;");
        header("Content-Range: bytes " . (($begin - $end) / $size));
        header("Content-Transfer-Encoding: binary\n");
        header("Connection: close");

        $cur = $begin;
        fseek($fm, $begin, 0);

        while (!feof($fm) && $cur < $end && (connection_status() == 0)) { 
            echo fread($fm, min(1024*16, $end-$cur));
            $cur += 1024*16;
            usleep(1000);
        }

    }

}