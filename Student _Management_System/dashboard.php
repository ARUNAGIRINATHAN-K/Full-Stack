<?php session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Dashboard</title>
<!-- Bootstrap Core CSS -->
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="bower_components/metisMenu/dist/metisMenu.min.css"
	rel="stylesheet">
<!-- Custom CSS -->
<link href="dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<form method="post" >
	<div id="wrapper">

		<!-- Navigation -->
		<?php include('leftbar.php')?>;

<!--nav-->
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h4 class="page-header"> <?php echo strtoupper("welcome"." ".htmlentities($_SESSION['login']));?></h4>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">Add Course</div>
						<div class="panel-body">
							<div class="row">
						 	<div class="col-lg-12">
									
						
		<!---Courses ----->
      <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file fa-5x"></i>
                                </div>

<?php $query=mysqli_query($con,"SELECT cid FROM tbl_course");
$listedcourses=mysqli_num_rows($query);

?>


                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($listedcourses);?></div>
                                    <div>Listed Courses</div>
                                </div>
                            </div>
                        </div>
                        <a href="manage-courses.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
								

<!------------Subjects------------>

             <div class="col-lg-4 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-files-o fa-5x"></i>
                                </div>
<?php 
$query1=mysqli_query($con,"SELECT subid FROM subject");
$tsubjects=mysqli_num_rows($query1);
?>

                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($tsubjects);?></div>
                                    <div>Subjects</div>
                                </div>
                            </div>
                        </div>
                        <a href="manage-subjects.php">
                            <div class="panel-footer">
                                <span class="pull-left">Courses Wise Subjects</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

<!---- Students----->
       <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-fw fa-5x"></i>
                                </div>

<?php 
$query2=mysqli_query($con,"SELECT id FROM registration");
$totalstudents=mysqli_num_rows($query2);
?>


                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($totalstudents);?></div>
                                    <div>Total Students</div>
                                </div>
                            </div>
                        </div>
                        <a href="manage-students.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

<!---Countries---------->
     <div class="col-lg-4 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-globe fa-5x"></i>
                                </div>
<?php 
$query3=mysqli_query($con,"SELECT id FROM countries");
$countires=mysqli_num_rows($query3);
?>

                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($countires);?></div>
                                    <div>Listed Countires</div>
                                </div>
                            </div>
                        </div>
                  <!--       <a href="manage-users.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a> -->
                    </div>
                </div>

<!----- States--->
  <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file  fa-5x"></i>
                                </div>

<?php 
$query4=mysqli_query($con,"SELECT id FROM states");
$states=mysqli_num_rows($query4);
?>

     

                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($states);?></div>
                                    <div>Listed States</div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>

<!----------Cities---------->
<div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file fa-5x"></i>
                                </div>

<?php $query5=mysqli_query($con,"SELECT id FROM cities");
$cities=mysqli_num_rows($query5);

?>


                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($cities);?></div>
                                    <div>Listed Cities</div>
                                </div>
                            </div>
                        </div>
      
                    </div>
                </div>



	</div>	
										
	
		
			
													
				</div>

					</div>
								
							</div>
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
		

	</div>
	
	<script src="bower_components/jquery/dist/jquery.min.js"
		type="text/javascript"></script>

	
	<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"
		type="text/javascript"></script>

	<!-- Metis Menu Plugin JavaScript -->
	<script src="bower_components/metisMenu/dist/metisMenu.min.js"
		type="text/javascript"></script>

	<!-- Custom Theme JavaScript -->
	<script src="dist/js/sb-admin-2.js" type="text/javascript"></script>
	
	<script>
function courseAvailability() {
	$("#loaderIcon").show();
jQuery.ajax({
url: "course_availability.php",
data:'cshort='+$("#cshort").val(),
type: "POST",
success:function(data){
$("#course-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

function coursefullAvail() {
	$("#loaderIcon").show();
jQuery.ajax({
url: "course_availability.php",
data:'cfull='+$("#cfull").val(),
type: "POST",
success:function(data){
$("#course-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>
</form>
</body>
</html>
<?php } ?>
