<?php

/**
* checkout page - transacts the transaction if balance of user is sufficient
* also balance record
*/

//start session and database connection, and redirects if username is not admin
include "staffSession.php";
// Variable for displaying message
$msg                  = "";
// Username
$username = $_GET['username'];
$quote_username = $db->quote($username);
if (isset($_POST["submitted"])) {
    // Check if book(s) selected else issue appropriate message.
    if (isset($_POST["book"])) {

        // Get the total price of books
        $amount_due = $_POST['total'];
    
        // Get user balance to check if user has sufficient balance for transaction
        $query_balance   = "SELECT balance FROM credit WHERE credit.username = $quote_username";
        $result          = $db->query($query_balance);
        $available_balance  = $result->fetch(PDO::FETCH_ASSOC);

        if ($available_balance['balance'] >= $amount_due){

            // Get Books from form check boxes
            $books = $_POST["book"];
            // delete from cart one by one.
            foreach ($books as $book) {
                // Get and assign the values
                $values    = explode(":", $book);
                $sub_price = $values[0];
                $isbn = $values[1];
                $quantity = $values[2];

                // Get the book quantity and title.
                $book_query = "SELECT quantity, title FROM book WHERE isbn = '$isbn'";
                $book_result    = $db->query($book_query);
                $bookRow   = $book_result->fetch(PDO::FETCH_ASSOC);

                // Calculate the total price for each book being purchased.
                $total_price = $sub_price * $quantity;
                /**
                * Deletes book from cart database
                * Updates the user balance
                */ 
                $delete_book_query    = "DELETE FROM cart WHERE isbn = $isbn AND username = $quote_username";
                $update_balance_query = "UPDATE credit SET balance =  ROUND(balance - $total_price, 2) WHERE username = $quote_username";
                $db->prepare($update_balance_query);
                $db->prepare($delete_book_query);
                $db->exec($update_balance_query);
                $db->exec($delete_book_query);

            }
            
            $msg = "*Transaction is successful!";
        
        }else{
            $msg = "*User does not have suffient balance!";
        }
    } else {
        $msg = "*No book selected!";
    }
}
// Get the book from the Book and cart by inner join query
$user_cart_query  = "SELECT b.title, b.price, b.isbn, c.quantity FROM book b INNER JOIN cart c ON c.username = '$username' AND c.isbn = b.isbn ORDER BY b.title";
$user_cart_result = $db->query($user_cart_query);
$user_cart_rows   = $user_cart_result->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Check Out Page</title>
        <link href="../CSS/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../totalFunction.js"></script>
    </head>
    <body>
        <?php
        // Include the nav bar and aston logo
        include "staffHeader.php";
        ?>
        <div class="container">
            <p class="text-danger"><?php echo $msg; ?></p>
            <?php
            if($user_cart_result->rowcount() != 0){
                ?>
                <h2 class="modal-header text-center">Check Out</h2>
                <h4 class='text-center'><?= $_GET['name'] ?>'s cart</h4>
                <form name='myform' method='post' action="checkout.php?name=<?= $_GET['name'] ?>&username=<?= $username ?>">
                    <table class="table table-striped table-bordered table-condensed" width="647">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Quantity</th>
                                <th>price in &pound;</th>
                                <th>Select</th>
                            </tr>
                            <?php
                            // Display the books in user's cart
                            foreach($user_cart_rows as $row){
                                echo "<tr>
                                <td>$row[title]</td>
                                <td>$row[quantity]</td>
                                <td>$row[price]</td>
                                <td><input type='checkbox' name='book[]'  value='$row[price]:$row[isbn]:$row[quantity]' onchange=\"checkTotal()\"/></td>
                                </tr>"; 
                            }
                            ?>
                        </thead>
                    </table >
                    <input type="checkbox" onClick="toggle(this)" /><label> Select All</label><br/>
                    <label>Total: &pound;</label> <input type="text" size="2" name="total" value="0.00" readonly/>
                    <input type="hidden" name="submitted" value="true"/>
                    <button class='btn btn-primary' type='submit'>Check Out</button> 
                </form>
                <?php
            }else {
                echo "<h4 class='text-center'> $_GET[name]'s cart is empty!</h4>";
            }
            ?>
        </div>
            <?php include "../Footer/footer.php";
            ?>
    </body>
</html>