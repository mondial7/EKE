<?php

/**
 * Set menu and submenu items
 */
function define_menu() {

    global $userLogged, $currentPage;

    if ($userLogged) {

        $menu = [
            
            [
                "label" => "Home",
                "link" => ""
            ]

        ];

    } else {

        $menu = [
            
            [
                "label" => "Home",
                "link" => ""
            ]

        ];

    }

    // Set the active menu item
    // To be improved (e.g. do check on the links)
    if (isset($currentPage)) {

        for ($i=0, $l=count($menu); $i < $l; $i++) { 
        
            if( strtolower($menu[$i]['label']) == $currentPage ){
                $menu[$i]["active_class"] = "menu_link--active";
            }
        
        }

    }

    return $menu;

}

function define_submenu() {

    global $userLogged;

    if ($userLogged) {

        $submenu = [];

    } else {

        $submenu = [];

    }

    return $submenu;

}

/**
 * Add variables to template
 */
$template_variables['menu'] = define_menu();
$template_variables['submenu'] = define_submenu();