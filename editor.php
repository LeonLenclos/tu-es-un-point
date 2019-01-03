<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>TU ES UN POINT - LEVEL EDITOR</title>
		<link rel="stylesheet" href="style.css"/>

	</head>

	<body>
<?php

	function edit_form($name='', $level='', $warning='')
	{
		if (!empty($warning)) {
			$warning = sprintf('<div class="warning">Attention : %s</div>', $warning);
		}
		$form = '%s<form action="editor.php" method="post">Titre :<input type="text" name="name" value="%s">Niveau :<textarea name="level" rows="30" cols="120">%s</textarea><input type="submit" name="submit" value="test"></form>';
		return sprintf($form, $warning, $name, $level);
	}

	function save_form($name='', $level='')
	{

		// Create connection
		require_once("config.php");
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		// Check connection
		if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		}

		$sql = sprintf("INSERT INTO leon_tuesunpoint_leveleditor (name, level)
		VALUES ('%s', '%s')", $name, $level);

		if ($conn->query($sql) === TRUE) {
		return sprintf('Ton niveau a été enregistré ! voici le lien vers ton niveau : <a href="userlevel?id=%s">%s</a>',$conn->insert_id, $name) ;
		} else {
		return "Error: " . $sql . "<br>" . $conn->error . "<br>(c'est pas normal, merci de contacter Léon)" ;
		}

		$conn->close();
	}

	function test_level($name='', $level='')
	{
		$form = '<h1>%s</h1><div id="gamecnv">%s</div><form action="editor.php" method="post"><input type="hidden" name="name" value="%s" /><input type="hidden" name="level" value="%s" /><input type="submit" name="submit" value="edit"><input id="enregistrer" type="submit" name="submit" value="enregistrer" disabled></form>';
		return sprintf($form, $name, $level, $name, $level);

	}
	// if (isset($_POST["submit"]) && !empty($_POST["name"])) {
	if (isset($_POST["submit"])) {
		$name = htmlspecialchars($_POST["name"]);
		if ($_POST["submit"] == 'test') {
			$warning = "";
			if (!isset($_POST["level"]) || empty($_POST["level"])){
				$warning = "Votre niveau est vide... ";
			}
			if (!isset($_POST["name"]) || empty($_POST["name"])){
				$warning = $warning."Vous devez indiquer un nom pour votre niveau...";
			}
			if (!empty($warning))
			{
				echo edit_form($_POST["name"], $_POST["level"], $warning);
			}
			else
			{
				echo test_level($_POST["name"], $_POST["level"]);
			}
		}
		elseif ($_POST["submit"] == 'edit') {
			echo edit_form($_POST["name"], $_POST["level"]);
		}
		elseif ($_POST["submit"] == 'enregistrer') {
			echo save_form($_POST["name"], $_POST["level"]);
		}

	}
	else {
		echo edit_form();
	}
?>

<div class="help">
	<h2>Aide :</h2>
	<p>Creer votre niveau dans la zone dédiée. Un niveau est fait de texte :</p>
	<ul>
		<li>Le point : <code>.</code> C'est le joueur. Le joueur commencera la partie là où vous l'avez placé.</li>
		<li>L'argent : <code>$</code> Le point peut ramasser ce truc.</li>
		<li>La lave : <code>~</code> Le point meurt s'il la touche.</li>
		<li>La porte : <code>∏</code> Le point doit arriver ici pour gagner la partie.</li>
		<li>Tout autre caractère est un mur que le point ne peut pas traverser.</li>
	</ul>
	<p>Une fois que vous avez créé votre niveau (et renseigné un titre), cliquez sur <strong>test</strong></p>
	<p>Vous pouvez tester le niveau et une fois que vous avez prouvé que le niveau est finissable (en rejoignant la porte) vous pouvez l'enregistrer en cliquant sur <strong>enregistrer</strong>. Vous pouvez aussi le ré-éditer en cliquant <strong>edit</strong></p>
</div>
<nav>
	<a href="index.html">tu es un point</a>
</nav>
</body>

<script type="text/javascript">let nxtLvlURL = "win";</script>
<script type="text/javascript" src="vector.js"></script>
<script type="text/javascript" src="game.js"></script>
<script type="text/javascript" src="script.js"></script>

</html>