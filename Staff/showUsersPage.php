<?php
/**
* Show user page - displays users' registered including add balance and check out option.
*/

// Start session and database connection, and redirects if username is not admin
include "staffSession.php";
// Query to get user and its balance by inner joining tables
$user_query  = "SELECT users.username, users.name, users.surname, credit.balance FROM users INNER JOIN credit ON credit.username = users.username ORDER BY users.name";
$user_result = $db->query($user_query);
$user_rows   = $user_result->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>User Display page</title>
    <link href="../CSS/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <?php
    // Including Nav Bar and logo
    include "staffHeader.php";
    ?>
    <div class="container">
      <h2 class='modal-header text-center' >Users</h2>
      <div class="row">
        <ul class="list-group">
          <?php
          // Display all the users.
          foreach($user_rows as $row){
            
            echo "<li class='list-group-item'>
                  <p>Name: <strong>$row[name] $row[surname]</strong> </p>
                  <p> Balance:<strong> &pound;$row[balance] </strong></p>
                  <a href='checkout.php?name=$row[name]&username=$row[username]'>Check out</a>
                  <br>
                  <a href='addBalance.php?name=$row[name]&username=$row[username]'>Add balance</a>
                  </li>";
          }
          ?>
        </table>
      </div>
    </div>
    <!-- Footer -->
    <?php include "../Footer/footer.php";
    ?>
  </body>
</html>