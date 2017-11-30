<?php

/**
* User cart page - Displays books in the cart of the user.
*/

//start session and database connection, and redirects to staffIndex.php if username is admin
include "userSession.php";
$username          = $db->quote($_SESSION['username']);
// Query to get the books from book table which are in user's cart table
$book_query        = "SELECT b.title, b.price, b.isbn, c.quantity FROM book b INNER JOIN cart c ON username = $username and b.ISBN = c.ISBN ORDER BY b.title";
// Query to get total amount for the books in the cart.
$book_total_query  = "SELECT ROUND(SUM(b.price * c.quantity), 2) AS total FROM book b, cart c WHERE username = $username AND b.isbn = c.isbn";
// Query to get the user available balance.
$balance_query     = "SELECT balance FROM credit WHERE $username = username";

/**
* Fetch the rows for each query
*/

$book_result       = $db->query($book_query);
$book_total_result = $db->query($book_total_query);
$balance_result    = $db->query($balance_query);
$balance_row       = $balance_result->fetch(PDO::FETCH_ASSOC);
$book_rows         = $book_result->fetchAll(PDO::FETCH_ASSOC);
$total_amount_row  = $book_total_result->fetch(PDO::FETCH_ASSOC);
$msg = "";
// If the msg is get then do this.
if(isset($_GET['msg'])){
	// If the book is new then issue this message.
	if($_GET['msg']=="new_book"){
		$msg = "Book has been added to the cart!";

	// If the book is already in cart then issue this message
	}else  if($_GET['msg']=="old_book"){
		$msg = "Book already exists and quantity of the book has been updated!";

	// If the book is bremoved then issue the following message.
	}else if($_GET['msg']=="removed"){
		$msg = "Book has been removed from the cart!";
	}

}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>User Cart Page</title>
		<link href="../CSS/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<?php
		// Including the nav bar and logo.
    	include "userHeader.php";
    	?>
		<div class="container">
			<p class="text-danger text-center"><?php echo $msg; ?></p>
			<?php
				echo "<h2 class='modal-header text-center' >". $_SESSION['name'] .  ", Books in your cart!</h2>";
				echo "<h5>Available balance <strong>&pound; $balance_row[balance]</strong></h5>";
				// If the cart is not empty then display the book.
				if($book_result->rowCount() != 0){
					echo "<h5>Total amount due: <strong>&pound; " . $total_amount_row['total'] . "</strong></h5>";
				?>
			<div class="row">
				<ul class="list-group">
				<?php
					// Display the books in the cart of the user.
					foreach($book_rows as $row){
						echo "<li class='list-group-item'>
							<p>Title: $row[title]</p>
							<p>Quantity: $row[quantity]</p>
							<p>Price: &pound; $row[price]</p>
							<form action ='removeBook.php' method='post'>
							<button class='btn btn-sm btn-primary' type='submit'>Remove</button>
							<input type='hidden' name='submitted' value='true'/>
							<input type='hidden' name='remove_book' value='$row[isbn]'/>
							<input type='hidden' name='quantity' value='$row[quantity]'/>
							</form>
							</li>";
						}
				// If the cart is empty then display this message.
				}else{
					echo "<p class='lead'> You have nothing in Basket</p>";
				}?>
			</div>
		</div>
		<!-- Footer -->
		<?php include "../Footer/footer.php";
		?>
	</body>
</html>