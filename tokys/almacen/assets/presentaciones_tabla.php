<?php
  //INVOCAR EL ARCHIVO DE CONEXION
  require_once '../../assets/scripts/php/connection.php';

  //FUNCIONES
  require_once '../../assets/scripts/php/functions/f_presentaciones.php';

  //PRESENTACIONES
  $presentaciones = get_presentaciones();
?>
<table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable" id="presentaciones">
  <thead class="bg-blue-300">
    <tr>
      <th class="white">#</th>
      <th class="white">Presentación</th>
      <th class="white">Status</th>
      <th class="white">Editar</th>
    </tr>
  </thead>
  
  <tbody>

    <? 
      $i = 0;
      foreach($presentaciones AS $presentacion){
        $i = $i+1;
    ?>
    <tr>
      <td><?=$i?></td>
      <td><?=$presentacion['presentacion']?></td>
      <td><?=get_status_presentacion($presentacion['status'])?></td>
      <td>
        <button data-toggle="modal" data-target="#modal_pre_editar" data-target-file="presentaciones_modal_editar" data-id="<?=$presentacion['id_presentacion']?>" type="button" class="btn btn-pure blue-grey-500 icon md-edit waves-effect waves-classic"></button>
      </td>
    </tr>
    <? } ?>
  </tbody>
</table>
<script type="text/javascript">
  $('#presentaciones').DataTable();
  $('[data-toggle="tooltip"]').tooltip();
</script>