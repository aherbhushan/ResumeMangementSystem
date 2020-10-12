<?php


if (!isset($_GET['term'])) {
	die("MISSING THE PARAMETER");
}

 // if ($_COOKIE[session_name()]) {
	// die("MUST BE LOGGED IN");
 // }

include 'connect.php';
session_start();
if (!isset($_SESSION['uid'])) {
	die("ACCESS DENIED");
}

$term=$_GET['term'];
// die($_GET['term']);
error_log("looking for term".$term);

$sql="SELECT * FROM institution WHERE name LIKE :prefix";
$stmt=$pdo->prepare($sql);
$stmt->execute(array(':prefix'=>$term."%"));

$retrieve=array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$retrieve[] = $row['name'];
}

echo (json_encode($retrieve));
?>