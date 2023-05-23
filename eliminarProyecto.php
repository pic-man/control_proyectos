<?php include ("php/config.php");
$id=$_REQUEST["id"];
$sql="delete from proyecto where id='".$id."'";
$result=mysqli_query($link,$sql);
$sql="delete from proyus where proyecto='".$id."'";
$result=mysqli_query($link,$sql);
header('Location:inicio.php?xd=2');?>