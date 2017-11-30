<?php

/**
* Removes book from the cart by using the ISBN
*/

// Start session and database connection, and redirects to staffIndex.php if username is admin
include "userSession.php";
// If the form has beeen submitted then get the values and delete the book from the cart.
if (isset($_POST['submitted'])) {
    
    $username     = $db->quote($_SESSION['username']);
    $isbn         = $db->quote($_POST['remove_book']);
    $quantity         = $db->quote($_POST['quantity']);
    // Query to delete the book from the cart.
    $delete_query = "DELETE FROM cart WHERE isbn = $isbn AND username = $username";
    $db->prepare($delete_query);
    $db->exec($delete_query);
    // Updates the Book catlogue quantity once the user has removed the book from the cart
    $update_book_query    = "UPDATE book SET quantity = quantity + $quantity WHERE isbn = $isbn";
    $db->prepare($update_book_query);
    $db->exec($update_book_query);
    // Redirect to the cart page after deleting the book from the cart and add a msg variable and assign removed.
    header('location:userCartPage.php?msg=removed');
    
}
?>