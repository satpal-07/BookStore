<?php

/**
* Validate the $number and returns true if it is a number and two decimal places
*/
function validatePrice($number)
{
    if (preg_match('/^[0-9]+\.[0-9]{2}$/', $number)) {
        return true;
    } else {
        return false;
    }
}

/**
* Validates $number by checking if it is a number of 13 digits long
*/
function validateISBN($number)
{
    if (preg_match('/^[0-9]{13}$/', $number)) {
        return true;
    } else {
        return false;
    }
    
}
?>