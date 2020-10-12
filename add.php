<?php
include 'connect.php';
session_start();
$id=$fname=$lname=$mail=$headline=$summary=$pid="";
if (isset($_SESSION['uid'])) {
	echo "WELCOME"."<br>".$_SESSION['id']."<br>";
	//$pid=$_GET['pid'];	

	if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) 
	{	

		$id=$_SESSION['uid'];
		$fname=$_POST['first_name'];
		$lname=$_POST['last_name'];
		$mail=$_POST['email'];
		$headline=$_POST['headline'];
		$summary=$_POST['summary'];
		if (strlen($_POST['first_name'])==0 || strlen($_POST['last_name'])==0 || strlen($_POST['email'])==0 ||strlen($_POST['headline']) == 0 || strlen($_POST['summary']) == 0) 
		{
			echo "<script>All values are required</script>";
		}
		else
		{
			$sql="INSERT INTO profile(id,first_name,last_name,email,headline,summary)  
					VALUES (:id,:fname,:lname,:mail,:headline,:summary)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array('id'=>$id,'fname' =>$_POST['first_name'] ,'lname'=>$_POST['last_name'],'mail'=>$_POST['email'],'headline'=>$_POST['headline'],'summary'=>$_POST['summary']));
			// if ($res) {
			// 	header("Location:index.php?reg=1");
			// }
			// var_dump($result=$stmt->fetch());
			// die();
			//  if($result=$stmt->fetch()){
			//  	$profile_id= $result['prof_id'];
			//  }
			$pid=$pdo->lastInsertId();
			$i=1;
			$rank=1;
			while (isset($_POST['year'.$i]) || isset($_POST['desc'.$i])) 
			{
				//$pos_id=$_REQUEST['pos_id'];
				$year=$_POST['year'.$i];
				$desc=$_POST['desc'.$i];
				
					if (!is_numeric($year)) {
					echo '<script>alert("year must be numeric")</script>';
					return true;
					header("Location:add.php");
				}

				if (strlen($year)==0 || strlen($desc)==0) {
					echo '<p style="color:red;">all fields must be filled</p>';
					return true;
					header("Location:add.php");
				}
			
				//die($profile_id);
				$query="INSERT INTO positions(prof_id,rank,year,description) VALUES (:pid,:rank,:year,:descr)";
				$res=$pdo->prepare($query);
				$res->execute(array(':pid'=>$pid,':rank'=>$rank,':year'=>$year,':descr'=>$desc));
				$i++;
				$rank++;
			}
			
			$int=1;
			$rnk=1;
			while (isset($_POST['yr'.$int]) || isset($_POST['edu'.$int])) 
			{
				//$pos_id=$_REQUEST['pos_id'];
				$yr=$_POST['yr'.$int];
				$edu=$_POST['edu'.$int];

				if (!is_numeric($yr)) {
					echo '<script>alert("year must be numeric")</script>';
					return true;
					header("Location:add.php");
				}
				
				if (strlen($yr)==0 || strlen($edu)==0) {
					echo '<p style="color:red;">all fields must be filled</p>';
					return true;
					header("Location:add.php");
				}
				$sq="SELECT * FROM institution WHERE name =:name";
				$stmte=$pdo->prepare($sq);
				$ress=$stmte->execute(array(':name'=>$edu));
				if ($ress) {
					$result=$stmte->fetch();
					if ($result != NULL) {
						$institutionid = $result[0];
					}
					else
					{
						$newInstId=$pdo->prepare("INSERT INTO institution (name) value (:name)");
						$perform=$newInstId->execute(array(':name'=>$edu));
						$institutionid=$pdo->lastInsertId();
					}
				}
			
				//die($profile_id);
				$query="INSERT INTO education(prof_id,inst_id,rank,year) VALUES (:pid,:instid,:rnk,:yr)";
				$res=$pdo->prepare($query);
				$res->execute(array(':pid'=>$pid,':instid'=>$institutionid,':rnk'=>$rank,':yr'=>$yr));
				$int++;
				$rnk++;
			}
			
			header("Location:index.php?reg=1");
		}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>add</title>
...
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
...
</head>
<body>
		<h3 style="text-align: center;">RESGISTER!</h3>
		<form autocomplete="off" style="text-align: center;" action="add.php" method="post">
			<p>FIRST NAME:<input type="text" name="first_name"></p>
			<p>LAST NAME:<input type="text" name="last_name"></p>
			<p>EMAIL:<input type="email" name="email"></p>
			<p>HEADLINE:<input type="text" name="headline"></p>
			<p>SUMMARY:<textarea name="summary" rows="4" cols="50"></textarea>></p>
			<p>
				POSITIONS: <input type="submit" id="addPos" value="+">
				<div id="positions">
				</div>
				EDUCATION: <input type="submit" id="addEd" value="+">
				<div id="posit">
					
				</div>
			</p>
			<p><input type="submit" name="Add" value="Add"></p>
			<!--<p><input type="reset" name="reset"></p>-->
		</form>
		<p style="text-align: center;">WISH TO LOGOUT!
		<a href="logout.php" style="text-align: center;"><input type="submit" name="logout" value="LOGOUT"></a></p>
<?php
}
else
{
	echo '<h2 style="text-align:center;">ACCESS DENIED</h2>';
}
?>
<script type="text/javascript">
	count=0;
	$(document).ready(function(){
			$('#addPos').click(function(event){
				window.console && console.log('document called')
				event.preventDefault();
				if (count>=9) {
					alert('can insert only 9 positions');
				}
				count++;
				window.console && console.log("adding position"+count)
				$('#positions').append(
						'<div id="position'+count+'">\
						<p>YEAR:<input type="text" name="year'+count+'" value=""/>\
						<input type="button" value="-" onclick=$("#position'+count+'").remove();></p> \
						<textarea name="desc'+count+'" rows="8" cols="50"></textarea>\
					</div>');
			});
	});
	counter=0;
	$(document).ready(function(){
			$('#addEd').click(function(event){
				window.console && console.log('document called')
				event.preventDefault();
				if (counter>=9) {
					alert('can insert only 9 positions');
				}
				counter++;
				window.console && console.log("adding education"+counter)
				$('#posit').append(
						'<div id="pos'+counter+'">\
						<p>YEAR:<input type="text" name="yr'+counter+'" value=""/>\
						<input type="button" value="-" onclick=$("#pos'+counter+'").remove();></p> \
						<p>SCHOOL:<input type="text" size="80" name="edu'+counter+'" class="school" value=""/></p>\
					</div>');
					$('.school').autocomplete({
  						source:"school.php"
  					});
			});
	});
</script>
</body>
</html>
