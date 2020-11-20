<?php
  //INVOCAR EL ARCHIVO DE CONEXION
  require_once '../../assets/scripts/php/connection.php';

  //FUNCIONES
  require_once '../../assets/scripts/php/functions/f_categorias.php';

  //CATEGORIAS
  $categorias = get_categorias();
?>
<table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable" id="categorias">
  <thead class="bg-blue-300">
    <tr>
      <th class="white">#</th>
      <th class="white">Categoría</th>
      <th class="white">Status</th>
      <th class="white">Editar</th>
    </tr>
  </thead>
  
  <tbody>

    <? 
      $i = 0;
      foreach($categorias AS $categoria){
        $i = $i+1;
    ?>
    <tr>
      <td><?=$i?></td>
      <td><?=$categoria['categoria']?></td>
      <td><?=get_status_cat($categoria['status'])?></td>
      <td>
        <button data-toggle="modal" data-target="#modal_categoria_editar" data-target-file="categorias_modal_editar" data-id="<?=$categoria['id_categoria']?>" type="button" class="btn btn-pure blue-grey-500 icon md-edit waves-effect waves-classic"></button>
      </td>
    </tr>
    <? } ?>
  </tbody>
</table>
<script type="text/javascript">
  $('#categorias').DataTable();
  $('[data-toggle="tooltip"]').tooltip();
</script>