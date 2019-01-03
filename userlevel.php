<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>TU ES UN POINT - USER LEVEL</title>
		<link rel="stylesheet" href="style.css"/>

	</head>

	<body>
<?php

		// Create connection
		require_once("config.php");
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		if(isset($_GET["id"]) && !empty($_GET["id"]))
		{
			$sql = sprintf("SELECT name, level FROM leon_tuesunpoint_leveleditor WHERE id = %s", $_GET["id"]);
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			    // output data of each row
			    $row = $result->fetch_assoc();
			    echo sprintf('<h1>%s</h1><div id="gamecnv">%s</div>',$row["name"], $row["level"]);
			} else {
			    echo "not found";
			}

		}
		else
		{
			$sql = "SELECT id, name FROM leon_tuesunpoint_leveleditor";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			    // output data of each row
			    echo "Voici la liste de tous les niveaux créés par les gens :";
			    echo "<ul>";
			    while($row = $result->fetch_assoc()) {
			    	echo sprintf('<li><a href="userlevel?id=%s">%s</a></li>',$row["id"], $row["name"]);
			    }
			    echo "</ul>";
			} else {
			    echo "0 results";
			}
		}

		$conn->close();
?>
<nav>
	<a href="index.html">tu es un point</a>
</nav>
</body>

<script type="text/javascript">let nxtLvlURL = "win";</script>
<script type="text/javascript" src="vector.js"></script>
<script type="text/javascript" src="game.js"></script>
<script type="text/javascript" src="script.js"></script>

</html>