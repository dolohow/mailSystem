<?php

session_start();
ob_start();

require("class.php");

if(loginCheck() == true) {
	require("connect.php");
	include("template/menu.html");
	include("template/request.html");
	
	$date = date(c);
	$numberOfBoxes = $_POST['numberOfBoxes'];
	$typeOfBoxes = $_POST['typeOfBoxes'];
	$login = $_SESSION['login'];

	if(isset($_POST['OK'])) {
		if(empty($numberOfBoxes) || empty($typeOfBoxes)) {
			echo "Uzupe³nij wszystkie pola";
		}
		mysql_query("INSERT INTO `requests` (number, type, createdby, created) VALUES ('$numberOfBoxes', '$typeOfBoxes', '$login', '$date')");
		header('Location: index.php');
		mysql_close();
	}

	include("template/footer.html");
	ob_end_flush();
}

?>