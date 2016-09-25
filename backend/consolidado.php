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

$mes  = (!isset($_POST['mes'])) ? null : $_POST['mes'];
$anio = (!isset($_POST['anio'])) ? null : $_POST['anio'];

// actuales
$fecha_rango_desde = date("d/m/Y");  
$fecha_rango_hasta = date("d/m/Y");
$fecha_rango_desde = null;  
$fecha_rango_hasta = null;

if(empty($mes)) {
 $mes = date("m");  
}

if(empty($anio)) {
 $anio = date("Y");  
}

switch ($accion) {
  case 'comicionar':
   $resultado = $Pedido->comicionar($id);
   $accion = "comicionado";
  break;
}

// si todos
if($accion == 'todos') {
 $id_vendedor = null;
 $id_estado = null;
 $fecha_desde = null;
 $fecha_hasta = null;
}

$sin_comencionar = "sin";

// supervisores
$result_supervisores = $BackendUsuario->obtener_all(null, null, null, null, null, null, 1, 4, null);
$filas_supervisores = @mysql_num_rows($result_supervisores);

//$result_todos = $Pedido->obtener_all_filtros($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, $id_estado, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $id_vendedor, null, null, $fecha_desde, $fecha_hasta, $sin_comencionar, $mes, $anio);
//$filas_todos = @mysql_num_rows($result_todos);
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
 function comicionar(idx) {
  if(confirm('Esta seguro de querer comicionar este pedido?')) { 	
	  var form = document.forms['frmPrincipal'];
  	form['accion'].value = "comicionar";
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
				<li class="active">Reporte Consolidado</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Consolidado <small>listado consolidado por vendedor</small></h1>
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
                                	<td width="10"></td>
                                	
                                	<td>
																	
																	<select id="mes" name="mes" style="width:180px; height:40px">
                                   	 <option value=""> - Mes -</option>
                                   	 		 <option value="01" <?=($mes=='01') ? 'selected' : ''?>>Enero</option>
                                   	 		 <option value="02" <?=($mes=='02') ? 'selected' : ''?>>Febrero</option>
                                   	 		 <option value="03" <?=($mes=='03') ? 'selected' : ''?>>Marzo</option>
                                   	 		 <option value="04" <?=($mes=='04') ? 'selected' : ''?>>Abril</option>
                                   	 		 <option value="05" <?=($mes=='05') ? 'selected' : ''?>>Mayo</option>
                                   	 		 <option value="06" <?=($mes=='06') ? 'selected' : ''?>>Junio</option>
                                   	 		 <option value="07" <?=($mes=='07') ? 'selected' : ''?>>Julio</option>
                                   	 		 <option value="08" <?=($mes=='08') ? 'selected' : ''?>>Agosto</option>
                                   	 		 <option value="09" <?=($mes=='09') ? 'selected' : ''?>>Septiembre</option>
                                   	 		 <option value="10" <?=($mes=='10') ? 'selected' : ''?>>Octubre</option>
                                   	 		 <option value="11" <?=($mes=='11') ? 'selected' : ''?>>Noviembre</option>
                                   	 		 <option value="12" <?=($mes=='12') ? 'selected' : ''?>>Diciembre</option>
                                   	</select>
																	</td>
																	<td width="10"></td>
																	<td>
																	
																	<select id="anio" name="anio" style="width:80px; height:40px">
                                   	 <option value=""> - A&ntilde;o -</option>
                                   	 		 <option value="2015" <?=($anio=='2015') ? 'selected' : ''?>>2015</option>
                                   	 		 <option value="2016" <?=($anio=='2016') ? 'selected' : ''?>>2016</option>
                                   	</select>
																	</td>
																	<td width="10"></td>
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
                                 
                                  <h4>Se encontraron <?=$filas_vendedores?> vendedores.</h4>
                                 
                                 </td>
                                </tr>
                              </table> 
                              
                              <?php if($accion == "comicionado" ) {?>
                               <div class="alert alert-success fade in">
                            			<button type="button" class="close" data-dismiss="alert">
                               			 <span aria-hidden="true">&times;</span>
                            			</button>
                                		Se ha pasado a comicionado el pedido
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
											
											
											<table class="table table-bordered" id="data-table_">
													<thead>
													<tr>
															   <th width="2%">Id</th>
											           <th width="15%">Vendedor</th>
											           <th width="10%">Pedidos totales</th>
											           <th width="10%">Pedidos baja</th>
											           <th width="10%">Incidencias graves</th>
											           <th width="10%">Ventas netas</th>
											           <th width="10%">Meta de ventas</th>
											           <th width="10%">Ventas comicion</th>
														</tr>
												  </thead> 
												  <tbody>
													<?php
					   								for($j=0; $j < $filas_supervisores; $j++) {
						 									$items_supervisores = @mysql_fetch_array($result_supervisores);	
															$cantidad_totales_mes_supervisores = $Pedido->cantidad_pedidos_by_vendedor_mes($items_supervisores['id'], $mes, $anio, null);
			 												$cantidad_totales_papelera_supervisores =  $Pedido->cantidad_pedidos_by_vendedor_mes_baja($items_supervisores['id'], $mes, $anio, null);
														  $cantidad_totales_incidencias_graves_supervisores =  $Pedido->cantidad_pedidos_by_vendedor_mes_tags($items_supervisores['id'], $mes, $anio, null, '4,9,16'); 
														  $cantidad_ventas_netas_supervisores = $cantidad_totales_mes_supervisores - $cantidad_totales_papelera_supervisores;
						 							 ?>
																	<tr bgcolor="#cccccc">
																			<td>
                                           	<span style="font-size:11px">
                                            		<?=$items_supervisores['id']?>
                                            	</span>	
                                            	</td>
                                            <td>
                                            	<span style="font-size:11px">
                                            		<strong>
                                            			<?=$items_supervisores['nombre']?> <?=$items_supervisores['apellido']?>
                                            	</strong>
                                            </span>
                                            	</td>
                                            <td align="center">
                                            	 <a href="pedidos_consolidado.php?filtro_id_vendedor=<?=$items_supervisores['id']?>&mes=<?=$mes?>&anio=<?=$anio?>&e=1"><?=$cantidad_totales_mes_supervisores?></a>
                                            </td>
                                            <td align="center">
                                            	 <a href="pedidos_baja.php?filtro_id_vendedor=<?=$items_supervisores['id']?>&mes=<?=$mes?>&anio=<?=$anio?>"><?=$cantidad_totales_papelera_supervisores?></a>
                                            </td>
                                            <td align="center">
                                            	<a href="pedidos_consolidado.php?filtro_id_vendedor=<?=$items_supervisores['id']?>&mes=<?=$mes?>&anio=<?=$anio?>&con_incidencias_graves=1"><?=$cantidad_totales_incidencias_graves_supervisores?></a>
                                            </td>
                                            <td  align="center">
                                            	<?=$cantidad_ventas_netas_supervisores?>
																						</td>
                                            <td  align="center">
                                            	<?=$items_supervisores['meta_ventas']?>
																						</td>
                                            <td  align="center">
                                            	<?=$cantidad_ventas_comicion_supervisores?>
																						</td>
                                        </tr>

															<?php
																			   
						 							    // todos los vendedores sin filtros
						 							
														  $result_todos = $BackendUsuario->obtener_all(null, null, null, null, null, null, 1, 10, $items_supervisores['id']);
														  $filas_todos = @mysql_num_rows($result_todos);
															for($i=1; $i <= $filas_todos; $i++) {
						 												$items = @mysql_fetch_array($result_todos);
						 												$cantidad_totales_mes = $Pedido->cantidad_pedidos_by_vendedor_mes($items['id'], $mes, $anio, null);
						 												$cantidad_totales_papelera =  $Pedido->cantidad_pedidos_by_vendedor_mes_baja($items['id'], $mes, $anio, null);
																	  $cantidad_totales_incidencias_graves =  $Pedido->cantidad_pedidos_by_vendedor_mes_tags($items['id'], $mes, $anio, null, '4,9,16'); 
																	  $cantidad_ventas_netas = $cantidad_totales_mes - $cantidad_totales_papelera;
																	  $arrVendedor = $Vendedor->obtener($items['id']);
															?>  
                                        <tr>
                                            <td>
                                            	<span style="font-size:11px">
                                            		<?=$items['id']?>
                                            	</span>	
                                           	</td>
                                            <td>
                                            	<span style="font-size:11px">
                                            	<?=$items['nombre']?> <?=$items['apellido']?>
                                            </span>
                                            	</td>
                                            <td align="center">
                                            	 <a href="pedidos_consolidado.php?filtro_id_vendedor=<?=$items['id']?>&mes=<?=$mes?>&anio=<?=$anio?>&e=1"><?=$cantidad_totales_mes?></a>
                                            </td>
                                            <td align="center">
                                            	 <a href="pedidos_baja.php?filtro_id_vendedor=<?=$items['id']?>&mes=<?=$mes?>&anio=<?=$anio?>"><?=$cantidad_totales_papelera?></a>
                                            </td>
                                            <td align="center">
                                            	<a href="pedidos_consolidado.php?filtro_id_vendedor=<?=$items['id']?>&mes=<?=$mes?>&anio=<?=$anio?>&con_incidencias_graves=1"><?=$cantidad_totales_incidencias_graves?></a>
                                            </td>
                                            <td  align="center">
                                            	<?=$cantidad_ventas_netas?>
																						</td>
                                            <td  align="center">
                                            	<?=$arrVendedor['meta_ventas']?>
																						</td>
                                            <td  align="center">
                                            	<?=$cantidad_ventas_comicion?>
																						</td>
  
                                        </tr>
                                      <?php }   // f 2 ?>
												<?php }  // f 1 ?>
                              </tbody>
											</table>
											
											

												<table class="table table-bordered" id="data-table_">
												<thead>
													<tr>
															   <th colspan="8">Vendedores sin supervisor</th>
									
														</tr>
												  </thead> 
													<thead>
													<tr>
															   <th width="2%">Id</th>
											           <th width="15%">Vendedor</th>
											           <th width="10%">Pedidos total</th>
											           <th width="10%">Pedidos baja</th>
											           <th width="10%">Incidencias graves</th>
											           <th width="10%">Ventas netas</th>
											           <th width="10%">Meta de ventas</th>
											           <th width="10%">Ventas comicion</th>
														</tr>
												  </thead> 
												  <tbody>
						 							<?php
						 							// vendedores sin supervisor
																	$result_vendedores_sin_supervisor = $BackendUsuario->obtener_all_sin_supervisor(null, null, null, null, null, null, 1, 10, null);
																	$filas_supervisores_sin_supervisor = @mysql_num_rows($result_vendedores_sin_supervisor);
					   											
					   											for($i=1; $i <= $filas_supervisores_sin_supervisor; $i++) {
						 												$items_sin_supervisor = @mysql_fetch_array($result_vendedores_sin_supervisor);
						 												
						 												$cantidad_totales_mes = $Pedido->cantidad_pedidos_by_vendedor_mes($items_sin_supervisor['id'], $mes, $anio, null);
						 												$cantidad_totales_papelera =  $Pedido->cantidad_pedidos_by_vendedor_mes_baja($items_sin_supervisor['id'], $mes, $anio, null);
																	  $cantidad_totales_incidencias_graves =  $Pedido->cantidad_pedidos_by_vendedor_mes_tags($items_sin_supervisor['id'], $mes, $anio, null, '4,9,16'); 
																	  $cantidad_ventas_netas = $cantidad_totales_mes - $cantidad_totales_papelera;
																	
																	  $arrVendedor = $Vendedor->obtener($items_sin_supervisor['id']);
																	?>  
                                        <tr class="odd gradeX">
                                            <td>
                                            	<span style="font-size:11px">
                                            		<?=$items_sin_supervisor['id']?>
                                            	</span>	
                                            	</td>
 
                                            <td>
                                            	<span style="font-size:11px">
                                            	<?=$items_sin_supervisor['nombre']?> <?=$items_sin_supervisor['apellido']?>
                                            </span>
                                            	</td>
                                            <td align="center">
                                            	 <a href="pedidos_consolidado.php?filtro_id_vendedor=<?=$items_sin_supervisor['id']?>&mes=<?=$mes?>&anio=<?=$anio?>&e=1"><?=$cantidad_totales_mes?></a>
                                            </td>
                                            <td align="center">
                                            	 <a href="pedidos_baja.php?filtro_id_vendedor=<?=$items_sin_supervisor['id']?>&mes=<?=$mes?>&anio=<?=$anio?>"><?=$cantidad_totales_papelera?></a>
                                            </td>
                                            <td align="center">
                                            	<a href="pedidos_consolidado.php?filtro_id_vendedor=<?=$items_sin_supervisor['id']?>&mes=<?=$mes?>&anio=<?=$anio?>&con_incidencias_graves=1"><?=$cantidad_totales_incidencias_graves?></a>
                                            </td>
                                            <td  align="center">
                                            	<?=$cantidad_ventas_netas?>
																						</td>
                                            <td  align="center">
                                            	<?=$arrVendedor['meta_ventas']?>
																						</td>
                                            <td  align="center">
                                            	<?=$cantidad_ventas_comicion?>
																						</td>
  
                                        </tr>
												<?php }  // f 1 ?>
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
