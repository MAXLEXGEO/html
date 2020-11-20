<?php
  //INVOCAR EL ARCHIVO DE CONEXION
  require_once '../../assets/scripts/php/connection.php';

  //FUNCIONES
  require_once '../../assets/scripts/php/functions/f_presentaciones.php';

  //RECIBE EL ID
  $presentacion = $_POST['presentacion'];

  //CONSULTA DETALLES
  $presentacion_det = get_presentacion_det($presentacion);
?>
<div class="modal-content">
<div class="modal-header bg-blue-300">
  <h4 class="modal-title white" id="exampleOptionalSmall">Editar Presentación</h4>
</div>
<div class="modal-body">
  <div class="modal-body">
    <form id="form_editar_presentacion" autocomplete="off" action="#">
      <div class="row row-lg">
        
        <div class="col-xl-12 form-horizontal">

          <div class="form-group form-material" >
            <b class="blue-400">Presentación</b>
            <input type="text" class="form-control" id="nombre_presentacion_editar" name="nombre_presentacion" value="<?=$presentacion_det['presentacion']?>" required>
          </div>

          <div class="form-group form-material" >
            <b class="blue-400">Status</b>
            <div class="radio-custom radio-success">
              <input type="radio" name="presentacion_status" value="A" <? if($presentacion_det['status'] == 'A'){ echo 'checked'; }?>/>
              <label>Activa</label>
            </div>
            <div class="radio-custom radio-danger">
              <input type="radio" name="presentacion_status" value="I" <? if($presentacion_det['status'] == 'I'){ echo 'checked'; }?>/>
              <label>Inactiva</label>
            </div>
            <input type="hidden" name="id_presentacion" value="<?=$presentacion?>">
          </div>

        </div>
        <div class="form-group form-material col-xl-12 text-right padding-top-m">
          <button type="button" class="btn btn-sm btn-round bg-blue-700 white" id="btn_actualizar_presentacion" onclick="actualizar_presentacion()">Guardar</button>
          <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>