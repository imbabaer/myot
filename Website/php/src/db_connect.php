<?php
/**
    * database login details
    **/
    // The host you want to connect to.
    define("HOST", "rdbms.strato.de");
    // The database username. 
    define("USER", "U3335774");    
    // The database password. 
    define("PASSWORD", "12345678abc");    
    // The database name.
    define("DATABASE", "DB3335774");   

    define("CAN_REGISTER", "any");
    define("DEFAULT_ROLE", "member");

    // FOR DEVELOPMENT ONLY!!!!
    define("SECURE", TRUE);

    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);





?>