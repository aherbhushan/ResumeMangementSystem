<?php
	include 'connect.php';
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>0deaeb75</title>
    <style>
table, th {
  border: 1px solid black;
}
</style>
</head>
<body style="text-align: center;">
     <h3>WELCOME,</h3>
    <h2>RESUME REGISTRY</h2>
<?php
if (isset($_GET['reg'])) {
    echo '<p style="color:green">entry added successfully</p>';
}


	if(isset($_SESSION['uid']))
	{
        ?>
        <?php
    $uid=$_SESSION['uid'];
	$sql="SELECT * FROM profile WHERE id=:uid";
	$stmt=$pdo->prepare($sql);
	$stmt->execute(array(':uid'=>$uid));
	?>
	<h2 style="text-align: center;">YOUR PROFILES</h2>
	   <table align="center">
               <tr>
                   <th>name</th>
                   <th>manage</th>
               </tr>

                    <?php while ($result = $stmt->fetch())
                            { 
                                $pid=$result['prof_id'];
                        ?>
                        <tr>
                            
                            <th><a href="view.php?pid=<?php echo $pid; ?>"><?php echo htmlspecialchars($result['first_name']."  ".$result['last_name']); ?></a></th>
                            <th><a href="edit.php?pid=<?php echo $pid; ?>">edit</a>&nbsp<a href="delete.php?pid=<?php echo $pid; ?>">delete</a></th>
                        </tr>
                     <?php 
                 }?></table> <br> <a href="add.php"><button>Add New Entry</button></a>&nbsp&nbsp
                        <a href="logout.php"><button>logout</button></a>
                 <?php 
               }
                 else
                {
                    ?>
                 
                    <h2>PROFILES</h2>
                    <p><a href="login.php">Please log in</a></p>
                    <p><a href="signup.php">SIGN UP</a></p>
                     <table align="center" style="padding:15px;">
                            <tr>
                                <th>name</th>
                                <th>headline</th>
                            </tr>
                        <tr>
                    <?php
                    $sql="SELECT * FROM profile";
                    $stmt=$pdo->prepare($sql);
                    $stmt->execute();
                    while($res=$stmt->fetch())
                    {
                        $pid=$res['prof_id'];
                        ?>
                        <tr>
                            <th><a href="view.php?pid=<?php echo $pid; ?>"><?php echo htmlspecialchars($res['first_name']." ".$res['last_name']); ?></a></th>
                             
                              <th><?php echo htmlspecialchars($res['headline']); ?></th>
                        </tr>
                        <?php
                    }  
                }?>
                </tbody>
            </table>
</body>
</html>