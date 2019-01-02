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
			$warning = sprintf('<div class="warning">%s</div>', $warning);
		}
		$form = '%s<form action="editor.php" method="post"><input type="text" name="name" value="%s"><textarea name="level">%s</textarea><input type="submit" name="submit" value="test"></form>'
		return sprintf($form, $warning, $name, $level)
	}

	function save_form($name='', $level='')
	{
		return "coucou";
	}

	function test_level($name='', $level='')
	{
		$form = '<h1>%s</h1><div id="gamecnv">%s</div><form action="editor.php" method="post"><input type="hidden" name="name" value="%s" /><input type="hidden" name="level" value="%s" /><input type="submit" name="submit" value="edit"><input type="submit" name="submit" value="enregistrer"></form>';
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
			if (empty($warning))
			{
				echo edit_form($_POST["name"], $_POST["level"], $warning);
			}
			else
			{
				echo test_level();
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


</body>

<script type="text/javascript">let nxtLvlURL = "editor";</script>
<script type="text/javascript" src="vector.js"></script>
<script type="text/javascript" src="game.js"></script>
<script type="text/javascript" src="script.js"></script>

</html>