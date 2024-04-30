<?php 
session_start();
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
header('location:index.php');
}
else{
if(isset($_POST['submit']))
{
$pname=$_POST['packagename'];
$ptype=$_POST['packagetype'];	
$plocation=$_POST['packagelocation'];
$pprice=$_POST['packageprice'];	
$pfeatures=$_POST['packagefeatures'];
$n_pepoles=$_POST['n_pepoles'];
$pdetails=$_POST['packagedetails'];	
$limit_of=$_POST['limit_of'];
$pimage=$_FILES["packageimage"]["name"];
move_uploaded_file($_FILES["packageimage"]["tmp_name"],"pacakgeimages/".$_FILES["packageimage"]["name"]);
$sql="INSERT INTO tbltourpackages(PackageName,PackageType,number_of_pepole,limit_of_pepole,PackageLocation,PackagePrice,PackageFetures,PackageDetails,PackageImage) VALUES(:pname,:ptype,:limit_of,:nu_pepole,:plocation,:pprice,:pfeatures,:pdetails,:pimage)";
$query = $dbh->prepare($sql);
$query->bindParam(':pname',$pname,PDO::PARAM_STR);
$query->bindParam(':limit_of',$limit_of,PDO::PARAM_STR);
$query->bindParam(':ptype',$ptype,PDO::PARAM_STR);
$query->bindParam(':nu_pepole',$n_pepoles,PDO::PARAM_STR);
$query->bindParam(':plocation',$plocation,PDO::PARAM_STR);
$query->bindParam(':pprice',$pprice,PDO::PARAM_STR);
$query->bindParam(':pfeatures',$pfeatures,PDO::PARAM_STR);
$query->bindParam(':pdetails',$pdetails,PDO::PARAM_STR);
$query->bindParam(':pimage',$pimage,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$msg="Package Created Successfully";
echo"<script>window.location.href='create-package.php';</script>";
}
else 
{
$error="Something went wrong. Please try again";
echo"<script>window.location.href='create-package.php';</script>";
}

}
}
?>