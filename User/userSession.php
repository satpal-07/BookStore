<?php

/**
* Establish database connection by using connectDb.php
* Checks if the session is active and redirects based on session
*/

// connect to database and start session
require_once("../ConnectDb/connectDb.php");
session_start();

// If the session username is not set then redirect to login page
if (!isset($_SESSION['username'])) {

    header('location:../Login&logout/login.php');

// If the session username is admin redirect to admin index page
} else if ($_SESSION['username'] == "admin") {

    header('location:../Staff/staffIndex.php');
}
?>