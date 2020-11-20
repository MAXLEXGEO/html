<?php
  //INVOCAR EL ARCHIVO DE CONEXION
  require_once '../../assets/scripts/php/connection.php';

  //FUNCIONES
  require_once '../../assets/scripts/php/functions/f_productos.php';

  //PRESENTACIONES
  $productos = get_productos();
?>
<table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable" id="productos">
  <thead class="bg-blue-300">
    <tr>
      <th class="white">#</th>
      <th class="white">Producto</th>
      <th class="white">Código</th>
      <th class="white">Stock mínimo</th>
      <th class="white">Stock</th>
      <th class="white">Status</th>
      <th class="white"></th>
    </tr>
  </thead>
  
  <tbody>

    <? 
      $i = 0;
      foreach($productos AS $producto){
        $i = $i+1;
    ?>
    <tr>
      <td><?=$i?></td>
      <td><?=$producto['producto']?></td>
      <td><?=$producto['codigo']?></td>
      <td><?=$producto['stock_minimo']?></td>
      <td><?=$producto['stock']?></td>
      <td><?=get_status_producto($producto['status'])?></td>
      <td>
        <button data-toggle="modal" data-target="#modal_producto_editar" data-target-file="productos_modal_editar" data-id="<?=$producto['id_producto']?>" type="button" class="btn btn-pure blue-grey-500 icon md-edit waves-effect waves-classic"></button>
      </td>
    </tr>
    <? } ?>
  </tbody>
</table>
<script type="text/javascript">
  $('#productos').DataTable();
  $('[data-toggle="tooltip"]').tooltip();
</script>