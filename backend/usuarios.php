<?php
// vendedores.php
// 05/08/2015 15:33:34
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Vendedor');
global $BackendUsuario, $Vendedor;

$BackendUsuario->EstaLogeadoBackend();

// permisos
// @supervisores

if(!$BackendUsuario->esGerenteGeneral() && !$BackendUsuario->esGerenteVentas() && !$BackendUsuario->esRoot()) {
 die;
}

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

switch ($accion) {
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  //$Vendedor->eliminar($idx);
   }
 break;

 case 'publicar':
	   $BackendUsuario->publicar($_POST['id'], $_POST['campo']);
 break;

 case 'estado':
	   $BackendUsuario->estado($_POST['id'], $_POST['campo']);
 break;
}

// solo muestra sus vendeores
$result = $BackendUsuario->obtener_all(null, null, null, null, null, null, ACTIVO, null, null);
$filas = @mysql_num_rows($result);
								
?>
<?php include("meta.php");?>

<?php //+  acciones js // ?>
 <script>
 function eliminar() {
  if(confirm('Esta seguro de querer eliminar los items seleccionados?')) {
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
  form.action = 'usuario_editar.php';
  form.submit();
 }
 function buscar() {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'bucar';
  form.submit();
 }
</script>
<?php //-  acciones js // ?>
<link   href="assets/plugins/colorbox/example1/colorbox.css" rel="stylesheet" />
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
				<li><a href="noticias.php">Usuarios</a></li>
				<li class="active">Listado</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Usuarios <small>listado de Usuarios</small></h1>
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
                            <h4 class="panel-title">Usuarios</h4>
                        </div>

                        
                        <?php //+ mensajes // ?>
                        <?php if($accion == "eliminado") { ?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            El Vendedores ha sido eliminado correctamente.
                        </div>
	                      <?php } ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5" class="sorting_desc_disabled"><button type="button" class="btn btn-primary btn-xs m-r-5" onClick="eliminar('<?=$items['id']?>')" onClick="eliminar()" title="Eliminar seleccionados">Eliminar Seleccion</button></th>
                                            <th width="1%">Id</th>                                        	
                                            <th width="10%">Usuario</th>
                                            <th width="30%">Nombre y Apellido</th>
                                            <th width="10%">Cargo</th>
                                            <th width="5%">Activo</th>
                                            <th width="5%">Ventas</th>
                                            <th width="10%">Fecha Alta</th>
                                            <th width="10%">Logeo</th>
                                            <th width="10%">Supervisor</th>
                                            <th width="10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($i=1; $i <= $filas; $i++) {
						 												$items = @mysql_fetch_array($result);
						 												
						 												$arrSupervisor = $BackendUsuario->obtener($items['id_supervisor']);
																	?>                                     	
                                        <tr class="odd gradeX">
                                    	  		<td align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items['id']?>"></td>
                                            <td align="center"><?=$items['id']?></td>
                                            <td><?=$items['_usuario']?></td>
                                            <td>
                                            	<?=$items['nombre']?> <?=$items['apellido']?>
                                            	<?php if($_GET['id'] == $items['id']) { ?>
                                            	  <strong>(actualizado)</strong>
	                                            <?php } ?>
                                            	</td>
                                            <td><?=$items['cargo']?></td>
                                            <td align="center">
	                                            <a href="javascript:publicar('<?=$items['id']?>','<?=$items['activo']?>');" title="Activar o desactivar un usuario en el backend"><b><?=($items['activo'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            </td>
                                            <td align="center">
	                                            <?=$cantidad?>
                                            </td>
                                            <td>
                                            	<?=GetFechaTexto($items['fecha_alta'])?>
                                            	</td>
                                            <td><?=$items['logeo']?></td>
                                            <td><?=$arrSupervisor['nombre']?> <?=$arrSupervisor['apellido']?></td>
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
	<script src="assets/plugins/colorbox/jquery.colorbox.js"></script>

	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
			TableManageColReorder.init();
			
			$(".importar").colorbox({iframe:true, width:"40%", height:"50%"});
				
		});
	</script>
</body>
</html>
