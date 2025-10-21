<?php session_start();
include('includes/dbconnection.php');

if(isset($_POST['submit']))
  {
    $uname=$_POST['id'];
    $Password=$_POST['password'];
    $query=mysqli_query($con,"select ID,loginid from tbl_login where  loginid='$uname' && password='$Password' ");
    $ret=mysqli_fetch_array($query);
    if($ret>0){
      $_SESSION['aid']=$ret['ID'];
      $_SESSION['login']=$ret['loginid'];
     header('location:dashboard.php');
    }
    else{
      echo "Invalid Details";
    }
  }
  ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Student Record Management System |  Login </title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../dist/css/jquery.validate.css" />
</head>
<body>
 <h2 align="center">Student Record Management System</h2>
    <div class="container">
        <br><br><br><br>

            <div class="col-md-4 col-md-offset-4">

                <div class="panel panel-primary">

                    <div class="panel-heading">
                     <h3 class="panel-title">Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post">
                            <fieldset>
                                <div class="form-group">
             <input class="form-control" placeholder="Login Id"  id="id" name="id" type="text" required autofocus autocomplete="off">
                                </div>
                                <div class="form-group">
                            <input class="form-control" placeholder="Password" id="password"name="password" type="password" value="" required>
                            <a href="password-recovery.php">Password Recovery</a>
                                </div>
                              
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" value="login" name="submit" class="btn btn-lg btn-success btn-block">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
 <script src="dist/jquery-1.3.2.js" type="text/javascript"></script>
 <script src="dist/jquery.validate.js" type="text/javascript"></script>
 <script type="text/javascript">
            
            jQuery(function(){
                jQuery("#id").validate({
                    expression: "if (VAL.match(/^[a-z]$/)) return true; else return false;",
                    message: "Should be a valid id"
                });
                jQuery("#password").validate({
                    expression: "if (VAL.match(/^[a-z]$/)) return true; else return false;",
                    message: "Should be a valid password"
                });
                
            });
            
        </script>
</body>

</html>
