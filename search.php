<?php
// This page is used to perform search.
session_start();
include 'redir_user.php';

echo <<<_HEAD
<html>
<head>
</head>
<body>
_HEAD;

$user = $_SESSION['user'];
echo <<<_USER
<p>User: {$user}</p>
<a href="logout.php">Logout</a>
_USER;

//search form
echo <<<_SEARCH
<h1>Protein search:</h1>
<form id="searchform">
    Protein family: <input type="text" id="family" required><br>
    Taxonomy group: <input type="text" id="taxonomy" required><br>
    <button type="submit" id="searchbtn">Search</button>
</form>
_SEARCH;

//result
echo <<<_RESULT
<table border="1">
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

<script>
// get elements
const btn = document.getElementById("searchbtn");
const output = document.getElementById("results");

async function getquery(){
    // query to get the maximun 20 results
    const family = document.getElementById("family").value;
    const taxonomy = document.getElementById("taxonomy").value;

    try{
    const response = await fetch("query.php",{
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `family=${family}&taxonomy=${taxonomy}`
    });

    // display the results in the table
    const data = await response.json();
    output.innerHTML = "";
    
    data.forEach(item => {
	const row = document.createElement("tr");
	row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td>${item.length}</td>
            <td style="max-width:300px; word-break:break-all;">
                ${item.sequence}
            </td>
	`;
	output.appendChild(row);
    } 
    } catch(err) {
    output.textContent = 'Error';    
    }
}

document.getElementById("searchform").addEventListener("submit",getquery)
</script>
_RESULT;

echo <<<_TAIL
</body>
</html>
_TAIL;
?>
~

