<?php

class boxes {
	function add($boxLogin,$boxPassword) {
		$date = date(c);
		$creator = $_SESSION['login'];
		mysql_query("INSERT INTO `boxes` (login, password, creator, date) VALUES ('$boxLogin', '$boxPassword', '$creator', '$date')");
	}
	function del($id) {
		if(mysql_num_rows(mysql_query("SELECT '$id' FROM boxes WHERE id='$id'"))!=0) {
			$deleter = $_SESSION['login'];
			$row = mysql_fetch_row(mysql_query("SELECT '$id', login, password, creator, date FROM boxes WHERE id='$id'"));
			mysql_query("INSERT INTO `old_boxes` (login, password, creator, deleter, date) VALUES ('$row[1]', '$row[2]', '$row[3]', '$deleter', '$row[4]')");
			mysql_query("DELETE FROM `boxes` WHERE id='$row[0]'");
        }
		else {
			echo "Skrzynka numer ".$id." nie istnieje<br>";
		}
	}
}

class rank {
	function getRank($userName) {
		$query = mysql_query("SELECT rank FROM `users` WHERE login='$userName'");
		$getRank = mysql_fetch_row($query);
		return $getRank[0];
	}
	function takeRank($userName) {
		switch($this->getRank($userName)) {
			case 1:
				$setName = "Mrówka-skrzynki";
				$setColor = "#33FFFF";
				return array($setName, $setColor);
			case 2:
				$setName = "Mrówka-forward";
				$setColor = "#33FFFF";
				return array($setName, $setColor);
			case 3:
				$setName = "Moderator";
				$setColor = "#00CC00";
				return array($setName, $setColor);
			case 4:
				$setName = "Administrator";
				$setColor = "#FF0000";
				return array($setName, $setColor);
			case NULL:
				$setName = "Użytkownik";
				$setColor = "#0066FF";
				return array($setName, $setColor);
			case -1:
				$setName = "Konto wyłączone";
				return array($setName);
			case -2:
				$setName = "Zbanowany";
				return array($setName);
		}
	}
}

function loginCheck() {
	if($_SESSION['logged'] == true) {
		return true;
	}
	else {
		echo "<a href=login.php>ZALOGUJ</a> | <a href=register.php>ZAREJESTRUJ</a>";
	}
}

function showNumberOfAllBoxes($table) {
	$query = mysql_query("SELECT * FROM $table");
	return mysql_num_rows($query);
}

?>