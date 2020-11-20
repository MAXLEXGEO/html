var app = new Vue({
  
  el: '#app',
  data: {
    scanner: null,
    activeCameraId: null,
    cameras: [],
    scans: []
  },
  mounted: function () {
    
    var self = this;
    
    self.scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false  });
    
    self.scanner.addListener('scan', function (content, image) {
    
      details_cupon(content);
      //self.scans.unshift({date: +(Date.now()), content: content });
    
    });
    
    Instascan.Camera.getCameras().then(function (cameras) {
    
      self.cameras = cameras;
    
      if (cameras.length > 0) {
    
        self.activeCameraId = cameras[0].id;
        self.scanner.start(cameras[0]);
    
      } else {
    
        console.error('No cameras found.');
    
      }
    
    }).catch(function (e) {
    
      console.error(e);
    
    });
  
  },
  methods: {
    
    formatName: function (name) {
    
      return name || '(unknown)';
    
    },
    selectCamera: function (camera) {
    
      this.activeCameraId = camera.id;
      this.scanner.start(camera);
    }
  }

});

//DETALLES DEL CUPON A CANJEAR
function details_cupon(content){
    //CONTENIDO DEL CUPON
    var cupon    = content;

    //OBTIENE LOS DETALLES
    $.ajax({
      type: 'POST',
      url: 'cupones_detalles.php',
      dataType: 'json',
      data: cupon,
      
      success: function(response) {
          
          //CUPON VIGENTE
          if(response['res'] == 'SUCCESS'){
            document.getElementById("cupon_img").setAttribute("src", "assets/images/cupones/"+response['cupon_data']['img']);
            $('#cupon_titulo').removeClass('text-danger');
            $('#cupon_titulo').addClass('text-primary');
            document.getElementById("cupon_titulo").innerHTML = response['cupon_data']['titulo'];
            document.getElementById("cupon_desc").innerHTML = response['cupon_data']['descripcion'];
            document.getElementById("cupon_codigo").innerHTML = "CÓDIGO: "+response['cupon_data']['codigo_cupon'];
            document.getElementById("cupon_rest").innerHTML = response['cupon_data']['restricciones'];
            document.getElementById('cupon_btn_canjear').style.display = 'inline';
            document.getElementById('id_cupon').value = response['cupon_data']['id_cupon'];
            $('#myModal').modal('show');
          }

          //ERROR EN EL ENVIO DE DATOS - QR INCORRECTO
          if(response['res'] == 'EMPTY_POST'){
            document.getElementById("cupon_img").setAttribute("src", "assets/images/cupones/default_not_found.jpg");
            $('#cupon_titulo').removeClass('text-primary');
            $('#cupon_titulo').addClass('text-danger');
            document.getElementById("cupon_titulo").innerHTML = "¡ ERROR !";
            document.getElementById("cupon_desc").innerHTML = "Cupón no válido. Verifique su código e intente de nuevo";
            document.getElementById("cupon_codigo").innerHTML = "CÓDIGO: -";
            document.getElementById("cupon_rest").innerHTML = "-";
            document.getElementById('cupon_btn_canjear').style.display = 'none';
            $('#myModal').modal('show');
          }

          //CUPON CADUCADO
          if(response['res'] == 'EXPIRED'){
            document.getElementById("cupon_img").setAttribute("src", "assets/images/cupones/default_expired.jpg");
            $('#cupon_titulo').removeClass('text-primary');
            $('#cupon_titulo').addClass('text-danger');
            document.getElementById("cupon_titulo").innerHTML = "¡ EL CUPÓN HA EXPIRADO !";
            document.getElementById("cupon_desc").innerHTML = "El cupón que intenta canjear, ha expirado. Lo invitamos a ver todos los cupones que aún siguen disponibles.";
            document.getElementById("cupon_codigo").innerHTML = "CÓDIGO: -";
            document.getElementById("cupon_rest").innerHTML = "-";
            document.getElementById('cupon_btn_canjear').style.display = 'none';
            $('#myModal').modal('show');
          }
      }
    });
}