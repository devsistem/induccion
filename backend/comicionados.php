<?php
// productos.php
// 05/08/2015 15:33:34
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Pedido', 'Incidencia');
global $BackendUsuario, $Producto, $Pedido, $Incidencia;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteVentas() && !$BackendUsuario->esRoot()) { 
 die;
}	

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$id_vendedor = ($_REQUEST['id_vendedor']) ? $_REQUEST['id_vendedor'] : null;
$id_estado = ($_REQUEST['id_estado']) ? $_REQUEST['id_estado'] : null;

$fecha_desde  = (!isset($_POST['fecha_desde'])) ? null : $_POST['fecha_desde'];
$fecha_hasta  = (!isset($_POST['fecha_hasta'])) ? null : $_POST['fecha_hasta'];

$fecha_rango_desde = date("d/m/Y");  
$fecha_rango_hasta = date("d/m/Y");
$fecha_rango_desde = null;  
$fecha_rango_hasta = null;

switch ($accion) {
  case 'descomicionar':
   $resultado = $Pedido->descomicionar($id);
   $accion = "descomicionado";
  break;
}

// si todos
if($accion == 'todos') {
 $id_vendedor = null;
 $id_estado = null;
 $fecha_desde = null;
 $fecha_hasta = null;
}

$sin_comencionar = "con";
$result_todos = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, $id_estado, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $id_vendedor, null, null, $fecha_desde, $fecha_hasta, $sin_comencionar);
$filas_todos = @mysql_num_rows($result_todos);
?>
<?php include("meta.php");?>

<link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2.css" />
<script>
 function filtrar() {
  var id_vendedor = $("#id_vendedor").val();
  var form = document.forms['frmPrincipal'];
 }

 function filtrar_estado() {
  var id_estado = $("#id_estado").val();
  var form = document.forms['frmPrincipal'];
 } 

 function filtrar_general() {
  var form = document.forms['frmPrincipal'];
	form.submit();
 }

 function filtrar_estado() {
  var id_vendedor = $("#id_vendedor").val();
  var form = document.forms['frmPrincipal'];
 }

 function filtrar_todos() {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = "todos";
	form.submit();
 }

 var handleDashboardDatepicker = function() {
	"use strict";
    $('.fecha_latina').datepicker({
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    });
   $('.fecha_latina2').datepicker({
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    });    
 };
 function descomicionar(idx) {
  if(confirm('Esta seguro de querer quitar de comicionados este pedido?')) { 	
	  var form = document.forms['frmPrincipal'];
  	form['accion'].value = "descomicionar";
  	form['id'].value = idx;
	  form.submit()	  
  }
 } 
