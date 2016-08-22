<?php
include("config.php");

if(isset($_POST['submit']))
{
 $username= $_POST['username'];
 $password= $_POST['password'];

$username = addslashes($username);
$password = addslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);

$pass= md5($password);

  $seladmin ="SELECT id,UserName,Password,type FROM admin WHERE UserName='$username' && Password='$pass'";
 $SelRecAdmin = mysql_query($seladmin);

//$row = mysql_fetch_array($SelRecAdmin); //mysql_fetch_assoc($sql)
//print_r($row);exit;
 $tot_num_row=mysql_num_rows($SelRecAdmin);


if($tot_num_row > 0)
{
 
$fetchadmin = mysql_fetch_assoc($SelRecAdmin);
extract($fetchadmin);
//print_r($fetchadmin);
		if($type == 'election')
		{
			 $_SESSION['eleadmin']=$id;
			 $_SESSION['adminunm'] = $UserName;
			header('location:electioncard.php');

exit;
		}


  	// echo"sucess";
 	$_SESSION['adminid']=$id;
	$_SESSION['adminunm'] = $UserName;
    header('location:home.php');
}
else
{
 $_SESSION['msg']= 'Invalid username or password';
 //  echo"unsucess";
header('location:index.php');
}


}

?>

