<?php
/**
    * database login details
    **/
    // The host you want to connect to.
    define("HOST", "rdbms.strato.de");
    // The database username. 
    define("USER", "U3833000");    
    // The database password. 
    define("PASSWORD", "asdgasdg324g234gw");    
    // The database name.
    define("DATABASE", "DB3833000");   

    define("CAN_REGISTER", "any");
    define("DEFAULT_ROLE", "member");

    // FOR DEVELOPMENT ONLY!!!!
    define("SECURE", TRUE);

    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);





?>