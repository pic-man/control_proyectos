<?php
require_once '../config.php';

$valido['success']=array('success'=>false,'mensaje'=>"");

if($_POST){
    $cedula=$_POST['cedula'];
    $nombres=strtoupper($_POST['nombres']);
    $telefono=$_POST['telefono'];
    $correo=$_POST['correo'];
    $password=md5($_POST['password']);

    $sql="select * from usuario where cedula='".$cedula."' or correo='".$correo."'";
    $resultado=mysqli_query($link,$sql);
    $n=$resultado->num_rows;

    if($n==0){
        $sql="insert into usuario(cedula,nombres,telefono,correo,pass,rol,status)values
        ('$cedula','$nombres','$telefono','$correo','$password',2,1)";
        if(mysqli_query($link,$sql)){
            $valido['success']=true;
            $valido['mensaje']="datos guardados";
        }
        else{
            $valido['success']=false;
            $valido['mensaje']="error de registro!";
        }
    }
    else{
        $valido['success']=false;
        $valido['mensaje']="datos usados";    
    }

}else{
    $valido['success']=false;
    $valido['mensaje']="registro fallido!";
}
echo json_encode($valido);
?>