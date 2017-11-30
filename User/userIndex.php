<?php

/**
* User index page - displays books in the catalogue and allows user to add it to the cart.
*/

//start session and database connection, and redirects to staffIndex.php if username is admin
include "userSession.php";
// Include the book query to display all the book in the catalogue.
include "../bookDisplayQuery.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <title>User Index page</title>
    <link href="../CSS/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <?php
    include "userHeader.php";
    ?>
    <div class="container">
      <?php
      // welcome the user by displaying name
      echo "<h2 class='modal-header text-center' >Welcome, " . $_SESSION['name'] . "!</h2>";
      echo "<p class='lead'> Books in catalogue</p>";
      
      ?>  
      <div class="row">
        <ul class="list-group"> 
          <?php
          // Display the books and set a form for each book so user can add book to the cart using form.
          foreach($book_rows as $row){
              echo "<li class='list-group-item'>
                    <p> Title: $row[title]</p>
                    <p>ISBN: $row[isbn]</p>
                    <p>Category: $row[category]</p>
                    <p>Price: &pound;$row[price]</p>
                    <p>Quantity: $row[quantity]</p>
                    <form method='post' action='addToCart.php'>
                    <p>Enter quantity: <input type='text' value='1' name='addQuantity' size='2'/> </p>
                    <input type='hidden' name='username' value='$_SESSION[username]'/>
                    <input type='hidden' name='isbn' value='$row[isbn]'/>
                    <input type='hidden' name='submitted' value='true'/>
                    <button class='btn btn-primary' type='submit'>Add to cart</button></form>
                    </form>
                    </li>";
          }
          ?>
        </ul>
      </div>
    </div>
    <!-- Footer -->
    <?php include "../Footer/footer.php";
    ?>
  </body>
</html>