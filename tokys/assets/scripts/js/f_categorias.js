$(document).ready(function(){
  //CARGA LA TABLA DE FORMA DINÁMICA
  $('#tabla_categorias').load('assets/categorias_tabla.php');

  //MODAL NUEVA
  $('#modal_categoria_nueva').on('show.bs.modal', function (e) {
      var targetFile = $(e.relatedTarget).data('target-file');
      $.ajax({
          type : 'post',
          url : 'assets/'+targetFile, //Here you will fetch records 
          success : function(data){
              $('#modal_categoria_nueva_content').html(data);//Show fetched data from database
          }
      });
  });

  //MODAL EDITAR
  $('#modal_categoria_editar').on('show.bs.modal', function (e) {
      var targetFile = $(e.relatedTarget).data('target-file');
      var categoria  = $(e.relatedTarget).data('id');
      $.ajax({
          type : 'post',
          url : 'assets/'+targetFile, //Here you will fetch records 
          data :  'categoria='+categoria,
          success : function(data){
              $('#modal_categoria_editar_content').html(data);//Show fetched data from database
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

function agregar_categoria(){
  
  //FORMULARIO
  var form = $('#form_nueva_categoria');

  //INICIA LA VALIDACIÓN
  form.validate();

  //SI EL VALIDO INICIA EL REGISTRO
  if(form.valid() == true){
    
    //REGISTRO
    $.ajax({
      type: 'POST',
      url: 'assets/categorias_registrar.php',
      data: form.serialize(),
      
      //RESPUESTA DEL REGISTRO
      success: function(response) {
      
        if(response == 'SUCCESS'){
            //CATEGORIA REGISTRADA
            toastr["success"]("<b>Categoría registrada.</b>");
            //RESET DEL FORMULARIO
            document.getElementById('form_nueva_categoria').reset();
            //REINICIA LA TABLA
            $('#tabla_categorias').load('assets/categorias_tabla.php');
        }

        if(response == 'FAILED'){
            //ERROR - CATEGORIA REGISTRADA
            toastr['error']('<b>Error al registrar la categoría.</b> Intentelo de nuevo');
        }
      }
  
    });
  }else{
    return false;
  }
}

function actualizar_categoria(){
  //FORMULARIO
  var form = $('#form_editar_categoria');

  //INICIA LA VALIDACIÓN
  form.validate();

  //SI EL VALIDO INICIA EL REGISTRO
  if(form.valid() == true){
    
    //REGISTRO
    $.ajax({
      type: 'POST',
      url: 'assets/categorias_actualizar.php',
      data: form.serialize(),
      
      //RESPUESTA DEL REGISTRO
      success: function(response) {
      
        if(response == 'SUCCESS'){
            //CATEGORIA ACTUALIZADA
            toastr["success"]("<b>Categoría actualizada.</b>");
            //REINICIA LA TABLA
            $('#tabla_categorias').load('assets/categorias_tabla.php');
        }

        if(response == 'FAILED'){
            //ERROR - CATEGORIA ACTUALIZADA
            toastr['error']('<b>Error al actualizar la categoría.</b> Intentelo de nuevo');
        }
      }
  
    });

  }else{
    return false;
  }
}