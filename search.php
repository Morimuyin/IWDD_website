<?php
// This page is used to perform search.
// 1. check the user session
// 2. search form
// 3. display search result, and update database
session_start();
include 'redir_user.php';
include 'menu.php';
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style/basic.css">
</head>
<body>

<?php
$user = $_SESSION['user'];
echo <<<_USER
<p>User: {$user} <a href="logout.php">Logout</a></p>
<hr>
_USER;
?>

<!--search form-->
<h1>Protein search:</h1>
<p>maximun 100 results</p>
<form id="searchform">
    Protein family: <input type="text" id="family" value="glucose-6-phosphatase" required><br>
    Taxonomy group: <input type="text" id="taxonomy" value="Aves" required><br>
    <button type="submit" id="searchbtn">Search</button>
</form>

<div id="loading" style="display:none;">
    Loading results...
</div>
<hr>
<!--result table-->
<table>
  <thead>
    <tr>
      <th>Select</th>
      <th>ID</th>
      <th>Name</th>
      <th>Length</th>
      <th>Sequence</th>
    </tr>
  </thead>
  <tbody id="results"></tbody>
</table>
<hr>
<p id="error_message"></p>

<hr>
<button id="btn_conservation">Run conservation analysis on selected sequences</button>
<pre id="conservation_alignment"></pre>
<img id="conservation_image" />

<hr>
<button id="btn_motif">Run motif analysis on one selected sequence</button>
<pre id="motif_result"></pre>

<hr>
<button id="btn_stats">Calculate statistics of selected sequences</button>
<pre id="stats_result"></pre>


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
	
	// get example search
         $stmt = $conn->prepare("
   	 SELECT * FROM searches
	    WHERE user_id = ?
	    ORDER BY id DESC
	    LIMIT 1
	");
	$stmt->execute([$example_user]);
	$search = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$search) {
            throw new Exception("No example search found");
	}

	// get example sequences 
	$stmt = $conn->prepare("
            SELECT * FROM sequences
            WHERE search_id = ?
        ");
        $stmt->execute([$search['id']]);
        $sequences = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// get example results
        $stmt = $conn->prepare("
	    SELECT * FROM results
	    WHERE search_id = ?
	    ORDER BY id DESC
	    LIMIT 1
	");
	$stmt->execute([$search['id']]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC); 

	 // insert the data into current user
	// new search
	$stmt = $conn->prepare("
   	 INSERT INTO searches (user_id, protein_family, taxonomy)
  	  VALUES (?, ?, ?)
	");
	$stmt->execute([
	    $user_id,
	    $search['protein_family'],
	    $search['taxonomy']
	]);

	$new_search_id = $conn->lastInsertId();
	
	//copy sequences
	$stmt = $conn->prepare("
            INSERT INTO sequences (search_id, sequence_id, name, sequence, length)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($sequences as $seq) {
            $stmt->execute([
                $new_search_id,
                $seq['sequence_id'],
                $seq['name'],
                $seq['sequence'],
                $seq['length']
            ]);
        }

	//copy result
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
// if history mode, load the selected search and its result
// set session search_id
	try {
	 $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
   	 $conn = new PDO($dsn, $username, $password);
   	 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// get search
	if ($search_id) {
        $search = $conn->prepare("
            SELECT * FROM searches 
            WHERE id = ? AND user_id = ?
        ");
        $search->execute([$search_id, $user_id]);
        $search = $search->fetch(PDO::FETCH_ASSOC);
        } else {
        $search = $conn->prepare("
            SELECT * FROM searches 
            WHERE user_id = ?
            ORDER BY id DESC
            LIMIT 1
        ");
        $search->execute([$user_id]);
        $search = $search->fetch(PDO::FETCH_ASSOC);
    }
	// get result if search exist
    if ($search) {
	// get sequences	
        $sequences = $conn->prepare("
            SELECT * FROM sequences
            WHERE search_id = ?
        ");
        $sequences->execute([$search['id']]);
        $sequences = $sequences->fetchAll(PDO::FETCH_ASSOC);


        $result = $conn->prepare("
            SELECT * FROM results 
            WHERE search_id = ?
            ORDER BY id DESC
            LIMIT 1
        ");
        $result->execute([$search['id']]);
        $result = $result->fetch(PDO::FETCH_ASSOC);

	
	//set session search_id
	$_SESSION['search_id'] = $search['id'];
    } else{
   	$_SESSION['search_id'] = null;
	$search = null;
	$sequences = null;
	$result = null;
    }
	} catch(PDOException $e) {
		echo "<b><font color=\"red\">Connection failed</font></b>:<br/>" . $e->getMessage();
	}
} else {
// if start from new search
	$_SESSION['search_id'] = null;
	$search = null;
	$sequences = null;
	$result = null;
}
$data = [
        "search" => $search,
	"sequences" => json_encode($sequences),
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
    if (data.sequences !== null){	

	    document.getElementById("family").value = data.search.protein_family;
	    document.getElementById("taxonomy").value = data.search.taxonomy;
	    output = document.getElementById("results");
	    output.innerHTML = "";
    sequences = JSON.parse(data.sequences);

    sequences.forEach(item => {
    //    console.log("item:",item);
	const row = document.createElement("tr");
	row.innerHTML = `
            <td>
            <input type="checkbox"
               class="row-select"
               data-id="${item.sequence_id}"
               data-name="${item.name}"
               data-sequence="${item.sequence}">
            </td>
            <td>${item.sequence_id}</td>
            <td>${item.name}</td>
            <td>${item.length}</td>
            <td style="max-width:500px; word-break:break-all;">
                ${item.sequence}
            </td>
	`;
	output.appendChild(row);
    }) 
    }



    // upload the results if exist
    if (data.result !== false){
    if (data.result.motif !== null){
    const motif = JSON.parse(data.result.motif);	
    fetch(motif.motif_filename)
    .then(res => res.text())
    .then(text => {
        document.getElementById("motif_result").textContent = text;
    });
    }
       
    if (data.result.conservation !== null){
    const conservation = JSON.parse(data.result.conservation);	
    document.getElementById("conservation_image").src = conservation.png_filename;
    fetch(conservation.aln_filename)
    .then(res => res.text())
    .then(text => {
        document.getElementById("conservation_alignment").textContent = text;
    });
    }

    //the other result
    if (data.result.other !== null){
    const stats = JSON.parse(data.result.other);		
    fetch(stats.stats_filename)
    .then(res => res.text())
    .then(text => {
        document.getElementById("stats_result").textContent = text;
    });
 
    }
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

<!--JS for protein statistics-->
<script src="stats.js"></script>


</body>
</html>
