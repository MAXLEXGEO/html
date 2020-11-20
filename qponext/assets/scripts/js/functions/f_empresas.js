//CARGAR DATOS EN TABLA - EMPRESAS
function get_empresas_table(){
    $.ajax({
        url: 'empresas_tabla.php',
        type: 'POST',
        dataType: 'json',
        success: function (response){
            //LIMPIA LA TABLA
            $('#table_empresas').DataTable().clear().draw();
            //PINTA LOS DATOS EN LA TABLA
            $.each(response, function(index, value){
                $('#table_empresas').DataTable().row.add({
                    '0': value.nombre,
                    '1': value.domicilio,
                    '2': value.telefono,
                    '3': value.status,
                    '4': value,
                }).draw();
            });
        },
        error: function (error) {
            //console.error('error en la respuesta');
            //LIMPIA LA TABLA
            $('#table_empresas').DataTable().clear().draw();
        }
    });
}

//BOTONES -ETIQUETAS TABLA EMPRESAS
$('#table_empresas').DataTable({
    order: [ [0, "asc"]],
    columnDefs: [{
        targets: [3,4],
        className: "text-center",
        
        },{
            targets: 3,
            render: function(data, type, row, meta, full) {
                //COLOR DE TEXTO
                var text_type  = 'text-secondary';
                var text_label = 'STATUS';
                //SWITCH VAL
                switch (data) {
                  
                  case 'A':
                    text_type  = 'text-success';
                    text_label = 'ACTIVA';
                    break;
                  
                  case 'I':
                    text_type  = 'text-danger';
                    text_label = 'INACTIVA';
                    break;
                  
                  default:
                    text_type  = 'text-secondary';
                    text_label = 'STATUS';
                }
                //PINTA LA ETIQUETA
                return '<b class="'+text_type+'">'+text_label+'</b>';
            }
        },{
            targets: 4,
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
        }
    ]
});

//REGISTRAR EMPRESAS
function add_empresa(){
    
    //VARIABLES
    var form       = $( "#add_empresa_form" );
    var btn_add    = document.getElementById('btn_add_empresa');
    var btn_cancel = document.getElementById('btn_cancel_empresa');
    var formData   = new FormData(document.getElementById('add_empresa_form'));
    //INICIA LA VALIDACIÓN
    form.validate();

    //SI EL VALIDO INICIA EL REGISTRO
    if(form.valid() == true){

        //BLOQUEAR BOTONES
        btn_add.innerHTML   = '<img src="assets/images/loader.gif" width="15">';
        btn_add.disabled    = true;
        btn_cancel.disabled = true;
        
        //REGISTRO DE LA EMPRESA
        $.ajax({
            type: 'POST',
            url: 'empresas_registrar.php',
            data: formData,
            contentType:false,
            processData:false,
            cache:false,
            success: function(response) {
                
                if(response == 'SUCCESS'){
                    //ALERT EMPRESA REGISTRADA
                    alert('EMPRESA REGISTRADA');
                    //RESET DEL FORMULARIO
                    document.getElementById('add_empresa_form').reset();
                    //REINICIA BOTONES
                    btn_add.innerHTML   = 'Registrar';
                    btn_add.disabled    = false;
                    btn_cancel.disabled = false;
                    btn_cancel.click();
                    //TABLA DE EMPRESAS
                    get_empresas_table();
                }

                if(response == 'LIMIT'){
                    //ALERT LOGO LIMIT
                    alert('EL LOGO SUPERA EL LIMITE PERMITIDO DE MB');
                    //REINICIA BOTONES
                    btn_add.innerHTML   = 'Registrar';
                    btn_add.disabled    = false;
                    btn_cancel.disabled = false;
                }

                if(response == 'FAILED'){
                    //ALERT LOGO LIMIT
                    alert('NO FUE POSIBLE REGISTRAR LA EMPRESA, INTENTELO NUEVAMENTE');
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