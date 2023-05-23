var sesion = localStorage.getItem("nombres");

const verificasesion = () => {
  if(sesion!=null){
    window.location.href = "inicio.html";
  }
}

const registrarUsuario = async() => {
    var cedula = document.querySelector("#cedula").value;
    var nombres = document.querySelector("#nombres").value;
    var telefono = document.querySelector("#telefono").value;
    var correo = document.querySelector("#correo").value;
    var password = document.querySelector("#password").value;
    var cPassword = document.querySelector("#cPassword").value;
    if((cedula.trim()==='') || 
       (nombres.trim()==='')||
       (telefono.trim()==='')||
       (correo.trim()==='')||
       (password.trim()==='')||
       (cPassword.trim()==='')
      ){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe llenar todos los campos',
            footer: 'Control de Proyectos'
          })
          return;
      }

      if(!validarCedula(cedula)){
        Swal.fire({
          icon: 'error',
          title: 'Error',
          html: 'La cedula solo debe contener numeros<br>minimo 6 digitos',
          footer: 'Control de Proyectos'
        })
        return;
      }

      if(!validarNombres(nombres)){
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ingresa nombre(s) y apellido(s) validos',
          footer: 'Control de Proyectos'
        })
        return;
      }

      if(!validarTelefono(telefono)){
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ingresa un telefono valido',
          footer: 'Control de Proyectos'
        })
        return;
      }

      if(!validarCorreo(correo)){
        valor=validarCorreo(correo);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ingresa un correo valido',
          footer: 'Control de Proyectos'
        })
        return;
      }

      if(!validarPassword(password,cPassword)){
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'La clave no coincide con la confirmacion',
          footer: 'Control de Proyectos'
        })
        return;
      }

      const datos=new FormData();
      datos.append("cedula",cedula);
      datos.append("nombres",nombres);
      datos.append("telefono",telefono);
      datos.append("correo",correo);
      datos.append("password",password);

      var respuesta=await fetch("php/usuario/registrarUsuario.php",{
        method:'POST',
        body:datos
      }); 
      
      var resultado=await respuesta.json();

      if(resultado.success == true){
        Swal.fire({
          icon: 'success',
          title: 'Exito!',
          text: resultado.mensaje,
          footer: 'Control de Proyectos'
        })
        document.querySelector("#formRegistrar").reset();
        setTimeout(()=>{
          window.location.href="index.html";
        },2000);
      }else{
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: resultado.mensaje,
          footer: 'Control de Proyectos'
        })
      }
}

const loginUsuario = async() => {

  var correo = document.querySelector("#correo").value;
  var password = document.querySelector("#password").value;
  if((correo.trim()==='')||
     (password.trim()==='')    
     ){
      Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Debe llenar todos los campos',
          footer: 'Control de Proyectos'
        })
        return;
    }

    if(!validarCorreo(correo)){
      valor=validarCorreo(correo);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Ingresa un correo valido',
        footer: 'Control de Proyectos'
      })
      return;
    }

    const datos=new FormData();
    datos.append("correo",correo);
    datos.append("password",password);

    var respuesta=await fetch("php/usuario/loginUsuario.php",{
      method:'POST',
      body:datos
    });     
    var resultado=await respuesta.json();

    if(resultado.success == true){
      Swal.fire({
        icon: 'success',
        title: 'Exito!',
        text: resultado.mensaje,
        footer: 'Control de Proyectos'
      })
      document.querySelector("#formIniciar").reset();
      localStorage.setItem("nombres",resultado.nombres);
      setTimeout(()=>{
        window.location.href="inicio.html";
      },2000);
    }else{
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: respuesta.mensaje,
        footer: 'Control de Proyectos'
      })
    }
  }