<?php session_start();if($_SESSION['access']!=true){header("location:index.php");}
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
$id=(isset($_POST['id']))?$_POST['id']:"";
$a=(isset($_POST['a']))?$_POST['a']:"";
$b=(isset($_POST['b']))?$_POST['b']:"";
$c=(isset($_POST['c']))?$_POST['c']:"";
$d=(isset($_POST['d']))?$_POST['d']:"";
$e=(isset($_POST['e']))?$_POST['e']:"";
$f=(isset($_POST['f']))?$_POST['f']:"";
$titulo=(isset($_POST['titulo']))?$_POST['titulo']:"";
$titulo=str_replace("á","a",$titulo);$titulo=str_replace("Á","A",$titulo);
$titulo=str_replace("é","e",$titulo);$titulo=str_replace("É","E",$titulo);
$titulo=str_replace("í","i",$titulo);$titulo=str_replace("Í","I",$titulo);
$titulo=str_replace("ó","o",$titulo);$titulo=str_replace("Ó","O",$titulo);
$titulo=str_replace("ú","u",$titulo);$titulo=str_replace("Ú","U",$titulo);
$titulo=str_replace("ñ","n",$titulo);$titulo=str_replace("Ñ","N",$titulo);
$titulo=strtoupper($titulo);
$titulo=trim($titulo);
$pnf=(isset($_POST['pnf']))?$_POST['pnf']:"";
$trayecto=(isset($_POST['trayecto']))?$_POST['trayecto']:"";
$trimestre=(isset($_POST['trimestre']))?$_POST['trimestre']:"";
$asesor=(isset($_POST['asesor']))?$_POST['asesor']:"";
$institucional=(isset($_POST['institucional']))?$_POST['institucional']:"";
if (isset($Ingresar))
   {
    $error=0;
    $a="";if(trim($titulo)==""){$a="Debe introducir el titulo del proyecto";$error=1;}
    $b="";if(trim($pnf)==""){$b="Debe seleccionar el Programa Nacional de Formación";$error=2;}
    $c="";if(trim($trayecto)==""){$c="Debe seleccionar el Trayecto";$error=3;}
    $d="";if(trim($trimestre)==""){$d="Debe seleccionar el Trimestre";$error=4;}
    $e="";if(trim($asesor)==""){$e="Debe seleccionar el Docente Asesor";$error=5;} 
    $f="";if(trim($institucional)==""){$f="Debe seleccionar el Representante Institucional";$error=6;}
          else{if($institucional==$asesor){$f="El Docente Asesor y el Representante Institucional deben ser distintos docentes";$error=6;}}
    if(postBlock($_POST['postID'])) 
	 {if($error==0) 
	     {$lider=$_SESSION['usuario'];
        $fecha=date("Y-m-d H:i:s");
        include("php/config.php");
        $result=mysqli_query($link,"SELECT id FROM lapso WHERE status='1'");
        if($row=mysqli_fetch_array($result))
          {$lapso=$row['id'];}
               
        $sql="update proyecto set pnf='$pnf',titulo='$titulo',trayecto='$trayecto',trimestre='$trimestre',asesor='$asesor',institucional='$institucional'
        where id='$id'";   
        $consulta=mysqli_query($link,$sql)or die(mysqli_error($link));
          
        header('Location:modificarIntegrantes.php');
       }
      }   
     }else{
        include("php/config.php");
        $consulta=mysqli_query($link,"select * from proyecto where id in (select proyecto from proyus where usuario=".$_SESSION['usuario'].")");
        if($row=mysqli_fetch_array($consulta)){
            $id=$row['id'];
            $pnf=$row['pnf'];
            $titulo=$row['titulo'];
            $trayecto=$row['trayecto'];
            $trimestre=$row['trimestre'];
            $asesor=$row['asesor'];
            $institucional=$row['institucional'];
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
    <link rel="stylesheet" href="iconos/font/bootstrap-icons.css">
    <title>Control de Proyectos</title>
</head>
<body>
<?php include_once("menu.php");?>    
  <div class="container text-center">
      <h3 class="mt-5">&nbsp;</h3>
      <h3 class="mt-5">Modificar Proyecto<br><br>1/4&nbsp;
      <a href="registrarIntegrantes.php"><font color="#000000"><i class="bi bi-chevron-right"></i></font></a></h3>
     <center> 
     <div class="position-relative m-4">
      <form id="formRegistrar" method="post" action="modificarProyecto.php" class="row g-3 border">
      <input type='hidden' name='postID' value=<?php echo "'".md5(uniqid(rand(), true))."'" ?>>
      <input type='hidden' name='id' value=<?php echo $id;?>>
      <div class="mb-3 col-12">
          <label for="pnf" class="form-label"><b>Programa Nacional de Formación</b></label>
          <select class="form-select" aria-label="Default select example" name="pnf">
          <option value="">Seleccione Programa Nacional de Formación</option> 
                <?php include("php/config.php");
                $consulta=mysqli_query($link,"select * from pnf where status=1 order by descripcion asc");
                while ($row=mysqli_fetch_array($consulta)){
                  if($row['id']==$pnf){echo "<option value='".$row['id']."' selected>".utf8_encode($row['descripcion'])."</option>";}
                  else{echo "<option value='".$row['id']."'>".utf8_encode($row['descripcion'])."</option>";}
                }?>
          </select>
          <?php if($b!=""){?><div class="alert alert-danger" role="alert"><?php echo $b;?></div><?php }?>
        </div>
        <div class="mb-3 col-12">
          <label for="titulo" class="form-label"><b>Titulo</b></label>
          <textarea class="form-control" id="titulo" name="titulo" rows="3"
          placeholder="Ingrese el titulo del proyecto"><?php echo $titulo;?></textarea>
          <?php if($a!=""){?><div class="alert alert-danger" role="alert"><?php echo $a;?></div><?php }?>
        </div>
        <div class="mb-3 col-6">
          <label for="trayecto" class="form-label"><b>Trayecto</b></label>
          <select class="form-select" aria-label="Default select example" id="trayecto" name="trayecto">
   	        <option value="">Seleccione</option>
              <option value="I"   <?php if($trayecto=="I"){echo"selected";}?>>I</option>
              <option value="II"  <?php if($trayecto=="II"){echo"selected";}?>>II</option>
              <option value="III" <?php if($trayecto=="III"){echo"selected";}?>>III</option>
              <option value="IV"  <?php if($trayecto=="IV"){echo"selected";}?>>IV</option>
          </select>
          <?php if($c!=""){?><div class="alert alert-danger" role="alert"><?php echo $c;?></div><?php }?>
        </div>
        <div class="mb-3 col-6">
          <label for="trimestre" class="form-label"><b>Trimestre</b></label>
          <select class="form-select" aria-label="Default select example" id="trimestre" name="trimestre">
   	        <option value="">Seleccione</option>
              <option value="1" <?php if($trimestre=="1"){echo"selected";}?>>1</option>
              <option value="2" <?php if($trimestre=="2"){echo"selected";}?>>2</option>
              <option value="3" <?php if($trimestre=="3"){echo"selected";}?>>3</option>
              <option value="4" <?php if($trimestre=="4"){echo"selected";}?>>4</option>
          </select>
          <?php if($d!=""){?><div class="alert alert-danger" role="alert"><?php echo $d;?></div><?php }?>
        </div>    
        <div class="mb-3 col-12 col-lg-6">
          <label for="asesor" class="form-label"><b>Docente Asesor</b></label>
          <select class="form-select" aria-label="Default select example" id="asesor" name="asesor">
          <option value="">Seleccione Docente Asesor</option>
          <?php include("php/config.php");
                $consulta=mysqli_query($link,"select * from usuario where rol<2 order by nombres asc");
                while ($row=mysqli_fetch_array($consulta)){
                    if($row['id']==$asesor){echo "<option value='".$row['id']."' selected>".utf8_encode($row['nombres'])."</option>";}
                    else{echo "<option value='".$row['id']."'>".utf8_encode($row['nombres'])."</option>";}
                }?>
          </select>
          <?php if($e!=""){?><div class="alert alert-danger" role="alert"><?php echo $e;?></div><?php }?>
        </div>
        <div class="mb-3 col-12 col-lg-6">
          <label for="institucional" class="form-label"><b>Representante Institucional</b></label>
          <select class="form-select" aria-label="Default select example" id="institucional" name="institucional">
          <option value="">Seleccione Representante Institucional</option>
          <?php include("php/config.php");
                $consulta=mysqli_query($link,"select * from usuario where rol<2 order by nombres asc");
                while ($row=mysqli_fetch_array($consulta)){
                  if($row['id']==$institucional){echo "<option value='".$row['id']."' selected>".utf8_encode($row['nombres'])."</option>";}
                  else{echo "<option value='".$row['id']."'>".utf8_encode($row['nombres'])."</option>";}
                }?>
          </select>
          <?php if($f!=""){?><div class="alert alert-danger" role="alert"><?php echo $f;?></div><?php }?>
        </div>    
        <div class="mb-3 col-3">
       
        </div>
        <div class="mb-3 col-6">
          <input type="submit" class="btn btn-success form-control" value="Guardar Cambios" name="Ingresar">
        </div>
        <!-- <div class="mb-3 col-3">
          <a href="registrarIntegrantes" class="btn btn-primary form-control" disabled>>></a>
        </div> -->
      </form>
    </center> 
  </div>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>