<?php
include('includes/dbconnection.php');
if(!empty($_POST["id"])) 
{
 $id=intval($_POST['id']);
 $stmt = mysqli_query($con,"SELECT * FROM states WHERE country_id = '$id'");
 ?><option selected="selected">Select State</option><?php
 while($row=mysqli_fetch_array($stmt))
 {
  ?>
  <option value="<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['name']); ?></option>
  <?php
 }
 
 
 
}

if(!empty($_POST["did"])) 
{
 $id=intval($_POST['did']);
 $stmt =mysqli_query($con,"SELECT * FROM cities WHERE state_id = '$id'");
 ?><option value="">Select City</option><?php
 while($row=mysqli_fetch_array($stmt))
 {
  ?>
  <option value="<?php echo htmlentities($row['name']); ?>"><?php echo htmlentities($row['name']); ?></option>
  <?php
 }
 
 }


 if(!empty($_POST["cid"])) 
{
 $id=intval($_POST['cid']);
 $stmt = mysqli_query($con,"SELECT * FROM subject WHERE cshort = '$id'");
 $count=mysqli_num_rows( $stmt);
 if($count>0){
 while($row=mysqli_fetch_array($stmt))
 {
 echo ($row['sub1']."+".$row['sub2']."+ ".$row['sub3']); 
 } } else{
    echo "Subjects listed yet";
 }
 
 }

?>