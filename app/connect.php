<?php

$mysql_host = "";
$mysql_database = "";
$mysql_user = "";
$mysql_password = "";

$connect = mysql_connect($mysql_host, $mysql_user, $mysql_password) || die("Błąd połączenia z bazą danych");

// Sprawdza czy tabela istnieje
mysql_select_db($mysql_database) || die("Nie można znaleźć odpowiedniej tebeli");

?>