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


<button id="btn_conservation">Run conservation analysis on selected sequences</button>
<pre id="conservation_alignment"></pre>
<img id="conservation_image" />


<button id="btn_motif">Run motif analysis on selected sequence</button>
<pre id="motif_result"></pre>

<!--developing front-->
<?php
require_once 'login.php';
$user_id = $_SESSION['user_id'];
$mode = $_GET['mode'] ?? null;
$search_id = $_GET['search_id'] ?? null;


// if POST request with mode example
// 1. copy the example data in a new search entry to the current user in MySQL
// 2. set session the new search_id
if ($mode == 'example'){
	try {
	 $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
   	 $conn = new PDO($dsn, $username, $password);
   	 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	 //get example user 'example_G6P'
    	 $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
	$stmt->execute(['example_G6P']);
	$example_user = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

         $stmt = $conn->prepare("
   	 SELECT * FROM searches
	    WHERE user_id = ?
	    ORDER BY id DESC
	    LIMIT 1
	");
	$stmt->execute([$example_user]);
	$search = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("
	    SELECT * FROM results
	    WHERE search_id = ?
	    ORDER BY id DESC
	    LIMIT 1
	");
	$stmt->execute([$search['id']]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC); 

	 // insert the data into current user
	
	$stmt = $conn->prepare("
   	 INSERT INTO searches (user_id, protein_family, taxonomy, sequences)
  	  VALUES (?, ?, ?, ?)
	");
	$stmt->execute([
	    $user_id,
	    $search['protein_family'],
	    $search['taxonomy'],
	    $search['sequences']
	]);

	$new_search_id = $conn->lastInsertId();

	$stmt = $conn->prepare("
	    INSERT INTO results (search_id, motif, conservation, other)
	    VALUES (?, ?, ?, ?)
	");
	$stmt->execute([
	    $new_search_id,
	    $result['motif'],
	    $result['conservation'],
	    $result['other']
	]);



	// set session search_id
         $_SESSION['search_id'] = $new_search_id;

  	  $stmt = null;
	} catch(PDOException $e) {
		echo "<b><font color=\"red\">Connection failed</font></b>:<br/>" . $e->getMessage();
	}
} elseif ($mode == 'history'){

	//waiting
} else {
	// if start from new search
	$_SESSION['search_id'] = null;
	$search = null;
	$result = null;
}
$data = [
        "search" => $search,
        "result" => $result
    ];
?>


<script>
// pass the data from php to js
const initialData = <?php echo json_encode($data); ?>;
const searchId = <?php echo $_SESSION['search_id'] ?? 'null'; ?>;
if (searchId !==null) {
    renderResults(initialData);
}
function renderResults(data){
	console.log(data);
	// load sequences
	// waiting for development...

    // upload the results if exist
    if (data.result.motif !== null){
    const motif = JSON.parse(data.result.motif);	
    fetch(motif.motif_filename)
    .then(res => res.text())
    .then(text => {
        document.getElementById("motif_result").textContent = text;
    });
    }
}
</script>




<!--developing end-->


<!--JS for query-->
<script src="query.js"></script>

<!--JS for select sequences-->
<script src="selection.js"></script>

<!--JS for conservation analysis-->
<script src="conservation.js"></script>

<!--JS for motif analysis-->
<script src="motif.js"></script>

</body>
</html>
