<?php
session_start();

// Check if the user is logged in and email is available in session
if(isset($_SESSION['login']) && !empty($_SESSION['login'])) {
    $user_email = $_SESSION['login'];
} else {
}

include('includes/config.php');

$pkgid = $_GET['pkgid'] ?? '';

$sql_check = "SELECT COUNT(*) AS recruited_count FROM tblmembers WHERE user_email = :user_email AND package_id = :pkgid";
$query_check = $dbh->prepare($sql_check);
$query_check->bindParam(':user_email', $user_email, PDO::PARAM_STR);
$query_check->bindParam(':pkgid', $pkgid, PDO::PARAM_INT);
$query_check->execute();
$result_check = $query_check->fetch(PDO::FETCH_ASSOC);

// Fetch limit_of_people for the current package
$sql_limit = "SELECT limit_of_people FROM tbltourpackages WHERE PackageId = :pkgid";
$query_limit = $dbh->prepare($sql_limit);
$query_limit->bindParam(':pkgid', $pkgid, PDO::PARAM_INT);
$query_limit->execute();
$limit_result = $query_limit->fetch(PDO::FETCH_ASSOC);
$limit_of_people = $limit_result['limit_of_people'] ?? 0;

if ($result_check['recruited_count'] >= $limit_of_people) {
    header("Location: package-details.php?pkgid=$pkgid&error=max_limit_reached");
    exit(); 
}

$name = $_POST['name'];
$age = $_POST['age'];
$mobile = $_POST['mobile'];
$aadhar = $_POST['aadhar'];

$check = "SELECT * FROM tblmembers WHERE aadhar_number = :aadhar";
$check1 = $dbh->prepare($check);
$check1->bindParam(':aadhar', $aadhar, PDO::PARAM_STR);
$check1->execute(); 
if ($check1->rowCount() > 0) {
    echo "<script>alert('Aadhar already added');window.location.href='package-details.php?pkgid=$pkgid';</script>";
} else {
    $sql = "INSERT INTO tblmembers (name, age, mobile, aadhar_number, user_email, package_id) VALUES (:name, :age, :mobile, :aadhar, :user_email, :pkgid)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':age', $age, PDO::PARAM_INT);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':aadhar', $aadhar, PDO::PARAM_STR);
    $query->bindParam(':user_email', $user_email, PDO::PARAM_STR);
    $query->bindParam(':pkgid', $pkgid, PDO::PARAM_INT);
    $query->execute();
    header("Location: package-details.php?pkgid=$pkgid");
    exit();
}
?>
