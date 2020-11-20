$(document).ready(function(){
  //CARGA LA TABLA DE FORMA DINÁMICA
  $('#tabla_presentaciones').load('assets/presentaciones_tabla.php');

  //MODAL NUEVA
  $('#modal_pre_nueva').on('show.bs.modal', function (e) {
      var targetFile = $(e.relatedTarget).data('target-file');
      $.ajax({
          type : 'post',
          url : 'assets/'+targetFile, //Here you will fetch records 
          success : function(data){
              $('#modal_pre_nueva_content').html(data);//Show fetched data from database
          }
      });
  });

  //MODAL EDITAR
  $('#modal_pre_editar').on('show.bs.modal', function (e) {
      var targetFile = $(e.relatedTarget).data('target-file');
      var presentacion  = $(e.relatedTarget).data('id');
      $.ajax({
          type : 'post',
          url : 'assets/'+targetFile, //Here you will fetch records 
          data :  'presentacion='+presentacion,
          success : function(data){
              $('#modal_pre_editar_content').html(data);//Show fetched data from database
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

function agregar_presentacion(){
  
  //FORMULARIO
  var form = $('#form_nueva_presentacion');

  //INICIA LA VALIDACIÓN
  form.validate();

  //SI EL VALIDO INICIA EL REGISTRO
  if(form.valid() == true){
    
    //REGISTRO
    $.ajax({
      type: 'POST',
      url: 'assets/presentaciones_registrar.php',
      data: form.serialize(),
      
      //RESPUESTA DEL REGISTRO
      success: function(response) {
      
        if(response == 'SUCCESS'){
            //MARCA REGISTRADA
            toastr["success"]("<b>Presentación registrada.</b>");
            //RESET DEL FORMULARIO
            document.getElementById('form_nueva_presentacion').reset();
            //REINICIA LA TABLA
            $('#tabla_presentaciones').load('assets/presentaciones_tabla.php');
        }

        if(response == 'FAILED'){
            //ERROR - MARCA REGISTRADA
            toastr['error']('<b>Error al registrar la presentación.</b> Intentelo de nuevo');
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