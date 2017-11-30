<?php

/**
* Staff index page - displays books in the catalogue.
*/

// Start session and database connection, and redirects if username is not admin
include "staffSession.php";
// Include the book query to display all the book in the catalogue.
include "../bookDisplayQuery.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Staff Index Page</title>
    <link href="../CSS/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <?php
    // Incuding the nav bar and logo
    include "staffHeader.php";
    ?>
    <div class="container">
      <?php
        echo "<h2 class='modal-header text-center'>Welcome, ". $_SESSION['name'] . "!</h2>";
        echo "<p class='lead'> Books in catalogue</p>";
        
        ?>  
      <div class="row">
        <ul class="list-group"> 
          <?php
          // Display all the books in the catalgue.
          foreach($book_rows as $row){
                echo "<li class='list-group-item'>
                      <p> Title: $row[title]</p>
                      <p>ISBN: $row[isbn]</p>
                      <p>Category: $row[category]</p>
                      <p>Price: &pound;$row[price]</p>
                      <p>Quantity: $row[quantity]</p>
                      </li>";
          }
          ?>
        </ul>
      </div>
    </div>
    <!-- Foooter -->
    <?php include "../Footer/footer.php";
    ?>
  </body>
</html>