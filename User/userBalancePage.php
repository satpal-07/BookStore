<?php

/**
* User balance page - Displays balance for the user.
*/

//start session and database connection, and redirects to staffIndex.php if username is admin
include "userSession.php";
$username       = $db->quote($_SESSION['username']);
// Query to get the balance of the user.
$balance_query  = "SELECT balance FROM credit WHERE $username = credit.username";
$balance_result = $db->query($balance_query);
$available_balance = $balance_result->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Display Book</title>
		<link href="../CSS/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<?php
		// including the nav bar and logo.
    	include "userHeader.php";
   		?>
		<div class="container jumbotron">
			<h2 class="modal-header text-center">Balance</h2>
			<h3 class="text-center"><?= $_SESSION['name']?>, Your balance is &pound; <?= $available_balance['balance']  ?></h3>
		</div>
		<!-- Footer -->
		<?php include "../Footer/footer.php";
		?>
	</body>
</html>