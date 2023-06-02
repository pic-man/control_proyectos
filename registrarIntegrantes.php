<?php session_start();if($_SESSION['access']!=true){header("location:index.php");}
error_reporting (0);
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
$pnf=(isset($_POST['pnf']))?$_POST['pnf']:"";
$cedula=(isset($_POST['cedula']))?$_POST['cedula']:"";
$lapso=(isset($_POST['lapso']))?$_POST['lapso']:"";
if (isset($IngresarC))
   {
    $error=0;
    $a="";if(trim($cedula)==""){$a="Debe introducir la cedula";$error=1;}
          else{if(!preg_match('/^[0-9]{5,15}$/i',$cedula)){$a="La cedula solo puede contener numeros";$error=2;}
               else{
                include("php/config.php");
                $result=mysqli_query($link,"SELECT * FROM usuario WHERE cedula='".$cedula."' and rol=9");
                if(!$row=mysqli_fetch_array($result))
                   {$a="La cedula NO esta registrada en el sistema<br>los integrantes deben estar previamente registrados";$error=3;}
                else
                   {$us=$row['id'];
                    include("php/config.php");
                    $result2=mysqli_query($link,"SELECT * FROM proyus WHERE usuario='".$us."' and pnf=".$pnf." and lapso=".$lapso."");
                    if($row2=mysqli_fetch_array($result2))
                       {$a="El estudiante ya esta registrado en un proyecto";$error=4;}
                    }   
               }
          }
   
    if(postBlock($_POST['postID'])) 
	 {if($error==0) 
	   {$fecha=date("Y-m-d H:i:s");
        include("php/config.php");
        $sql="insert into proyus(proyecto,usuario,lapso,pnf,registro)values
            ('$id','$us','$lapso','$pnf','$fecha')";   
         $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
         header('Location:registrarIntegrantes.php');
       }
      }   
     }  include("php/config.php");
        /* $result=mysqli_query($link,"SELECT id FROM lapso WHERE status='1'");
        if($row=mysqli_fetch_array($result))
            {$lapso=$row['id'];} */          
        
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
        function eliminarIntegrante(id_to_delete)
        {var confirmation=confirm('¿Está seguro de que desea retirar el integrante?');
         if(confirmation)
            {window.location.href='eliminarIntegrante.php?integrante='+id_to_delete+'&proyecto=<?php echo $id;?>&pnf=<?php echo $pnf;?>&lapso=<?php echo $lapso;?>';}
        }
    </script>
</head>
<body>
<?php include_once("menu.php");?>    
  <div class="container text-center">
      <h3 class="mt-5">&nbsp;</h3>
      <h3 class="mt-5">Registro/Modificacion de Proyecto<br><br>
      <a href="modificarProyecto.php"><font color="#000000"><i class="bi bi-chevron-left"></i></font></a>
      2/4&nbsp;
      <a href="registrarInstitucion.php"><font color="#000000"><i class="bi bi-chevron-right"></i></font></a></h3>
     <center> 
     <div class="position-relative m-4">
      <div class="mb-3 col-12">
          <label for="titulo" class="form-label">Titulo: <b><?php echo $titulo;?></b></label>
        </div>
      <div class="mb-3 col-12">
          <label for="pnf" class="form-label"><b>Agregar Integrantes</b></label>
      </div>
      <form id="formRegistrar" method="post" action="registrarIntegrantes.php" class="row g-3 border">
        <input type='hidden' name='postID' value=<?php echo "'".md5(uniqid(rand(), true))."'" ?>>
        <input type='hidden' name='id' value="<?php echo $id;?>">
        <input type='hidden' name='lapso' value="<?php echo $lapso;?>">
        <input type='hidden' name='pnf' value="<?php echo $pnf;?>">
        <div class="mb-1 col-12 col-lg-6">
            <label for="cedula" class="form-label">Cedula</label>
            <input type="text" class="form-control" id="cedula" name="cedula" 
                   placeholder="Ingresar Cedula" value="<?php echo $cedula;?>">
                   <?php if($a!=""){?>
                         <div class="alert alert-danger" role="alert"><?php echo $a;?>
                         <?php if($error==3){?>
                            <a href="registro2.php?ncedula=<?php echo $cedula;?>" class="btn btn-primary form-control">Registrar Estudiante</a>
                         <?php }?>
                        </div>
                   <?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
        <label for="cedula" class="form-label d-none d-lg-block">&nbsp;</label>
        <input type="submit" class="btn btn-success form-control" value="Agregar Integrante" name="IngresarC">
        </div>
      </form>
        <div class="mb-3 col-12">
          <label for="titulo" class="form-label"><b>Integrantes del Proyecto</b></label>
        </div>
        <div class="mb-3 col-12">
        <table width="100%" class="integrantes">
        <?php include("php/config.php");
              $consulta=mysqli_query($link,"select id,cedula,nombres from usuario where id in 
              (select usuario from proyus where proyecto=".$id.") order by nombres asc");
              while($row=mysqli_fetch_array($consulta)){?>
              <tr><td><?php echo $row['cedula'];?></td><td><?php echo $row['nombres'];?></td>    
              <td>
              <?php if($_SESSION['usuario']!=$row['id']){?>  
                 <a href="#" class="btn btn-danger" onclick="eliminarIntegrante(<?php echo $row['id'];?>)">
                 <i class="bi bi-trash p-1"></i></a>
              <?php }?>
              </td>
        <?php }?> 
       </table>
        </div>
    </center> 
  </div>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>