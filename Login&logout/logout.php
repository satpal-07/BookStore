<?php

/**
* Log out page - closes the database and destroys the settion
*/

require_once("../ConnectDb/connectDb.php");
$db = null;
session_start();
session_destroy();
header('location:login.php');
?>