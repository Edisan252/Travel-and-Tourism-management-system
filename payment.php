<?php
session_start();

include('includes/config.php');
$get_user = $_GET['pkgid'];
$select = "SELECT * FROM tblbooking WHERE BookingId = :id";
$qcheck = $dbh->prepare($select);
$qcheck->bindParam(':id', $get_user, PDO::PARAM_STR);
$qcheck->execute();
$results = $qcheck->fetchAll(PDO::FETCH_OBJ);

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
                style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">Pay to Scaner</h1>
        </div>
    </div>
    <!--- /banner ---->
    <!--- selectroom ---->
    <div class="selectroom">
        <div class="container">
            <div class="row">

            <div class="col-lg-3"></div>
            <div class="col-lg-6 text-center">
                <?php 
                foreach ($results as $result) {
                    if (property_exists($result, 'final_amount')) {
                        $final_amount = $result->final_amount;
                    } else {
                        echo "Error: 'final_amount' property does not exist.";
                    }
                }
                ?>
                <h2>Amount : RS.<?php echo $final_amount; ?>.</h2>
                
                <img src="images/payment.jpg" class="w-100">
            </div>
            <div class="col-lg-3"></div>
</div>
           
        </div>
    </div>
    <!--- /selectroom ---->
    <<!--- /footer-top ---->
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




</body>

</html>