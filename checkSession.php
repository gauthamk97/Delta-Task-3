<?php

	include("config.php");
	session_start();

	if (! isset($_SESSION['loginID'])) {
		mysqli_close($conn);
		header("Location: index.php");
	}

	else {
		$username = $_SESSION['loginID'];
		$sqlQuery = "select * from users where email='$username'";
		$queryRet = mysqli_query($conn, $sqlQuery);
		$row = mysqli_fetch_array($queryRet, MYSQLI_ASSOC);
		
		//User Details
		$userID = $row['id'];
		$firstName = $row['firstName'];
		$lastName = $row['lastName'];
		$phoneNo = $row['phone'];

	}
	
?>