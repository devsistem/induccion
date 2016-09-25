<?php 
ob_start();
include_once("config/conn.php");
declareRequest('accion','usuario','clave');
loadClasses('BackendUsuario', 'Vendedor');
global $BackendUsuario, $Vendedor;

$BackendUsuario->EstaLogeadoBackend();

if($BackendUsuario->esRoot() == 0) {
	//print "No hay permisos";
  //exit;
}

$strError = '';
$errores  = 0;

$id    = ($_POST['id']) ? $_POST['id'] : null;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

switch ($accion) {
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Vendedor->eliminar($idx);
   }
 break;

 case 'publicar':
	   $Vendedor->publicar($_POST['id'], $_POST['campo']);
 break;

 case 'estado':
	   $Vendedor->estado($_POST['id'], $_POST['campo']);
 break;
}


$result = $Vendedor->obtener_all($paginacion, $porPagina, $usuario, $email, $nombre, null, null);
$filas = @mysql_num_rows($result);

// todos 
?>
<?php include("meta.php");?>

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
 form.action = 'edit_vendedor.php';
 form.submit();
}
</script>
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin #header -->
		<?php include("header.php")?>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<?php include("sidebar.php");?>
		<!-- end #sidebar -->

		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Portada</a></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Vendedores<small> Listado de vendedores de inducción</small></h1>
			<!-- end page-header -->
			
			<!-- begin row -->
			<div class="row">
			    <!-- begin col-12 -->
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
                            <h4 class="panel-title">Listado</h4>
                        </div>
                        
												<form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
					  							<input type="hidden" name="accion">
  												<input type="hidden" name="id">
 						  						<input type="hidden" name="campo">
			  		  
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <td width="10" style="background: url("../images/blanco.png") no-repeat center right;" class="sorting_desc_disabled"><button type="button" class="btn btn-primary btn-xs m-r-5" onClick="eliminar('<?=$items['id']?>')" onClick="eliminar()" title="Eliminar seleccionados">Eliminar Seleccion</button></td>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Activo</th>
                                            <th>Alta</th>
                                            <th>Ultimo Logeo</th>
                                            <th>Ventas</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
																	<?php
					   												for($i=1; $i <= $filas; $i++) {
						 													$items = @mysql_fetch_array($result);	
						 													$cantidad = 0;
						 													?> 
                                        <tr class="odd gradeX">
                                        	  <td  align="center">
                                            <?php	if($items['tipo'] > 1 && $items['roles'] != TIPOS_USUARIO_ROOT) { ?>
                                        	  		<input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items['id']?>">
                                            <?php } ?>	
                                        	  </td>
                                            <td><?=$items['nombre']?></td>
                                            <td><?=$items['email']?></td>
                                            <td align="center">
                                            <?php	if($items['tipo'] > 1 && $items['roles'] != TIPOS_USUARIO_ROOT) { ?>
	                                            <a href="javascript:publicar('<?=$items['id']?>','<?=$items['activo']?>');" title="Activar o desactivar un usuario en el backend"><b><?=($items['activo'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            <?php } ?>	
                                            </td>
                                            <td><?=$items['fecha_alta']?></td>
                                            <td><?=$items['logeo']?></td>
                                            <td><?=$cantidad?></td>
                                            <td>
                                            	<button type="button" class="btn btn-primary btn-xs m-r-5" onClick="editar('<?=$items['id']?>')" title="Editar un item">Editar</button>
                                            	<button type="button" class="btn btn-primary btn-xs m-r-5" onClick="eliminar('<?=$items['id']?>')"  title="Eliminar un item">Eliminar</button>
                                            </td>
                                        </tr>
                                <?php
                                	}
                                ?>    </tbody>     	
                                 
                                </table>
                            </div>
                        </div>
                    </div>
                  </form>
                   <!-- end panel -->
                </div>
                <!-- end col-12 -->
            </div>
            <!-- end row -->
		</div>
		<!-- end #content -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery-1.8.2/jquery-1.8.2.min.js"></script>
	<script src="assets/plugins/jquery-ui-1.10.4/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap-3.1.1/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/DataTables-1.9.4/js/jquery.dataTables.js"></script>
	<script src="assets/plugins/DataTables-1.9.4/js/data-table.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
</body>
</html>
