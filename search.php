<?php
// This page is used to perform search.
// 1. check the user session
// 2. search form
// 3. display search result, and update database
session_start();
include 'redir_user.php';
?>
<!DOCTYPE html>
<html>
<head></head>
<body>

<?php
$user = $_SESSION['user'];
echo <<<_USER
<p>User: {$user}</p>
<a href="logout.php">Logout</a>
_USER;
?>

<!--search form-->
<h1>Protein search:</h1>
<p>maximun 20 results</p>
<form id="searchform">
    Protein family: <input type="text" id="family" value="glucose-6-phosphatase" required><br>
    Taxonomy group: <input type="text" id="taxonomy" value="Aves" required><br>
    <button type="submit" id="searchbtn">Search</button>
</form>

<div id="loading" style="display:none;">
    Loading results...
</div>

<!--result table-->
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Length</th>
      <th>Sequence</th>
    </tr>
  </thead>
  <tbody id="results"></tbody>
</table>

<p id="error_message"></p>

<button id="btn_select">Get selected sequences:</button>

<button id="btn_conservation">Run conservation analysis</button>
<p id="conservation_alignment"></p>
<img id="conservation_image" />

<!--JS for query-->
<script src="query.js"></script>

<!--JS for select sequences-->
<script src="selection.js"></script>

<!--JS for conservation analysis-->
<script src="conservation.js"></script>

</body>
</html>
