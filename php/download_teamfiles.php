<?php
if(isset($_GET['id'])){
	require('connect.php');
	$id = $_GET['id'];
	$query = "SELECT name, type, size, content FROM teamfiles WHERE id = '$id'";
	$result = mysqli_query($link, $query);
	if (!$result){
		die('Error: ' . mysqli_error($link));
	}
	list($name,$type,$size,$content) = mysqli_fetch_array($result);
	header("Content-length: $size");
	header("Content-type: $type");
	header("Content-Disposition: attachment; filename=$name");
	echo $content;
	mysqli_close($link);
	exit;
}
?>