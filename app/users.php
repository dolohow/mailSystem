<?php

session_start();

require("class.php");
error_reporting(0);

if(loginCheck() == true) {
	require("connect.php");
	include("template/menu.html");
	if($_GET['show'] == "all") {
		$query = mysql_query("SELECT id, login FROM `users` ORDER BY `login` ASC");
		$rank = new rank;
		while($rows = mysql_fetch_row($query)) {
			list($setNameOfRank, $setColorOfRank) = $rank->takeRank($rows[1]);
			echo "<a href=users.php?id=".$rows[0]."><font color=".$setColorOfRank.">".$rows[1]."</font>, ";
		}
		echo "<br><br>";
	}
	if($_GET['id']) {
		$id = intval($_GET['id']);
		$queryUser = mysql_query("SELECT login FROM `users` WHERE id='$id'");
		$user = mysql_fetch_row($queryUser);
		// $lastWeek = date("omd", strtotime("-1 week")); /* Not impleted yet */
		
		$queryBoxes = mysql_query("SELECT `date` FROM `boxes` WHERE creator='$user[0]'");
		$queryOldBoxes = mysql_query("SELECT `date` FROM `old_boxes` WHERE creator='$user[0]'");
		$queryTakenBoxes = mysql_query("SELECT `date` FROM `old_boxes` WHERE deleter='$user[0]'");
		$queryRequests = mysql_query("SELECT `filled`, `number` FROM `requests` WHERE filledby='$user[0]'");
		
		$numberOfBoxes = mysql_num_rows($queryBoxes);
		$numberOfOldBoxes = mysql_num_rows($queryOldBoxes);
		$numberOfTakenBoxes = mysql_num_rows($queryTakenBoxes);
		while($numberOfFilledRequests = mysql_fetch_row($queryRequests)) {
			$count[] = $numberOfFilledRequests[1];
		}
		$numberOfFilled = array_sum($count);
		$numberOfCreatedBoxes = $numberOfBoxes + $numberOfOldBoxes + $numberOfFilled;
		
		$rank = new rank;
		list($setNameOfRank, $setColorOfRank) = $rank->takeRank($user[0]);
		
		echo "<h1><font color=".$setColorOfRank.">".$user[0]."</font></h1><br>";
		echo "Ranga: ".$setNameOfRank;
		echo "<p>Liczba założonych skrzynek: <b>".$numberOfCreatedBoxes."</b></p>";
		echo "<p>Liczba wziętych skrzynek: <b>".$numberOfTakenBoxes."</b></p>";
		echo "<p><a href='index.php?showuser=".$user[0]."'>Pokaż stworzone skrzynki</a></p>";
	}
	mysql_close();
	include("template/footer.html");
}

?>