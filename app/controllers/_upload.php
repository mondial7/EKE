<?php


/**
 * Alert a message in the parent window
 *
 * @param string
 * @param string
 * @return string : js callback
 */
function set_notify($text){
    
    return "<script>parent.notify_callback('$text');</script>";
 
}


/**
 * Rename file without loosing extension
 *
 * @param string filename
 * @param string new basename
 * @return string new filename
 */
function rename_basename($filename, $basename){

    return $basename . "." . pathinfo($filename, PATHINFO_EXTENSION);

}


/**
 * Render the picture uploaded in the parent window
 *
 * @param string
 * @param string
 * @return string : js callback
 */
function render_idea_picture($filename, $dir){

    return "<script>parent.SpIdea.uploadIdeaPictureCallback('$filename','$dir');</script>";

}


/**
 * Render the picture uploaded in the parent window
 *
 * @param string
 * @param string
 * @return string : js callback
 */
function render_picture_profile($filename, $dir){

    return "<script>parent.render_picture_callback('$filename','$dir');</script>";

}