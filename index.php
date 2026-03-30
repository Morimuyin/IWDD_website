<?php
// index.php
// 1. provide basic information about the website
// 2. user login
// code reference: IWDD let_me_in.php

session_start();
require_once 'login.php';
echo <<<_HEAD
<html>
<head>
<title>index.php</title>
</head>
<body>
_HEAD;

// provide basic info with a list
echo <<<_INFO
<h1>About this website:</h1>
<p>This is a website for the IWDD ICA. The website provides the following functionalities:</p>
<ol> 
<li>Query the NCBI database for sequences from a protein family and a taxonomy group.</li>
<li>Perform the following analyses: conversation, motif, and other sequence-based analysis</li>
_INFO;

// if user and password set by POST, check and set session
// if not valid, show message
if (!empty($_POST['user']) && !empty($_POST['password'])) {
$user = $_POST['user'];
$pw = $_POST['password'];
try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'select * from users where username = ?';
    $stmt = $conn->prepare($sql) ;
    $stmt->execute([$user]);
    $result = $stmt->fetch();
    if ($result) {
	// check password
	if (($pw == $result['password'])) {
	    // valid
	    $_SESSION['user'] = $user;
	    echo "<p>Login successful</p>";
	} else {
	    // wrong password
	    echo "<p>Wrong password</p>";
	}
    }  else {
	    // new user
	    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

		    if ($stmt->execute([$user, $pw])) {
     		         echo "<p>User created successfully</p>";
  		    } else {
       			 echo "<p>Error when creating user</p>";
     		    }
                $_SESSION['user'] = $user;
              
            }  

	    $stmt = null;
        } catch(PDOException $e) {
          echo "<b><font color=\"red\">Connection failed</font></b>:<br/>" . $e->getMessage();
	}
	
}

if(isset($_SESSION['user']) ) {
	//show welcome, give links to start.php
echo <<<_WELCOME
<p>Welcome {$_SESSION['user']}</p>
<a href="start.php">Start analysis</a>
_WELCOME;
} else {
echo <<<_FORM
<form action="index.php" method="post">
    <h1>Website login:</h1>
    <p>Please enter your relevant details in the table below.</p>

    <table border="0">
    <tr><td>Username</td><td><input type="text" name="user" required/></td></tr>
    <tr><td>Password</td><td><input type="password" name="password" required/></td></tr>
    </table>

<br/><input type="submit" value="Request access" />
</form>
_FORM;
}
echo <<<_TAIL
</body>
</html>
_TAIL;
session_destroy() ;
?>

