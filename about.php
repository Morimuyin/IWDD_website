<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style/basic.css">
</head>

<body>
<?php
include 'menu.php';
?>

<h1>About</h1>
<br>
<img src="structure.png" alt="Structure of the website and database" width="600" style="display:block;margin:auto">
<p style="text-align:center;"> Struture of the website and database</p>
<pre>
================================================================================
Database:
================================================================================
4 tables
users: Storing user information.
searches: Storing search record.
sequences: Storing sequences from searches.
results: Storing protein analysis results(filenames).

Foreign keys used are shown as arrows.
Some results not suitable for storing in the database are stored in results/.

================================================================================
Website: 
================================================================================
1. Home - index.php
	Provide basic information about this website.
	Login.

2. Start - start.php
	Three choices are provided:
		New search
		Example
		History

3. Analysis - search.php
	Protein sequences query: form + result table
	Analysis:
		Conservation(fasta -> aln -> png)
		Motif(fasta -> patmatmotifs)
		Protein statistics(fasta -> pepstats)

4. Help
Provide introduction to usage of the page and external links about the results.

5. About
Provide information about the design of the website and the database.
	
6. Credit
Statement of credits.

7. GitHub

Other files are used to support these pages:
	providing basic functionalities
	query and analysis
	managing data in MySQL database and results/
	
================================================================================
Details:
================================================================================
Example and history functionality is realized by POST and initialization:
	1. example
	load predefined demo data (user: example_G6P)
	Database: copy the data for the current user
	Session: update session search_id
	html: display the data

	2. history
	Show all the last search and corresponding results of current user
	html: display if exist
	Session: update session search_id if exist
The query and analysis are on the same page, which is realized using AJAX.
</pre>

</body>
</html>

