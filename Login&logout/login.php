<?php

/**
* Login page - finds user in database and redirects user to appropriate page in terms of admin or normal user
* also checks if the user/staff is already logged in and redirects to respective page.
*/

// Connect to database and start session
require_once("../ConnectDb/connectDb.php");
session_start();
// If the session username is not set means the user is logged in
if (!isset($_SESSION['username'])) {
	// Error message variable
    $error = "";
    // Form is submitted
    if (isset($_POST["submitted"])) {
    	// Quote the username and password
        $username = $db->quote($_POST["user"]);        
        $password   = $db->quote($_POST["password"]);
        // Get user from user table if it matches the username and password
        $user_query = "select * from users where username = $username and password = $password";
        $return     = $db->query($user_query);
        $user_row = $return->fetch(PDO::FETCH_ASSOC);
        // If the query returns one row means the user log in detail matches the record
        if ($return->rowcount() == 1) {
        	// Set the session using the username and name
            $_SESSION['username'] = $user_row['username'];
            $_SESSION['name']     = $user_row['name'];
            // If the username is admin redirect to admin index page else to user index page
            if ($_SESSION['username'] == 'admin') {

                header('location:../Staff/staffIndex.php');

            } else {

                header('location:../User/userIndex.php');

            }
        // If no row returned issue error message
        } else {

            $error = "*wrong username or password!";

        }
    }

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login Page</title>
		<link href="../CSS/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container jumbotron">
			<div class="row">
				<div class="col-sm-6 col-md-4 col-md-offset-4">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4>
							Please sign in
							</h4>
						</div>
						<div class="panel-body">
							<form class="form-signin" action ="login.php" method="post" >
								<input type="text" name="user" class="form-control" placeholder="Username" required autofocus />
								<input type="password" name="password" class="form-control" placeholder="Password" required  />
								<div class="text-danger"> <?php echo $error;?> </div>
								</br>
								<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
								<input type="hidden" name="submitted" value="true"/>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php include "../Footer/footer.php";
// If the Session username is admin redirect to admin page
}else if ($_SESSION['username']=="admin"){

	header('location:../Staff/staffIndex.php');
// Else redirect to user index page
}else{ 

	header('location:../User/userIndex.php');

}?>