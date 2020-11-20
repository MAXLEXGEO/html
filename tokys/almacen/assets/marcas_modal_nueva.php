<div class="modal-content">
  <div class="modal-header bg-blue-300">
    <h4 class="modal-title white">Agregar Marca</h4>
  </div>
  <div class="modal-body">
    <div class="modal-body">
      <form id="form_nueva_marca" autocomplete="off" action="#">
        <div class="row row-lg">
          
          <div class="col-xl-12 form-horizontal">

            <div class="form-group form-material" >
              <b class="blue-400">Marca</b>
              <input type="text" class="form-control" id="nombre_marca" name="nombre_marca" required>
            </div>

          </div>
          <div class="form-group form-material col-xl-12 text-right padding-top-m">
            <button type="button" class="btn btn-sm btn-round bg-blue-700 white" id="btn_agregar_marca" onclick="agregar_marca();">Agregar</button>
            <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>