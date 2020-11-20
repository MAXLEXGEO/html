<?php
  //INVOCAR EL ARCHIVO DE CONEXION
  require_once '../../assets/scripts/php/connection.php';

  //FUNCIONES
  require_once '../../assets/scripts/php/functions/f_marcas.php';

  //MARCAS
  $marcas = get_marcas();
?>
<table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable" id="marcas">
  <thead class="bg-blue-300">
    <tr>
      <th class="white">#</th>
      <th class="white">Marca</th>
      <th class="white">Status</th>
      <th class="white">Editar</th>
    </tr>
  </thead>
  
  <tbody>

    <? 
      $i = 0;
      foreach($marcas AS $marca){
        $i = $i+1;
    ?>
    <tr>
      <td><?=$i?></td>
      <td><?=$marca['marca']?></td>
      <td><?=get_status_marca($marca['status'])?></td>
      <td>
        <button data-toggle="modal" data-target="#modal_marca_editar" data-target-file="marcas_modal_editar" data-id="<?=$marca['id_marca']?>" type="button" class="btn btn-pure blue-grey-500 icon md-edit waves-effect waves-classic"></button>
      </td>
    </tr>
    <? } ?>
  </tbody>
</table>
<script type="text/javascript">
  $('#marcas').DataTable();
  $('[data-toggle="tooltip"]').tooltip();
</script>