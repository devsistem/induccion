<?php
// SOLO PARA GERENTE GENERAL
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido');
global $BackendUsuario, $Pedido;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteLogistica() && !$BackendUsuario->esRoot()) { 
 die;
}		


$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$id_asistente = ($_REQUEST['id_asistente']) ? $_REQUEST['id_asistente'] : null;
$id_asistente_sel = ($_POST['id_asistente_sel']) ? $_POST['id_asistente_sel'] : null;

if($id_asistente_sel > 0) {
 $id_asistente = null;
}

switch ($accion) {
 
 case 'asignar':
	   $Pedido->asignar($_POST['id'], $_POST['campo']);
	   $accion = "asignado";
 break;

 case 'asignar-multiple':
	  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
	   $Pedido->asignar($id_asistente_sel, $idx);
    }
	   $accion = "asignado";
 break;
}

// todos los pedidos, por orden de llegada y asignados o en estado 1
$result_todos = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 1, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, null, null, $id_asistente);
$filas_todos = @mysql_num_rows($result_todos);

include("meta.php");
?>

<?php //+  acciones js // ?>
 <script>
 function asignar(idx) {
  if(confirm('Esta seguro de querer asignar este pedido')) {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'asignar';
  form['id'].value = idx;
  var campo =  "#id_asistente"+idx; 
  form['campo'].value = $(campo).val();;
  form.submit();
  }
 }
 
 function asignar_multiple() {
  if(confirm('Esta seguro de querer asignar este pedido')) {
   var form = document.forms['frmPrincipal'];
   form['accion'].value = 'asignar-multiple';
   var id_asistente = $("#id_asistente").val();;
   form['id_asistente_sel'].value = id_asistente;
   form.submit();
  }
 } 
</script>
<?php //-  acciones js // ?>

</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade in page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<!-- begin #header -->
		<?php include("header.php")?>
		<!-- end #header -->
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<!-- begin #sidebar -->
		<?php include("sidebar.php")?>
		<!-- end #sidebar -->
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="index.php">Portada</a></li>
				<li><a href="noticias.php">Pedidos</a></li>
				<li class="active">Asignar Pedidos</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Asignar Pedidos: <small>listado de pedidos para asignar a los asistentes</small></h1>
			<!-- end page-header -->

		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_asistente_sel">
			<input type="hidden" name="id_item">			
			<!-- begin row -->
			<div class="row">
			    
			    <!-- end col-2 -->
			    <!-- begin col-10 -->
			    <div class="col-md-12">
			        <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Pedidos</h4>
                        </div>
                        
                        <?php //+ mensajes // ?>
                        <?php if($accion == "asignado") { ?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            Los pedidos fueron asignados correctamente
                        </div>
	                      <?php } ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                    												<th width="2%"></th>
                    												<th width="2%">Id</th>
                                            <th width="10%">Nombre</th>
                                            <th width="10%">Apellido</th>
                                            <th width="10%">Vendedor</th>
                                            <th width="10%">Provincia</th>
                                            <th width="10%">Ciudad</th>
                                            <th width="10%">Fecha Ingreso</th>
                                            <th width="15%">Asignado a</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
																	
					   											for($i=1; $i <= $filas_todos; $i++) {
						 												$items = @mysql_fetch_array($result_todos);
						 												$arrAsistente = $BackendUsuario->obtener($items['id_asistente']);
																	?>                                     	
                                        <tr class="odd gradeX">
	                                        	<td><input type="checkbox" id="arrSeleccion" name="arrSeleccion[]" value="<?=$items['id']?>"></td>
                                            <td align="center"><?=$items['id']?></td>
                                            <td><?=$items['cliente_nombre']?></td>
                                            <td><?=$items['cliente_apellido']?></td>
                                            <td><?=$items['vendedor_nombre']?></td>
                                            <td><?=$items['cliente_provincia']?></td>
                                            <td><?=$items['cliente_canton']?></td>

                                            <td align="center">
	                                            	<?=GetFechaTexto($items['fecha_alta'])?>
                                            </td>
                                            <td>
                                              <?php if($arrAsistente['id'] > 0) { ?>
                                              <?=$arrAsistente['nombre']?> <?=$arrAsistente['apellido']?>
	                                            <?php } else { ?>
	                                             SIN ASIGNAR
  	                                          <?php }  ?>
	 
                                            </td>
                                        </tr>
                                  <?php
                                  } 
                                  ?>     
																			       	
                                    </tbody>
                                </table>
                                <div style="clear:both"></div>
                                <table>
                               	 <tr>
                                	<td>
                                		<strong>Asignar Seleccionados:</strong>
                															<?php
                                               	// asistentes
																								$result_asistentes = $BackendUsuario->obtener_asistentes(12,ACTIVO); 
																								$filas_asistentes = @mysql_num_rows($result_asistentes);
                                               	?>
                                               	<select id="id_asistente" name="id_asistente" onChange="asignar_multiple()" style="width:200px; height:50px" required="true">
                                               	 <option value=""> - Seleccionar Aistente -</option>
                                               	 <?php
                                               	  for($k=0; $k < $filas_asistentes; $k++) {
						 																				$items_asistentes = @mysql_fetch_array($result_asistentes); ?>
						 																				
                                               	  <option value="<?=$items_asistentes['id']?>" <?=($items_asistentes['id']==$items['id_asistente']) ? 'selected' : ''?>><?=$items_asistentes['apellido']?>,  <?=$items_asistentes['nombre']?></option>
						 																				
						 																			<?php
						 																				}
						 																			?>	
                                               	</select>
                                	</td>
                               	 </tr>
                                </table>
                                               	                                
                            </div>
                        </div>
                    </div>
                    

                    
                    <!-- end panel -->
            
                <!-- end col-10 -->
          </div>
            <!-- end row -->
		  </div>
		</form>
		<!-- end #content -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
 <?php include("footer_meta.php")?>
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="assets/plugins/DataTables/js/dataTables.colReorder.js"></script>
	<script src="assets/js/table-manage-colreorder.demo.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
			TableManageColReorder.init();

		});
	</script>
</body>
</html>
