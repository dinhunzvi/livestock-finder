<?php
    /**
     * init.php - contains constants and values used throughout the application
     */
    session_start(); // start a session

    /* define directory separator, DS */
    defined( 'DS' ) ? null : define( 'DS', DIRECTORY_SEPARATOR );

    /* define the root directory, BASE_FOLDER */
    defined( 'BASE_FOLDER' ) ? null : define( 'BASE_FOLDER', 'C:' . DS . 'xampp' . DS . 'htdocs' . DS .
        'livestock_finder' . DS );

    /* define the classes directory, CLASSES_DIR */
    defined( 'CLASSES_DIR' ) ? null : define( 'CLASSES_DIR', BASE_FOLDER . 'classes' . DS );

    /* define the config directory, CONFIG_DIR */
    defined( 'CONFIG_DIR' ) ? null : define( 'CONFIG_DIR', BASE_FOLDER . 'config' . DS );

    /* define the email templates directory, EMAILS_DIR */
    defined( 'EMAILS_DIR' ) ? null : define( 'EMAILS_DIR', BASE_FOLDER . 'emails' . DS );

    /* define package with composer installed libraries, LIB_PATH */
    defined( 'LIB_PATH' ) ? null : define( 'LIB_PATH', BASE_FOLDER . 'libraries' . DS );

    /* define the livestock brands directory, BRANDS_DIR */
    defined( 'BRANDS_DIR' ) ? null : define( 'BRANDS_DIR', BASE_FOLDER . 'livestock_brands' . DS );

    /* define the layout templates directory, TEMPLATES_DIR */
    defined( 'TEMPLATES_DIR' ) ? null : define( 'TEMPLATES_DIR', BASE_FOLDER . 'templates' . DS );

    /* load user created classes using spl_autoload_register */
    spl_autoload_register( function ( $class_name ) {
        require_once CLASSES_DIR . strtolower( $class_name ) . '.php';
    });

    /* load composer installed packages */
    require_once LIB_PATH . 'vendor' . DS . 'autoload.php';

    /* load the Scale class for image manipulation */
    require_once LIB_PATH . 'scale.php';

    /* load general functions */
    require_once LIB_PATH . 'functions.php';