<?php session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{

if(isset($_GET['del'])){  
$courseid=$_GET['del'];
$query=mysqli_query($con,"delete from subject where subid='$courseid'");
echo '<script>alert("Subjects  deleted")</script>';
echo "<script>window.location.href='manage-subjects.php'</script>";

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>view subject</title>
    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
      
      <?php include('leftbar.php')?>;

           
         <nav>
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
                        <div class="panel-heading">
                            View Course
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>S No</th>
                                            <th>Course</th>
                                            <th>Subject1</th>
                                            <th>Subject2</th>
                                            <th>Subject3</th>
                                            <th>Subject4</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                            <?php $query=mysqli_query($con,"SELECT * FROM `subject`");
                                   $sn=1;
                                while($res=mysqli_fetch_array($query)){?>	
                                        <tr class="odd gradeX">
                                            <td><?php echo $sn?></td>
                                               <td><?php echo htmlentities(strtoupper($res['cfull']));?></td>
                                            <td><?php echo htmlentities( strtoupper($res['sub1']));?></td>
                                             <td><?php echo htmlentities( strtoupper($res['sub2']));?></td>
                                             <td><?php echo htmlentities( strtoupper($res['sub3']));?></td>
                                              <td><?php echo htmlentities( strtoupper($res['sub4']));?></td>
                                            <td width="100"><a href="edit-subject.php?sid=<?php echo htmlentities($res['subid']);?>" class="btn btn-primary btn-xs">Edit</a> &nbsp;
                                             <a href="manage-subjects.php?del=<?php echo htmlentities($res['subid']); ?>" class="btn btn-danger btn-xs">Delete </a></td>
                                            
                                        </tr>
                                        
                                    <?php $sn++;}?>   	           
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
           
            
           
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- DataTables JavaScript -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
    </script>
</body>
</html>
<?php }  ?>
