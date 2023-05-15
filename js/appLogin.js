const registrarUsuario = () => {
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
      }
}