<?php session_start();if($_SESSION['access']!=true){header("location:index.php");}?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/logo-universidad.png" type="image/x-icon">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="iconos/font/bootstrap-icons.css">
  <script src="js/sweetalert2.all.min.js"></script>
  <script>
        function eliminarProyecto(id_to_delete)
        {var confirmation=confirm('¿Está seguro de que desea eliminar el Proyecto?');
         if(confirmation)
            {window.location.href='eliminarProyecto.php?id='+id_to_delete;}
        }
    </script>
  <title>Control de Proyectos</title>
</head>
<body>
<?php if($xd==1){$xd=0;?>
      <script>Swal.fire('Proyecto eliminado satisfactoriamente','','success')
              setTimeout(()=>{
              window.location.href="index.php";
              },2000)
    </script>
  <?php }?>
    <?php include_once("menu.php");
    $usuario=$_SESSION['usuario'];
    include('php/config.php');
    $result=mysqli_query($link,"SELECT id FROM lapso WHERE status='1'");
    if($row=mysqli_fetch_array($result))
       {$lapso=$row['id'];}

    if($_SESSION['rol']<9){

    }
    else{
        $sql="select * from proyecto where id in (select proyecto from proyus where usuario=".$usuario." and lapso=".$lapso.")";
        }
    $consulta=mysqli_query($link,$sql);  
    $numProyectos=mysqli_num_rows($consulta);
    ?>
    <div class=" text-center">
      <h3 class="mt-5">&nbsp;</h3>
      <h3 class="mt-5">Control de Proyectos</h3>
      <div class="card text-center">
        <div class="card-body">
          <?php if($_SESSION['rol']==9 && $numProyectos==0){?>  
            <div class="text-center">
                <a class="btn btn-danger mb-3" href="registrarProyecto.php">
                <i class="bi bi-file-earmark-plus p-1"></i></i>Agregar Proyecto</a>
            </div>
           <?php }?> 
            <center>
            <table class="table table-hover table-responsive d-none d-lg-block">
                <thead class="table-dark">
                    <tr>
                        <td>Trayecto</td>
                        <td width="40%">Titulo</td>
                        <td width="25%">Asesores</td>
                        <td width="20%">Integrantes</td>
                        <td colspan="3" width="15%">Acciones</td>
                    </tr>
                </thead>
                <tbody>
                <?php while($row=mysqli_fetch_array($consulta)){?>    
                <tr>
                        <td><?php echo $row['trayecto'];?></td>
                        <td><?php echo $row['titulo']."<br>";
                        $consulta2=mysqli_query($link,"select descripcion from pnf where id=".$row['pnf']."");
                        if($row2=mysqli_fetch_array($consulta2)){echo "<b>PNF:</b>&nbsp;".$row2['descripcion'];}
                        ?></td>
                        <td><b>Docente Asesor</b><br><?php $consulta2=mysqli_query($link,"select nombres from usuario where id=".$row['asesor']."");
                                  if($row2=mysqli_fetch_array($consulta2)){echo $row2['nombres'];}?>
                            <br><b>Representante Institucional</b><br><?php $consulta2=mysqli_query($link,"select nombres from usuario where id=".$row['institucional']."");
                                  if($row2=mysqli_fetch_array($consulta2)){echo $row2['nombres'];}?>     
                        </td>
                        <td>
                        <?php $consulta2=mysqli_query($link,"select nombres from usuario where id in(select usuario from proyus where proyecto=".$row['id'].")");
                              while($row2=mysqli_fetch_array($consulta2)){echo $row2['nombres']."<br>";}?>
                        </td>
                        <td colspan="3">
                          <?php if($row['status']==0){?>  
                            <a href="modificarProyecto.php"><button class="btn btn-primary"><i class="bi bi-pencil-square p-1"></i></button></a><br><br>
                            <a href="#"  class="btn btn-danger" onclick="eliminarProyecto(<?php echo $row['id'];?>)">
                            <i class="bi bi-trash p-1"></i></a></td>
                          <?php }elseif($row['status']==1){?>
                            <button class="btn btn-success"><i class="bi bi-search p-1"></i></button>
                            <button class="btn btn-primary"><i class="bi bi-pencil-square p-1"></i></button>
                          <?php }?>
                        </td>    
                    </tr>

                    <table class="table table-hover table-responsive d-lg-none text-center">        
                        <thead class="table-dark"><tr><td width="100%" colspan="2">Titulo</td></tr></thead>
                        <tbody><tr><td width="100%" colspan="2"><?php echo $row['titulo'];?></td></tr></tbody>
                        
                        <thead class="table-dark"><tr><td width="50%">PNF</td><td width="50%">Trayecto</td></tr></thead>
                        <tbody><tr><td width="50%"><?php $consulta2=mysqli_query($link,"select descripcion from pnf where id=".$row['pnf']."");
                                  if($row2=mysqli_fetch_array($consulta2)){echo $row2['descripcion'];}?></td>
                                   <td width="50%"><?php echo $row['trayecto'];?></td></tr>
                        </tbody>

                        <thead class="table-dark"><tr><td width="100%" colspan="2">Docente Asesor</td></tr></thead>
                        <tbody><tr><td width="100%" colspan="2"><?php $consulta2=mysqli_query($link,"select nombres from usuario where id=".$row['asesor']."");
                                  if($row2=mysqli_fetch_array($consulta2)){echo $row2['nombres'];}?></td></tr>
                        </tbody>

                        <thead class="table-dark"><tr><td width="100%" colspan="2">Representante Institucional</td></tr></thead>
                        <tbody><tr><td width="100%" colspan="2"><?php $consulta2=mysqli_query($link,"select nombres from usuario where id=".$row['institucional']."");
                                  if($row2=mysqli_fetch_array($consulta2)){echo $row2['nombres'];}?></td></tr>
                        </tbody>
                        
                        <thead class="table-dark"><tr><td width="100%" colspan="2">Representante Comunitario</td></tr></thead>
                        <tbody><tr><td width="100%" colspan="2"><?php $consulta2=mysqli_query($link,"select nombres from usuario where id=".$row['comunitario']."");
                                  if($row2=mysqli_fetch_array($consulta2)){echo $row2['nombres'];}?></td></tr>
                        </tbody>

                        <thead class="table-dark"><tr><td width="100%" colspan="2">Integrantes</td></tr></thead>
                        <tbody><tr><td width="100%" colspan="2"><?php $consulta2=mysqli_query($link,"select cedula,nombres from usuario where id in(select usuario from proyus where proyecto=".$row['id'].")");
                                  while($row2=mysqli_fetch_array($consulta2)){echo $row2['cedula']." - ".$row2['nombres']."<hr>";}?></td></tr>
                        </tbody>
                        
                    </table>
                <?php }?>    
                </tbody>
            </table>
            </center>
        </div>
      </div>
    </div>  
  <script src="js/local.js"></script>
  <script src="js/bootstrap.bundle.js"></script>
  <script src="js/sweetalert2.all.min.js"></script>
</body>
</html>