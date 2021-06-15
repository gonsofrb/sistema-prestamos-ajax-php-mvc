<script>
    /****Buscar cliente*/ 
    function buscar_cliente(){
        let input_cliente=document.querySelector('#input_cliente').value; 

        input_cliente=input_cliente.trim();

            if(input_cliente !=""){
                let datos = new FormData();
                datos.append("buscar_cliente",input_cliente );
                fetch("<?php echo SERVER_URL; ?>ajax/prestamoAjax.php",{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta => respuesta.text())
                .then(respuesta =>{
                    let tabla_clientes=document.querySelector('#tabla_clientes'); 
                    tabla_clientes.innerHTML=respuesta;
            });
            }else{
                Swal.fire({
            //alerta.Titulo , titulo es el índice.
            title: 'Ocurrió un error en el inesperado.',
            text: 'Debes introducir el DNI,NOMBRE,APELLIDOS Y TELEFONO.',
            type: 'error',
            confirmButtonText: 'Aceptar'
          });
            }
        
    }

    /****Agregar cliente*/
    function agregar_cliente(id){

        //Ocultar ventana modal al agregar el cliente
        $('#ModalCliente').modal('hide');

        Swal.fire({
            title: '¿Quieres agregar este cliente?',
            text: 'Se va a agregar este cliente para realizar un prestamo.',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, agregar.',
            cancelButtonText: 'No, Cancelar'
        }).then((result) => {
            if(result.value) {
                let datos = new FormData();
                datos.append("id_agregar_cliente",id);
                fetch("<?php echo SERVER_URL; ?>ajax/prestamoAjax.php",{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta => respuesta.json())
                .then(respuesta =>{
                    return alertas_ajax(respuesta);
                });
                
            }else{
                //Volvemos a mostrar la ventana modal con los datos.
                $('#ModalCliente').modal('show');
            }
      });

    } 

    
    /****Agregar item*/
    function buscar_item(){
          
        let input_item=document.querySelector('#input_item').value; 

        input_item=input_item.trim();

            if(input_item !=""){
                let datos = new FormData();
                datos.append("buscar_item",input_item );
                fetch("<?php echo SERVER_URL; ?>ajax/prestamoAjax.php",{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta => respuesta.text())
                .then(respuesta =>{
                    let tabla_items=document.querySelector('#tabla_items'); 
                    tabla_items.innerHTML=respuesta;
            });
            }else{
                    Swal.fire({
                //alerta.Titulo , titulo es el índice.
                title: 'Ocurrió un error en el inesperado.',
                text: 'Debes introducir el codigo o nombre del item.',
                type: 'error',
                confirmButtonText: 'Aceptar'
                });
            }

    }

    /****Modales de item*/
    function modal_agregar_item(id){
        //Ocultar ventana modal al agregar el item
        $('#ModalItem').modal('hide');

        //Se abre otra ventana modal
        $('#ModalAgregarItem').modal('show');

        document.querySelector('#id_agregar_item').setAttribute("value",id); 
    }

    function modal_buscar_item(){
         
        $('#ModalAgregarItem').modal('hide');
        $('#ModalItem').modal('show');
    }
</script>