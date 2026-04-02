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
<p style="text-align:center;"> Structure of the website and database</p>
<pre>
================================================================================
MySQL Database:
================================================================================
The database consists of 4 tables:
users: Stores user account information.
searches: Stores search records.
sequences: Stores protein sequences retrieved from searches.
results: Stores protein analysis results(e.g., filenames).

Foreign keys relationships are illustrated by arrows in the diagram.
Some results not suitable for storage in the database are instead saved in the "results/" directory.

================================================================================
Website: 
================================================================================
1. Home - index.php
	Provides basic information about the website and user login.

2. Start - start.php
	Provides three options:
		- New Search
		- Example
		- History

3. Analysis - search.php
	Protein sequences query: 
		- Input form and result table
	Analysis:
		- Conservation(FASTA → alignment → plot)
		- Motif Analysis(FASTA → patmatmotifs)
		- Protein statistics(fasta → pepstats)

4. Help
	Provides guidance on how to use the website and include external links explaining the analysis results.

5. About
	Describes the design and structure of the website and the database.
	
6. Credit
	Statement of credits.

7. GitHub
	Link to the project repository.

Supporting files:
Additional files support the main pages by:
	- providing basic functionalities
	- handling sequence queries and analyses
	- managing data in the MySQL database and the "results/" directory
	
================================================================================
Details:
================================================================================
The "Example" and "History" functionalities are implemented using POST requests and session initialization.

1. Example
	- loads predefined demo data (user: example_G6P)
	- Database: copies the demo data to the current user
	- Session: updates the session search_id
	- HTML: displays the data

2. History
	- Displays the most recent search and corresponding results for the current user
	- HTML: displays the data if available
	- Session: updates session search_id if data exists

The query and analysis functionalities are implemented on the same page using AJAX.

This website uses essential cookies to manage user sessions and maintain login functionality. No tracking or third-party analytics cookies are used.
</pre>
</pre>

</body>
</html>

