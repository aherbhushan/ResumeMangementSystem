<?php
include 'connect.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>view</title>
</head>
<body>
	<?php
		$pid=$_GET['pid'];
		$sql="SELECT * FROM profile WHERE prof_id=:pid";
		$stmt=$pdo->prepare($sql);
		$stmt->execute(array(':pid'=>$pid));
		$result=$stmt->fetch();

	?>
	<div>
		<b>NAME:</b>&nbsp<?php echo $result['first_name']." ".$result['last_name']; ?>
	</div><hr>
	<div>
		<b>EMAIL:</b>&nbsp<?php echo $result['email']; ?>
	</div><hr>
	<div>
		<b>HEADLINE:</b>&nbsp<?php echo $result['headline']; ?>
	</div><hr>
	<div>
		<b>SUMMARY:</b>&nbsp<?php echo $result['summary']; ?>
	</div><hr>
	<?php

	$que="SELECT * FROM positions WHERE prof_id=:pid";
	$exe=$pdo->prepare($que);
	$exe->execute(array(':pid'=>$pid));
	//$result=$exe->fetch();
	$position=array();
	while ($result=$exe->fetch()) {
		$position[]=$result;
		$ent=count($position);
	}	
	?>
	<div>
		<b>POSITIONS:</b>&nbsp<ul><?php
		if( count($position) > 0){ 
		$i=0;
	while($i<$ent){
		echo "<li>year:  ".$position[$i]['year']."<br>";
		echo "description:  ".$position[$i]['description']."<br><br></li>"; 
		$i++; 
	}
	?></ul><?php } else{ echo "No positions added yet"; }?>
	</div><hr>
	<?php
	$q="SELECT education.prof_id,institution.name,education.year
	 	FROM education 
	 	INNER JOIN institution ON education.inst_id=institution.inst_id
	 	WHERE prof_id=:prof ORDER BY rank";
	 $perform=$pdo->prepare($q);
	 $perform->execute(array(':prof'=>$pid));
	 $ress=$perform->fetchAll(PDO::FETCH_ASSOC);
	 $num=count($ress);
	?>
	<div>
		<b>EDUCATION:</b>&nbsp<ul><?php
		if($num > 0){
			$in=0;
			 while($in<$num)
	 {
		echo "<li>year:  ".$ress[$in]['year']."<br>";
		echo "education:  ".$ress[$in]['name']."<br><br></li>";
		$in++;}
		?>
	</ul>
	<?php } else{ echo "no education entries to show"; }?>
	</div><hr>
<a href="index.php"><button>done</button></a>
</body>
</html>