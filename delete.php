<?php
include 'connect.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>delete</title>
</head>
<body>
<?php
if (isset($_SESSION['uid'])) 
{
	$pid=$_GET['pid'];
	$sql="DELETE FROM profile WHERE prof_id=:pid";
	$stmt=$pdo->prepare($sql);
	$stmt->execute(array(':pid'=>$pid));
	//$res=$stmt->fetch();
	?>
	<form method="post" action="index.php">
		<p>DELETE<?php //echo $res['first_name']; ?></p>
		<a href="index.php"><input type="submit" name="del" value="Delete"></a>
	</form>
		<?php
}
?>	
</body>
</html>