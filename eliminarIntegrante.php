<?php include ("php/config.php");
$integrante=$_REQUEST["integrante"];
$proyecto=$_REQUEST["proyecto"];
$pnf=$_REQUEST["pnf"];
$lapso=$_REQUEST["lapso"];

$sql="delete from proyus where proyecto='".$proyecto."' and usuario='".$integrante."' and pnf='".$pnf."' and lapso='".$lapso."'";

$result=mysqli_query($link,$sql);
header('Location:registrarIntegrantes.php');?>