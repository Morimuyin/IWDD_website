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
A new user account can be created when using the website for the first time by typing in a new username and password. The username and password will be used for login.
</pre>

<h2>Start</h2>
<pre>
Three choices, new search, example and history, are provided.
Example: glucose-6-phosphatase proteins from Aves (birds)
History: the last search and result of the current user
</pre>

<h2>Analysis</h2>
<pre>
Protein sequences query:
Protein family and taxonomic group should be defined. Results are shown in the table. The query result is obtained from NCBI using Edirect. Sometimes retry is recommended since the connection is not stable.

Protein conservation:
Text reports and plots will be generated using the selected multiple sequences. The result is obtained using EMBOSS plotcon and the alignment is obtained using clustalo.
<a href=“https://emboss.sourceforge.net/apps/release/6.6/emboss/apps/plotcon.html”>About protein conservation plot</a>


Motif:
Text reports will be generated using the selected sequence. The result is obtained using EMBOSS patmatmotifs, which scans a protein sequence with motifs from the PROSITE database.

Statistics:
Text reports will be generated using the selected sequence(s). The result is obtained using EMBOSS pepstats.
<a href="https://emboss.sourceforge.net/apps/release/6.6/emboss/apps/pepstats.html">About statistics of protein properties</a>

</pre>



</body>
</html>

