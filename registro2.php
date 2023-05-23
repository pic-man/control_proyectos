<?php session_start();
date_default_timezone_set("America/caracas");
function postBlock($postID) 
{if(isset($_SESSION['postID'])) 
   {if ($postID == $_SESSION['postID']) 
       {return false;} 
	else 
		{$_SESSION['postID']=$postID;
         return true;}
    } 
 else 
   {$_SESSION['postID']=$postID;
    return true;}
  }
include("php/seg.php");
$error=0;
$Ingresar=isset($_POST['Ingresar'])?$_POST['Ingresar']:null;
$xd=(isset($_POST['xd']))?$_POST['xd']:"";
$a=(isset($_POST['a']))?$_POST['a']:"";
$c=(isset($_POST['c']))?$_POST['c']:"";
$t=(isset($_POST['t']))?$_POST['t']:"";
$x=(isset($_POST['x']))?$_POST['x']:"";
$p=(isset($_POST['p']))?$_POST['p']:"";
$q=(isset($_POST['q']))?$_POST['q']:"";
$cedula=(isset($_POST['cedula']))?$_POST['cedula']:"";
$ncedula=(isset($_REQUEST['ncedula']))?$_REQUEST['ncedula']:"";

if($ncedula!=""){$cedula=$ncedula;}

$nombres=(isset($_POST['nombres']))?$_POST['nombres']:"";
$nombres=str_replace("á","a",$nombres);$nombres=str_replace("Á","A",$nombres);
$nombres=str_replace("é","e",$nombres);$nombres=str_replace("É","E",$nombres);
$nombres=str_replace("í","i",$nombres);$nombres=str_replace("Í","I",$nombres);
$nombres=str_replace("ó","o",$nombres);$nombres=str_replace("Ó","O",$nombres);
$nombres=str_replace("ú","u",$nombres);$nombres=str_replace("Ú","U",$nombres);
$nombres=str_replace("ñ","n",$nombres);$nombres=str_replace("Ñ","N",$nombres);
$nombres=strtoupper($nombres);
$nombres=trim($nombres);
$celular=(isset($_POST['celular']))?$_POST['celular']:"";
$correo=(isset($_POST['correo']))?$_POST['correo']:"";
$password=(isset($_POST['password']))?$_POST['password']:"";
$cPassword=(isset($_POST['cPassword']))?$_POST['cPassword']:"";
if (isset($Ingresar))
   {
    $error=0;
    $a="";if(trim($cedula)==""){$a="Debe introducir la cedula";$error=1;}
          else{if(!preg_match('/^[0-9]{5,15}$/i',$cedula)){$a="La cedula solo puede contener numeros";$error=2;}
               else{
                include("php/config.php");
                $result=mysqli_query($link,"SELECT * FROM usuario WHERE cedula='".$cedula."'");
                if($row=mysqli_fetch_array($result))
                   {$a="La cedula ya esta registrada en el sistema";$error=2;}
               }
          }
    $c="";if(trim($nombres)==""){$c="Debe introducir el(los) nombre(s) y apellido(s)";$error=3;}
          else{if(!preg_match('/^[[a-záéíóú.ñÑ ]{2,40}$/i',$nombres)){$c="El nombre solo puede contener letras";$error=4;}}         
    $x="";if(trim($correo)==""){$x="Debe introducir el correo";$error=2;
    }else{
    $mail_correcto=0;  
    $x="";if (trim($correo)!=""){if ((strlen($correo) >= 6) && (substr_count($correo,"@") == 1) && (substr($correo,0,1) != "@") && (substr($correo,strlen($correo)-1,1) != "@")){ 
          if ((!strstr($correo,"'")) && (!strstr($correo,"\"")) && (!strstr($correo,"\\")) && (!strstr($correo,"\$")) && (!strstr($correo," "))) { 
          if (substr_count($correo,".")>= 1){ 
          $term_dom = substr(strrchr ($correo, '.'),1); 
          if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
          $antes_dom = substr($correo,0,strlen($correo) - strlen($term_dom) - 1); 
          $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
          if ($caracter_ult != "@" && $caracter_ult != "."){ 
          $mail_correcto = 1;}}}}} 
          if (!$mail_correcto)  
       	      {$x="Formato de Correo no valido";$error=7;}
          else{
               include("php/config.php");
               $result=mysqli_query($link,"SELECT * FROM usuario WHERE correo='".$correo."'");
               if($row=mysqli_fetch_array($result))
                   {$x="El correo ya esta registrado en el sistema";$error=7;}
               }
     }
    else
    {$x="Debe introducir el correo";$error=7;}
    }
     $t="";if(trim($celular)=="")
            {$t="Debe introducir el telefono";$error=25;}
          else      
            {$expresion="/[0-9]{10}$/";         
             if(!preg_match($expresion,$celular)){$t="El telefono debe tener 10 numeros";$error=25;}
            }
   
   $p="";if(trim($password)=="")
      {$p="Debe introducir la clave";$error=26;}
   
    $q="";if(trim($cPassword)=="")
      {$q="Debe repetir la clave";$error=27;}
          else      
            {if($password!=$cPassword){$q="La clave y su confirmacion no coinciden";$error=28;}
            }

    if(postBlock($_POST['postID'])) 
	 {if($error==0) 
	   {$password=md5($password);
        $sql="insert into usuario(cedula,nombres,telefono,correo,pass,rol,status)values
            ('$cedula','$nombres','$celular','$correo','$password',9,1)";   
        $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
         
        $consulta1=mysqli_query($link,"select id from usuario where cedula='".$cedula."'");
        if($row1=mysqli_fetch_array($consulta1))
            {$usuario=$row1['id'];}
            
        $consulta2=mysqli_query($link,"select id,titulo,pnf from proyecto where id in(select proyecto from proyus where usuario=".$_SESSION['usuario'].")");
        if($row2=mysqli_fetch_array($consulta2)){
           $id=$row2['id'];
           $titulo=$row2['titulo'];
           $pnf=$row2['pnf'];
           $lapso=$row2['lapso'];
        }

        $fecha=date("Y-m-d H:i:s");
        include("php/config.php");
        $sql="insert into proyus(proyecto,usuario,lapso,pnf,registro)values
            ('$id','$usuario','$lapso','$pnf','$fecha')";   
        $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
        header('Location:registrarIntegrantes.php');   
       }
      }   
     }?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/logo-universidad.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/estilos.css">
    <script src="js/sweetalert2.all.min.js"></script>
    <title>Control de Proyectos</title>
