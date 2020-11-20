$('#tipo_descuento').selectpicker('refresh');

//ACTUALIZAR CUPON
function save_cupon(){
    //FORM VARS
    var form     = $('#edit_cupon_form');
    var formData = new FormData(document.getElementById('edit_cupon_form'));
    var cuponera = $('#cuponera_id').val();
    var btn_save = document.getElementById('btn_save_cupon');

    //INICIA LA VALIDACIÓN
    form.validate();

    //SI EL VALIDO INICIA EL REGISTRO
    if(form.valid() == true){

        //BLOQUEAR BOTONES
        btn_save.innerHTML = '<div class="spinner-grow text-light" role="status"></div>';
        btn_save.disabled  = true;

        //REGISTRAR CUPON
        $.ajax({
            type: 'POST',
            url: 'cupones_actualizar.php',
            data: formData,
            contentType:false,
            processData:false,
            cache:false,
            success: function(response) {
                
                if(response == 'SUCCESS'){
                    //ALERT CUPON REGISTRADO
                    alert('CUPÓN ACTUALIZADO');
                    //REINICIA BOTONES
                    btn_save.innerHTML = '<i class="fas fa-save"></i>&emsp;Guardar';
                    btn_save.disabled  = false;

                    localStorage.clear();
                    location.reload();
                }

                if(response == 'LIMIT'){
                    //ALERT LOGO LIMIT
                    alert('EL LOGO SUPERA EL LIMITE PERMITIDO DE MB');
                }

                if(response == 'FAILED'){
                    //ALERT LOGO LIMIT
                    alert('NO FUE POSIBLE REGISTRAR LA EMPRESA, INTENTELO NUEVAMENTE');
                }
            }
        });

    }else{
        
        return false;
    }
}