<?php
//start.php
//1.check user
//2.provide 3 choices: New search/Example/History
session_start();
//var_dump($_SESSION);
include 'redir_user.php';

echo<<<_HEAD
<html>
<body>
_HEAD;

$user = $_SESSION['user'];
echo <<<_WELCOME
<p>Welcome {$user}</p>
_WELCOME;

echo <<<_BODY
<p>Please choose a dataset:</p>
<ul>
    <li><a href="search.php">New Search</a></li>
    <li><a href="example.php">Example</a></li>
    <li><a href="history.php">History</a></li>
</ul>
<br>
<a href="logout.php">Logout</a>
_BODY;
