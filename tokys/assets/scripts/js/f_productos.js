$(document).ready(function(){
  //CARGA LA TABLA DE FORMA DINÁMICA
  $('#tabla_productos').load('assets/productos_tabla.php');

  //MODAL NUEVO
  $('#modal_producto_nuevo').on('show.bs.modal', function (e) {
      var targetFile = $(e.relatedTarget).data('target-file');
      $.ajax({
          type : 'post',
          url : 'assets/'+targetFile, //Here you will fetch records 
          success : function(data){
              $('#modal_producto_nuevo_content').html(data);//Show fetched data from database
          }
      });
  });

  //MODAL EDITAR
  $('#modal_producto_editar').on('show.bs.modal', function (e) {
      var targetFile = $(e.relatedTarget).data('target-file');
      var producto   = $(e.relatedTarget).data('id');
      $.ajax({
          type : 'post',
          url : 'assets/'+targetFile, //Here you will fetch records 
          data :  'producto='+producto,
          success : function(data){
              $('#modal_producto_editar_content').html(data);//Show fetched data from database
          }
      });
  });

  toastr.options = {
    "containerId": "toast-topFullWidth",
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-full-width",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "slideDown",
    "hideMethod": "slideUp"
  };

});

function agregar_producto(){
  
  //FORMULARIO
  var form = $('#form_nuevo_producto');

  //INICIA LA VALIDACIÓN
  form.validate();

  //SI EL VALIDO INICIA EL REGISTRO
  if(form.valid() == true){
    
    //REGISTRO
    $.ajax({
      type: 'POST',
      url: 'assets/productos_registrar.php',
      data: form.serialize(),
      
      //RESPUESTA DEL REGISTRO
      success: function(response) {
      
        if(response == 'SUCCESS'){
            //PRODUCTO REGISTRADO
            toastr["success"]("<b>Producto registrado.</b>");
            //RESET DEL FORMULARIO
            document.getElementById('form_nuevo_producto').reset();
            //REINICIA LA TABLA
            $('#tabla_productos').load('assets/productos_tabla.php');
        }

        if(response == 'FAILED'){
            //ERROR - PRODUCTO REGISTRADO
            toastr['error']('<b>Error al registrar el producto.</b> Intentelo de nuevo');
        }
      }
  
    });
  }else{
    return false;
  }
}

function actualizar_presentacion(){
  //FORMULARIO
  var form = $('#form_editar_presentacion');

  //INICIA LA VALIDACIÓN
  form.validate();

  //SI EL VALIDO INICIA EL REGISTRO
  if(form.valid() == true){
    
    //REGISTRO
    $.ajax({
      type: 'POST',
      url: 'assets/presentaciones_actualizar.php',
      data: form.serialize(),
      
      //RESPUESTA DEL REGISTRO
      success: function(response) {
      
        if(response == 'SUCCESS'){
            //PRESENTACIONES ACTUALIZADA
            toastr["success"]("<b>Presentación actualizada.</b>");
            //REINICIA LA TABLA
            $('#tabla_presentaciones').load('assets/presentaciones_tabla.php');
        }

        if(response == 'FAILED'){
            //ERROR - PRESENTACIONES ACTUALIZADA
            toastr['error']('<b>Error al actualizar la presentación.</b> Intentelo de nuevo');
        }
      }
  
    });

  }else{
    return false;
  }
}