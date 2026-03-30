<?php
// redirect to the index page if no user is set
if(!(isset($_SESSION['user'])))
  {
  header('Location: index.php');
  exit;
  }
?>
