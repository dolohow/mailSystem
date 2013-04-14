<?php

session_start();

if($_SESSION['logged']) {
	$_SESSION['logged'] = false;
        $_SESSION['id'] = NULL;
        $_SESSION['login'] = NULL;
	echo "Zostałeś wylogowany<br><a href=login.php>ZALOGUJ PONOWNIE</a>";
}
else {
	echo "Nie jesteś zalogowany";
}

?>