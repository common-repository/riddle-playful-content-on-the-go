<?php

use src\Api\RiddleLoaderV2;

function loadRiddleMenu() 
{
    $isAuthorized = RiddleLoaderV2::isAuthorized();

    if ($isAuthorized) {
        connectedRiddleMenu();
    } else {
        disconnectedRiddleMenu();
    }

    \add_submenu_page(
        'riddle-admin-menu', 
        'Help', 
        'Help', 
        'edit_pages', 
        'riddle-help', 
        'src\controller\AdminController::help'
    );
}

function connectedRiddleMenu()
{
    \add_menu_page(
        'Riddle Plugin', // page title
        'Riddle Plugin', // menu title
        'edit_pages', // Who has access?
        'riddle-admin-menu', // unique name for this top level menu
        'src\controller\AdminController::riddleList', // Method (Controller!)
        RIDDLE_IMAGE_PATH . '/icon-white-tiny.png'
    );
    \add_submenu_page(
        'riddle-admin-menu', 
        'My Riddles', 
        'My Riddles', 
        'edit_pages', 
        'riddle-admin-menu', 
        'src\controller\AdminController::riddleList'
    );
}

function disconnectedRiddleMenu()
{
    \add_menu_page(
        'Riddle Plugin', // page title
        'Riddle Plugin', // menu title
        'edit_pages', // Who has access?
        'riddle-admin-menu', // unique name for this top level menu
        'src\controller\AdminController::connect', // Method (Controller!)
        RIDDLE_IMAGE_PATH . '/icon-white-tiny.png'
    );
    \add_submenu_page(
        'riddle-admin-menu', 
        'Connect account', 
        'Connect account', 
        'edit_pages', 
        'riddle-admin-menu', 
        'src\controller\AdminController::connect'
    );
}