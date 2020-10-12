<?php
include 'connect.php';
session_start();	
  if (isset($_SESSION['uid'])) 
 {	$id=$_SESSION['uid'];	
	$pid=$_GET['pid'];
	if(isset($_POST['save']))
	{
	
	$fname=$_POST['first_name'];
	$lname=$_POST['last_name'];
	$email=$_POST['email'];
	$hdl=$_POST['headline'];
	$summ=$_POST['summary'];
	$query="UPDATE profile SET first_name=:fname,last_name=:lname,email=:mail,headline=:hdl,summary=:summ WHERE prof_id=:pid AND id=:uid";
	$stmt=$pdo->prepare($query);
	$stmt->execute(array(':fname'=>$fname,':lname'=>$lname,':mail'=>$email,':hdl'=>$hdl,':summ'=>$summ,':pid'=>$pid,'uid'=>$id));


	// //deleting the pre existed entries.....
		 $sqli="DELETE FROM positions WHERE prof_id=:pid";
	 	 $stmti=$pdo->prepare($sqli);
	 	 $stmti->execute(array(':pid'=>$pid));

		//adding the positions again
		 $i=1;
		 $rank=1;
		 while (isset($_POST['year'.$i]) || isset($_POST['desc'.$i])) 
		 {
		 	$year=$_POST['year'.$i];
		 	$desc=$_POST['desc'.$i];
		 	$i++;
		 	if (strlen($year)==0 || strlen($desc)==0) {
		 		echo '<p style="color:red">all fields must be filled</p>';
		 		return true;
		 		header("Location:edit.php");
			}
		 	if (!is_numeric($year)) {
		 		echo '<p style="color:red;">year must be numeric</p>';
		 		return true;
		 		header("Location:edit.php");	
		 	}
		 	//die($profile_id);
		 	$query="INSERT INTO positions(prof_id,rank,year,description) VALUES (:pid,:rank,:year,:descr)";
		 	$res=$pdo->prepare($query);
		 	$res->execute(array(':pid'=>$pid,':rank'=>$rank,':year'=>$year,':descr'=>$desc));
		 	$rank++;
		 }
		 // //deleting out the previous one's from education
		    $problem="DELETE FROM education WHERE prof_id=:profi";
	 	    $ans=$pdo->prepare($problem);
	 	    $ans->execute(array(':profi'=>$pid));

		 //adding new one's to education
		 $int=1;
			$rnk=1;
			while (isset($_POST['yr'.$int]) || isset($_POST['edu'.$int])) 
			{
				//$pos_id=$_REQUEST['pos_id'];
				$yr=$_POST['yr'.$int];
				$edu=$_POST['edu'.$int];

				if (!is_numeric($yr)) {
					echo '<p style="color:red;">year must be numeric</p>';
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

		header("Location:index.php");
	
}
	else
	{
	$sql="SELECT * FROM profile WHERE prof_id=:pid AND id=:uid";
	$stmt=$pdo->prepare($sql);
	$stmt->execute(array(':pid'=>$pid,':uid'=>$id));
	$res=$stmt->fetch();

?>
<!DOCTYPE html>
<html>
<head>
	<title>edit</title>
	 <head>
...
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
...
</head>
</head>
<body>
		<h3 style="text-align: center;">REGISTER!</h3>
		<form style="text-align: center;" action="edit.php?pid=<?php echo $pid; ?>" method="post">
			<p>FIRST NAME:<input type="text" name="first_name" value="<?php echo $res['first_name']; ?>"></p>
			<p>LAST NAME:<input type="text" name="last_name" value="<?php echo $res['last_name']; ?>"></p>
			<p>EMAIL:<input type="email" name="email" value="<?php echo $res['email']; ?>"></p>
			<p>HEADLINE:<input type="text" name="headline" value="<?php echo $res['headline']; ?>"></p>
			<p>SUMMARY:<textarea name="summary" rows="4" cols="50"><?php echo $res['summary']; ?></textarea>></p>
			<p>
				POSITIONS: <input type="submit" id="addPos" value="+">
				<div id="positions">
				</div>
				EDUCATION: <input type="submit" id="addEd" value="+">
				<div id="posit">
					
				</div>
			</p>
			<p><input type="submit" name="save" value="Save"></p>
			<p><input type="reset" name="reset"></p>
		</form>
<?php

//fetching the data from positions table.....
	$que="SELECT * FROM positions WHERE prof_id=:pid";
	$exe=$pdo->prepare($que);
	$exe->execute(array(':pid'=>$pid));
	//$position=array();
	// while ($result=$exe->fetch()) {
	// 	$position[]=$result;
	// 	$ent=count($position);
	// }
	$result=$exe->fetchAll();
	$ent=count($result);
	for($i=0;$i<$ent;$i++){?>
<script type="text/javascript">
	count=0;
	$(document).ready(function(){
				//event.preventDefault();
				if (count>=9) {
					alert('can insert only 9 positions');
				}
				count++;
				window.console && console.log("adding position"+count)
				$('#positions').append(
						'<div id="position'+count+'">\
						<p>YEAR:<input type="text" name="year'+count+'" value="<?php echo $result[$i]['year']; ?>"/>\
						<input type="button" value="-" onclick=$("#position'+count+'").remove();></p> \
						<label>description</label><textarea name="desc'+count+'" rows="8" cols="50"><?php  echo $result[$i]['description']; ?></textarea>\
					</div>');
			});
</script>
<?php } 
//for showing the previous data from the education table	
	 $q="SELECT education.prof_id,institution.name,education.year
	 	FROM education 
	 	INNER JOIN institution ON education.inst_id=institution.inst_id
	 	WHERE prof_id=:prof ORDER BY rank";
	 $perform=$pdo->prepare($q);
	 $perform->execute(array(':prof'=>$pid));
	 $ress=$perform->fetchAll(PDO::FETCH_ASSOC);
	 $num=count($ress);
	 for($in=0;$in<$num;$in++)
	 {
?>
<script type="text/javascript">
	counter=0;
	$(document).ready(function(){
		if (counter >= 9) {
			alert('can insert only 9 entries');
		}
		counter++;
		window.console && console.log("adding education"+counter)
		$('#posit').append(
						'<div id="pos'+counter+'">\
						<p>YEAR:<input type="text" name="yr'+counter+'" value="<?php echo $ress[$in]['year'] ?>"/>\
						<input type="button" value="-" onclick=$("#pos'+counter+'").remove();></p>\
						<p>SCHOOL:<input type="text" size="80" name="edu'+counter+'" class="school" value="<?php echo $ress[$in]['name'] ?>"/></p>\
					</div>');
					$('.school').autocomplete({
  						source:"school.php"
  					});
			});
</script>

<?php }}} ?>


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
				window.console && console.log("adding position"+counter)
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