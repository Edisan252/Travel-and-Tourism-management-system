<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{ 
	// code for cancel
if(isset($_REQUEST['bkid']))
	{
$bid=intval($_GET['bkid']);
$status=2;
$cancelby='a';
$sql = "UPDATE tblbooking SET status=:status,CancelledBy=:cancelby WHERE  BookingId=:bid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query -> bindParam(':cancelby',$cancelby , PDO::PARAM_STR);
$query-> bindParam(':bid',$bid, PDO::PARAM_STR);
$query -> execute();

$msg="Booking Cancelled successfully";
}


if(isset($_REQUEST['bckid']))
	{
$bcid=intval($_GET['bckid']);
$status=1;
$cancelby='a';
$sql = "UPDATE tblbooking SET status=:status WHERE BookingId=:bcid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':bcid',$bcid, PDO::PARAM_STR);
$query -> execute();
$msg="Booking Confirm successfully";
}

	?>
<!DOCTYPE HTML>
<html>
<head>
<title>Admin manage Bookings</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="css/morris.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-2.1.4.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/table-style.css" />
<link rel="stylesheet" type="text/css" href="css/basictable.css" />
<script type="text/javascript" src="js/jquery.basictable.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#table').basictable();

      $('#table-breakpoint').basictable({
        breakpoint: 768
      });

      $('#table-swap-axis').basictable({
        swapAxis: true
      });

      $('#table-force-off').basictable({
        forceResponsive: false
      });

      $('#table-no-resize').basictable({
        noResize: true
      });

      $('#table-two-axis').basictable();

      $('#table-max-height').basictable({
        tableWrapper: true
      });
    });
</script>
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>
</head> 
<body>
   <div class="page-container">
   <!--/content-inner-->
<div class="left-content">
	   <div class="mother-grid-inner">
            <!--header start here-->
				<?php include('includes/header.php');?>
				     <div class="clearfix"> </div>	
				</div>
<!--heder end here-->
<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i>Manage Bookings</li>
            </ol>
<div class="agile-grids">	
				<!-- tables -->
				<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
				<div class="agile-tables">
					<div class="w3l-table-info">
					  <h2>Manage Bookings</h2>
					  <!--report-->


					  <form action="manage-bookings.php" method="get">
  <div class="date">
    <label class="inputLabel">From</label>
    <input type="date" name="from_date">
    <label class="inputLabel">To</label>
    <input type="date" name="to_date">
    <label class="inputLabel">Status</label>
    <select id="status" name="status">
      <option value="">All</option>
      <option value="0">Pending</option>
      <option value="1">Confirm</option>
      <option value="2">Cancel</option>
    </select>
    <button type="submit" class="btn btn-primary">Filter</button>
  </div>
</form>



					    <table id="table">
						<thead>
						  <tr>
						  <th>Booing id</th>
							<th>Name</th>
							<th>Mobile No</th>
							<th>Email Id</th>
							<th>Package</th>
							<th>From Date </th>
							<th>To Date </th>
							<!--<th>Passengers </th>-->
							<th>Reg Date</th>
							<th>Action </th>
						  </tr>
						</thead>
						<tbody>
<?php

$sql = "SELECT tblbooking.BookingId as bookid, tblusers.FullName as fname, tblusers.MobileNumber as mnumber, tblusers.EmailId as email, tbltourpackages.PackageName as pckname, tblbooking.PackageId as pid, tblbooking.FromDate as fdate, tblbooking.ToDate as tdate, tblbooking.Comment as comment, tblbooking.status as status, tblbooking.CancelledBy as cancelby, tblbooking.UpdationDate as upddate from tblbooking
left join tblusers on tblbooking.UserEmail=tblusers.EmailId
left join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId WHERE 1=1";

if (isset($_GET['from_date']) && !empty($_GET['from_date'])) {
    $sql .= " AND tblbooking.FromDate >= :from_date";
}

if (isset($_GET['to_date']) && !empty($_GET['to_date'])) {
    $sql .= " AND tblbooking.ToDate <= :to_date";
}

if (isset($_GET['status']) && $_GET['status'] !== '') {
    $sql .= " AND tblbooking.status = :status";
}

$query = $dbh->prepare($sql);

if (isset($_GET['from_date']) && !empty($_GET['from_date'])) {
    $query->bindParam(':from_date', $_GET['from_date'], PDO::PARAM_STR);
}

if (isset($_GET['to_date']) && !empty($_GET['to_date'])) {
    $query->bindParam(':to_date', $_GET['to_date'], PDO::PARAM_STR);
}

if (isset($_GET['status']) && $_GET['status'] !== '') {
    $query->bindParam(':status', $_GET['status'], PDO::PARAM_INT);
}

$query->execute();

$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{				?>		
						  <tr>
							<td>#BK-<?php echo htmlentities($result->bookid);?></td>
							<td><?php echo htmlentities($result->fname);?></td>
							<td><?php echo htmlentities($result->mnumber);?></td>
							<td><?php echo htmlentities($result->email);?></td>
							<td><a href="update-package.php?pid=<?php echo htmlentities($result->pid);?>"><?php echo htmlentities($result->pckname);?></a></td>
							<td><?php echo htmlentities($result->fdate);?></td>
							<td><?php echo htmlentities($result->tdate);?></td>
								<!--<td><a href="" class="btn btn-primary">Details</td>-->
								<td><?php if($result->status==0)
{
echo "Pending";
}
if($result->status==1)
{
echo $result->upddate;
}
if($result->status==2 and  $result->cancelby=='a')
{
echo  $result->upddate;
} 
if($result->status==2 and $result->cancelby=='u')
{
echo  $result->upddate;

}
?></td>

<?php if($result->status==2)
{
	?><td>Cancelled</td>
<?php } elseif($result->status==1)
{
	?><td>Confirmed</td>
<?php }


else {?>
<td><a href="manage-bookings.php?bkid=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('Do you really want to cancel booking')" >Cancel</a> / <a href="manage-bookings.php?bckid=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('booking has been confirm')" >Confirm</a></td>
<?php }?>

						  </tr>
						 <?php $cnt=$cnt+1;} }?>
						</tbody>
					  </table>
					</div>
				  </table>

				
			</div>
<!-- script-for sticky-nav -->
		<script>
		$(document).ready(function() {
			 var navoffeset=$(".header-main").offset().top;
			 $(window).scroll(function(){
				var scrollpos=$(window).scrollTop(); 
				if(scrollpos >=navoffeset){
					$(".header-main").addClass("fixed");
				}else{
					$(".header-main").removeClass("fixed");
				}
			 });
			 
		});
		</script>
		<!-- /script-for sticky-nav -->
<!--inner block start here-->
<div class="inner-block">

</div>
<!--inner block end here-->
<!--copy rights start here-->
<?php include('includes/footer.php');?>
<!--COPY rights end here-->
</div>
</div>
  <!--//content-inner-->
		<!--/sidebar-menu-->
						<?php include('includes/sidebarmenu.php');?>
							  <div class="clearfix"></div>		
							</div>
							<script>
							var toggle = true;
										
							$(".sidebar-icon").click(function() {                
							  if (toggle)
							  {
								$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
								$("#menu span").css({"position":"absolute"});
							  }
							  else
							  {
								$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
								setTimeout(function() {
								  $("#menu span").css({"position":"relative"});
								}, 400);
							  }
											
											toggle = !toggle;
										});
							</script>
<!--js -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   <!-- /Bootstrap Core JavaScript -->	   

</body>
</html>
<?php } ?>