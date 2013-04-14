<?php

session_start();

require("class.php");

if(loginCheck() == true) {
	require("connect.php");
	include("template/menu.html");
	include("template/archive.html");
	
	echo "Liczba skrzynek w archiwum: <b>".showNumberOfAllBoxes('old_boxes')."</b>";
	
	$query = mysql_query("SELECT login, creator, deleter, date FROM `old_boxes` ORDER BY `id` ASC");
	$rank = new rank;
	while($rows = mysql_fetch_row($query)) {
		list($setNameOfRank1, $setColorOfRank1) = $rank->takeRank($rows[1]);
		list($setNameOfRank2, $setColorOfRank2) = $rank->takeRank($rows[2]);
		echo "<tr><td>".$rows[0]."</td><td><font color=".$setColorOfRank1.">".$rows[1]."</font></td><td><font color=".$setColorOfRank2.">".$rows[2]."</font></td><td>".$rows[3]."</td></tr>";
	}
	echo "</table>";
	mysql_close();
	include("template/footer.html");
}

?>