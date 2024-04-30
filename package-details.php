<?php
session_start();
 error_reporting(0);
include('includes/config.php');
if(isset($_POST['submit2'])) {
    $pid = intval($_GET['pkgid']);
    $useremail = $_SESSION['login'];
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $comment = '';
    $price=$_POST['price'];
    $limit_of_pepole=$_POST['limit_of_pepole'];
    $status = 0;

    $select = "SELECT count(*) as total FROM tblmembers where user_email = :useremail and package_id = :pid";
$qcheck = $dbh->prepare($select);
$qcheck->bindParam(':pid', $pid, PDO::PARAM_STR);
$qcheck->bindParam(':useremail', $useremail, PDO::PARAM_STR);
$qcheck->execute();
$total_max_nm = $qcheck->fetchColumn();
$final_amount=($price/$limit_of_pepole) * $total_max_nm ;
// (100 / 10) * 8 = 80
     
    $contion_check = "SELECT * FROM tblbooking where PackageId = :pid and UserEmail = :useremail";
    $qchec1 = $dbh->prepare($contion_check);
    $qchec1->bindParam(':pid', $pid, PDO::PARAM_STR);
    $qchec1->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $qchec1->execute();
if($qchec1->rowCount() > 0){
echo"<script>alert('Already You have booking this package ');window.location.href='package-details.php?pkgid=$pid';</script>";
}else{
    $sql = "INSERT INTO tblbooking (PackageId, UserEmail, FromDate, ToDate, Comment, status, totall ,final_amount) VALUES (:pid, :useremail, :fromdate, :todate, :comment, :status, :total_people ,:final_amount)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':pid', $pid, PDO::PARAM_STR);
    $query->bindParam(':total_people', $total_max_nm, PDO::PARAM_STR); // Corrected binding
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $query->bindParam(':todate', $todate, PDO::PARAM_STR);
    $query->bindParam(':comment', $comment, PDO::PARAM_STR);
    $query->bindParam(':final_amount', $final_amount, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId) {
        echo"<script>alert('Booked Successfully');window.location.href='payment.php?pkgid=$lastInsertId';</script>";
    } else {
        $error = "Something went wrong. Please try again";
    }
}
}

?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Package Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="applijewelleryion/x-javascript">
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } 
    </script>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Custom Theme files -->
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--animate-->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.css" />
    <script>
    new WOW().init();
    </script>
    <!--<script src="js/jquery-ui.js"></script>
					<script>
						$(function() {
						$( "#datepicker,#datepicker1" ).datepicker();
						});
					</script>-->
    <style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }
    </style>
</head>

<body>
    <!-- top-header -->
    <?php include('includes/header.php');?>
    <div class="banner-3">
        <div class="container">
            <h1 class="wow zoomIn animated animated" data-wow-delay=".5s"
                style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;"> Package Details</h1>
        </div>
    </div>
    <!--- /banner ---->
    <!--- selectroom ---->
    <div class="selectroom">
        <div class="container">
            <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
            <?php 
$pid=intval($_GET['pkgid']);
$sql = "SELECT * from tbltourpackages where PackageId=:pid";
$query = $dbh->prepare($sql);
$query -> bindParam(':pid', $pid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)

