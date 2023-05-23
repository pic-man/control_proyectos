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
$rif=(isset($_POST['rif']))?$_POST['rif']:"";
$nrif=(isset($_REQUEST['nrif']))?$_REQUEST['nrif']:"";

if($nrif!=""){$rif=$nrif;}

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
$direccion=(isset($_POST['direccion']))?$_POST['direccion']:"";
$direccion=strtoupper($direccion);
$direccion=trim($direccion);
if (isset($Ingresar))
   {
    $error=0;
    $a="";if(trim($rif)==""){$a="Debe introducir el rif";$error=1;}
          else{if(!preg_match('/^[JGVEP][-][0-9]{8}[-][0-9]{1}$/',$rif)){$a="El Rif deb tener el formato V-00000000-0";$error=2;}
               else{
                include("php/config.php");
                $result=mysqli_query($link,"SELECT * FROM organizacion WHERE rif='".$rif."'");
                if($row=mysqli_fetch_array($result))
                   {$a="El Rif ya esta registrado en el sistema<br>";$error=3;}
                else
                   {$us=$row['id'];
                   }   
               }
          }
    $c="";if(trim($nombres)==""){$c="Debe introducir el nombre de la organizacion";$error=3;}

    $t="";if(trim($celular)=="")
            {$t="Debe introducir el telefono";$error=25;}
          else      
            {$expresion="/[0-9]{10}$/";         
             if(!preg_match($expresion,$celular)){$t="El telefono debe tener 10 numeros";$error=25;}
            }
   
   $p="";if(trim($direccion)=="")
      {$p="Debe introducir la direccion";$error=26;}
   
    if(postBlock($_POST['postID'])) 
	 {if($error==0) 
	   {$password=md5($password);
        $sql="insert into organizacion(rif,nombre,telefono,direccion,status)values
            ('$rif','$nombres','$celular','$direccion',1)";   
        $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
         
        $consulta1=mysqli_query($link,"select id from organizacion where rif='".$rif."'");
        if($row1=mysqli_fetch_array($consulta1))
            {$organizacion=$row1['id'];}
            
        $consulta2=mysqli_query($link,"select id from proyecto where id in(select proyecto from proyus where usuario=".$_SESSION['usuario'].")");
        if($row2=mysqli_fetch_array($consulta2)){
           $id=$row2['id'];
           $titulo=$row2['titulo'];
           $pnf=$row2['pnf'];
           $lapso=$row2['lapso'];
        }

        $fecha=date("Y-m-d H:i:s");
        include("php/config.php");
        $sql="update proyecto set organizacion=".$organizacion." where id='$id'";   
        $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
        header('Location:registrarInstitucion.php');   
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
      <h3 class="mt-2">Registro de Organizacion</h3>
     <center> 
      <form id="formRegistrar" method="post" class="row border" action="registro3.php">
      <input type='hidden' name='postID' value=<?php echo "'".md5(uniqid(rand(), true))."'" ?>>
        <div class="mb-3 col-12 col-lg-6">
          <label for="rif" class="form-label">Rif</label>
          <input type="text" class="form-control" id="rif" name="rif" 
          placeholder="Ingresar Rif" value="<?php echo $rif;?>">
          <?php if($a!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $a;?></div>
        <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
          <label for="nombres" class="form-label">Nombre Organizacion</label>
          <input type="text" class="form-control" id="nombres" name="nombres"  
          placeholder="Ingresar Nombre" value="<?php echo $nombres;?>">
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
          <label for="correo" class="form-label">Direccion</label>
          <input type="correo" class="form-control" id="direccion" name="direccion" 
          placeholder="Ingresar direccion" value="<?php echo $direccion;?>">
          <?php if($p!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $p;?></div>
        <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-12">
          <input type="submit" class="btn btn-success form-control" value="Registrar" name="Ingresar">
        </div>
        <div class="mb-3 col-12 col-lg-12">
          <a href="registrarInstitucion.php" class="btn btn-primary form-control">Volver</a>
        </div>  
    </form>
    </center> 
  </div>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>