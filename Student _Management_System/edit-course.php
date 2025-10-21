<?php session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{

if(isset($_POST['submit'])){
	
$cshortname=$_POST['course-short'];
$cfullname=$_POST['course-full'];
$udate=$_POST['udate'];
$cid=intval($_GET['cid']);
$query=mysqli_query($con,"update tbl_course set cshort='$cshortname',cfull='$cfullname',cdate='$udate' where cid='$cid'");
if($query){
echo '<script>alert("Course updated successfully")</script>';
echo "<script>window.location.href='manage-courses.php'</script>";
} else{
echo '<script>alert("Something went wrong. Please try again")</script>';
echo '<script>window.location.href=add-course.php</script>';
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title></title>
<!-- Bootstrap Core CSS -->
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<!-- MetisMenuCSS -->
<link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>
<body>
<form method="post" >
	<div id="wrapper">

		<!-- Navigation -->
		<?php include('leftbar.php')?>;


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
						<div class="panel-heading">Edit Course</div>
						<div class="panel-body">
							<div class="row">
						 	<div class="col-lg-10">
									

<?php $cid=intval($_GET['cid']);
$query=mysqli_query($con,"select * from tbl_course where cid='$cid'");
$sn=1;
$count=mysqli_num_rows($query);
if($count>0){
while($res=mysqli_fetch_array($query)){?>	


<div class="form-group">
<div class="col-lg-4">
<label>Course Short Name<span id="" style="font-size:11px;color:red">*</span>	</label>
</div>
<div class="col-lg-6">
  <input class="form-control" name="course-short" id="cshort"  value="<?php echo $res['cshort'];?>" required="required"  onblur="courseAvailability()">       
<span id="course-availability-status" style="font-size:12px;"></span>				</div></div>		
<br><br>
								
		<div class="form-group">
		<div class="col-lg-4">
		<label>Course Full Name<span id="" style="font-size:11px;color:red">*</span></label>
		</div>
		<div class="col-lg-6">
<input class="form-control" name="course-full" id="cfull" value="<?php echo $res['cfull'];?>" required="required"  onblur="coursefullAvail()">         
	<span id="course-status" style="font-size:12px;"></span>				</div>
	 </div>	
										
	 <br><br>								
										
	<div class="form-group">
	<div class="col-lg-4">
	 <label>Date</label>
	</div>
	<div class="col-lg-6">
	<input class="form-control" value="<?php echo date('d-m-Y');?>" readonly="readonly" name="udate">
	
	</div>
	</div>
	</div>	
<br><br>	
<?php }} else { ?>

<h5 style="color:red;" align="center">No record found</h5>
<?php } ?>	
		
							<div class="form-group">
											<div class="col-lg-4">
												
											</div>
											<div class="col-lg-6"><br><br>
							<input type="submit" class="btn btn-primary" name="submit" value="Update Course"></button>
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
	
jQuery.ajax({
url: "course_availability.php",
data:'cshort='+$("#cshort").val(),
type: "POST",
success:function(data){
$("#course-availability-status").html(data);


},
error:function (){}
});
}

function coursefullAvail() {
	
jQuery.ajax({
url: "course_availability.php",
data:'cfull='+$("#cfull").val(),
type: "POST",
success:function(data){
$("#course-status").html(data);


},
error:function (){}
});
}



</script>
</form>
</body>

</html>
<?php } ?>