{	?>


            <!-- Inside the loop that displays tour packages -->
            <div class="col-md-8 selectroom_right wow fadeInRight animated" data-wow-delay=".5s">
                <h2><?php echo htmlentities($result->PackageName); ?></h2>
                <p class="dow">#PKG-<?php echo htmlentities($result->PackageId); ?></p>
                <!-- Display number of people and limit of people -->
                <p><b>Number of People:</b> <?php echo htmlentities($result->number_of_people); ?></p>
                <p><b>Limit of People:</b> <?php echo htmlentities($result->limit_of_people); ?></p>
                <!-- Rest of the code -->
            </div>


            <form name="book" method="post">
                <div class="selectroom_top">
                    <div class="col-md-4 selectroom_left wow fadeInLeft animated" data-wow-delay=".5s">
                        <img src="admin/pacakgeimages/<?php echo htmlentities($result->PackageImage);?>"
                            class="img-responsive" alt="">
                    </div>
                    <div class="col-md-8 selectroom_right wow fadeInRight animated" data-wow-delay=".5s">
                        <h2><?php echo htmlentities($result->PackageName);?></h2>
                        <p class="dow">#PKG-<?php echo htmlentities($result->PackageId);?></p>
                        <p><b>Package Type :</b> <?php echo htmlentities($result->PackageType);?></p>
                        <p><b>Package Location :</b> <?php echo htmlentities($result->PackageLocation);?></p>
                        <p><b>Features:</b> <?php echo htmlentities($result->PackageFetures);?></p>
                        <div class="ban-bottom">
                            <div class="bnr-right">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="hidden" name="price" value="<?php echo htmlentities($result->PackagePrice); ?>">
                                        <input type="hidden" name="limit_of_pepole" value="<?php echo htmlentities($result->limit_of_people); ?>">
                                        <label class="inputLabel">From Date</label>
                                        <input type="date" placeholder="dd-mm-yyyy" name="fromdate" required="">
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="inputLabel">To Date</label>
                                        <input type="date" placeholder="dd-mm-yyyy" name="todate" required="">
                                    </div>
                                </div>

                            </div>
                            <div class="bnr-right"><br><br>

                            </div>
                            <div class="grand">
                                <p>Grand Total</p>
                                <h3>RS <?php echo htmlentities($result->PackagePrice);?></h3>

                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!--<div class="grand">
					<p>Grand Total</p>
					<h3>RS <?//php echo htmlentities($result->PackagePrice);?></h3>
				</div>-->
                    </div>
                    <h3>Package Details</h3>
                    <p style="padding-top: 1%"><?php echo htmlentities($result->PackageDetails);?> </p>
                    <div class="clearfix"></div>
                </div>
                <div class="selectroom_top">
                    <h2>Travelers Details</h2>
                    <div class="selectroom-info animated wow fadeInUp animated" data-wow-duration="1200ms"
                        data-wow-delay="500ms"
                        style="visibility: visible; animation-duration: 1200ms; animation-delay: 500ms; animation-name: fadeInUp; margin-top: -70px">
                        <ul>
                            <li class="spe">
                                <a href="#"  style="position:relative;bottom:20px;" class="btn btn-primary"
                                 data-toggle="modal" data-target="#newMemberModal">Add New Member</a>
                            </li>
                        </ul>
                        <div>
                            <?php
	   
if(isset($_SESSION['login'])){
	$logid=$_SESSION['login'];
}else{
$logid='0';
}
include('includes/config.php'); 

$sql = "SELECT * FROM tblmembers WHERE user_email = :logid and package_id=:pid";
$query = $dbh->prepare($sql);
$query->bindParam(':logid', $logid, PDO::PARAM_STR);
$query -> bindParam(':pid', $pid, PDO::PARAM_STR);
$query->execute();

if ($query->rowCount() > 0) {
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    ?>
                            <!-- Display members in a table -->
                            <table class="table table-hover table-bordered table-striped text-center bg-info">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Mobile</th>
                                        <th>Aadhar Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($results as $result) : ?>
                                    <tr>
                                        <td><?php echo htmlentities($result->name); ?></td>
                                        <td><?php echo htmlentities($result->age); ?></td>
                                        <td><?php echo htmlentities($result->mobile); ?></td>
                                        <td><?php echo htmlentities($result->aadhar_number); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php
}
?>
                            <script>
                            $(document).ready(function() {
                                <?php if ($query->rowCount() == 0) : ?>
                                $('#bookButton').prop('disabled', true);
                                $('#bookButton').attr('title', 'Please add members to book');
                                $('#bookButton').tooltip();
                                <?php endif; ?>
                            });
                            </script>

                        </div>
                        <ul>
                            <?php if($_SESSION['login'])
					{?>
                            <li class="spe" align="center">

                                <button id="bookButton" type="submit" name="submit2"
                                    class="btn-primary btn">Book</button>
                                    <div class="term" align="left">
                                    <input type="checkbox" name="term" id="term" required>
                                    <a href="cancel" download>Team and condition</a></div>
                            </li>
                            <?php } else {?>
                                <li>
                                    <input type="checkbox" name="term" id="term" required>
                                    <a href="cancel" download>Team and condition</a>
                                </li>
                            <li class="sigi" align="center" style="margin-top: 1%">
                                <a href="#" data-toggle="modal" data-target="#myModal4" class="btn-primary btn">
                                    Book</a>
                            </li>
                            <?php } ?>
                            <div class="clearfix"></div>
                        </ul>

                    </div>

                </div>
            </form>
            <?php }} ?>

            <div id="newMemberModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add New Member</h4>
                        </div>
                        <div class="modal-body">
                            <form action="add_member.php?pkgid=<?php echo $pid; ?>" method="post">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="age">Age:</label>
                                    <input type="number" class="form-control" id="age" name="age" required>
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile:</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" required>
                                </div>
                                <div class="form-group">
                                    <label for="aadhar">Aadhar Number:</label>
                                    <input type="text" class="form-control" id="aadhar" name="aadhar" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Member</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--- /selectroom ---->
    <!--- /footer-top ---->
        <?php include('includes/footer.php');?>
        <!-- signup -->
        <?php include('includes/signup.php');?>
        <!-- //signu -->
        <!-- signin -->
        <?php include('includes/signin.php');?>
        <!-- //signin -->
        <!-- write us -->
        <?php include('includes/write-us.php');?>




        <script>
        // Function to check if URL contains the error parameter
        function checkUrlForError() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error') && urlParams.get('error') === 'max_limit_reached') {
                // If error parameter is present and its value is 'max_limit_reached'
                // Display Bootstrap modal with the error message
                $('#maxLimitModal').modal('show');
            }
        }

        // Call the function when the page is loaded
        $(document).ready(function() {
            checkUrlForError();
        });
        </script>



        <script>
        // Function to clear the error parameter from the URL
        function clearErrorParameter() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error') && urlParams.get('error') === 'max_limit_reached') {
                var newUrl = window.location.pathname + window.location.search.replace(
                    /([&?])error=max_limit_reached(&|$)/, '$1').replace(/([?&])$/, '');
                history.replaceState(null, null, newUrl);
            }
        }

        // Call the function when the page is loaded
        $(document).ready(function() {
            clearErrorParameter();
        });
        </script>





        <!-- Bootstrap modal for maximum limit reached error message -->
        <div class="modal fade" id="maxLimitModal" tabindex="-1" role="dialog" aria-labelledby="maxLimitModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="maxLimitModalLabel">Error</h4>
                    </div>
                    <div class="modal-body">
                        Maximum number of people reached.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>