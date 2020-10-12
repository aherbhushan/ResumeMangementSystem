<?php
	
	include 'connect.php';
	session_start();
	$username=$password="";
	if(isset($_POST["log"]))
	{
		$uname=$_POST["email"];
		$pass=$_POST["pass"];
		if (empty($_POST["email"]) || empty($_POST["pass"])) 
		{
			echo '<script>alert("All fields must be filled")';
		}
		else{
			$sql="SELECT * FROM users WHERE email=:mail AND password=:pass";
			$stmt=$pdo->prepare($sql);
			$stmt->execute(array('mail' => $_POST["email"] ,'pass' => $_POST["pass"]));
			$res=$stmt->fetch();
			if ($stmt->rowCount()>0) {

				$_SESSION['id']=$_POST["email"];
				$_SESSION['uid']=$res['id'];
				header("Location:index.php");
			}
			else{
				echo "<script>alert('wrong credentials')</script>";
			}
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>login</title>
</head>
<body>
		<form method="post" action="login.php" style="text-align:center;">
			<p>EMAIL:<input type="email" name="email" id ="un"></p>
			<p>PASS:<input type="password" name="pass" id="pw"></p>
			<p><a href="signup.php">new? signup here</a></p>
			<p><input type="submit" name="log" value="Log In"></p>
		</form>
</body>
</html>