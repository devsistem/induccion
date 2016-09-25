<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Cliente');
global $BackendUsuario, $Cliente;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

switch ($accion) {
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Cliente->eliminar($idx);
   }
 break;
 case 'publicar':
	   $Cliente->publicar($_POST['id'], $_POST['campo']);
 break;
 case 'estado':
	   $Cliente->estado($_POST['id'], $_POST['campo']);
 break;
}

// todos 
$result_todos = $Cliente->obtener_all(null, null, null, $palabra, $order_by, $filtro, null, $estado, $destacado, $filtro_id_categoria);
$filas_todos = @mysql_num_rows($result_todos);
?>
<?php include("meta.php");?>

<?php //+  acciones js // ?>
 <script>
 function eliminar() {
  if(confirm('Esta seguro de querer borrar')) {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'eliminar';
  form.submit();
  }
 }
 function publicar(idx,campo) {
 var form = document.forms['frmPrincipal'];
 form['campo'].value = campo;
 form['id'].value = idx;
 form['accion'].value = 'publicar';
 form.submit();
 }
 function destacado(idx,campo) {
 var form = document.forms['frmPrincipal'];
 form['campo'].value = campo;
 form['id'].value = idx;
 form['accion'].value = 'destacado';
 form.submit();
 }
 function estado(idx,campo) {
 var form = document.forms['frmPrincipal'];
 form['campo'].value = campo;
 form['id'].value = idx;
 form['accion'].value = 'estado';
 form.submit();
 }
 function editar(idx) {
  var form = document.forms['frmPrincipal'];
  form['id'].value = idx;
  form.action = 'clientes_editar.php';
  form.submit();
 }
 function buscar() {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'buscar';
  form.submit();
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
				<li><a href="noticias.php">Clientes</a></li>
				<li class="active">Listado</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Cliente <small>listado de Clientes</small></h1>
			<!-- end page-header -->

		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
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
                            <h4 class="panel-title">Clientes</h4>
                        </div>
                        
                        <?php //+ mensajes // ?>
                        <?php if($accion == "eliminado") { ?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            El Cliente ha sido eliminado correctamente.
                        </div>
	                      <?php } ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
																						<th width="2%"></th>
                                            <th width="1%">Id</th>                                        	
                                            <th width="40%">Nombre</th>
                                            <th width="20%">Email</th>
                                            <th width="10%">Activo</th>
                                            <th width="10%">Estado</th>
                                            <th width="10%">Fecha Alta</th>
                                            <th width="10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($i=1; $i <= $filas_todos; $i++) {
						 												$items = @mysql_fetch_array($result_todos);
						 												//$cantidad_fotos = $Foto->cantidad($items['id']);
																	?>                                     	
                                        <tr class="odd gradeX">
                                    	  		<td align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items['id']?>"></td>
                                            <td align="center"><?=$items['id']?></td>
                                            <td><?=$items['nombre']?> <?=$items['apellidos']?></td>
                                            <td><?=$items['email']?></td>
                                            <td align="center">
	                                            <a href="javascript:publicar('<?=$items['id']?>','<?=$items['activo']?>');" title="Activar o desactivar un usuario en el backend"><b><?=($items['activo'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            </td>
                                            <td align="center">
	                                            <a href="javascript:estado('<?=$items['id']?>','<?=$items['estado']?>');" title="estado"><b><?=($items['estado'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            </td>
                                            <td>
                                            	<?=GetFechaTexto($items['fecha_alta'])?>
                                            	/
                                            	<?php if(strlen($items['fecha_mod']) > 10) { ?>
                                            		<?=GetFechaTexto($items['fecha_mod'])?>
	                                            <?php } ?>
                                            	</td>
                                            <td>
	                                            <button type="button" class="btn btn-inverse" onClick="editar('<?=$items['id']?>')" ><i class="fa fa-cog"></i> Editar</button>
                                            </td>
                                        </tr>
                                  <?php
                                  } 
                                  ?>     
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end panel -->
                </div>
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
