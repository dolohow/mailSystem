<?php

function show($query) {
	while($rows = mysql_fetch_row($query)) {
		echo "<tr><td>".$rows[1]."</td><td>".$rows[2]."</td><td>".$rows[3]."</td><td><input type=\"checkbox\" id=\"show\" name=\"id[]\" value=\"".$rows[0]."\" /></td></tr>";
	}
}

session_start();
include("class.php");

if(loginCheck() == true) {
	require("connect.php");
	include("template/menu.html");
	include("template/index.html");
	
	echo "Liczba skrzynek w bazie danych: <b>".showNumberOfAllBoxes('boxes')."</b><br /><br />";
	
	if(isset($_GET['Filtruj'])) {
		$max = 100;
		$page = $_GET['page'];
		settype($page, "integer");
		$login = addslashes($_GET['login']);
		$query = mysql_query("SELECT `id`, `login`, `password`, `creator` FROM `boxes` WHERE `login` LIKE '%$login%' ORDER BY `password` ASC LIMIT ".($page*$max).",".$max);
		$queryBoxx = mysql_query("SELECT * FROM `boxes` WHERE `login` LIKE '%$login%'");
		$all = mysql_num_rows($queryBoxx);
		$numberOfPages = ceil($all/$max);
		for($i=0;$i<$numberOfPages;$i++) {
			echo "<a href=index.php?login=".$login."&page=".$i."&Filtruj=Filtruj>".$i." </a>";
		}
		show($query);
	}
	
	if(isset($_GET['bring'])) {
		$id = $_GET['id'];
		$boxes = new boxes;
		$numberToMove = count($id);
		echo "<textarea rows=10 cols=100>";
		for($i=0 ; $i<$numberToMove ; $i++) {
			$query = mysql_query("SELECT login, password FROM `boxes` WHERE id='$id[$i]'");
			while($rows = mysql_fetch_row($query)) {
				echo "L: ".$rows[0];
				echo "P: ".$rows[1]."\r";
			}
		}
		echo "</textarea>";
		for($i=0 ; $i<$numberToMove ; $i++) {
			$boxes->del($id[$i]);
		}
		echo "Operacja zakończona powodzeniem";
	}
	
	if(isset($_GET['showuser'])) {
		$id = addslashes($_GET['showuser']);
		$queryBoxes = mysql_query("SELECT * FROM `boxes` WHERE `creator`='$id'");
		$queryOldBoxes = mysql_query("SELECT * FROM `old_boxes` WHERE `creator`='$id'");
		show($queryBoxes);
		show($queryOldBoxes);
	}
	mysql_close();
	include("template/footer.html");
}

?>