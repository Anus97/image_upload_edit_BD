<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">


<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Add New Row
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary" id="exampleModalLabel">Modal for Image uploading</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  
       <form action="/admin/go.php?widget=edit_data" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label>Enter the Id</label>
    <input type="text" class="form-control disable" name="id" placeholder="Enter your Id">
  </div>
  <div class="form-group">
    <label>Enter your name</label>
    <input type="text" class="form-control" name="name" placeholder="Enter your name">
  </div>
  <div class="form-group">
    <label>Your email Id</label>
    <input type="email" class="form-control" name="email" placeholder="Enter your email address">
  </div>
  <div class="form-group">
    <label>Enter your address</label>
    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
  </div>
   <div class="form-group">
    <label>upload your image</label>
    <input type="file" class="form-control" name="image">
  </div>		   
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-success" value="submit" name="submit">
		 </div>
		   </form>
        </div>
		</div>
	</div>
	</div>

<?php
$username = brilliantDirectories::getDatabaseConfiguration("website_user");
$password = brilliantDirectories::getDatabaseConfiguration("website_pass");
$host = brilliantDirectories::getDatabaseConfiguration("ftp_server");

($ftp = ftp_connect($host)) or die("Couldn't connect to $ftp");
ftp_login($ftp, $username, $password);

ftp_pasv($ftp, true);

$local_directory = $_FILES["image"]["tmp_name"];

$server_path = "/public_html/images/" . $_FILES["image"]["name"];

if (ftp_put($ftp, $server_path, $local_directory, FTP_BINARY)) {
    //echo "Successfully uploaded $server_path.";
}
ftp_close($ftp);

if (isset($_POST["submit"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $image = $_FILES["image"]["name"];

    $sql = "INSERT INTO image_upload_table (id,name,email,address,image)
	 VALUES ('$id','$name','$email','$address','$image')";
     (mysql_query($sql));
	
	//echo "INSERT INTO image_upload_table (id,name,email,address,image)
	 //VALUES ('$id','$name','$email','$address','$image')";

}

$result = mysql_query("select * from image_upload_table");
?>
	<table class="table table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Address</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    </thead>
		
		<?php
while($row = mysql_fetch_assoc($result))
{
	?>
	<tr>
		<td><?php echo $row['id'];?></td>
		<td><?php echo $row['name'];?></td>
		<td><?php echo $row['email'];?></td>
		<td><?php echo $row['address'];?></td>
		<td><img src = "http://www.fiftyinc.com/images/<?php echo $row['image']; ?>" width=50 height=50/></td>
		<td>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal_<?php echo $row['id'];?>">Edit</button>
			
<button type="button"  class="btn btn-danger delete_btn"  data-toggle="modal" data-target="#deletemodal" delete-id = "<?php echo $row['id'];?>"> DELETE </button>
			</td></tr>

			
 <div class="modal" tabindex="-1" role="dialog" id="myModal_<?php echo $row['id'];?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		
      <div class="modal-body">
        <form method="POST">
		<div class="form-group">
			<label>Enter user Id</label>	
            <input type="text" class="form-control" name="id" value="<?php echo $row['id'];?>"><br><br>
        </div>
		<div class="form-group">
			<label>Enter First Name</label>
	        <input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>"><br><br>
        </div>
		<div class="form-group">
			<label>Enter the Last Name</label>
	        <input type="text" class="form-control" name="email" value="<?php echo $row['email'];?>"><br><br>
        </div>
		<div class="form-group">
			<label>Enter the Last Name</label>
	        <input type="text" class="form-control" name="address" value="<?php echo $row['address'];?>"><br><br>
        </div>
		<div class="form-group">
			<label>image</label>
	        <input type="file" class="form-control" name="image" value="<?php echo $row['image'];?>"><br><br>
        </div>
	     <div class="modal-footer">
        <input type="submit" class="btn btn-success" name="edit" value="Save Changes" >
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
	</form>
    </div>
  </div>
</div>

<?php
if(isset($_POST['edit'])) {
	$id =  $_POST['id'];
	$name =  $_POST['name'];
	$email =  $_POST['email'];
	$address =  $_POST['address'];
	$image = $_POST['image'];
	
$editt = "UPDATE `image_upload_table` SET `name`='$name',`email`='$email',`address`='$address',`image`='$image' WHERE `id` = '$id'";
	
	$qry = mysql_query($editt);
} 
}?>
			
	<form action=" " method="POST">
  <div class="modal-body">
    <input type="hidden" name="delete" id="delete_id" />
    <h4> Are you sure want to delete this data? </h4>
  </div>
  <div class="modal-footer">
    <button type="submit" name="deletedata" class="btn btn-primary">Yes</button>
    <button type="button" class="btn btn-secondary delete_btn" data-dismiss="modal">No</button>
  </div>
</form>
</div>
</table>

<?php

<?php

$results= mysql_query("SELECT * FROM image_upload_table WHERE id = '$id'");

while($row = mysql_fetch_assoc($results)){

   $id =  $results['id'];
  $name =  $results['name'];
  $email =  $results['email'];
  $address =  $results['address'];
  $image = $results['image'];
}

$qry = "DELETE FROM 'image_upload_table' WHERE id = '$id'";

//echo "DELETE FROM 'image_upload_table' WHERE id = '$id'";

mysql_query($qry);
?>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
 
	 