<?php

session_start();
ob_start();

function showForm() {
	echo "<textarea name=add rows=25 cols=50></textarea><br>
		<input type=submit name=OK value=OK />
		</form>";
}

require("class.php");

if(loginCheck() == true) {
	require("connect.php");
	include("template/menu.html");
	include("template/add.html");
    $add = $_POST['add'];
	if($_GET['id'] != "all") {
		$id = intval($_GET['id']);
		$date = date(c);
		$login = $_SESSION['login'];
		$request = mysql_fetch_row(mysql_query("SELECT number, createdby FROM `requests` WHERE id='$id'"));
		$email = mysql_fetch_row(mysql_query("SELECT `email` FROM `users` WHERE login='$request[1]'"));
		$subject = "Twój Request został wypełniony - mailSystem";
		echo "<form action=add.php?id=$id method=POST>";
		showForm();
		if(isset($_POST['OK'])) {
			mail($email[0], $subject, $add);
			mysql_query("UPDATE `requests` SET filledby='$login', filled='$date' WHERE id='$id'");
			header('Location: index.php');
		}
	}
			
	if($_GET['id'] == "all") {
		echo "<form action=add.php?id=all method=POST>";
		showForm();
		$add = str_replace("\n", " ", $add);
		$add = str_replace("L: ", "", $add);
		$add = str_replace("P: ", "", $add);
		$add = explode(" ", $add);
		$numberOfIndexes = count($add);
		$boxes = new boxes;
		if(isset($_POST['OK'])) {
			for($i=0 ; $i<$numberOfIndexes-1 ; $i++) {
				$boxLogin = $add[$i];
				$boxPassword = $add[$numberOfIndexes-1];
				$boxes->add($boxLogin, $boxPassword);
			}
			echo "<br>Operacja zakończona sukcesem<br>";
			echo "Dodano <b>".$i."</b> skrzynek";
		}
	}
	mysql_close();
	include("template/footer.html");
	ob_end_flush();
}

?>