</head>
 <body>
  <?php if($xd==1){$xd=0;?>
      <script>Swal.fire('Usuario registrado satisfactoriamente','','success')
              setTimeout(()=>{
              window.location.href="index.php";
              },2000)
    </script>
  <?php }?>
  <div class="container text-center">
      <img src="img/logo-universidad.png" class="mt-5" width="150px">
      <h3 class="mt-2">Registro de Estudiante</h3>
     <center> 
      <form id="formRegistrar" method="post" class="row border" action="registro2.php">
      <input type='hidden' name='postID' value=<?php echo "'".md5(uniqid(rand(), true))."'" ?>>
        <div class="mb-3 col-12 col-lg-6">
          <label for="cedula" class="form-label">Cedula</label>
          <input type="text" class="form-control" id="cedula" name="cedula" 
          placeholder="Ingresar Cedula" value="<?php echo $cedula;?>">
          <?php if($a!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $a;?></div>
        <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
          <label for="nombres" class="form-label">Nombres y Apellidos</label>
          <input type="text" class="form-control" id="nombres" name="nombres"  
          placeholder="Ingresar Nombre(s) y apellido(s)" value="<?php echo $nombres;?>">
          <?php if($c!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $c;?></div>
        <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
          <label for="celular" class="form-label">Telefono</label>
          <input type="text" class="form-control" id="celular" name="celular" maxlength="11" 
          placeholder="Ingresar Telefono" value="<?php echo $celular;?>">
          <?php if($t!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $t;?></div>
        <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
          <label for="correo" class="form-label">Correo Electronico</label>
          <input type="correo" class="form-control" id="correo" name="correo" 
          placeholder="Ingresar Correo" value="<?php echo $correo;?>">
          <?php if($x!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $x;?></div>
        <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
          <label for="password" class="form-label">Clave</label>
          <input type="password" class="form-control" id="password" name="password" 
          placeholder="Ingresar Clave" value="<?php echo $password;?>">
          <?php if($p!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $p;?></div>
        <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
          <label for="cPassword" class="form-label">Confirmar Clave</label>
          <input type="password" class="form-control" id="cPassword" name="cPassword" 
          placeholder="Confirmar Clave" value="<?php echo $cPassword;?>">
          <?php if($q!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $q;?></div>
        <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-12">
          <input type="submit" class="btn btn-success form-control" value="Registrar" name="Ingresar">
        </div>
        <div class="mb-3 col-12 col-lg-12">
          <a href="registrarIntegrantes.php" class="btn btn-primary form-control">Volver</a>
        </div>  
    </form>
    </center> 
  </div>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>