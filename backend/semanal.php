<?php
// semanal.php
// Reporte semanal de ventas
// 05/08/2015 15:33:34
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Pedido', 'Incidencia');
global $BackendUsuario, $Producto, $Pedido, $Incidencia;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteVentas() && !$BackendUsuario->esRoot()) { 
 //die;
}	

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$id_vendedor = ($_REQUEST['id_vendedor']) ? $_REQUEST['id_vendedor'] : null;
$id_estado = ($_REQUEST['id_estado']) ? $_REQUEST['id_estado'] : null;

$fecha_desde  = (!isset($_REQUEST['fecha_desde'])) ? null : $_REQUEST['fecha_desde'];
$fecha_hasta  = (!isset($_REQUEST['fecha_hasta'])) ? null : $_REQUEST['fecha_hasta'];

$mes  = (!isset($_POST['mes'])) ? null : $_POST['mes'];
$anio = (!isset($_POST['anio'])) ? null : $_POST['anio'];

// si no esta el get de la fecha, pone la mes actual
if(strlen($mes) == 0 || strlen($anio) == 0) {

 $fecha_hoy = date("Y-m-d");
 $temp_fecha_hoy = explode("-",$fecha_hoy); 
 $mes  = $temp_fecha_hoy[1];
 $anio = $temp_fecha_hoy[0];
 
}

// armar las semanas
$fecha_desde_semana_1 = $anio."-".$mes."-"."01";
$fecha_hasta_semana_1 = $anio."-".$mes."-"."07";

$fecha_desde_semana_2 = $anio."-".$mes."-"."08";
$fecha_hasta_semana_2 = $anio."-".$mes."-"."14";

$fecha_desde_semana_3 = $anio."-".$mes."-"."15";
$fecha_hasta_semana_3 = $anio."-".$mes."-"."21";

$fecha_desde_semana_4 = $anio."-".$mes."-"."22";
$fecha_hasta_semana_4 = $anio."-".$mes."-"."31";

// si no esta el get de la fecha, pone la semana actual
/*
if(strlen($fecha_desde) == 0 || strlen($fecha_hasta) == 0) {

 $fecha_rango_desde = date("Y-m-d");
 $fecha_rango_hasta = strtotime( '+7 day' , strtotime ( $fecha_rango_desde ) ) ;
 $fecha_rango_hasta = date( 'Y-m-d' , $fecha_rango_hasta );

 $fecha_rango_desde_txt = to_mysql_semanal($fecha_rango_desde);
 $fecha_rango_hasta_txt = to_mysql_semanal($fecha_rango_hasta);

} else {

 if($accion == 'anterior') {
	 
	 $fecha_rango_desde = $fecha_desde;
	 $fecha_rango_desde = strtotime( '-7 day' , strtotime ( $fecha_rango_desde ) ) ;
	 $fecha_rango_desde = date( 'Y-m-d' , $fecha_rango_desde );
 	 $fecha_rango_hasta = strtotime( '+7 day' , strtotime ( $fecha_rango_desde ) ) ;
 	 $fecha_rango_hasta = date( 'Y-m-d' , $fecha_rango_hasta );
	
	 $fecha_rango_desde_txt = to_mysql_semanal($fecha_rango_desde);
	 $fecha_rango_hasta_txt = to_mysql_semanal($fecha_rango_hasta);

 } elseif($accion == 'siguiente') {

	 $fecha_rango_desde = strtotime( '+1 day' , strtotime ( $fecha_hasta ) ) ;;
	 $fecha_rango_desde = date( 'Y-m-d' , $fecha_rango_desde );
 	 $fecha_rango_hasta = strtotime( '+7 day' , strtotime ( $fecha_rango_desde ) ) ;
 	 $fecha_rango_hasta = date( 'Y-m-d' , $fecha_rango_hasta );

	 $fecha_rango_desde_txt = to_mysql_semanal($fecha_rango_desde);
	 $fecha_rango_hasta_txt = to_mysql_semanal($fecha_rango_hasta);
 }
}
*/
// extraer el mes

switch ($accion) {
  case 'comicionar':

  break;
}

// si todos
if($accion == 'todos') {
 $id_vendedor = null;
 $id_estado = null;
 $fecha_desde = null;
 $fecha_hasta = null;
}

