<?php
// This page is used to perform search.
// 1. check the user session
// 2. search form
// 3. search result
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

<!--JS for query-->
<script>
console.log("hello world");
// get elements
const btn = document.getElementById("searchbtn");
const output = document.getElementById("results");

async function getquery(event){
//stop the default form submission
     event.preventDefault();
    console.log("function started");  
	// query to get the maximun 20 results
    const family = document.getElementById("family").value;
    const taxonomy = document.getElementById("taxonomy").value;
    const errormes = document.getElementById("error_message"); 
    const loading = document.getElementById("loading");

    console.log("family:",family);
    console.log("taxonomy:",taxonomy);
    loading.style.display = "block";

    try{
            const response = await fetch("query.php",{
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `family=${family}&taxonomy=${taxonomy}`
            });

	    // check result
	    //const text = await response.text();
	    //console.log("respone text:", text); 
    // display the results in the table
    const data = await response.json();


/* test using example_dataset.json
    const response = await fetch("example_dataset.json");
    const data = await response.json();
*/

/* test js array	
    const data = [
  {id:"test", name:"demo", length:3, sequence:"AAA"}
];
*/
	
    output.innerHTML = "";


    data.forEach(item => {
        console.log("item:",item);
	const row = document.createElement("tr");
	row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td>${item.length}</td>
            <td style="max-width:500px; word-break:break-all;">
                ${item.sequence}
            </td>
	`;
	output.appendChild(row);
    }) 
 

/* test DOM
	const row = document.createElement("tr");
        row.innerHTML = `
            <td>id1</td>
            <td>name1</td>
            <td>length1</td>
            <td style="max-width:300px; word-break:break-all;">
                ASASASAS
            </td>
        `;
        output.appendChild(row);
 */

   } catch(err) {
    errormes.innerHTML = 'Error';
   console.error("FULL ERROR:", err);
   }

    loading.style.display = "none";

/* test code for event
	//stop the default form submission
	event.preventDefault();
	const result_t = document.getElementById("result_test");
	result_t.innerHTML = "successful";
*/

}

document.getElementById("searchform").addEventListener("submit",getquery)
</script>


</body>
</html>
