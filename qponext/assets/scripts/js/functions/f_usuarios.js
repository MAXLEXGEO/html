//CARGAR DATOS EN TABLA - EMPRESAS
function get_usuarios_table(){
    $.ajax({
        url: 'usuarios_tabla.php',
        type: 'POST',
        dataType: 'json',
        success: function (response){
            //LIMPIA LA TABLA
            $('#table_usuarios').DataTable().clear().draw();
            //PINTA LOS DATOS EN LA TABLA
            $.each(response, function(index, value){
                $('#table_usuarios').DataTable().row.add({
                    '0': value.usuario,
                    '1': value.rol,
                    '2': value.email,
                    '3': value.login,
                    '4': value.status,
                    //'5': value,
                    //'6': value,
                }).draw();
            });
        },
        error: function (error) {
            //console.error('error en la respuesta');
            //LIMPIA LA TABLA
            $('#table_usuarios').DataTable().clear().draw();
        }
    });
}

//BOTONES -ETIQUETAS TABLA EMPRESAS
$('#table_usuarios').DataTable({
    order: [ [0, "asc"]],
    columnDefs: [{
        targets: [3,4],
        className: "text-center",
        
        },{
            targets: 4,
            render: function(data, type, row, meta, full) {
                //COLOR DE TEXTO
                var text_type  = 'text-secondary';
                var text_label = 'STATUS';
                //SWITCH VAL
                switch (data) {
                  
                  case 'A':
                    text_type  = 'text-success';
                    text_label = 'ACTIVO';
                    break;
                  
                  case 'I':
                    text_type  = 'text-danger';
                    text_label = 'INACTIVO';
                    break;
                  
                  default:
                    text_type  = 'text-secondary';
                    text_label = 'STATUS';
                }
                //PINTA LA ETIQUETA
                return '<b class="'+text_type+'">'+text_label+'</b>';
            }
        }/*,{
            targets: 5,
            render: function(data, type, row, meta, full) {
                //COLOR DE TEXTO
                var text_type  = 'text-secondary';
                //SWITCH VAL
                switch (data.status) {
                  
                  case 'A':
                    text_type  = 'text-success';
                    break;
                  
                  case 'I':
                    text_type  = 'text-danger';
                    break;
                  
                  default:
                    text_type  = 'text-secondary';
                }
                //PINTA LA ETIQUETA
                return '<i class="icon-power '+text_type+' font-weight-bold" data-toggle="tooltip" data-placement="left" title="Cambiar Status"></i>';
            }
        }/*,{
            targets: 6,
            render: function(data, type, row, meta, full) {
                //PINTA LA ETIQUETA
                return '<a href="#" onclick="editar_usuario('+data.id_usuario+')"><i class="icon-pencil text-info font-weight-bold" data-toggle="tooltip" data-placement="left" title="Editar"></i></a>';
            }
        }*/
    ]
});

//REGISTRAR USUARIOS
function add_usuario(){
    
    //VARIABLES
    var form       = $( "#add_usuario_form" );
    var btn_add    = document.getElementById('btn_add_usuario');
    var btn_cancel = document.getElementById('btn_cancel_usuario');
    var formData   = new FormData(document.getElementById('add_usuario_form'));
    
    //INICIA LA VALIDACIÓN
    form.validate();

    //SI EL VALIDO INICIA EL REGISTRO
    if(form.valid() == true){

        //BLOQUEAR BOTONES
        btn_add.innerHTML   = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn_add.disabled    = true;
        btn_cancel.disabled = true;

        //VERIFICAR SI EXISTE EL USUARIO EN EL GRUPO
        $.ajax({
            type: 'POST',
            url: 'usuarios_check.php',
            data: formData,
            contentType:false,
            processData:false,
            cache:false,
            success: function(response) {
                
                //SI NO EXISTE, SE CONTINUA CON EL REGISTRO
                if(response == 'CONTINUE'){

                    $.ajax({
                        type: 'POST',
                        url: 'usuarios_registrar.php',
                        data: formData,
                        contentType:false,
                        processData:false,
                        cache:false,
                        success: function(res) {
                            
                            if(res == 'SUCCESS'){
                                //ALERT
                                alert('USUARIO REGISTRADO');
                                //RESET DEL FORMULARIO
                                document.getElementById('add_usuario_form').reset();
                                //REINICIA BOTONES
                                btn_add.innerHTML   = 'Registrar';
                                btn_add.disabled    = false;
                                btn_cancel.disabled = false;
                                btn_cancel.click();
                                //TABLA DE USUARIOS
                                get_usuarios_table();
                            }

                            if(res == 'FAILED'){
                                //ALERT LOGO LIMIT
                                alert('NO FUE POSIBLE REGISTRAR EL USUARIO, INTENTELO NUEVAMENTE');
                                //REINICIA BOTONES
                                btn_add.innerHTML   = 'Registrar';
                                btn_add.disabled    = false;
                                btn_cancel.disabled = false;
                            }
                        }
                    });
                }

                //SI EXISTE FINALIZA EL PROCESO
                if(response == 'USER_ALREADY_EXISTS'){
                    //ALERT LOGO LIMIT
                    alert('EL USUARIO YA SE ENCUENTRA REGISTRADO, FAVOR DE VERIFICAR');
                    //REINICIA BOTONES
                    btn_add.innerHTML   = 'Registrar';
                    btn_add.disabled    = false;
                    btn_cancel.disabled = false;
                }
            }
        });

    }else{
        
        return false;
    }
}