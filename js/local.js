var sesion = localStorage.getItem("nombres");

const verificarsesion = () => {
  if(sesion==null){
    window.location.href = "index.html";
  }
  document.querySelector("#usuario").innerHTML=sesion;
}

const cerrarsesion = () =>{
    localStorage.clear();
    window.location.href="index.html";
}