<?php
// this php copy example search and result to the current user in MySQL and set the session search_id
session_start();
require_once 'login.php';
$user_id = $_SESSION['user_id'];
$mode = $_POST['mode'] ?? null;
$search_id = $_POST['search_id'] ?? null;

if ($mode == 'example'){
	try {
	 $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
   	 $conn = new PDO($dsn, $username, $password);
   	 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	 //get example user 'example_G6P'
    	 $example_user = $conn->query(
         "SELECT id FROM users WHERE username='example_G6P'"
         )->fetch_assoc()['id'];

	 $search = $conn->query(
         "SELECT * FROM searches
          WHERE user_id=$example_user
          ORDER BY id DESC LIMIT 1"
         )->fetch_assoc();
	 
         $search = $conn->query(
         "SELECT * FROM searches 
          WHERE user_id=$example_user 
          ORDER BY id DESC LIMIT 1"
         )->fetch_assoc();

	 // insert the data into current user
	 $conn->query("
          INSERT INTO searches (user_id, protein_family, taxonomy, sequences)
          VALUES ($user_id, '{$search['family']}', '{$search['taxonomy']}','{$search['sequences']}')
          ");

         $new_search_id = $conn->insert_id;

         $conn->query("
         INSERT INTO results (search_id, motif, conservation, other)
         VALUES ($new_search_id, '{$result['motif']}', '{$result['conservation']}', '{$result['other']}')
          ");

	 // set session search_id
         $_SESSION['search_id'] = $new_search_id;

  	  $stmt = null;
	} catch(PDOException $e) {
        	  echo "<b><font color=\"red\">Connection failed</font></b>:<br/>" . $e->getMessage();
	}	
}
?>
