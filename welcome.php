<?php
	include("checkSession.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
</head>
<body>
<h2 style="text-align: center;"> Welcome to ConnectU </h2>
	
	<div style="text-align: center;"><img src="profilePictures/<?php echo $userID; ?>" style="width: 200px;"></div>
	<table style="text-align: center;margin: auto;">
		
		<tr>
			<th>First Name : </th>
			<td><?php echo $firstName; ?></td>
		</tr>

		<tr>
			<th>Last Name : </th>
			<td><?php echo $lastName; ?></td>
		</tr>

		<tr>
			<th>E-mail : </th>
			<td><?php echo $username; ?></td>
		</tr>

		<tr>
			<th>Phone : </th>
			<td><?php echo $phoneNo; ?></td>
		</tr>

	</table>
	
<br />

<div style="text-align: center;">
	<a href="edit.php">Edit</a>
	<a style="" href="logout.php">Logout</a>
	
</div>
</body>
</html>