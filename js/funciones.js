const validarCedula=(cedula)=>{
    return /^\d{6,8}$/.test(cedula.trim());
}

const validarNombres=(nombres)=>{
    return /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,}$/.test(nombres.trim());
}

const validarTelefono=(telefono)=>{
    return /^\d{11}$/.test(telefono.trim());
} 
      
const validarCorreo=(correo)=>{
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/.test(correo.trim());
    
}

const validarPassword=(password,cPassword)=>{
    return password === cPassword;
}