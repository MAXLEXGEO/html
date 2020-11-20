<?php
  //INVOCAR EL ARCHIVO DE CONEXION
  require_once '../../assets/scripts/php/connection.php';

  //FUNCIONES
  require_once '../../assets/scripts/php/functions/f_categorias.php';

  //RECIBE EL ID
  $categoria = $_POST['categoria'];

  //CONSULTA DETALLES
  $categoria_det = get_categoria_det($categoria);
?>
<div class="modal-content">
<div class="modal-header bg-blue-300">
  <h4 class="modal-title white" id="exampleOptionalSmall">Editar Categoría</h4>
</div>
<div class="modal-body">
  <div class="modal-body">
    <form id="form_editar_categoria" autocomplete="off" action="#">
      <div class="row row-lg">
        
        <div class="col-xl-12 form-horizontal">

          <div class="form-group form-material" >
            <b class="blue-400">Categoría</b>
            <input type="text" class="form-control" id="nombre_categoria_editar" name="nombre_categoria" value="<?=$categoria_det['categoria']?>" required>
          </div>

          <div class="form-group form-material" >
            <b class="blue-400">Status</b>
            <div class="radio-custom radio-success">
              <input type="radio" name="categoria_status" value="A" <? if($categoria_det['status'] == 'A'){ echo 'checked'; }?>/>
              <label>Activa</label>
            </div>
            <div class="radio-custom radio-danger">
              <input type="radio" name="categoria_status" value="I" <? if($categoria_det['status'] == 'I'){ echo 'checked'; }?>/>
              <label>Inactiva</label>
            </div>
            <input type="hidden" name="id_categoria" value="<?=$categoria?>">
          </div>

        </div>
        <div class="form-group form-material col-xl-12 text-right padding-top-m">
          <button type="button" class="btn btn-sm btn-round bg-blue-700 white" id="btn_actualizar_categoria" onclick="actualizar_categoria()">Guardar</button>
          <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>