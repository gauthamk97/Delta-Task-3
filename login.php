<?php

	include("config.php");
	$error='';
	session_start();

	//Already logged in
	if (isset($_SESSION['loginID'])) {
		header("Location: welcome.php");
	}

	//Log In
	else if (isset($_POST['emailID'])) {

		//Preventing SQL Injection
		$email = $_POST['emailID'];
		$email = stripslashes($email);
		$email = mysqli_escape_string($conn, $email);

		$password = $_POST['password'];
		$password = stripslashes($password);
		$password = mysqli_escape_string($conn, $password);

		//Hashing and checking if username and passwords match
		$sqlQuery = "select password from users where email='$email'";
		$queryRet = mysqli_query($conn, $sqlQuery);
		$rows = mysqli_num_rows($queryRet);

		if ($rows == 1) {
			$row = mysqli_fetch_array($queryRet, MYSQLI_NUM);
			$hashedpassword = $row[0];

			if (crypt($password, $hashedpassword)==$hashedpassword) {
				$_SESSION['loginID'] = $email;
				header('Location: welcome.php');
			}

			else {
				$error = "Passwords don't match";
			}
		}

		else {
			$error = "User doesn't exist";
		}

		//Checking if username and passwords match
		$sqlQuery = "select * from users where email='$email' and password='$password'";
		$queryRet = mysqli_query($conn, $sqlQuery);
		$rows = mysqli_num_rows($queryRet);

	}

	//Register
	else if (isset($_POST['firstName'])) {

		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$phoneNo = $_POST['phoneNo'];
		$password = $_POST['regPassword'];

		//Hashing
		if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
			$salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
			$password = crypt($password, $salt);
		}

		//Obtaining id
		$countIDQuery = "select count(id) from users;";
		$return = mysqli_query($conn, $countIDQuery);
		$row = mysqli_fetch_array($return, MYSQLI_NUM);
		$count = $row[0]+1;

		//Image requirements
		$fileName = $_FILES['profPic']['name'];
		$fileTempName = $_FILES['profPic']['tmp_name'];
		$fileSize = $_FILES['profPic']['size'];
		$fileArray = explode(".",$fileName);
		$fileExt = strtolower(end($fileArray));
		$validExtensions = array('jpg', 'jpeg', 'png');
		$dirpath = realpath(dirname(getcwd()));

		if (in_array($fileExt, $validExtensions) == false) 
			$error = "Please upload only JPEG or PNG images";
		else if ($fileSize > 2097152)
			$error = "File Size too large. Please upload image of size less than 2MB";

		//List of existing users
		$users[] = [];
		$userQuery = "select email from users";
		$userQueryRet = mysqli_query($conn, $userQuery);
		while ($row = mysqli_fetch_array($userQueryRet, MYSQLI_ASSOC))
			$users[] = $row['email'];
		if (! $users)
			if (in_array($email, $users)) 
				$error = "User is already registered with this email ID";

		if (! $error) {
			$ret = move_uploaded_file($fileTempName, "$dirpath/Documents/profilePictures/$count");
			if ($ret) {
				$sqlQuery = "insert into users values($count, \"$firstName\", \"$lastName\", \"$phoneNo\", \"$email\", \"$password\");";

				echo $sqlQuery;
				$retVal = mysqli_query($conn, $sqlQuery);

				if (! $retVal)
					$error = "Sorry. Insertion Failed.";

				else {
					$_SESSION['loginID'] = $email;
					header('Location: welcome.php');
				}
			}
			
			else {
				$error = "Picture upload failed";
			}
		}
		
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>

	<link rel="stylesheet" type="text/css" href="style.css">
	<h1 style="text-align: center;">ConnectU</h1>

	<div id="overallContainer">
		<div id="logInDiv" class="containerDiv">
			<h2 style="text-align: center;">Log In</h2>
			<form style="text-align: center;" action = "" method='post'>
				<input type="email" placeholder="E-mail ID" name="emailID" required><br />
				<input type="password" placeholder="Password" name="password"><br />
				<input style="margin-top:8px;" type="submit" value="Log In"><br />
			</form>
		</div>

		<div id="registerDiv" class="containerDiv">
			<h2 style="text-align: center;">Register</h2>
			<form style="text-align: center;" action="" method="post" enctype="multipart/form-data">
				<input type="text" placeholder="First Name" name="firstName" required><br />
				<input type="text" placeholder="Last Name" name="lastName" required><br />
				<input type="email" placeholder="E-mail" name="email" required><br />
				<input type="text" placeholder="Phone Number" name="phoneNo" required><br />
				<input type="password" placeholder="Password" name="regPassword" required><br />
				<input type="file" name="profPic" required><br />
				<input type="submit" style="margin-top:8px;" value="Sign Up"><br />
			</form>
		</div>
	</div>

	<p id="errorMessage"><?php echo $error ?></p>

</body>
</html>