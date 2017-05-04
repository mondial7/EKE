<?php

// Include helpers functions
require_once $CONTROLLERS_DIR . '/_upload.php';


if (!$userLogged) { 

    exit(set_notify("Error you appear to be not logged.."));

}


// Include and Initialize Upload and Account Functions
require_once $MODELS_DIR . '/Upload.php';
require_once $MODELS_DIR . '/ResourcesUtility.php';
require_once $MODELS_DIR . '/AccountManager.php';
$Upload = new Upload();
$account_func = new AccountManager($_SESSION["id"]);

// Real directory is obfuscated to client
$dir_client = "show/people-profile/img/";
$dir = $DOCS_DIR . "/" . SP_CLIENT . "/people/profile/";


// VALIDATION CHECKS

// Exit with an error if the file content is not set
if (!isset($_FILES['picture']) || empty($_FILES['picture'])) {

    exit(set_notify("No file selected. " . $_FILES['picture']["tmp_name"]));

} else {

    $pic = $_FILES['picture'];

}


// Check file extensions
if (!ResourcesUtility::isPicture($pic['tmp_name'])) {

    exit(set_notify("File extension not supported."));

}


// Check for errors
if ($pic["error"] > 0) {

    // Collect errrors
    $errors = "Error: " . $_FILES["picture"]["error"];
    // Send email to notify for uploads errors
    mail("dev@startuppuccino.com","Upload errors",$errori);
    exit(set_notify($errors));

}


// Check if $dir is a directory
if (!is_dir($dir)) {

    // Send error email to devs
    mail("dev@startuppuccino.com","Upload Error","$dir is not a directory.");
    exit(set_notify("We are sorry, at the moment the service is not available. Please try later."));

}


// STORING PICTURE

// Rename file with the hash of the user email
$pic["name"] = rename_basename($pic["name"], md5(Session::get('email')));


// Save the file
$Upload->setDir( $dir );
$Upload->setFileName( $pic["name"] );
$Upload->setTemporaryName( $pic["tmp_name"] );
$Upload->setReplace( TRUE );

if ($Upload->store()) {

    if ($account_func->saveProfilePicture( $pic["name"] )) {

        $_SESSION["avatar"] = $pic["name"];
        exit( render_picture_profile( $pic["name"], $dir_client ) );

    } else {

        // TODO -> remove eventually the saved profile picture on the server
        exit(set_notify("Error while uploading.."));

    }

} else {

    exit(set_notify("Error while uploading.."));

}