<?php session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


if(isset($_POST['submit'])){	
$subid=$_GET['sid'];
$s1=$_POST['sub1'];
$s2=$_POST['sub2'];
$s3=$_POST['sub3'];
$s4=$_POST['sub4'];
$udate=$_POST['udate'];

$query=mysqli_query($con,"update subject set sub1='$s1',sub2='$s2',sub3='$s3',sub4='$s4',update_date='$udate' where subid='$subid'");
if($query){
echo '<script>alert("Subjects updated successfully")</script>';
echo "<script>window.location.href='manage-subjects.php'</script>";
}
	
 }

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Edit Subjects</title>
<!-- Bootstrap Core CSS -->
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="bower_components/metisMenu/dist/metisMenu.min.css"
	rel="stylesheet">
<!-- Custom CSS -->
<link href="dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="bower_components/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">
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
                            <?php 
$subid=intval($_GET['sid']);
$query=mysqli_query($con,"SELECT * FROM `subject` where subid='$subid'");
$sn=1;
while($res=mysqli_fetch_array($query)){?>	


						<div class="panel-heading"><b>Edit <?php echo $res['cfull'];?> course Subjects</b></div>
						<div class="panel-body">
							<div class="row">
						 	<div class="col-lg-10">


										<div class="form-group">
											<div class="col-lg-4">
<label>Subject 1</label>
</div>
<div class="col-lg-6">		
<input class="form-control" name="sub1" id="sub1"  value="<?php echo $res['sub1'];?>" required="required">       
</div>
</div>	<br><br>
								
<div class="form-group">
<div class="col-lg-4">
<label>Subject 2</label>
</div>
<div class="col-lg-6">
<input class="form-control" name="sub2" id="sub2" value="<?php echo $res['sub2'];?>" required="required">         
</div>
</div>	
										
<br><br>								
<div class="form-group">
<div class="col-lg-4">
<label>Subject 3</label>
</div>
<div class="col-lg-6">
<input class="form-control" name="sub3" id="sub3" value="<?php echo $res['sub3'];?>" required="required"></div>
</div> <br><br>	


<div class="form-group">
<div class="col-lg-4">
<label>Subject 4</label>
</div>
<div class="col-lg-6">
<input class="form-control" name="sub4" id="sub4" value="<?php echo $res['sub4'];?>" required="required"></div>
</div> <br><br>	

<div class="form-group">
<div class="col-lg-4">
<label>Date</label></div>
<div class="col-lg-6">
<input class="form-control" value="<?php echo date('d-m-Y');?>" readonly="readonly" name="udate"></div></div></div>	
<br><br>		
	
<?php }  ?>

<div class="form-group">
<div class="col-lg-4"></div>
<div class="col-lg-6"><br><br><input type="submit" class="btn btn-primary" name="submit" value="Update Course"></button></div>
</div></div></div>
								
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
</form>
</body>
</html>
<?php } ?>
