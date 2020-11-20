<?php
  //INVOCAR EL ARCHIVO DE CONEXION
  require_once '../../assets/scripts/php/connection.php';

  //FUNCIONES
  require_once '../../assets/scripts/php/functions/f_marcas.php';

  //RECIBE EL ID
  $marca = $_POST['marca'];

  //CONSULTA DETALLES
  $marca_det = get_marca_det($marca);
?>
<div class="modal-content">
<div class="modal-header bg-blue-300">
  <h4 class="modal-title white" id="exampleOptionalSmall">Editar Marca</h4>
</div>
<div class="modal-body">
  <div class="modal-body">
    <form id="form_editar_marca" autocomplete="off" action="#">
      <div class="row row-lg">
        
        <div class="col-xl-12 form-horizontal">

          <div class="form-group form-material" >
            <b class="blue-400">Marca</b>
            <input type="text" class="form-control" id="nombre_marca_editar" name="nombre_marca" value="<?=$marca_det['marca']?>" required>
          </div>

          <div class="form-group form-material" >
            <b class="blue-400">Status</b>
            <div class="radio-custom radio-success">
              <input type="radio" name="marca_status" value="A" <? if($marca_det['status'] == 'A'){ echo 'checked'; }?>/>
              <label>Activa</label>
            </div>
            <div class="radio-custom radio-danger">
              <input type="radio" name="marca_status" value="I" <? if($marca_det['status'] == 'I'){ echo 'checked'; }?>/>
              <label>Inactiva</label>
            </div>
            <input type="hidden" name="id_marca" value="<?=$marca?>">
          </div>

        </div>
        <div class="form-group form-material col-xl-12 text-right padding-top-m">
          <button type="button" class="btn btn-sm btn-round bg-blue-700 white" id="btn_actualizar_marca" onclick="actualizar_marca()">Guardar</button>
          <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>