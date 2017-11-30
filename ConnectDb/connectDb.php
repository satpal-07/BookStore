<?php

/**
* Connecting to the database using PDO.
*/

//connect to database using PDO. try and catch to catch any error occurs when accessing the database.
try {
    $db = new PDO("mysql:dbname=book_store_DB;host=localhost", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
}catch (PDOException $e) {
    echo "Error Accessing the database: " . $e->getMessage();
    die();
}
?>