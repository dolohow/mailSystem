<?php

require("connect.php");
session_start();
ob_start();

if($_SESSION['logged']) {
	include("template/menu.html");
	echo "Jesteś już zalogowany";
}
else {
	$_SESSIOON['id'] = NULL;
	$_SESSION['login'] = NULL;
	include("template/login.html");
	if(isset($_POST['OK'])) {
		$login = $_POST['login'];
		$password = $_POST['password'];
		
		if(empty($login) || empty($password)) {
			echo "Uzupełnij wszystkie pola";
		}
		else {
			$login = trim(strip_tags(mysql_real_escape_string(HTMLSpecialChars($login))));
			$password = trim(strip_tags(mysql_real_escape_string(HTMLSpecialChars($password))));
			
			$password = sha1(md5($password));
			
			$results = mysql_query("SELECT id FROM `users` WHERE login='$login' AND password='$password'");
			$query = mysql_query("SELECT `rank` FROM `users` WHERE login='$login'");
			$rows = mysql_fetch_row($query);
			
			if($rows[0] == "-1") {
				echo "Twoje konto zostało wyłączone";
			}
			elseif(mysql_num_rows($results)==1) {
				$row = mysql_fetch_array($results);
				$_SESSION['logged'] = true;
				header('Location: index.php');
			}
			else {
				echo "Podałeś nieprawidłowe dane";
			}
		}
	}
	include("template/footer.html");
}
mysql_close();
ob_end_flush();
?>