// todos los vendedores y supervisores
$result = $BackendUsuario->obtener_vendedores_y_supervisores(1);
$filas = @mysql_num_rows($result);
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

 function anterior(fecha_desde, fecha_hasta) {
	  var form = document.forms['frmPrincipal'];
  	form['accion'].value = "anterior";
  	form['fecha_desde'].value = fecha_desde;
  	form['fecha_hasta'].value = fecha_hasta;
	  form.submit()	  
 } 

 function siguiente(fecha_desde, fecha_hasta) {
	  var form = document.forms['frmPrincipal'];
  	form['accion'].value = "siguiente";
  	form['fecha_desde'].value = fecha_desde;
  	form['fecha_hasta'].value = fecha_hasta;
	  form.submit()	  
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
				<li class="active">Reportes Semanal de Ventas</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Reportes <small>listado semanal de ventas</small></h1>
			<!-- end page-header -->
						
		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_item">
			<input type="hidden" name="fecha_desde">
			<input type="hidden" name="fecha_hasta">
			
			<div class="row">
						<div class="col-md-12">
							<!-- start: DYNAMIC TABLE PANEL -->
							<div class="panel panel-default">
								<div class="panel-body">
								     <div class="btn-group">
                              
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
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
													 <td colspan="9" align="center">
	                       	
                         	<select name="id_vendedor" id="id_vendedor" style="width:300px; height:30px;padding-left:20px" onChange="filtrar()">
                         	 <option value="">-Todos los vendedores-</option>
                         			<?php
					   											for($i=1; $i <= $filas; $i++) {
						 												$items = @mysql_fetch_array($result);
															?>
	                         	        <option value="<?=$items['id']?>" <?=($id_vendedor==$items['id']) ? 'selected' : ''?>> <?=$items['apellido']?> <?=$items['nombre']?></option>
                         	    <?php
                         	   	}
                         	   	?>
                         	</select>
                         	
													   <select name="mes" id="mes">
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

													   <select name="anio" id="anio">
													    <option value="2016" <?=($anio=='2016') ? 'selected' : ''?>>2016</option>
	  												 </select>
	  												 <input type="submit" value="Mostrar"name="btMostrar" />											   
													 </td>
													</tr>
													
													<tr>
                           <td colspan="9">
                           		<table border="2" width="100%" class="table table-striped table-bordered table-hover" width="100%">
																<tr>
																	<td></td>
																	<td colspan="5" align="center" bgcolor="#888888"><strong>Semana 1-7</strong></td>
																	<td colspan="5" align="center"><strong>Semana 8-14</strong></td>
																	<td colspan="5" align="center" bgcolor="#cccccc"><strong>Semana 15-21</strong></td>
																	<td colspan="5" align="center"><strong>Semana 22-31</strong></td>
																</tr>
																<tr>
																	<td align="center"><img src="spacer.png" width="150" height="1"></td>
																	<td align="center"><strong>TP</strong></td>
																	<td align="center"><strong>E</strong></td>
																	<td align="center"><strong>H</strong></td>
																	<td align="center"><strong>B</strong></td>
																	<td align="center"><strong>F</strong></td>
																	<td align="center"><strong>TP</strong></td>
																	<td align="center"><strong>E</strong></td>
																	<td align="center"><strong>H</strong></td>
																	<td align="center"><strong>B</strong></td>
																	<td align="center"><strong>F</strong></td>
																	<td align="center"><strong>TP</strong></td>
																	<td align="center"><strong>E</strong></td>
																	<td align="center"><strong>H</strong></td>
																	<td align="center"><strong>B</strong></td>
																	<td align="center"><strong>F</strong></td>
																	<td align="center"><strong>TP</strong></td>
																	<td align="center"><strong>E</strong></td>
																	<td align="center"><strong>H</strong></td>
																	<td align="center"><strong>B</strong></td>
																	<td align="center"><strong>F</strong></td>
																</tr>
												<?php //+ cada item ?>
												<?php
                         							$salario_basico = 375;

												  // total de pedidos mensuales
													$total_pedidos_mes = $Pedido->cantidad_pedidos_by_mes(null, $fecha_desde_semana_1, $fecha_hasta_semana_4, ACTIVO, null);
													
													$total_pedidos_columna = 0;
													$total_encimeras_columna = 0;
													$total_con_horno_columna = 0;

													$total_pedidos_columna1 = 0;
													$total_encimeras_columna1 = 0;
													$total_con_horno_columna1 = 0;
													$total_baja_columna1 = 0;
													$total_fabrica_columna1 = 0;

													$total_pedidos_columna2 = 0;
													$total_encimeras_columna2 = 0;
													$total_con_horno_columna2 = 0;
													$total_baja_columna2 = 0;
													$total_fabrica_columna2 = 0;

													$total_pedidos_columna3 = 0;
													$total_encimeras_columna3 = 0;
													$total_con_horno_columna3 = 0;
													$total_baja_columna3 = 0;
													$total_fabrica_columna3 = 0;


													$total_pedidos_columna4 = 0;
													$total_encimeras_columna4 = 0;
													$total_con_horno_columna4 = 0;
													$total_baja_columna4 = 0;
													$total_fabrica_columna4 = 0;


                         	// vendedores activos
													$result_vendedores = $BackendUsuario->obtener_vendedores_y_supervisores(1, $id_vendedor); 
													$filas_vendedores = @mysql_num_rows($result_vendedores);
														
														for($k=0; $k < $filas_vendedores; $k++) {
															$items_vendedores = @mysql_fetch_array($result_vendedores); 
															 
															 //if($items_vendedores['id'] == 24) 
															 //{
															 
															 // calculos
															
															 // SEMANA 1 de 4

															 // total pedidos de una semana
															 // se utiliza la fecha de alta
															 $total_pedidos_semana1   = $Pedido->cantidad_pedidos_by_semana($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_1, null, null);
															 $total_pedidos_columna1 = $total_pedidos_columna1 + $total_pedidos_semana1;

															 $total_encimeras_semana1 = $Pedido->pedidos_encimera_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_1, ACTIVO, 7);
															 $total_encimeras_columna1 = $total_encimeras_columna1 + $total_encimeras_semana1;
															 
															 // no anda esta sql
															 $total_con_horno_semana1 = $Pedido->pedidos_hornos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_1, ACTIVO, 7);
															 //$total_con_horno_semana1 = $total_pedidos_semana - $total_encimeras_semana;
															 $total_con_horno_columna1 = $total_con_horno_columna1 + $total_con_horno_semana1;
															 //$total_con_horno_columna1=-2;
															 // se usa fecha de modificacion
															 $total_baja_semana1  = $Pedido->pedidos_baja_fechas($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_1, null, null);;
															 $total_baja_columna1 = $total_baja_columna1 + $total_baja_semana1;
															 
															 $total_fabrica_semana1 = $Pedido->pedidos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_1, ACTIVO, 7);;
															 $total_fabrica_columna1 = $total_fabrica_columna1 + $total_fabrica_semana1;
															 
															 $total_pago_semana1 = 0;
															 $total_encimera_y_horno1 = $total_enscimera_semana1 + $total_con_horno_semana1; 
															
															 // calculo de pago semana dependiendo de la
															 // cantidad de cocinas en fabrica
															 // hasta 2, 10 * cocina

															 if($total_fabrica_semana1 == 1 or $total_fabrica_semana1 == 2) {
															  $total_pago_semana1 = $total_fabrica_semana1 * 10;
															  
															 } else if($total_fabrica_semana1 == 3 or $total_fabrica_semana1 == 4 or $total_fabrica_semana1 == 5) {
															   
															   $total_pago_semana1 = 25 * $salario_basico1 / 100;
															   
															 } else if($total_fabrica_semana1 >= 6) {
															  
															  $total_pago_semana1 = 25 * $salario_basico1 / 100;
															  
															  // 25%
															  // $total_con_horno_semana * $total_con_horno_semana
															  $temp_horno1 = $total_con_horno_semana1 * $total_con_horno_semana1;
															  // 
															  $total_pago_semana1 = $total_pago_semana1 + $temp_horno1 + $total_encimeras_semana1 * ($total_con_horno_semana1 / 2);
															}
															
															
															// SEMANA 2 de 4
															 //print "-" . $fecha_desde_semana_2;
															 //print "-" . $fecha_hasta_semana_2;
															 
															 // total pedidos de una semana
															 $total_pedidos_semana2   = $Pedido->cantidad_pedidos_by_semana($items_vendedores['id'], $fecha_desde_semana_2, $fecha_hasta_semana_2, null, null);
															 $total_pedidos_columna2 = $total_pedidos_columna2 + $total_pedidos_semana2;

															 $total_encimeras_semana2 = $Pedido->pedidos_encimera_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_2, $fecha_hasta_semana_2, ACTIVO, 7);
															 $total_encimeras_columna2 = $total_encimeras_columna2 + $total_encimeras_semana2;

															 //$total_con_horno_semana2 = $total_pedidos_semana2 - $total_encimeras_semana2;
															 $total_con_horno_semana2 = $Pedido->pedidos_hornos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_2, $fecha_hasta_semana_2, ACTIVO, 7);
															 $total_con_horno_columna2 = $total_con_horno_columna2 + $total_con_horno_semana2;

															 $total_encimera_y_horno2 = $total_encimeras_semana2 + $total_con_horno_semana2; 
															 $total_baja_semana2   = $Pedido->pedidos_baja_fechas($items_vendedores['id'], $fecha_desde_semana_2, $fecha_hasta_semana_2, null, null);;
															 $total_baja_columna2 = $total_baja_columna2 + $total_baja_semana2;

															 $total_fabrica_semana2 = $Pedido->pedidos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_2, $fecha_hasta_semana_2, ACTIVO, 7);;
															 $total_fabrica_columna2 = $total_fabrica_columna2 + $total_fabrica_semana2;

															 $total_pago_semana2 = 0;
															
															
															 // calculo de pago semana dependiendo de la
															 // cantidad de cocinas en fabrica
															
															 // hasta 2, 10 * cocina
															 if($total_fabrica_semana2 == 1 or $total_fabrica_semana2 == 2) {
															  $total_pago_semana2 = $total_fabrica_semana2 * 10;
															  
															 } else if($total_fabrica_semana2 == 3 or $total_fabrica_semana2 == 4 or $total_fabrica_semana2 == 5) {
															   
															   $total_pago_semana2 = 25 * $salario_basico2 / 100;
															   
															 } else if($total_fabrica_semana2 >= 6) {
															  
															  $total_pago_semana2 = 25 * $salario_basico2 / 100;
															  
															  // 25%
															  // $total_con_horno_semana * $total_con_horno_semana
															  $temp_horno2 = $total_con_horno_semana2 * $total_con_horno_semana2;
															  // 
															  $total_pago_semana2 = $total_pago_semana2 + $temp_horno2 + $total_encimeras_semana2 * ($total_con_horno_semana2 / 2);
															}


														 	 // SEMANA 3 de 4
															 // total pedidos de una semana
															 $total_pedidos_semana3   = $Pedido->cantidad_pedidos_by_semana($items_vendedores['id'], $fecha_desde_semana_3, $fecha_hasta_semana_3, null, null);
															 $total_pedidos_columna3 = $total_pedidos_columna3 + $total_pedidos_semana3;

															 $total_encimeras_semana3 = $Pedido->pedidos_encimera_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_3, $fecha_hasta_semana_3, ACTIVO, 7);
															 $total_encimeras_columna3 = $total_encimeras_columna3 + $total_encimeras_semana3;

															 // no anda esta sql
															 $total_con_horno_semana3 = $Pedido->pedidos_hornos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_3, $fecha_hasta_semana_3, ACTIVO, 7);
															 $total_con_horno_columna3 = $total_con_horno_columna3 + $total_con_horno_semana3;

															 $total_baja_semana3   = $Pedido->pedidos_baja_fechas($items_vendedores['id'], $fecha_desde_semana_3, $fecha_hasta_semana_3, null);
	 														 $total_baja_columna3 = $total_baja_columna3 + $total_baja_semana3;

															 $total_fabrica_semana3 = $Pedido->pedidos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_3, $fecha_hasta_semana_3, ACTIVO, 7);;
															 $total_fabrica_columna3 = $total_fabrica_columna3 + $total_fabrica_semana3;

															 $total_pago_semana3 = 0;
															
															 // calculo de pago semana dependiendo de la
															 // cantidad de cocinas en fabrica
															
															 // hasta 2, 10 * cocina
															 if($total_fabrica_semana3 == 1 or $total_fabrica_semana3 == 2) {
															  $total_pago_semana3 = $total_fabrica_semana3 * 10;
															  
															 } else if($total_fabrica_semana3 == 3 or $total_fabrica_semana3 == 4 or $total_fabrica_semana3 == 5) {
															   
															   $total_pago_semana3 = 25 * $salario_basico3 / 100;
															   
															 } else if($total_fabrica_semana3 >= 6) {
															  
															  $total_pago_semana3 = 25 * $salario_basico3 / 100;
															  
															  // 25%
															  // $total_con_horno_semana * $total_con_horno_semana
															  $temp_horno3 = $total_con_horno_semana3 * $total_con_horno_semana3;
															  // 
															  $total_pago_semana3 = $total_pago_semana3 + $temp_horno3 + $total_encimeras_semana3 * ($total_con_horno_semana3 / 2);
															}


															// SEMANA 4 de 4
															
															// total pedidos de una semana
															 $total_pedidos_semana4   = $Pedido->cantidad_pedidos_by_semana($items_vendedores['id'], $fecha_desde_semana_4, $fecha_hasta_semana_4, null, null);
															 $total_pedidos_columna4 = $total_pedidos_columna4 + $total_pedidos_semana4;

															 $total_encimeras_semana4 = $Pedido->pedidos_encimera_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_4, $fecha_hasta_semana_4, ACTIVO, 7);
															 $total_encimeras_columna4 = $total_encimeras_columna4 + $total_encimeras_semana4;

															 // no anda esta sql
															 $total_con_horno_semana4 = $Pedido->pedidos_hornos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_4, $fecha_hasta_semana_4, ACTIVO, 7);
															 $total_con_horno_columna4 = $total_con_horno_columna4 + $total_con_horno_semana4;

															 $total_encimera_y_horno4 = $total_horno_columna4 + $total_con_horno_semana4; 
															
															 $total_baja_semana4   = $Pedido->pedidos_baja_fechas($items_vendedores['id'], $fecha_desde_semana_4, $fecha_hasta_semana_4, null);;
	 														 $total_baja_columna4 = $total_baja_columna4 + $total_baja_semana4;

															 $total_fabrica_semana4 = $Pedido->pedidos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_4, $fecha_hasta_semana_4, ACTIVO, 7);;
															 $total_fabrica_columna4 = $total_fabrica_columna4 + $total_fabrica_semana4;

															 $total_pago_semana4 = 0;
															
															 // calculo de pago semana dependiendo de la
															 // cantidad de cocinas en fabrica
															 // hasta 2, 10 * cocina

															 if($total_fabrica_semana4 == 1 or $total_fabrica_semana4 == 2) {
															  $total_pago_semana4 = $total_fabrica_semana4 * 10;
															  
															 } else if($total_fabrica_semana4 == 3 or $total_fabrica_semana4 == 4 or $total_fabrica_semana4 == 5) {
															   
															   $total_pago_semana4 = 25 * $salario_basico4 / 100;
															   
															 } else if($total_fabrica_semana3 >= 6) {
															  
															  $total_pago_semana4 = 25 * $salario_basico4 / 100;
															  
															  // 25%
															  // $total_con_horno_semana * $total_con_horno_semana
															  $temp_horno4 = $total_con_horno_semana4 * $total_con_horno_semana4;
															  // 
															  $total_pago_semana4 = $total_pago_semana4 + $temp_horno4 + $total_encimeras_semana4 * ($total_con_horno_semana4 / 2);
															}

												?>													

																<tr>
																	<td align="center" width="150"><?=$items_vendedores['apellido']?>,  <?=$items_vendedores['nombre']?></td>
																	<!-- 1-4 -->
																	<td align="center"><?=$total_pedidos_semana1?></td>
																	<td align="center"><?=$total_encimeras_semana1?></td>
																	<td align="center"><?=$total_con_horno_semana1?></td>
																	<td align="center"><?=$total_baja_semana1?></td>
																	<td align="center"><?=$total_fabrica_semana1?></td>

																	<!-- 2-4 -->																	
																	<td align="center"><?=$total_pedidos_semana2?></td>
																	<td align="center"><?=$total_encimeras_semana2?></td>
																	<td align="center"><?=$total_con_horno_semana2?></td>
																	<td align="center"><?=$total_baja_semana2?></td>
																	<td align="center"><?=$total_fabrica_semana2?></td>

																	<!-- 3-4 -->
																	<td align="center"><?=$total_pedidos_semana3?></td>
																	<td align="center"><?=$total_encimeras_semana3?></td>
																	<td align="center"><?=$total_con_horno_semana3?></td>
																	<td align="center"><?=$total_baja_semana3?></td>
																	<td align="center"><?=$total_fabrica_semana3?></td>

																	<!-- 4-4 -->
																	<td align="center"><?=$total_pedidos_semana4?></td>
																	<td align="center"><?=$total_encimeras_semana4?></td>
																	<td align="center"><?=$total_con_horno_semana4?></td>
																	<td align="center"><?=$total_baja_semana4?></td>
																	<td align="center"><?=$total_fabrica_semana4?></td>
																</tr>	
															  
															<?php  //} // 24?>
																<?php } // f?>
																<?php //- cada item ?>
																<tr>
																	<td align="center" width="150"><STRONG>Subtotales</STRONG></td>
																	<!-- 1-4 -->
																	<td align="center"><strong><?=$total_pedidos_columna1?></strong></td>
																	<td align="center"><strong><?=$total_encimeras_columna1?></strong></td>
																	<td align="center"><strong><?=$total_con_horno_columna1?></strong></td>
																	<td align="center"><strong><?=$total_baja_columna1?></strong></td>
																	<td align="center"><strong><?=$total_fabrica_columna1?></strong></td>

																	<!-- 2-4 -->																	
																	<td align="center"><strong><?=$total_pedidos_columna2?></strong></td>
																	<td align="center"><strong><?=$total_encimeras_columna2?></strong></td>
																	<td align="center"><strong><?=$total_con_horno_columna2?></strong></td>
																	<td align="center"><strong><?=$total_baja_columna2?></strong></td>
																	<td align="center"><strong><?=$total_fabrica_columna2?></strong></td>

																	<!-- 3-4 -->
																	<td align="center"><strong><?=$total_pedidos_columna3?></strong></td>
																	<td align="center"><strong><?=$total_encimeras_columna3?></strong></td>
																	<td align="center"><strong><?=$total_con_horno_columna3?></strong></td>
																	<td align="center"><strong><?=$total_baja_columna3?></strong></td>
																	<td align="center"><strong><?=$total_fabrica_columna3?></strong></td>

																	<!-- 4-4 -->
																	<td align="center"><strong><?=$total_pedidos_columna4?></strong></td>
																	<td align="center"><strong><?=$total_encimeras_columna4?></strong></td>
																	<td align="center"><strong><?=$total_con_horno_columna4?></strong></td>
																	<td align="center"><strong><?=$total_baja_columna4?></strong></td>
																	<td align="center"><strong><?=$total_fabrica_columna4?></strong></td>
																</tr>	
																<?php /* ?>
																<tr>
																	<td align="center" width="150"><STRONG>MENSUALES</STRONG></td>
																	<!-- 1-4 -->
																	<td align="center"><STRONG><?=$total_pedidos_mes?></STRONG></td>
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>

																	<!-- 2-4 -->																	
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>

																	<!-- 3-4 -->
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>

																	<!-- 4-4 -->
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>
																	<td align="center"></td>
																</tr>	
															<?php */ ?>


															</table>
														 </td>
													</tr>
													<?php /* ?>
													<tr>
													 <td colspan="9" align="center"><h4><?=strtolower($mes)?> - Semana del <strong><?=$fecha_rango_desde_txt?></strong> al <strong><?=$fecha_rango_hasta_txt?></strong></h4></td>
													</tr>
													
													<?php */ ?>


							
												</thead>
												<tbody>
                         
                     

													<tr>
													 <td colspan="9" align="center"></td>
													</tr>
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
