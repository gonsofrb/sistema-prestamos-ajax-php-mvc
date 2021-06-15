<!--Detectar cuando se haga click en el boton de cerrar sesión del home-->
<script>
    let btn_salir = document.querySelector(".btn-exit-system");

    //Capturamos el evento
    btn_salir.addEventListener('click',function(e){
        e.preventDefault();

        //Mostar alerta si queremos salir del sistema.
        Swal.fire({
			title: '¿Quieres salir del sistema?',
			text: "La sesion actual se cerrara y saldras del sistema",
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, salir',
			cancelButtonText: 'No, cancelar'
		}).then((result) => {
			if (result.value) {
				//Envio de datos por ajax y la función fetch

                //url donde vamos a enviar los datos
                let url='<?php echo SERVER_URL?>ajax/loginAjax.php';

                let token='<?php echo $ilc->encryption($_SESSION['token_spm']); ?>';
                let usuario='<?php echo $ilc->encryption($_SESSION['usuario_spm']); ?>';

                let datos = new FormData();
                datos.append("token",token);
                datos.append("usuario",usuario);

                fetch(url,{
                    method:'POST',
                    body: datos
                })
                .then(respuesta => respuesta.json())
                .then(respuesta =>{
                    return alertas_ajax(respuesta);
                });

			}
		});

    });
</script>