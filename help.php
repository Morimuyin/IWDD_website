<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style/basic.css">
</head>

<body>
<?php
include 'menu.php';
?>

<h1>Help</h1>
<br>

<h2>Login</h2>
<pre>
A new user account can be created when using the website for the first time by entering a new username and password. 
The username and password will be used for future login.
</pre>

<h2>Start</h2>
<pre>
Three options are available: New Search, Example and History.

Example: glucose-6-phosphatase proteins from Aves (birds)
History: displays the most recent search and results of the current user
</pre>

<h2>Analysis</h2>
<pre>
Protein sequences query:
A protein family and taxonomic group should be specified. 
Results are displayed in a table. 
Queries are performed using NCBI via edirect.
If the connection is unstable, retrying the query is recommened.

Protein conservation:
Text reports and plots are generated from selected multiple sequences. 
Results are obtained using EMBOSS plotcon, with sequence alignment performed by Clustal Omega.
<a href="https://emboss.sourceforge.net/apps/release/6.6/emboss/apps/plotcon.html">About protein conservation plot</a>

Motif Analysis:
Text reports are generated from the selected sequence. 
Results are obtained using EMBOSS patmatmotifs, which scans a protein sequence with motifs from the PROSITE database.

Statistics:
Text reports are generated using the selected sequence(s). 
Results are obtained using EMBOSS pepstats.
<a href="https://emboss.sourceforge.net/apps/release/6.6/emboss/apps/pepstats.html">About protein statistics</a>

</pre>



</body>
</html>

