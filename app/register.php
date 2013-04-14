<?php

require("connect.php");
include("template/register.html");

if(isset($_POST['OK'])) {
	$login = $_POST['login'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	$email = $_POST['email'];
	$verificationCode = $_POST['verificationCode'];
	
	// Sprawdza czy wszystkie pola zostały wypełnione
	if(empty($login) || empty($password1) || empty($password2) || empty($email) || empty($verificationCode)) {
		echo "Uzupełnij wszystkie pola";
	}
	// Sprawdza poprawność kodu weryfikacyjnego
	elseif($verificationCode != md5("mailSystem.".$login)) {
		echo "Wprowadziłeś niewłaściwy kod weryfikacyjny.";
	}
	else {
		// Sprawdza czy podane hasła są identyczne
		if($password1 == $password2) {
			$login = trim(strip_tags(mysql_real_escape_string(HTMLSpecialChars($login))));
			$password1 = trim(strip_tags(mysql_real_escape_string(HTMLSpecialChars($password1))));
			$email = trim(strip_tags(mysql_real_escape_string(HTMLSpecialChars($email))));
			
			$results = mysql_query("SELECT * FROM users WHERE login='$login'");
	
			if(mysql_num_rows($results) != 0) {
				echo "Użytkownik o podanym loginie istnieje w bazie danych";
			}
			else {
				$password1 = sha1(md5($password1));
				mysql_query("INSERT INTO users (login, password, email) VALUES ('$login', '$password1', '$email')");
				echo "Twoje konto zostało utworzone<br>";
				echo "<a href=login.php>Zaloguj</a>";
			}
			mysql_close();
		}
		else {
			echo "Podane hasła nie zgadzają się";
		}
	}
}
?>