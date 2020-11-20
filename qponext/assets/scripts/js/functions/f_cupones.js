//CARGAR DATOS EN TABLA - CUPONES
function get_cupones_table(cuponera){
    $.ajax({
        url: 'cupones_tabla.php',
        type: 'POST',
        dataType: 'json',
        data: {
            cuponera : cuponera
        },
        success: function (response){
            //LIMPIA LA TABLA
            $('#table_cupones').DataTable().clear().draw();
            //PINTA LOS DATOS EN LA TABLA
            $.each(response, function(index, value){
                $('#table_cupones').DataTable().row.add({
                    '0': value.codigo_cupon,
                    '1': value.titulo,
                    '2': value,
                    '3': value.descripcion,
                    '4': value.cuponera,
                    '5': value.empresa,
                    '6': value.disponibles,
                    '7': value.id_cupon,
                }).draw();
            });
        },
        error: function (error) {
            //LIMPIA LA TABLA
            $('#table_cupones').DataTable().clear().draw();
        }
    });
}

//BOTONES -ETIQUETAS TABLA CUPONES
$('#table_cupones').DataTable({
    dom: 'Bfrtip',
    responsive: true,
    columnDefs: [{
        
        targets: [2,6,7],
        className: "text-center",
        
        },{
            targets: 2,
            render: function(data, type, row, meta, full) {
                //COLOR DE TEXTO
                var text  = 'Descuento';
                //SWITCH VAL
                switch (data.tipo_descuento) {
                  
                  case 'PCT':
                    text = Math.round(data.descuento) + ' %';
                    break;
                  
                  case 'MND':
                    text = '$ ' + data.descuento;
                    break;
                  
                  default:
                    text = 'Descuento';
                }
                //PINTA LA ETIQUETA
                return text;
            }
        },{
        
        targets: 7,
        render: function(data, type, row, meta, full) {
            
            return '<button type="button" class="btn btn-danger btn-circle" title="Borrar Cupón" id="'+data+'" onclick="delete_cupon(this);"><i class="fa fa-times"></i></button>';
        }
    }]
});

//REGISTRAR CUPON
function add_cupon(){
    //FORM VARS
    var form     = $('#add_cupon_form');
    var cuponera = $('#cuponera_id').val();
    var btn_add  = document.getElementById('btn_add_cupon');
    var btn_done = document.getElementById('btn_done_cupon');

    //INICIA LA VALIDACIÓN
    form.validate();

    //SI EL VALIDO INICIA EL REGISTRO
    if(form.valid() == true){

        //BLOQUEAR BOTONES
        btn_add.innerHTML = '<img src="assets/images/loader.gif" width="15">';
        btn_add.disabled  = true;
        btn_done.disabled = true;

        //REGISTRAR CUPON
        $.ajax({
            type: 'POST',
            url: 'cupones_registrar.php',
            data: form.serialize(),
            
            success: function(response) {
                if(response == 'SUCCESS'){
                    //ALERT CUPON REGISTRADO
                    //alert('CUPÓN REGISTRADO');
                    //RESET DEL FORMULARIO
                    document.getElementById('add_cupon_form').reset();
                    document.getElementById('tipo_descuento').value = "";
                    //REINICIA BOTONES
                    btn_add.innerHTML = '<i class="fas fa-plus"></i>&emsp;Añadir';
                    btn_add.disabled  = false;
                    btn_done.disabled = false;
                    //TABLA DE CUPONES
                    get_cupones_table(cuponera);
                }
            }
        });

    }else{
        
        return false;
    }
}

//BORRAR CUPÓN
function delete_cupon(cupon){
    //FORM VARS
    var cupon    = cupon.id;
    var cuponera = $('#cuponera_id').val();
    var btn_del  = document.getElementById(cupon);

    //BLOQUEAR BOTONES
    btn_del.innerHTML = '<img src="assets/images/loader.gif" width="15">';
    btn_del.disabled  = true;

    $.ajax({
        type: 'POST',
        url: 'cupones_borrar.php',
        data: 'cupon='+cupon,
        
        success: function(response) {

            if(response == 'SUCCESS'){
                
                //alert('CUPÓN BORRADO');
                //TABLA DE CUPONES
                get_cupones_table(cuponera);
            }
        }
    });
}

//EDITAR CUPON - MODAL
$('#editar_cupon_modal').on('show.bs.modal', function (e) {
    var cupon = $(e.relatedTarget).data('id');
    var file  = $(e.relatedTarget).data('target-file');
    $.ajax({
        type : 'post',
        url : file, //Here you will fetch records 
        data : 'cupon='+cupon, //Pass $id
        success : function(data){
            $('.modal-content').html(data);//Show fetched data from database
        }
    });
});

//CANJEAR CUPÓN
function canjear_cupon(cupon){
    //FORM VARS
    var cupon       = cupon;
    var btn_canjear = document.getElementById('cupon_btn_canjear');

    //BLOQUEAR BOTONES
    //btn_canjear.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    //btn_canjear.disabled  = true;

    $.ajax({
        type: 'POST',
        url: 'cupones_canjeo.php',
        data: 'cupon='+cupon,
        
        success: function(response) {

            if(response == 'SUCCESS'){
                
                alert('CUPÓN CANJEADO');
                //btn_canjear.innerHTML = 'Canjear';
                //btn_canjear.disabled  = false;
                $('#myModal').modal('hide');
            }
        }
    });
}