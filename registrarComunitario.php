<?php session_start();if($_SESSION['access']!=true){header("location:index.php");}
error_reporting(0);
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
$IngresarC=isset($_POST['IngresarC'])?$_POST['IngresarC']:null;
$id=(isset($_POST['id']))?$_POST['id']:"";
$cedula=(isset($_POST['cedula']))?$_POST['cedula']:"";
if (isset($IngresarC))
   {
    $error=0;
    $a="";if(trim($cedula)==""){$a="Debe introducir la cedula";$error=1;}
          else{if(!preg_match('/^[0-9]{5,15}$/i',$cedula)){$a="La cedula solo puede contener numeros";$error=2;}
               else{
                include("php/config.php");
                $result=mysqli_query($link,"SELECT * FROM comunitario WHERE cedula='".$cedula."'");
                if(!$row=mysqli_fetch_array($result))
                   {$a="La cedula NO esta registrada en el sistema";$error=3;}
                else
                   {$comunitario=$row['id'];
                    }   
               }
          }
   
    if(postBlock($_POST['postID'])) 
	 {if($error==0) 
	   {$fecha=date("Y-m-d H:i:s");
      include("php/config.php");
      $sql="update proyecto set comunitario=".$comunitario." where id='$id'";   
      $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
      header('Location:inicio.php?xd=2'); 
       }
      }   
     }  include("php/config.php");
            $consulta2=mysqli_query($link,"select id,titulo,pnf,lapso from proyecto where id in(select proyecto from proyus where usuario=".$_SESSION['usuario'].")");
        if($row2=mysqli_fetch_array($consulta2)){
           $id=$row2['id'];
           $titulo=$row2['titulo'];
           $pnf=$row2['pnf'];
           $lapso=$row2['lapso'];
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
    <link rel="stylesheet" href="iconos/font/bootstrap-icons.css">
    <title>Control de Proyectos</title>
    <script>
        function eliminarComunitario(id_to_delete)
        {var confirmation=confirm('¿Está seguro de que desea retirar el Representante Comunitario?');
         if(confirmation)
            {window.location.href='eliminarComunitario.php?proyecto='+id_to_delete;}
        }
    </script>
</head>
<body>
<?php include_once("menu.php");?>    
  <div class="container text-center">
      <h3 class="mt-5">&nbsp;</h3>
      <h3 class="mt-5">Registro/Modificacion de Proyecto<br><br>
      <a href="registrarInstitucion.php"><font color="#000000"><i class="bi bi-chevron-left"></i></font></a>
      4/4&nbsp;
      </h3>
     <center> 
     <div class="position-relative m-4">
      <div class="mb-3 col-12">
          <label for="titulo" class="form-label">Titulo: <b><?php echo $titulo;?></b></label>
        </div>
      <div class="mb-3 col-12">
          <label for="pnf" class="form-label"><b>Agregar Representante Comunitario</b></label>
      </div>
      <form id="formRegistrar" method="post" action="registrarComunitario.php" class="row g-3 border">
        <input type='hidden' name='postID' value=<?php echo "'".md5(uniqid(rand(), true))."'" ?>>
        <input type='hidden' name='id' value="<?php echo $id;?>">
        <div class="mb-1 col-12 col-lg-6">
            <label for="cedula" class="form-label">Cedula</label>
            <input type="text" class="form-control" id="cedula" name="cedula" 
                   placeholder="Ingresar Cedula" value="<?php echo $cedula;?>">
                   <?php if($a!=""){?>
                         <div class="alert alert-danger" role="alert"><?php echo $a;?>
                         <?php if($error==3){?>
                            <a href="registro4.php?ncedula=<?php echo $cedula;?>" class="btn btn-primary form-control">Registrar Representante Comunitario</a>
                         <?php }?>
                        </div>
                   <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
        <label for="cedula" class="form-label d-none d-lg-block">&nbsp;</label>
        <input type="submit" class="btn btn-success form-control" value="Agregar Representante Comunitario" name="IngresarC">
        </div>
      </form>
        <div class="mb-3 col-12">
          <label for="titulo" class="form-label"><b>Representante Comunitario del Proyecto</b></label>
        </div>
        <div class="mb-3 col-12">
        <table width="100%" class="integrantes">
        <?php include("php/config.php");
              $consulta=mysqli_query($link,"select id,cedula,grado,nombres from comunitario where id in 
              (select comunitario from proyecto where id=".$id.")");
              while($row=mysqli_fetch_array($consulta)){
                $consulta2=mysqli_query($link,"select descripcion from grado where id=".$row['grado']."");
              if($row2=mysqli_fetch_array($consulta2)){
                ?>
              <tr><td><?php echo $row['cedula'];?></td><td><?php echo $row2['descripcion']." ".$row['nombres'];?></td>    
              <td>
                 <a href="#" class="btn btn-danger" onclick="eliminarComunitario(<?php echo $id;?>)">
                 <i class="bi bi-trash p-1"></i></a>
              </td>
        <?php }
             }?> 
       </table>
        </div>
    </center> 
  </div>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>