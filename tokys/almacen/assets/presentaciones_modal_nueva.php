<div class="modal-content">
  <div class="modal-header bg-blue-300">
    <h4 class="modal-title white">Agregar Presentación</h4>
  </div>
  <div class="modal-body">
    <div class="modal-body">
      <form id="form_nueva_presentacion" autocomplete="off" action="#">
        <div class="row row-lg">
          
          <div class="col-xl-12 form-horizontal">

            <div class="form-group form-material" >
              <b class="blue-400">Presentación</b>
              <input type="text" class="form-control" id="nombre_presentacion" name="nombre_presentacion" required>
            </div>

          </div>
          <div class="form-group form-material col-xl-12 text-right padding-top-m">
            <button type="button" class="btn btn-sm btn-round bg-blue-700 white" id="btn_agregar_presentacion" onclick="agregar_presentacion();">Agregar</button>
            <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>