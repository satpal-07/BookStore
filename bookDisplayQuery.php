<?php

/**
* Inner join Query to get all the book record along with the group concat the category with the category table
* to display all the category for a book in one column.
*/

// Connect to the database.
require_once('ConnectDb/connectDb.php');
// Query to get the books from the book table using inner join with the category table and group concat the category so it can be display as one list of category.
$book_query   = "SELECT b.isbn, b.title, b.price, b.quantity, group_concat(c.category SEPARATOR ', ') AS category FROM book b INNER JOIN category c ON c.isbn = b.isbn GROUP BY b.isbn ORDER BY b.title";
$book_result  = $db->query($book_query);
$book_rows    = $book_result->fetchAll(PDO::FETCH_ASSOC);
?>