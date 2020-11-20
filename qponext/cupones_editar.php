<?php
    //INVOCAR EL ARCHIVO DE CONEXION
    require_once 'assets/scripts/php/connection.php';
    //FUCIONES DEL PROYECTO
    require_once 'assets/scripts/php/functions/f_cupones.php';
    //RECIBE EL ID DEL CUPON
    $cupon = $_POST['cupon'];
    //CONSULTA LOS DATOS DEL CUPON
    $cupon_detalles = get_cupon_det($cupon);
?>
<div class="modal-header modal-colored-header bg-primary">
    <h4 class="modal-title" id="primary-header-modalLabel">Editar Cupón</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">

    <form action="#" method="POST" id="edit_cupon_form" enctype="multipart/form-data">
        <div class="form-body">
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="#titulo">Título</label>
                        <input id="titulo" name="titulo" type="text" class="form-control" placeholder="Título o nombre del cupón" maxlength="50" required value="<?=$cupon_detalles['titulo']?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="#codigo_cupon">Código</label>
                        <input id="codigo_cupon" name="codigo_cupon" type="text" class="form-control" placeholder="Código" maxlength="50" required value="<?=$cupon_detalles['codigo_cupon']?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="#disponibles">Num. Cupones</label>
                        <input type="number" class="form-control" name="disponibles" id="disponibles" required value="<?=$cupon_detalles['disponibles']?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="#tipo_descuento">Tipo Descuento</label>
                        <select name="tipo_descuento" class="form-control selectpicker" id="tipo_descuento" required>
                            <option value="">Tipo descuento...</option>
                            <option value="PCT" <? if($cupon_detalles['tipo_descuento'] == 'PCT'){ echo "selected"; }?>>%</option>
                            <option value="MND" <? if($cupon_detalles['tipo_descuento'] == 'MND'){ echo "selected"; }?>>$</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="#descuento">Descuento</label>
                        <input type="number" class="form-control" name="descuento" id="descuento" required value="<?=$cupon_detalles['descuento']?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="#descripcion">Descripción</label>
                        <textarea class="form-control" rows="2" placeholder="Descripción del cupón..." name="descripcion" id="descripcion" required><?=$cupon_detalles['descripcion']?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="#restricciones">Restricciones</label>
                        <textarea class="form-control" rows="2" placeholder="Restricciones del cupón..." name="restricciones" id="restricciones"><?=$cupon_detalles['restricciones']?></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="#img_cupon">Imagen del Cupón</label>
                        <input id="img_cupon" name="img_cupon" type="file" class="form-control" accept="image/jpeg,image/gif,image/png">
                    </div>
                </div>
                
            </div>
        </div>
        <div class="form-actions">
            <div class="text-right">
                <input type="hidden" class="form-control" name="cupon_id" id="cupon_id" value="<?=$cupon?>">
                <input type="hidden" class="form-control" name="cuponera_id" id="cuponera_id" value="<?=$cupon_detalles['id_cuponera']?>">
                <input type="hidden" class="form-control" name="cupon_qr" id="cupon_qr" value="<?=$cupon_detalles['qr']?>">
                <button id="btn_save_cupon" type="button" class="btn btn-rounded btn-success" onclick="save_cupon()">
                    <i class="fas fa-save"></i>&emsp;Guardar
                </button>
                <button type="button" class="btn btn-rounded btn-light" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </form>
</div>
<script src="assets/scripts/js/functions/f_cupones_modal.js"></script>