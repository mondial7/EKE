<?php

/**
 * Set website base url
 *
 */	
if ($_SERVER['HTTP_HOST'] === "localhost") {

    $template_variables['website_url'] = "http://localhost/EKE/";

} else {
    
    $template_variables['website_url'] = "https://eke.mondspace.com/";

}