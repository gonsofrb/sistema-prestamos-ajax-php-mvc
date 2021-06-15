const formularios_ajax = document.querySelectorAll(".FormularioAjax");

//Detectar el envio de formularios
formularios_ajax.forEach(formularios => {

function enviar_formulario_ajax(e){
    //Prevención envío formulario
    e.preventDefault();

    //data contiene los valores de los inputs del formulario
    let data = new FormData(this);
    let method = this.getAttribute("method"); //atributo del formulario
    let action = this.getAttribute("action"); //atributo del formulario
    let tipo = this.getAttribute("data-form"); //atributo del formulario

    let encabezados = new Headers();

    //En config hay toda la configuracion de envio que se hara por fetch
    let config = {
        method: method,
        headers: encabezados,
        mode: 'cors',
        cache: 'no-cache',
        body: data
    }
    //Definición del texto dependiendo del formulario que se trate
    let texto_alerta;
    if(tipo==="save"){

        texto_alerta="Los datos quedarán guardados en el sistema";

    }else if(tipo==="delete"){

        texto_alerta="Los datos serán eliminados del  sistema";
        
    }else if(tipo==="update"){

        texto_alerta="Los datos del sistema serán actualizados";
    }else if(tipo==="search"){

        texto_alerta="Se eliminará el término de busqueda y tendrás que escribir uno nuevo";
        
    }else if(tipo==="loans"){
        //parte de préstamo
        texto_alerta="Desea remover los datos seleccionados para préstamos o reservaciones";
    }else{

        texto_alerta="¿Quieres realizar la operación solicitada?";
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: texto_alerta,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {

            //envio de datos con fetch
            fetch(action,config)
            .then(respuesta => respuesta.json())
            .then(respuesta =>{
                return alertas_ajax(respuesta);
            });
        }
      });
}


        //Escuchamos un evento a ejecutar
    formularios.addEventListener("submit",enviar_formulario_ajax);
});


function alertas_ajax(alerta){
    //alerta=array o json
    if(alerta.Alerta==="simple"){
        Swal.fire({
            //alerta.Titulo , titulo es el índice.
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
          });
          
          //Si se da click a Aceptar pues se recarga la página
    }else if(alerta.Alerta==="recargar"){
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
          }).then((result) => {
            if (result.value) {
                location.reload();
            }
          })

    }else if(alerta.Alerta==="limpiar"){
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
          }).then((result) => {
            document.querySelector(".FormularioAjax").reset();
          })

    }else if(alerta.Alerta==="redireccionar"){
        window.location.href=alerta.URL;
    }
}