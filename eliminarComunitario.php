<?php include ("php/config.php");
$proyecto=$_REQUEST["proyecto"];
echo $proyecto;
$sql="update proyecto set comunitario='' where id='".$proyecto."'";
$result=mysqli_query($link,$sql);
header('Location:registrarComunitario.php');?>