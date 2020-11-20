$(document).ready(function(){
  //CARGA LA TABLA DE FORMA DINÁMICA
  $('#tabla_marcas').load('assets/marcas_tabla.php');

  //MODAL NUEVA
  $('#modal_marca_nueva').on('show.bs.modal', function (e) {
      var targetFile = $(e.relatedTarget).data('target-file');
      $.ajax({
          type : 'post',
          url : 'assets/'+targetFile, //Here you will fetch records 
          success : function(data){
              $('#modal_marca_nueva_content').html(data);//Show fetched data from database
          }
      });
  });

  //MODAL EDITAR
  $('#modal_marca_editar').on('show.bs.modal', function (e) {
      var targetFile = $(e.relatedTarget).data('target-file');
      var marca  = $(e.relatedTarget).data('id');
      $.ajax({
          type : 'post',
          url : 'assets/'+targetFile, //Here you will fetch records 
          data :  'marca='+marca,
          success : function(data){
              $('#modal_marca_editar_content').html(data);//Show fetched data from database
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

function agregar_marca(){
  
  //FORMULARIO
  var form = $('#form_nueva_marca');

  //INICIA LA VALIDACIÓN
  form.validate();

  //SI EL VALIDO INICIA EL REGISTRO
  if(form.valid() == true){
    
    //REGISTRO
    $.ajax({
      type: 'POST',
      url: 'assets/marcas_registrar.php',
      data: form.serialize(),
      
      //RESPUESTA DEL REGISTRO
      success: function(response) {
      
        if(response == 'SUCCESS'){
            //MARCA REGISTRADA
            toastr["success"]("<b>Marca registrada.</b>");
            //RESET DEL FORMULARIO
            document.getElementById('form_nueva_marca').reset();
            //REINICIA LA TABLA
            $('#tabla_marcas').load('assets/marcas_tabla.php');
        }

        if(response == 'FAILED'){
            //ERROR - MARCA REGISTRADA
            toastr['error']('<b>Error al registrar la marca.</b> Intentelo de nuevo');
        }
      }
  
    });
  }else{
    return false;
  }
}

function actualizar_marca(){
  //FORMULARIO
  var form = $('#form_editar_marca');

  //INICIA LA VALIDACIÓN
  form.validate();

  //SI EL VALIDO INICIA EL REGISTRO
  if(form.valid() == true){
    
    //REGISTRO
    $.ajax({
      type: 'POST',
      url: 'assets/marcas_actualizar.php',
      data: form.serialize(),
      
      //RESPUESTA DEL REGISTRO
      success: function(response) {
      
        if(response == 'SUCCESS'){
            //MARCA ACTUALIZADA
            toastr["success"]("<b>Marca actualizada.</b>");
            //REINICIA LA TABLA
            $('#tabla_marcas').load('assets/marcas_tabla.php');
        }

        if(response == 'FAILED'){
            //ERROR - MARCA ACTUALIZADA
            toastr['error']('<b>Error al actualizar la marca.</b> Intentelo de nuevo');
        }
      }
  
    });

  }else{
    return false;
  }
}