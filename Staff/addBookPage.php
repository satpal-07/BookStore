<?php

/**
* Add book page - validates all the required values for adding the book
* If values are valid and ISBN is not in book table then adds the book into book table
*/

//start session and database connection, and redirects if username is not admin
include "staffSession.php";
//Variables for sticky form, error messages and validation check
$bookAdded        = false;
$bookAddedMessage = $ISBNErr = $titleErr = $catErr = $quantityErr = $priceErr = $ISBN = $title = $category = $quantity = $price = "";
$validate         = true;
/**
* Validation for adding book into database
*/
if (isset($_POST["submitted"])) {

    //Insert validation helper functions
    include "../Validate/validate.php";
    validateISBN($_POST["isbn"]);
    //checks if ISBN is of right format and digits
    //If not in right format assign error message
    if (!empty($_POST["isbn"]) && validateISBN($_POST["isbn"])) {

        $ISBN      = $_POST["isbn"];
        $quoteISBN = $db->quote($ISBN);

    } else {

        $ISBNErr  = "*Please provide valid ISBN";
        $validate = false;
    
    }
    //Checks the title
    //If empty assign error message for displaying
    if (!empty($_POST["title"])) {
        
        $title      = $_POST["title"];
        $quoteTitle = $db->quote($title);
    
    } else {
        
        $titleErr = "*No book title provided";
        $validate = false;
    
    }
    //Checks the category
    //If empty assign error message for displaying
    if (empty($_POST["category"])) {
        
        $catErr   = "*No book category provided";
        $validate = false;
    
    }
    //Checks the quantity is in right format
    //If empty or not in right format assign error message
    if (!empty($_POST["quantity"]) && is_numeric($_POST["quantity"])) {
        
        $quantity      = $_POST["quantity"];
        $quoteQuantity = $db->quote($quantity);
    
    } else {
        
        $quantityErr = "*No book quantity provided";
        $validate    = false;
    
    }
    //Checks the price format and two decimal places
    //If not in right format assign error message
    if (!empty($_POST["price"]) && validatePrice($_POST["price"])) {
        
        $price      = $_POST["price"];
        $quotePrice = $db->quote($price);
    
    } else {
        
        $priceErr = "*Please enter price in valid format (0.00)";
        $validate = false;
    
    }
    //Inserts book into database if validation is successful
    if ($validate) {
        // Query to get book, to check if ISBN already exists or new book being added
        $Book_ISBN_query  = "SELECT book.isbn FROM book WHERE book.isbn = $quoteISBN";
        $book_ISBN_result = $db->query($Book_ISBN_query)->fetch();
        // Insert new book into database if the ISBN is new else issue error message specifying book exists
        if ($book_ISBN_result["isbn"] != $ISBN) {
            
            $book_query  = "INSERT INTO book VALUES($quoteISBN, $quoteTitle, $quoteQuantity, $quotePrice)";
            $book_result = $db->prepare($book_query);
			$book_result->execute();
            
            foreach ($_POST["category"] as $catSelected) {
                
                $cat_query  = "INSERT INTO category VALUES('$catSelected', $quoteISBN)";
                $cat_result = $db->prepare($cat_query);
				$cat_result->execute();
            
            }

            $bookAdded = true;
            
            if ($bookAdded) {
                
                $bookAddedMessage = "*Book has been added.";
                $ISBN             = $title = $category = $quantity = $price = "";
            
            }
        } else {
          
            $bookAddedMessage = "*Book not added because ISBN already exists.";
        
        }
    }else {
        $bookAddedMessage = "Error: Please provide missing field(s)!";
    }
             
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add Book Page</title>
        <link href="../CSS/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
        include "staffHeader.php";
        ?>
        <div class="container">
            <!-- displaying message -->
            <p class="text-danger text-center"><?php  echo $bookAddedMessage; ?></p>
            <div class="row modal-header">
                <div class="col-sm-6 col-md-4 col-md-offset-4">
                    <div class="wrapper">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Add Book</div>
                            <div class="panel-body">
                                <!-- Form for adding book -->
                                <form action ="addBookPage.php" method="post" class="simple_form" >
                                    <label class="required control-label" ><abbr title="required">*</abbr>ISBN:</label>
                                    <input type="text" name="isbn" placeholder="13 Number Digit only" class="string required form-control" value="<?php
                                        echo $ISBN;
                                        ?>" maxlength="13" />
                                    <span class="text-danger"> <?php
                                        echo $ISBNErr;
                                        ?> </span>
                                    <br>
                                    <label>Title:</label>
                                    <input type="text" name="title" placeholder="Title" class="string required form-control" value="<?php
                                        echo $title;
                                        ?>"/>
                                    <span class="text-danger"> <?php
                                        echo $titleErr;
                                        ?> </span><br>
                                    <label>Category:</label>
                                    <br>
                                    <select name="category[]" multiple="multiple" class="multiselect" >
                                        <option value="Art">Art</option>
                                        <option value="Computer Science">Computer Science</option>
                                        <option value="English">English</option>
                                        <option value="Fiction">Fiction</option>
                                        <option value="Geology">Geology</option>
                                        <option value="Health and Care">Health and Care</option>
                                        <option value="History">History</option>
                                        <option value="Mathematics">Mathematics</option>
                                        <option value="Other">Other</option>
                                        <option value="Philosophy">Philosophy</option>
                                        <option value="Physics">Physics</option>
                                        <option value="Political Science">Political Science</option>
                                        <option value="Social Science">Social Science</option>
                                        <option value="Sports">Sports</option>
                                        <option value="Travel">Travel</option>
                                    </select>
                                    <span class="help-block">Hold ctrl for multi select</span>
                                    <span class="text-danger"> <?php
                                        echo $catErr;
                                        ?> </span>
                                    <br>
                                    <label>Quantity:</label>
                                    <input type="text" name="quantity" placeholder="Number" class="string required form-control" value="<?php
                                        echo $quantity;
                                        ?>"/>
                                    <span class="text-danger"> <?php
                                        echo $quantityErr;
                                        ?> </span>
                                    <br>
                                    <label>Price: &pound;</label>
                                    <input type="text" name="price" placeholder="0.00 format please" class="string required form-control" value="<?php
                                        echo $price;
                                        ?>"/>
                                    <span class="text-danger"> <?php
                                        echo $priceErr;
                                        ?> </span>
                                    <br>
                                    <input type="hidden" name="submitted" value="true"/>
                                    <button class="btn btn-lg btn-primary btn-block" type="submit">Add Book</button>  
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <?php include "../Footer/footer.php"; 
        ?>
    </body>
</html>