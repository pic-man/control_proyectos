<?php session_start();
error_reporting (0);
if($_SESSION['access']==true){header("location:inicio.php");}
require_once('php/seg.php');
$errores["login"] = "";
$Ingresar=isset($_POST['Ingresar'])?$_POST['Ingresar']:NULL;
$correo=(isset($_POST['correo']))?$_POST['correo']:"";
$klave=(isset($_POST['klave']))?$_POST['klave']:"";
$correo=daniela($correo);
if (isset($Ingresar))
 {if($correo!="" && $klave!="")
    {
     include ("php/config.php");	 
     $result=mysqli_query($link,"SELECT * FROM usuario WHERE correo='".$correo."'");
	 if($row=mysqli_fetch_array($result))
	   {if($row["pass"] == md5($klave))
	      {if($row["status"] == 1)
	          {$_SESSION['access']=true;
		         $_SESSION['usuario']=$row["id"];
             $_SESSION['nom_us']=$row["nombre"];
             $_SESSION['rol']=$row["rol"];
             header('Location:inicio.php');
            }
           else
              {$errores["login"] = "Usuario inhabilitado";}
         }
        else
       {$errores["login"] = "Clave incorrecta";}
       }
    else
     {$errores["login"] = "Correo no registrado</font>";}
}else{$errores["login"] = "Debe introducir el correo y la clave";}}?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/logo-universidad.png" type="image/x-icon">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/estilos.css">
  <title>Control de Proyectos</title>
</head>
<body>
  <div class="container">
    <div class="text-center">
      <img src="img/logo-universidad.png" class="mt-5" width="150px">
      <center>
      <h3 class="mt-2">Iniciar sesion</h3>
      <form class="row needs-validation" novalidate method="post" action="index.php">
        <div class="mb-3">
          <label for="correo" class="form-label">Correo Electronico</label>
          <input type="email" class="form-control" id="correo" name="correo" 
                 placeholder="Ingresar Correo" value="<?php echo $correo;?>">
        </div>  
        <div class="mb-3">
          <label for="password" class="form-label">Clave</label>
          <input type="password" class="form-control" id="password" name="klave" 
                 placeholder="Ingresar Clave" required>
        </div>
        <?php if($errores["login"]!=""){?>
            <div class="alert alert-danger" role="alert">
               <?php echo $errores["login"];?>
            </div>
        <?php }?>
        <div class="mb-3 col-12 col-lg-12">
          <input type="submit" class="btn btn-success form-control" name="Ingresar" value="Ingresar">
        </div>
        <div class="mb-3 col-12 col-lg-12">
          <a href="registro.php" class="btn btn-primary form-control">Registarse</a>
        </div>
      </form>
     </center>
    </div>
  </div>
  <script src="js/bootstrap.bundle.js"></script>
  <script src="js/sweetalert2.all.min.js"></script>
</body>
</html>