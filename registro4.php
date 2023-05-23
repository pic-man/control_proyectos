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
$grado=(isset($_POST['grado']))?$_POST['grado']:"";
if (isset($Ingresar))
   {
    $error=0;
    $a="";if(trim($cedula)==""){$a="Debe introducir la cedula";$error=1;}
          else{if(!preg_match('/^[0-9]{5,15}$/i',$cedula)){$a="La cedula solo puede contener numeros";$error=2;}
               else{
                include("php/config.php");
                $result=mysqli_query($link,"SELECT * FROM comunitario WHERE cedula='".$cedula."'");
                if($row=mysqli_fetch_array($result))
                   {$a="La cedula ya esta registrada en el sistema";$error=2;}
               }
          }
    $c="";if(trim($nombres)==""){$c="Debe introducir el(los) nombre(s) y apellido(s)";$error=3;}
          else{if(!preg_match('/^[[a-záéíóú.ñÑ ]{2,40}$/i',$nombres)){$c="El nombre solo puede contener letras";$error=4;}}         
     $t="";if(trim($celular)=="")
            {$t="Debe introducir el telefono";$error=25;}
          else      
            {$expresion="/[0-9]{10}$/";         
             if(!preg_match($expresion,$celular)){$t="El telefono debe tener 10 numeros";$error=25;}
            }
   
   $p="";if(trim($grado)=="")
      {$p="Debe seleccionar el grado academico";$error=26;}
   
    if(postBlock($_POST['postID'])) 
	 {if($error==0) 
	   {$sql="insert into comunitario(cedula,nombres,telefono,grado,status)values
            ('$cedula','$nombres','$celular','$grado',1)";   
        $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
         
        $consulta1=mysqli_query($link,"select id from comunitario where cedula='".$cedula."'");
        if($row1=mysqli_fetch_array($consulta1))
            {$comunitario=$row1['id'];}
            
        $consulta2=mysqli_query($link,"select id from proyecto where id in(select proyecto from proyus where usuario=".$_SESSION['usuario'].")");
        if($row2=mysqli_fetch_array($consulta2)){
            $id=$row2['id'];
            $titulo=$row2['titulo'];
            $pnf=$row2['pnf'];
            $lapso=$row2['lapso'];
        }
    
        $fecha=date("Y-m-d H:i:s");
        include("php/config.php");
        $sql="update proyecto set comunitario=".$comunitario." where id='$id'";   
        $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
        header('Location:registrarOrganizacion.php'); 
        }
       }
      }   
     ?>
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
      <h3 class="mt-2">Registro de Representante Comunitario</h3>
     <center> 
      <form id="formRegistrar" method="post" class="row border" action="registro4.php">
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
          <label for="grado" class="form-label">Grado Academico</label>
          <select class="form-select" aria-label="Default select example" id="grado" name="grado">
          <option value="">Seleccione Grado Academico</option>
          <?php include("php/config.php");
                $consulta=mysqli_query($link,"select * from grado order by descripcion asc");
                while ($row=mysqli_fetch_array($consulta)){
                    if($row['id']==$grado){echo "<option value='".$row['id']."' selected>".utf8_encode($row['descripcion'])."</option>";}
                    else{echo "<option value='".$row['id']."'>".utf8_encode($row['descripcion'])."</option>";}
                }?>
          </select>
          <?php if($x!=""){?>
            <div class="alert alert-danger" role="alert"><?php echo $x;?></div>
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