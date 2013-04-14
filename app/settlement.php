<?php

require("connect.php");
error_reporting(0);

$query = mysql_query("SELECT `login` FROM `users` WHERE `rank`='1' ORDER BY `login` ASC");
while($users = mysql_fetch_row($query)) {
	$queryBoxes = mysql_query("SELECT `date` FROM `boxes` WHERE creator='$users[0]'");
	$queryOldBoxes = mysql_query("SELECT `date` FROM `old_boxes` WHERE creator='$users[0]'");
	$queryRequests = mysql_query("SELECT `filled`, `number` FROM `requests` WHERE filledby='$users[0]'");
	$numberOfBoxes = mysql_num_rows($queryBoxes);
	$numberOfOldBoxes = mysql_num_rows($queryOldBoxes);
	while($numberOfFilledRequests = mysql_fetch_row($queryRequests)) {
		$count[] = $numberOfFilledRequests[1];
	}
	$numberOfFilled = array_sum($count);
	$numberOfCreatedBoxes = $numberOfBoxes + $numberOfOldBoxes + $numberOfFilled;
	$message = "Liczba założonych skrzynek: ".$numberOfCreatedBoxes;
	mail('cthetmanek@gmail.com', $users[0], $message) or die("Błąd");
	unset($count);
}
$querySave = mysql_query("SELECT `login`, `password`, `creator` FROM `boxes` WHERE `creator`!='NULL'");
$open = fopen("save.html", "w");
fwrite($open, "<table>");
while($save = mysql_fetch_row($querySave)) {
	fwrite($open, "<tr><td>$save[0]</td><td>$save[1]</td><td>$save[2]</td></tr>");
}
fclose($open);

mail('cthetmanek@gmail.com', "Wszystkie skrzynki", "http://nh.bij.pl/mailSystem/save.html");

mysql_query("TRUNCATE TABLE `old_boxes`");
mysql_query("TRUNCATE TABLE `requests`");
mysql_query("UPDATE `boxes` SET `creator`='NULL'");

?>
