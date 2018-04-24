<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../Reset.css">
		<link rel="stylesheet" type="text/css" href="../ResultsStyle.css">
		<title>Search</title>
	</head>
	<body>

	<div id="topBar">

		<form id="return" action="homepage.php">
		<input type="button" value="Return" />
		</form>

		<form id="account" action="accountSettings.php">
		<input type="button" value="Account" />
		</form>



	</div>

	<div id="searchContainer">

		<h2>Title</h2>


		<div id="searchBar">
			<form action="">
				<input type="text" placeholder="Search..." name="search">
				<button type="submit">Search</button>
			</form>
		</div>

		<div class="dropdown">
			<button class="dropbtn">Sort</button>
			<div class="dropdown-content">
				<a href="#">Most viewed</a>
				<a href="#">Highest rating</a>
				<a href="#">Alphabetical</a>
			</div>
		</div>

		<textarea name="tagBox" placeholder="Tags" disabled></textarea>



	</div>

	<div id="results">

		<table id="resultsTable">
			<tr>
			<!-- rows and collumns added via script-->
			</tr>
		</table>

	<!-- <script>
function myFunction() {
    var table = document.getElementById("resultsTable");
    var row = table.insertRow(0);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerHTML = "NEW CELL1";
    cell2.innerHTML = "NEW CELL2";
}
</script>
-->

	</div>

	</body>

</html>
