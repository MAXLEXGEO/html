//CARGAR DATOS EN TABLA - CUPONERAS
function get_cuponeras_table(){
    $.ajax({
        url: 'cuponeras_tabla.php',
        type: 'POST',
        dataType: 'json',
        success: function (response){
            //LIMPIA LA TABLA
            $('#table_cuponeras').DataTable().clear().draw();
            //PINTA LOS DATOS EN LA TABLA
            $.each(response, function(index, value){
                $('#table_cuponeras').DataTable().row.add({
                    '0': value.codigo,
                    '1': value.empresa,
                    '2': value.fecha_inicio,
                    '3': value.fecha_caducidad,
                    '4': value.num_cupones,
                    '5': value.status,
                    '6': value.id_cuponera
                }).draw();
            });
        },
        error: function (error) {
            $('#table_cuponeras').DataTable().clear().draw();
        }
    });
}

//BOTONES -ETIQUETAS TABLA CUPONERAS
$('#table_cuponeras').DataTable({
    dom: 'Bfrtip',
    responsive: true,
    columnDefs: [
        {
            targets: [2,3,4,5,6],
            className: "text-center",
        },{
        
            targets: 5,
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
        
            targets: 6,
            render: function(data, type, row, meta, full) {
                
                return '<a href="cuponeras_personalizar.php?cuponera='+data+'"><h5><i class="icon-pencil text-info font-weight-bold" data-toggle="tooltip" data-placement="top" title="Personalizar"></i></h5></a>';
            }
        }
    ]
});

//REGISTRAR CUPONERA
function add_cuponera(){
    //FORM VARS
    var form       = $('#add_cuponera_form');
    var btn_add    = document.getElementById('btn_add_cuponera');
    var btn_cancel = document.getElementById('btn_cancel_cuponera');

    //INICIA LA VALIDACIÓN
    form.validate();

    //SI EL VALIDO INICIA EL REGISTRO
    if(form.valid() == true){

        //BLOQUEAR BOTONES
        btn_add.innerHTML   = '<img src="assets/images/loader.gif" width="15">';
        btn_add.disabled    = true;
        btn_cancel.disabled = true;

        $.ajax({
            type: 'POST',
            url: 'cuponeras_registrar.php',
            dataType: 'json',
            data: form.serialize(),
            
            success: function(response) {
                if(response['res'] == 'SUCCESS'){
                    //ALERT CUPONERA REGISTRADA
                    alert('CUPONERA REGISTRADA');
                    //RESET DEL FORMULARIO
                    document.getElementById('add_cuponera_form').reset();
                    document.getElementById('empresa').value = "";
                    //REINICIA BOTONES
                    btn_add.innerHTML   = 'Registrar';
                    btn_add.disabled    = false;
                    btn_cancel.disabled = false;
                    //REINICIA LA TABLA
                    get_cuponeras_table();
                    //MUESTRA FORMULARIO PARA AGREGAR CUPONES Y MANDA EL ID DE LA CUPONERA CREADA
                    document.getElementById('add_cuponera_div').style.display = 'none';
                    document.getElementById('add_cupon_div').style.display = 'block';
                    document.getElementById('cuponera_id').value = response['cuponera'];
                    //PINTA LA TABLA DE CUPONES
                    get_cupones_table(response['cuponera']);
                }
            }
        
        });

    }else{
        
        return false;
    }
}

//FINALIZAR LA INSERCION DE CUPONES
function done_cuponera(){
    //RESET DEL FORMULARIO AGREGAR CUPONES
    document.getElementById('add_cupon_form').reset();
    //OCULTAR FORMULARIO DE CUPONES
    document.getElementById('add_cuponera_div').style.display = 'block';
    document.getElementById('add_cupon_div').style.display = 'none';
    document.getElementById('cuponera_id').value = '';
    //LIMPIA LA TABLA DE CUPONES
    $('#table_cupones').DataTable().clear().draw();
    $('#btn_cancel_cuponera').click();
}