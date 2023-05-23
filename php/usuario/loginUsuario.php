<?php
require_once '../config.php';
$valido['success']=array('success'=>false,'mensaje'=>"",'nombres'=>"");
if($_POST){
    $correo=$_POST['correo'];
    $password=md5($_POST['password']);

    $sql="select * from usuario where correo='$correo' and pass='$password'";
    $resultado=mysqli_query($link,$sql);
    $n=$resultado->num_rows;

    if($n>0){
        $row=$resultado->fetch_array();
        $valido['success']=true;
        $valido['mensaje']="Bienvenido ".$row['nombres'];
        $valido['nombres']=$row['nombres'];
        }
        else{
            $valido['success']=false;
            $valido['mensaje']="no encontrado!";
        }
    }else{
        $valido['success']=false;
        $valido['mensaje']="datos usados";    
    }
echo json_encode($valido);
?>