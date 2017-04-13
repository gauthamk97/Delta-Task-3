<?php

	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASSWORD','idk');
	define('DB_DATABASE','deltaBook');
	$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);

	if (! $conn)
		die('Connection to database failed'.mysqli_error($conn));

?>
