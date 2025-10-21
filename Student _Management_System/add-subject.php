<?php session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{

if(isset($_POST['submit'])){
	
$cdata=$_POST['course-short'];
$coursedata=explode('-',$cdata);
$cshortname=$coursedata['0'];
$cfullname=$coursedata['1'];
$sb1=$_POST['sub1'];
$sb2=$_POST['sub2'];
$sb3=$_POST['sub3'];
$sb4=$_POST['sub4'];
$query=mysqli_query($con,"insert into subject(cshort,cfull,sub1,sub2,sub3,sub4)values('$cshortname','$cfullname','$sb1','$sb2','$sb3','$sb4')");
if($query){
echo '<script>alert("Subject Added successfully")</script>';
echo "<script>window.location.href='manage-subjects.php'</script>";
} else{
echo '<script>alert("Something went wrong. Please try again")</script>';
echo '<script>window.location.href=add-subject.php</script>';
}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title></title>
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
<link href="dist/css/sb-admin-2.css" rel="stylesheet">
<link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
						<div class="panel-heading">Add Subject</div>
						<div class="panel-body">
							<div class="row">
						 	<div class="col-lg-10">
									
										<div class="form-group">
											<div class="col-lg-4">
					 <label>Course Short Name<span id="" style="font-size:11px;color:Red">*</span>	</label>
											</div>
			
			<div class="col-lg-6">
			<select class="form-control" name="course-short" id="cshort" onchange="courseAvailability()" required="required" >
			<option VALUE="">SELECT</option>
				<?php $query=mysqli_query($con,"select * from tbl_course");
				while($res=mysqli_fetch_array($query)){
					?>							
			
                        <option VALUE="<?php echo htmlentities($res['cid']."-".$res['cfull']);?>"><?php echo htmlentities($res['cshort'])?> (<?php echo htmlentities($res['cfull'])?>)</option>
                        
                        
                    <?php }?>   			</div>
											 
                        </select>
					<span id="course-availability-status" style="font-size:12px;"></span>	
					</div>
					    </div>	
										
								<br><br>
								
								
		<div class="form-group">
		<div class="col-lg-4">
		<label>Subject 1</label>
		</div>
		<div class="col-lg-6">
		<input class="form-control"  name="sub1" required>
	</div>
	 </div>	
	<br><br>	

     <div class="form-group">
		<div class="col-lg-4">
		<label>Subject 2</label>
		</div>
		<div class="col-lg-6">
		<input class="form-control"  name="sub2" required>
	 </div>
	 </div>	
	<br><br>									
	<div class="form-group">
	<div class="col-lg-4">
	 <label>Subject 3</label>
	</div>
	<div class="col-lg-6">
	<input class="form-control"  name="sub3" required>
	
	</div>
	</div>


	<br><br>									
	<div class="form-group">
	<div class="col-lg-4">
	 <label>Subject 4</label>
	</div>
	<div class="col-lg-6">
	<input class="form-control"  name="sub4" >
	</div>
	</div>


	</div>	
	<br><br><br>								
										
	<div class="form-group">
											<div class="col-lg-4">
												
											</div>
											<div class="col-lg-6"><br><br>
	<input type="submit" class="btn btn-primary" name="submit" value="Add Subject"></button>
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
	

	<!-- jQuery -->
	<script src="bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- Metis Menu Plugin JavaScript -->
	<script src="../bower_components/metisMenu/dist/metisMenu.min.js"
		type="text/javascript"></script>
	<!-- Custom Theme JavaScript -->
	<script src="../dist/js/sb-admin-2.js" type="text/javascript"></script>
	<script>
function coursefullAvail() {
	$("#loaderIcon").show();
jQuery.ajax({
url: "course_availability.php",
data:'cfull1='+$("#cfull").val(),
type: "POST",
success:function(data){
$("#course-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

function courseAvailability() {
	$("#loaderIcon").show();
jQuery.ajax({
url: "course_availability.php",
data:'cshort1='+$("#cshort").val(),
type: "POST",
success:function(data){
$("#course-availability-status").html(data);
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
