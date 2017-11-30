<?php

/**
* Add balance page - Adds balance to the users current balance
*/

//start session and database connection, and redirects if username is not admin
include "staffSession.php";
//Get username and quote it to be used for queries
$username = $_GET['username'];
$quote_username = $db->quote($username);
$msg                  = "";
if (isset($_POST["submitted"])) {
    //Updating balance of a given user
    $balance                = $db->quote($_POST["balance"]);
    $update_balance_query   = "UPDATE credit SET balance = ROUND(balance + $balance, 2) WHERE username = $quote_username";
    $db->prepare($update_balance_query);
    $result = $db->exec($update_balance_query);
    //Assigns message based of success of updating balance
    if($result){

        $msg = "Balance has been updated!";

    }else{

        $msg = "Error: adding balance!";

    }
    
}
//Query to get balance of user
$query_balance   = "SELECT balance FROM credit WHERE credit.username = $quote_username";
$result          = $db->query($query_balance);
$available_balance  = $result->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Display Book</title>
        <link href="../CSS/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
        //Nav Bar and Aston logo
        include "staffHeader.php";
        ?>
        <div class="container jumbotron">
            <h2 class="modal-header text-center">Balance</h2>
            <p class="text-danger text-center"><?php echo $msg; ?></p>
            <div class="text-center">
                <!-- Displays user name and balance -->
                <h4><?= $_GET['name'] ?>'s balance is &pound; <?= $available_balance['balance']  ?></h4>
                <!-- Form for updating balance -->
                <form class="form-inline form-group" action ="addbalance.php?name=<?= $_GET['name'] ?>&username=<?= $username ?>" method="post" >
                    <fieldset>
                        <label>Balance: &pound;</label>
                        <input type="text" name="balance" size="3" placeholder="0"/>
                        <input type="hidden" name="submitted" value="true"/>
                        <button class='btn btn-sm btn-primary' type='submit'>Add Balance</button> 
                    </fieldset>
                </form>
            </div>
        </div>
        <!-- Footer -->
       <?php include "../Footer/footer.php"; 
       ?>
    </body>
</html>