<?php
/**
* Add to cart page - finds the book exists or not, adds book to the cart if new book is being added 
* otherwise adds up the quantity to the book in the cart already. It also decrement the books' quantity from 
* book table when book(s) added to the cart
*/

//start session and database connection, and redirects to staffIndex.php if username is admin
include "userSession.php";
// Checks if the form is submitted
if(isset($_POST['submitted'])){
    // Gets all the values from the form
    $username   = $_POST['username'];
    $isbn       = $_POST['isbn'];
    $quantity   = $_POST['addQuantity'];
    // Query to get book of given user from cart to check if the book alredy in cart
    $cart_query = "SELECT * FROM cart WHERE isbn = '$isbn' AND username = '$username'";
    // Query to get book from book table to check if quantity is sufficient
    $book_query = "SELECT b.isbn, b.quantity FROM book b WHERE '$isbn' = b.isbn GROUP BY b.isbn";
    $book_row   = $db->query($book_query)->fetch(PDO::FETCH_ASSOC);
    $cart_result = $db->query($cart_query);
    // Check if the quantity is sufficient and the quantity being added is more than 0
    if ($book_row['quantity'] >= $quantity && $quantity > 0) {
        //Check if the book already in cart and add it as new book into cart table
        if ($cart_result->rowCount() == 0) {
            $insert_query = "INSERT INTO cart VALUES ('$quantity', '$isbn', '$username')";
            $db->query($insert_query);
            header('location:userCartPage.php?msg=new_book');
		
		// If the record already exits then update the quantity only
        } else {
            $update_query = "UPDATE cart SET quantity = quantity + '$quantity' WHERE isbn = '$isbn' AND username = '$username'";
            $db->query($update_query);
            header('location:userCartPage.php?msg=old_book');
            
        }
        // Updates the Book catalogue quantity once the user has added book to the cart
        $update_book_query    = "UPDATE book SET quantity = quantity - $quantity WHERE isbn = $isbn";
        $db->prepare($update_book_query);
        $db->exec($update_book_query);

    // Display error page if the book quantity is not sufficient for order
    } else {
        $error_msg = "";
        if(empty($quantity)){

            $error_msg = "No quantity entered! <br> Please add the book again!";

        }else{
            $error_msg = "The entered quantity is not available!";
        }

    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Add to Cart</title>
            <link href="../CSS/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <?php
            // Including the nav bar and logo.
            include "userHeader.php";
            ?>
            <div class="container">
                <h4 class=" text-center text-danger"><?=$error_msg?></h4>
            </div>
            <!-- Footer -->
            <?php include "../Footer/footer.php"; 
            ?>
        </body>
    </html>
    <?php
        }
}
 ?>