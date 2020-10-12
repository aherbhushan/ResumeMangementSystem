<?php
include 'connect.php';
session_start();
$uname=$pass=$email=$id="";
if(count($_POST)>0)
{
	// var_dump($_POST);
	// die();
	$uname=htmlentities($_POST['name']);
	$pass=hash('md5',$_POST['pwd'] );
	$email=htmlentities($_POST['mail']);


	 if (empty($uname)|| empty($pass) || empty($email)) 
	 {
	 	echo '<h4 style="text-align:center;">*fields must be filled</h4>';
	 }
	 else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
	 {
	 	echo "<h4>*invalid email</h4>";
	 }

	 else 
	 {
	$sql="INSERT INTO users(name,password,email) VALUES (:name,:pass,:mail)";
	$stmt = $pdo->prepare($sql);
	$exe = $stmt->execute(array('name'=>$_POST['name'],'pass'=>$_POST['pwd'],'mail'=>$_POST['mail']));

	if ($exe) {
		echo "<script>alert('signup successful')</script>";
	}
	else{
		echo "<script>alert('signup failed')</script>";
	}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>signup</title>
</head>
<body>
		<form style="text-align:center;" method="post" action="signup.php" onsubmit="return submit();">
			<p>USERNAME:<input type="text" name="name" id ="un"></p>
			<p>PASSWORD:<input type="password" name="pwd" id="pw"></p>
			<p>E-MAIL:<input type="email" name="mail" id="ml"></p>
			<P><a href="login.php">go to login</a></P>
			<p><input type="submit"  value="signup" onclick="validate();"></p>
		</form>
		<script type="text/javascript">
			function validate()
			{
					var pass=document.getElementById('pw').value;
					var name=document.getElementById('un').value;
					var mail=document.getElementById('ml').value;
					if (pass=='' || name=='' || mail=='') {

						event.preventDefault();
						alert('please fill all the fields');
					}
					var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
					if (!expr.test(mail)) {
						fn4.innerHTML = "Invalid email address.";
						return false;
					}
					return true;

					
				}
		</script>
</body>
</html>