</script>
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<?php include("header.php")?>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<?php include("sidebar.php")?>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Home</a></li>
				<li><a href="javascript:;">Reportes</a></li>
				<li class="active">Reportes de Ventas</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Reportes <small>listado de reportes</small></h1>
			<!-- end page-header -->
						
		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_item">	
			
			<div class="row">
						<div class="col-md-12">
							<!-- start: DYNAMIC TABLE PANEL -->
							<div class="panel panel-default">
								<div class="panel-body">
								     <div class="btn-group">
											   <table>
                           <tr>
                               	 	<td><button class="btn btn-white active">Todos los pedidos</button></td>
                               	 	<td width="20"></td>
                                	<td>
    															<?php
                                 	// vendedores
																	$result_vendedores = $BackendUsuario->obtener_vendedores_y_supervisores(1); 
																	$filas_vendedores = @mysql_num_rows($result_vendedores);
                                   	?>
                                   	<select id="id_vendedor" name="id_vendedor" onChange="filtrar()" style="width:300px; height:40px" required="true">
                                   	 <option value=""> - Filtrar por vendedores -</option>
                                   	 <?php
                                   	  for($k=0; $k < $filas_vendedores; $k++) {
																			$items_vendedores = @mysql_fetch_array($result_vendedores); ?>
                                   	 		 <option value="<?=$items_vendedores['id']?>" <?=($id_vendedor==$items_vendedores['id']) ? 'selected' : ''?>><?=$items_vendedores['apellido']?>,  <?=$items_vendedores['nombre']?></option>
																		<?php	} ?>	
                                   	</select>
                                	</td>
                                	<td width="20"></td>
                                	<td>
                                	
                                	<?php
                                 	// estados
																	$result_estados = $Pedido->obtener_estados(ACTIVO); 
																	$filas_estados = @mysql_num_rows($result_estados);
                                   	?>
                                   	<select id="id_estado" name="id_estado" onChange="filtrar_estado()" style="width:180px; height:40px" required="true">
                                   	 <option value=""> - Filtrar por Estado -</option>
                                   	 <?php
                                   	  for($k=0; $k < $filas_estados; $k++) {
																			$items_estados = @mysql_fetch_array($result_estados); ?>
                                   	 		 <option value="<?=$items_estados['id']?>" <?=($id_estado==$items_estados['id']) ? 'selected' : ''?>><?=$items_estados['nombre']?></option>
																		<?php	} ?>	
                                   	</select>
                                	</td>
																	<td width="20"></td>
                                	<td>
                                	 
                     						  <div class="input-group input-daterange">
  						       						  	<table cellpadding="2" width="400">
  						       						  	 <tr>
  						       						  	  <td width="200"><input type="text" class="form-control fecha_latina"  style="height:40px" id="fecha_desde_1" name="fecha_desde" placeholder="Desde" value="<?=$fecha_rango_desde?>"/></td>
  						       						  	  <td width="20"><span class="input-group-addon">a</span></td>
  						       						  	  <td width="200"><input type="text" class="form-control fecha_latina2" style="height:40px" name="fecha_hasta" id="fecha_hasta_1" placeholder="Hasta"  value="<?=$fecha_rango_hasta?>" /></td>
  						       						  	  <td width="20"></td>
  						       						  	  <td width="100">
  						       						  	  	<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" onClick="filtrar_general()">
																					Filtrar
																				</button>
																			</td>	
  						       						  	  <td width="100">
  						       						  	  	<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" onClick="filtrar_todos()">
																					Todos
																				</button>
  						       						  	  </td>
  						       						  	 </tr>
  						       						  	</table>
 						         						  </div>
                                 </td>
                                </tr>
                                <tr>
                                 <td colspan="7">
                                 
                                  <h4>Se encontraron <?=$filas_todos?> pedidos.</h4>
                                 
                                 </td>
                                </tr>
                              </table>
             						 <?php if($accion == "descomicionado" ) {?>
                               <div class="alert alert-success fade in">
                            			<button type="button" class="close" data-dismiss="alert">
                               			 <span aria-hidden="true">&times;</span>
                            			</button>
                                		Se ha eliminado de comicionados el pedido
                        			 </div>
	                            <?php } ?>                               
											</div>
										<div class="row">
											<div class="col-md-12 space20">
												<div class="btn-group pull-right" style="padding:20px">
													<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
														Exportar <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu dropdown-light pull-right">
														<li>
															<a href="#" class="export-pdf" data-table="#data-table">
																Exportar a PDF
															</a>
														</li>

														<li>
															<a href="#" class="export-csv" data-table="#data-table">
																Exportar a CSV
															</a>
														</li>
														<li>
															<a href="#" class="export-txt" data-table="#data-table">
																Exportar a TXT
															</a>
														</li>
														<li>
															<a href="#" class="export-xml" data-table="#data-table">
																 Exportar a XML
															</a>
														</li>
														<li>
															<a href="#" class="export-excel" data-table="#data-table">
																Exportar a Excel
															</a>
														</li>
														<li>
															<a href="#" class="export-doc" data-table="#data-table">
																Exportar a Word
															</a>
														</li>
														<li>
															<a href="#" class="export-powerpoint" data-table="#data-table">
																Export to PowerPoint
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover" id="data-table">
												<thead>
													<tr>
															   <th  width="2%">Id</th>
											           <th width="15%">Cliente</th>
											           <th width="20%">Producto</th>
											           <th width="15%">Vendedor</th>
											           <th width="5%">Estado</th>
											           <th width="15%">Fecha Ingreso</th>
											           <th width="10%">Comicionar</th>
											           <th width="25%">Incidencias</th>
														</tr>
												</thead>
												<tbody>
												<?php
					   											for($i=1; $i <= $filas_todos; $i++) {
						 												$items = @mysql_fetch_array($result_todos);
																	?>  
                                        <tr class="odd gradeX">
                                            <td>
                                            	<span style="font-size:11px">
                                            		<?=$items['id']?>
                                            	</span>	
                                            	</td>
  																					<td>
  																						<span style="font-size:11px">
  																							<?=$items['cliente_nombre']?> <?=$items['cliente_apellido']?></td> 
          																	  </span>
          																	  <td>
         																	  	<span style="font-size:11px">
         																	  	<?=$items['marca']?> <?=$items['modelo']?> <?=$items['color']?>
         																	  	                                           <?=$items['marca']?>
                                            	<?php if($_GET['id'] == $items['id']) { ?>
                                            	  <strong>(actualizado)</strong>
	                                            <?php } ?>
	                                            
	                                            </span>
 
         																	  	</td>
 
                                            <td>
                                            	<span style="font-size:11px">
                                            	(<?=$items['id_vendedor']?>) <?=$items['vendedor_nombre']?>
                                            </span>
                                            	</td>
                                            <td align="center">
                                            	<span style="font-size:11px">
	                                            <span class="label label-primary">
	                                            <?php if($items['estado'] == 1) { ?>
	                                            	INGRESA PEDIDO
	                                            <?php } else if($items['estado'] == 2) { ?> 
	                                              PRE DESPACHO	
	                                            <?php } else if($items['estado'] == 3) { ?>
	                                              AGENDAR
	                                            <?php } else if($items['estado'] == 4) { ?>
	                                              GENERACION DE DOCUMENTACION 	
	                                            <?php } else if($items['estado'] == 5) { ?>
	                                              DESPACHO 	
	                                            <?php } else if($items['estado'] == 6) { ?> 
	                                            	RECEPCION  DE DOCUMENTACION	
	                                            <?php } else if($items['estado'] == 7) { ?>
	                                            	ENTREGA A FABRICA
	                                            <?php } ?>
		                                          </legend>
	                                            </span>

                                            </td>
                                            <td>
                                            	<span style="font-size:11px">
                                            		<?=GetFechaTexto($items['fecha_alta'])?>
                                            	</span>	
                                            </td>
                                            <td align="center">
                                             <input type="checkbox" name="ck_comicionar" value="<?=$items['id']?>" onClick="descomicionar('<?=$items['id']?>');"/>
                                            </td>
                                            <td>

																						<?php                                            
																						// incidencias
																						$result_incidencia = $Pedido->obtener_incidencia_by_pedido($items['id']);
																						$filas_incidencia = @mysql_num_rows($result_incidencia);
																						 for($k=1; $k <= $filas_incidencia; $k++) {
																								$items_incidencia = @mysql_fetch_array($result_incidencia);
																					    	$arrIncidencia = $Incidencia->obtener($items_incidencia['id_incidencia']);
																						?>	
																						 
																						 <span style="font-size:11px">
																						 <strong> <?=$arrIncidencia['nombre']?> </strong>
																						 <?php if(strlen($items_incidencia['contenido']) > 0) { ?>
																						 <br/>
																						 (
																						<?php } ?>
																						 <?=$items_incidencia['contenido']?>
																						 <?php if(strlen($items_incidencia['contenido']) > 0) { ?>
																						 )
																						<?php } ?>
																						 </span>

																						 <?php } ?>
																						</td>
                                        </tr>
                                      <?php } ?>
												</tbody>
											</table>
										</div>
								</div>
							</div>
							<!-- end: DYNAMIC TABLE PANEL -->
						</div>
					</div>
			<!-- begin row -->

            <!-- end row -->
				</div>
		<!-- end #content -->
		</form>

        <!-- begin theme-panel -->
     
        <!-- end theme-panel -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="assets/js/table-manage-default.demo.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->


		<script src="assets/plugins/tableExport/tableExport.js"></script>
		<script src="assets/plugins/tableExport/jquery.base64.js"></script>
		<script src="assets/plugins/tableExport/html2canvas.js"></script>
		<script src="assets/plugins/tableExport/jquery.base64.js"></script>
		<script src="assets/plugins/tableExport/jspdf/libs/sprintf.js"></script>
		<script src="assets/plugins/tableExport/jspdf/jspdf.js"></script>
		<script src="assets/plugins/tableExport/jspdf/libs/base64.js"></script>
		<script src="assets/js/table-export.js"></script>
			
	<script>

		$(document).ready(function() {
			App.init();
			TableManageDefault.init();
			TableExport.init();
			

			handleDashboardDatepicker();
		});
	</script>
</body>
</html>
