<?php
	include("checkSession.php");
	$updateError = '';
	if (isset($_POST['firstName'])) {

		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$phonoNo = $_POST['phoneNo'];
		$password = $_POST['newPassword'];

		$sqlQuery = "update users set firstName='$firstName', lastName='$lastName', email='$email', phone='$phoneNo' where id='$userID';";
		$queryRet = mysqli_query($conn, $sqlQuery);

		if ($_POST['newPassword'] != '') {
			
			if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
				$salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
				$password = crypt($password, $salt);
				$sqlQuery = "update users set password='$password' where id='$userID';";
				$queryRet = mysqli_query($conn, $sqlQuery);
			}

		}

		if ($_FILES['updatedProfPic']['name'] != '') {

			//Image requirements
			$fileName = $_FILES['updatedProfPic']['name'];
			$fileTempName = $_FILES['updatedProfPic']['tmp_name'];
			$fileSize = $_FILES['updatedProfPic']['size'];
			$fileArray = explode(".",$fileName);
			$fileExt = strtolower(end($fileArray));
			$validExtensions = array('jpg', 'jpeg', 'png');
			$dirpath = realpath(dirname(getcwd()));

			if (in_array($fileExt, $validExtensions) == false) 
				$updateError = "Please upload only JPEG or PNG images";
			else if ($fileSize > 2097152)
				$updateError = "File Size too large. Please upload image of size less than 2MB";

			$delOld = unlink("$dirpath/Documents/profilePictures/$userID");
			if (! $delOld)
				$updateError = "Image Deletion Failed";

			$ret = move_uploaded_file($fileTempName, "$dirpath/Documents/profilePictures/$userID");
			if (! $ret)
				$updateError = "Image Upload Failed";
		}

		if (! $updateError)
			header("Location: welcome.php");

	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit</title>
</head>
<body>

<link rel="stylesheet" type="text/css" href="style.css">
<h2 style="text-align: center;"> Edit Your Profile </h2>

<div>

	<div style="text-align: center;"><img src="profilePictures/<?php echo $userID; ?>" style="width: 200px;"></div>
	<form style="text-align: center;" action="" method="post" enctype="multipart/form-data">
		<input type="file" name="updatedProfPic"><br />
		<input type="text" placeholder="First Name" name="firstName" value=<?php echo $firstName;?> required><br />
		<input type="text" placeholder="Last Name" name="lastName" value=<?php echo $lastName;?> required><br />
		<input type="email" placeholder="E-mail" name="email" value=<?php echo $username;?> required><br />
		<input type="text" placeholder="Phone Number" name="phoneNo" value=<?php echo $phoneNo;?> required><br />
		<input type="password" placeholder="New Password" name="newPassword"><br />
		<input type="submit" style="margin-top:8px;" value="Update"><br />
	</form>
	<p><?php echo $updateError ?></p>

</div>


</body>
</html>