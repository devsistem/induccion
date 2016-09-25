<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Slider');
global $BackendUsuario, $Slider;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

switch ($accion) {
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Slider->eliminar($idx);
   }
 break;
 
 case 'publicar':
	   $Slider->publicar($_POST['id'], $_POST['campo']);
 break;
}

// todos 
$result_todos = $Slider->obtener_all(null, null, null, $palabra, $order_by, $filtro, null, $estado, $destacado, $filtro_id_categoria);
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
  form.action = 'slider_editar.php';
  form.submit();
 }
 function fotos(idx) {
  var form = document.forms['frmPrincipal'];
  form['id'].value = idx;
  form.action = 'slider_imagenes.php';
  form.submit();
 } 
 function buscar() {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'bucar';
  form.submit();
 }
</script>
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
				<li><a href="noticias.php">Slider</a></li>
				<li class="active">Listado</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Slider <small>listado de imagenes del slider</small></h1>
			<!-- end page-header -->
			
			<!-- begin row -->
			<div class="row">
		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_item">			
			    
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
                            <h4 class="panel-title">Imagenes</h4>
                        </div>
                        
                        <?php if($accion == "eliminado" ) {?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            
                            Item eliminado correctamente
                        </div>
                      <?php } ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
																						<th width="2%"></th>
                                            <th width="1%">Id</th>                                        	
                                            <th width="10%"></th>
                                            <th width="20%">Titulo</th>
                                            <th width="40%">Url</th>
                                            <th width="10%">Activo</th>
                                            <th width="10%">Fecha Alta</th>
                                            <th width="10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($i=1; $i <= $filas_todos; $i++) {
						 												$items = @mysql_fetch_array($result_todos);
						 												$cantidad_fotos = $Slider->cantidad_fotos($items['id']);
																	?>
																				<tr class="odd gradeX">
                                    	  		<td align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items['id']?>"></td>
                                            <td align="center"><?=$items['id']?></td>
                                            <td align="center">
																					  <?php if(strlen($items['imagen_th']) > 4) { ?>
																						  <img id="imagen_muestra" src="<?=URL_PATH_FRONT_ADJ?>/slider/<?=($items['imagen_th']) ? $items['imagen_th'] : '_default.gif'?>" border="1" width="75" height="75">
																						<?php } elseif(strlen($items['imagen']) > 4) { ?>
																						  <img id="imagen_muestra" src="<?=URL_PATH_FRONT_ADJ?>/slider/<?=($items['imagen']) ? $items['imagen'] : '_default.gif'?>" border="1" width="75" height="75">
																						<?php } ?>
																						</td>
																						<td><?=$items['nombre']?></td>
                                            <td><?=$items['pagina']?></td>
                                            <td align="center">	                                            
                                            	<a href="javascript:publicar('<?=$items['id']?>','<?=$items['activo']?>');" title="Activar o desactivar un usuario en el backend"><b><?=($items['activo'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>                                       
                                            </td>
                                            <td><?=$items['fecha_alta']?></td>
                                            <td>
                                              <button type="button" class="btn btn-inverse" onClick="fotos('<?=$items['id']?>')"> (<?=$cantidad_fotos?>) Fotos</button>
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
                
               </form>
               <!-- end col-10 -->
            </div>
            <!-- end row -->
		</div>
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
