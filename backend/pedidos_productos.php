<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Incidencia', 'Vendedor', 'Pedido', 'Producto', 'Localizacion');
global $BackendUsuario, $Pedido, $Incidencia, $Vendedor, $Pedido, $Producto, $Localizacion;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteVentas()) {
 //die;
}

// id vendedor
$id 		= ($_REQUEST['id_vendedor']) ? $_REQUEST['id_vendedor'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$e = ($_REQUEST['e']) ? $_REQUEST['e'] : null;

$filtro_id_provincia = ($_REQUEST['filtro_id_provincia']) ? $_REQUEST['filtro_id_provincia'] : null;
$filtro_id_vendedor = ($_REQUEST['filtro_id_vendedor']) ? $_REQUEST['filtro_id_vendedor'] : null;
$filtro_cedula = ($_REQUEST['filtro_cedula']) ? $_REQUEST['filtro_cedula'] : null;
$filtro_nombre = ($_REQUEST['filtro_nombre']) ? $_REQUEST['filtro_nombre'] : null;
$filtro_apellido = ($_REQUEST['filtro_apellido']) ? $_REQUEST['filtro_apellido'] : null;
$fecha_desde  = (!isset($_POST['fecha_desde'])) ? null : $_POST['fecha_desde'];
$fecha_hasta  = (!isset($_POST['fecha_hasta'])) ? null : $_POST['fecha_hasta'];

$mes  = (!isset($_POST['mes'])) ? null : $_POST['mes'];
$anio = (!isset($_POST['anio'])) ? null : $_POST['anio'];

$fecha_rango_desde = date("d/m/Y");  
$fecha_rango_hasta = date("d/m/Y");
$fecha_rango_desde = null;  
$fecha_rango_hasta = null;

$filtro_id_tipo = "olla";

switch ($accion) {
	/*
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Pedido->eliminar($idx);
   }
 break;
 */
 case 'papelera':
	   $Pedido->papelera($_POST['id'], $_POST['campo']);
 break;
 
 case 'publicar':
	   $Pedido->publicar($_POST['id'], $_POST['campo']);
 break;
 
 case 'estado-pedido-agendar':
   if(is_array($_POST['arrSeleccion1'])) foreach($_POST['arrSeleccion1'] as $idx) {
 	  $Pedido->estado_pedido_agendar($idx, 1);
   }
 break;
 
 case 'estado-agendar-documentacion':
   if(is_array($_POST['arrSeleccion3'])) foreach($_POST['arrSeleccion3'] as $idx) {
 	  $Pedido->estado_agendar_documentacion($idx, 3);
   }
 break;

 case 'estado-documentacion-agendar':
   if(is_array($_POST['arrSeleccion4'])) foreach($_POST['arrSeleccion4'] as $idx) {
 	  $Pedido->estado_documentacion_agendar($idx, 4);
   }
 break;
 
 case 'estado-documentacion-despacho':
   if(is_array($_POST['arrSeleccion4'])) foreach($_POST['arrSeleccion4'] as $idx) {
 	  $Pedido->estado_documentacion_despacho($idx, 4);
   }
 break;
}

// dependiendo del perfil del usuario se muestran los epdidos
// VENDEDORES
if($BackendUsuario->esVendedor() || $BackendUsuario->esASistente() ) {

	// estado 1
	$result_todos_1 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 1, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_1 = @mysql_num_rows($result_todos_1);

	// estado 2
	$result_todos_2 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 2, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_2 = @mysql_num_rows($result_todos_2);

	// estado 3
	$result_todos_3 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 3, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_3 = @mysql_num_rows($result_todos_3);

	// estado 4
	$result_todos_4 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 4, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_4 = @mysql_num_rows($result_todos_4);

	// estado 5
	$result_todos_5 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 5, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_5 = @mysql_num_rows($result_todos_5);
	


} else if( $BackendUsuario->esSupervisor()) {

  if($id > 0 ) {
	  $UsuarioId = $id;
  } else { 
  	$UsuarioId = $BackendUsuario->getUsuarioId();
  }
  
	// estado 1
	$result_todos_1 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 1, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_1 = @mysql_num_rows($result_todos_1);

	// estado 3
	$result_todos_3 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 3, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_3 = @mysql_num_rows($result_todos_3);

	// estado 4
	$result_todos_4 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 4, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_4 = @mysql_num_rows($result_todos_4);

	// estado 5
	$result_todos_5 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 5, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_5 = @mysql_num_rows($result_todos_5);
	

} else {

	$result_todos_1 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 1, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_1 = @mysql_num_rows($result_todos_1);

	// estado 3
	$result_todos_3 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 3, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_3 = @mysql_num_rows($result_todos_3);

	// estado 4
	$result_todos_4 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 4, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_4 = @mysql_num_rows($result_todos_4);

	// estado 5
	$result_todos_5 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 5, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor);
	$filas_todos_5 = @mysql_num_rows($result_todos_5);
}

$result_estados_mostrar = $Pedido->obtener_estados(1);
$filas_estados_mostrar = @mysql_num_rows($result_estados_mostrar);

// provincias
$result_provincias = $Localizacion->obtener_provincias(1, ACTIVO, null, null, null);
$filas_provincias = @mysql_num_rows($result_provincias);

// si viene con un id, ya se cambia el estado
// el estado de nuevo se completa
if($id > 0) {
 $arrPedido = $Pedido->obtener($id);
 // leido
 $Pedido->leido($id);
}
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Pedidos - Productos -  Induccion</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="assets/plugins/bootstrap-wizard/css/bwizard.min.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->

	<link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
  <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
  <link href="assets/plugins/colorbox/example1/colorbox.css" rel="stylesheet" />
  
  <script>
 	 function filtrar() {
 	  var id_vendedor = $("id_vendedor").val();
	  var form = document.forms['frmPrincipal'];
	  form['accion'].value = 'filtrar';
 	  form.submit(); 	  
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

 	function estado_pedido_agendar() {
 	 if(confirm('Esta seguro de querer cambiar a estado AGENDAR los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion1[]");
		var chklength = chk_arr.length;   
		var chk_arr_seleccionados = [];          

		for(k=0; k < chklength;k++) {
		  if(chk_arr[k].checked == true) {
		  	chk_arr_seleccionados.push(chk_arr[k].value);
		  }
		} 
    if(chk_arr_seleccionados == 0) {
  	    alert("Debe seleccionar al menos un pedido");
  	  } else {
  	    var idx =  chk_arr_seleccionados.join(",")
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'estado-pedido-agendar';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}
 	
 	function estado_agendar_generacion() {
 	 if(confirm('Esta seguro de querer cambiar a estado GENERACION DOCUMENTACION los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion3[]");
		var chklength = chk_arr.length;   
		var chk_arr_seleccionados = [];          

		for(k=0; k < chklength;k++) {
		  if(chk_arr[k].checked == true) {
		  	chk_arr_seleccionados.push(chk_arr[k].value);
		  }
		} 
    if(chk_arr_seleccionados == 0) {
  	    alert("Debe seleccionar al menos un pedido");
  	  } else {
  	    var idx =  chk_arr_seleccionados.join(",")
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'estado-agendar-documentacion';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}

 	function estado_documentacion_agendar() {
 	 if(confirm('Esta seguro de querer cambiar a estado AGENDAR los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion4[]");
		var chklength = chk_arr.length;   
		var chk_arr_seleccionados = [];          

		for(k=0; k < chklength;k++) {
		  if(chk_arr[k].checked == true) {
		  	chk_arr_seleccionados.push(chk_arr[k].value);
		  }
		} 
    if(chk_arr_seleccionados == 0) {
  	    alert("Debe seleccionar al menos un pedido");
  	  } else {
  	    var idx =  chk_arr_seleccionados.join(",")
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'estado-documentacion-agendar';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	} 

 	function estado_documentacion_despacho() {
 	 if(confirm('Esta seguro de querer cambiar a estado DESPACHO los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion4[]");
		var chklength = chk_arr.length;   
		var chk_arr_seleccionados = [];          

		for(k=0; k < chklength;k++) {
		  if(chk_arr[k].checked == true) {
		  	chk_arr_seleccionados.push(chk_arr[k].value);
		  }
		} 
    if(chk_arr_seleccionados == 0) {
  	    alert("Debe seleccionar al menos un pedido");
  	  } else {
  	    var idx =  chk_arr_seleccionados.join(",")
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'estado-documentacion-despacho';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
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
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Home</a></li>
				<li><a href="javascript:;">Pedidos</a></li>
				<li class="active">Listado de pedidos de productos</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Pedidos productos <small> listado</small></h1>
			<!-- end page-header -->
			
		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_item">	
			<input type="hidden" name="pedidos_idx"  id="pedidos_idx">	
			<input type="hidden" name="imagen1">
			<input type="hidden" name="imagen2">		
						
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
                            <h4 class="panel-title">SEGUIMIENTO DE PEDIDOS</h4>
                        </div>
                        <div class="panel-body">
                        	
													<div id="wizard">
															<ol>
																<li>
																    Ingresa Pedido  &nbsp;&nbsp;(<?=($filas_todos_1) ? $filas_todos_1 : '0'?>)
																    <small>El vendedor esta en obligaci&oacute;n de verificar la informaci&oacute;n que env&iacute;a por el sistema</small>
																</li>
																<li>
																    Agendar &nbsp;&nbsp;(<?=($filas_todos_1) ? $filas_todos_3 : '0'?>)
																    <small>Pedidos en el estado Agendar.</small>
																</li>

																<li>
																   Generaci&oacute;n de Documentaci&oacute;n &nbsp;&nbsp;(<?=($filas_todos_4) ? $filas_todos_1 : '0'?>)
																    <small>Pedidos en el estado Generaci&oacute;n de Documentaci&oacute;n.</small>
																</li>
																<li>
																    Despacho &nbsp;&nbsp;(<?=($filas_todos_5) ? $filas_todos_5 : '0'?>)
																    <small>Pedidos en el estado Despacho.</small>
																</li>
															</ol>
								  <table width="100%">
									  	<tr>
									  		<td height="20"></td>
									  	</tr>									  	
									  	<tr>
									  		<td>
											 <fieldset>
												<legend class="pull-left width-full">Filtros</legend>
                         <!-- begin row -->
                          <div class="row">
                            <!-- begin col-6 -->
                            <div class="col-md-6">
															
											
															 <div class="form-group">
                                    <div class="col-md-8">
                      
															
                                        <div class="input-group input-daterange">
                                            <input type="text" class="form-control" name="start" placeholder="Fecha de Inicio" />
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="form-control" name="end" placeholder="Fecha Fin" />
                                        </div>
                                    </div>
                                </div>
                            </div>
	                           <!-- end col-6 -->
  	                         <!-- begin col-6 -->
                             <div class="col-md-6">

														   <div class="form-group">
                                  <div class="controls">                             	
			                      				<?php
                                 	// vendedores
																	$result_vendedores = $BackendUsuario->obtener_vendedores_y_supervisores(1); 
																	$filas_vendedores = @mysql_num_rows($result_vendedores);
                                   	?>
                                   	<select id="filtro_id_vendedor" name="filtro_id_vendedor"  class="form-control">
                                   	 <option value=""> - Filtrar  vendedor -</option>
                                   	 <?php
                                   	  for($k=0; $k < $filas_vendedores; $k++) {
																			$items_vendedores = @mysql_fetch_array($result_vendedores); ?>
                                   	 		 <option value="<?=$items_vendedores['id']?>" <?=($filtro_id_vendedor==$items_vendedores['id']) ? 'selected' : ''?>> <?=$items_vendedores['apellido']?>,  <?=$items_vendedores['nombre']?></option>
																		<?php	} ?>	
                                   	</select>


			                      				<select name="filtro_id_provincia" id="filtro_id_provincia" class="form-control">
						 													<option value="">-Filtrar Provincia-</option>
																				<?php // PROVINCIAS
																					for($i=0; $i < $filas_provincias; $i++) {
																							$items_provincias = @mysql_fetch_array($result_provincias); ?>						 	
							 																 <option value="<?=$items_provincias['id']?>"  <?=($_POST['filtro_id_provincia']==$items_provincias['id']) ? 'selected' : ''?>><?=$items_provincias['nombre']?></option>
																			<?php } ?>  
																		</select>

                             				<input  type="text" class="form-control" name="filtro_cedula" id="filtro_cedula" class="input" placeholder="Cédula Identidad" />
                             				<input  type="text" class="form-control" name="filtro_nombre" id="filtro_nombre" class="input" placeholder="Nombre Cliente" />
                             				<input  type="text" class="form-control" name="filtro_apellido" id="filtro_apellido" class="input" placeholder="Apellido Cliente" />
				    	                      <div style="padding-top:5px"></div>
				    	                      <input type="button"  class="btn btn-inverse" value="Filtrar" onClick="filtrar()" />
				    	                    </div>
				    	                 </div>   

    	                     </div>
                            <!-- end col-6 -->
                          </div>
                          <!-- end row -->
													 </fieldset>
												 </td>
									  		</tr>
									</table>
																
															<div>
															  <fieldset>
																  <legend class="pull-left width-full">1. Ingresa Pedido </legend>
									                   <div class="row">
                					           	 <div class="col-md-12"> 
                	          				 	    <div class="table-responsive">
				          	                 	    	
				          	                 	    	<?php //+ ESTADO 1 // ?>
				          	                 	    	
				          	            								  <table id="data-table" class="table table-striped table-bordered">
                                								    <thead>
                                								        <tr>
                    																				<th width="2%">Id</th>
                                								            <th width="10%">Cedula Identidad</th>
                                								            <th width="10%">Nombre</th>
                                								            <th width="10%">Apellido</th>
                                								            <th width="20%">Producto Solicitado</th>
                                								            <th width="10%">Fecha Ingreso</th>
                                								            <th width="10%">Fecha Modificacion</th>
                                								            <th width="15%">Vendedor</th>
                                								            <th width="5%" align="center"><input type="checkbox" id="selck1" onClick="seleccionar_todo('1')"></th>
                                								            <th width="15%">Estado</th>
                                								        </tr>
                                								    </thead>
                                								    <tbody>
                                								
																									<?php
					   																			for($k=1; $k <= $filas_todos_1; $k++) {
						 																				$items = @mysql_fetch_array($result_todos_1);
																											$arrIncidencia = $Pedido->obtener_incidencia_by_estadov2($items['id'],1);
                                								      $tooltip = "";
																											if(strlen($arrIncidencia['contenido']) > 0) {
																													$tooltip =  $arrIncidencia['contenido'];
																											}
																									?>                                     	
                                								     <?php if($items['id'] > 0) { ?>
                                								       
                                								        <tr class="odd gradeX">
                                								            <td><?=$items['id']?></td>
                                								            <td><?=$items['cliente_dni']?></td>
                                								            <td><?=$items['cliente_nombre']?></td>
                                								            <td><?=$items['cliente_apellido']?></td>
                                								            <td>

                                								            		<?php 
                                								            		// hay q especificar el listado
                                								            		// de productos de un pedido
                                								            		if($filas_ollas > 0) { ?>
                                								
	                              								                  <div style="clear:both"></div>
	                              								                  Ollas:<br/>
	                              								                  <strong>
																																	<?php
																																		for($o=1; $o <= $filas_ollas; $o++) {
																																			$items_ollas = @mysql_fetch_array($result_ollas); ?>
    																																
																																			<?=$items_ollas['olla_caracteristicas']?> - <?=$items_ollas['olla_color']?> <br/>
																																			
																																		<?php } ?>
																																		</strong>	
	                              								              	<?php } ?>
                                								
                                								            	</td>
	                              								              <td>
                                								             	<?=GetFechaTexto($items['fecha_alta'])?>
                                								            	</td>
                                								            	<td>
                                								            		<?=GetFechaTexto($items['fecha_mod'])?>
                                								            	</td>
                                								            	<td><?=$items['vendedor_nombre']?></td>
                                								            <td>
                                								            
                                								            </td>
                                								            <td>
                                								              <span class="label label-primary">Pedido</span>
                                								
                                								              <div style="padding-left:5px;display:inline">
                                								              	<span class="label label-danger"><?=$arrIncidencia['nombre']?></span>
                                								              	
                                								              	<?php 
                                								              	if(strlen($tooltip) > 5) {?>
                                								              		<i class="fa fa-2x fa-info" border="0" data-toggle="tooltip" data-placement="top" data-original-title="Default tooltip" class="" style="" title="<?=$tooltip?>"></i>
                                								              	<?php } ?>                                              
																																
																																<div style="padding-top:2px"></div>
																														 	
                                								            </td>
                                								        </tr>
                                								  <?php
                                								     }
                                								  } // f pedidos
                                								  ?>     
                                								         <tr>
                                								          <td colspan="7"></td>
                                								          <td colspan="2" align="right">
	                              								            
		                            								              <button type="button" class="btn btn-default m-r-5 m-b-5" onClick="estado_pedido_a_agendar()">Agendar</button>
	                              								            	<button type="button" class="btn btn-danger" onClick="incidencia('1')">Agregar Incidencia</button>
                                								
	                              								          </td>
                                								         </tr>
                                								    </tbody>
                                								</table>

				          	                 	    	


				          	                 	    	<?php //- ESTADO 1 // ?>
				          	                 	    	
        				    	               	  	</div>                        	
                      					  	   </div>
                        	 					 </div>
                    						</fieldset>   
				                 		 </div>

															<div>
															  <fieldset>
																  <legend class="pull-left width-full">2. Agendar </legend>
									                   <div class="row">
                					           	 <div class="col-md-12"> 
                	          				 	    <div class="table-responsive">
				          	                 	    	
				          	                 	    	
				          	                 	    	<?php //+ ESTADO 3 // ?>
				          	                 	    	
				          	               <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="2%">Id</th>
                                            <th width="10%">Cedula Identidad</th>
                                            <th width="10%">Nombre</th>
                                            <th width="10%">Apellido</th>
                                            <th width="20%">Producto Solicitado</th>
                                            <th width="10%">Fecha Ingreso</th>
                                            <th width="10%">Fecha Modificacion</th>
                                            <th width="15%">Vendedor</th>
                                            <th width="5%" align="center"><input type="checkbox" id="selck3" onClick="seleccionar_todo('3')"></th>
                                            <th width="15%">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($k=1; $k <= $filas_todos_3; $k++) {
						 												  $items = @mysql_fetch_array($result_todos_3);
						 												
																			$arrIncidencia = $Pedido->obtener_incidencia_by_estadov2($items['id'],3);
                                      $tooltip = "";
																			if(strlen($arrIncidencia['contenido']) > 0) {
																					$tooltip =  $arrIncidencia['contenido'];
																			}
																	?>                                     	
                                        <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td>
                                            	<a href="javascript:agendar('<?=$items['id']?>')"><strong><?=$items['cliente_dni']?></strong></a></td>
                                            <td><?=$items['cliente_nombre']?></td>
                                            <td><?=$items['cliente_apellido']?></td>
                                            <td><?=$items['modelo']?> <?=$items['marca']?> <?=$items['color']?></td>
                                            <td>
                                             	<?=GetFechaTexto($items['fecha_alta'])?>
                                            	</td>
                                            	<td>
                                            		<?=GetFechaTexto($items['fecha_mod'])?>
                                            	</td>
  
                                            	<td><?=$items['vendedor_nombre']?></td>
                                            <td><input type="checkbox" id="arrSeleccion3<?=$k?>" name="arrSeleccion3[]" value="<?=$items['id']?>"></td>
                                            <td>
                                              <span class="label label-primary">Agendar</span>

                                              <div style="padding-left:5px;display:inline">
                                              	<span class="label label-danger"><?=$arrIncidencia['nombre']?></span>
                                              	
                                              	<?php 
                                              	if(strlen($tooltip) > 5) {?>
                                              		<i class="fa fa-2x fa-info" border="0" data-toggle="tooltip" data-placement="top" data-original-title="Default tooltip" class="" style="" title="<?=$tooltip?>"></i>
                                              	<?php } ?>
                                              	<a href="javascript:detalle('<?=$items['id']?>')"><i class="fa fa-2x fa-eye"></i></a>
                                              	<a href="javascript:papelera('<?=$items['id']?>')"><i class="fa fa-2x fa-trash-o"></i></a>
																							</div>


                                            </td>
                                        </tr>
                                  <?php
                                  } // f pedidos
                                  ?>     
                                         <tr>
                                          <td colspan="5"></td>
                                          <td colspan="4" align="right">
	                                         	<button type="button" class="btn btn-default btn-sm" onClick="estado_agendar_generacion()">3 Generacion de Documentacion</button> 
  	                                        <button type="button" class="btn btn-danger" onClick="incidencia('3')">Agregar Incidencia</button>
		                                      </td>
                                         </tr>
                                    </tbody>
                                </table>

				          	                 	    	<?php // - ESTADO 3 // ?>
        				    	               	  	</div>                        	
                      					  	   </div>
                        	 					 </div>
                    						</fieldset>   
				                 		 	</div>


															<div>
															  <fieldset>
																  <legend class="pull-left width-full">3. Generaci&oacute;n de Documentaci&oacute;n </legend>
									                   <div class="row">
                					           	 <div class="col-md-12"> 
                	          				 	    <div class="table-responsive">
				          	                 	    	
				          	                 	    	
				          	                 	    	<?php //+ ESTADO 4 ?>
				          	                 	    	
				          	                 	    	                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="2%">Id</th>
                                            <th width="5%">Cedula Identidad</th>
                                            <th width="10%">Nombre</th>
                                            <th width="10%">Apellido</th>
                                            <th width="20%">Producto Solicitado</th>
                                            <th width="10%">Fecha Ingreso</th>
                                            <th width="10%">Fecha Modificacion</th>
                                            <th width="15%">Vendedor</th>
                                            <th width="5%" align="center"><input type="checkbox" id="selck4" onClick="seleccionar_todo('4')"></th>
                                            <th width="20%">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($k=1; $k <= $filas_todos_4; $k++) {
						 												$items = @mysql_fetch_array($result_todos_4);
																			// busca la inicidencia del estado
																			$arrIncidencia = $Pedido->obtener_incidencia_by_estadov2($items['id'],4);
                                      $tooltip = "";
																			if(strlen($arrIncidencia['contenido']) > 0) {
																					$tooltip =  $arrIncidencia['contenido'];
																			}
																	?>   
																					<tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td>
                                            	<a href="javascript:generar_documentacion('<?=$items['id']?>')"><strong><?=$items['cliente_dni']?></strong></a></td>
                                            <td><?=$items['cliente_nombre']?></td>
                                            <td><?=$items['cliente_apellido']?></td>
                                            <td><?=$items['modelo']?> <?=$items['marca']?> <?=$items['color']?></td>
                                            <td>
	                                            	<?=GetFechaTexto($items['fecha_alta'])?>
	                                            	</td>
                                            <td>
	                                            	<?=GetFechaTexto($items['fecha_mod'])?>
	                                            	</td>

                                            	<td><?=$items['vendedor_nombre']?></td>
                                            <td><input type="checkbox" id="arrSeleccion4<?=$k?>" name="arrSeleccion4[]" value="<?=$items['id']?>"></td>
                                            <td>

                                              <span class="label label-primary">Generar Documentación</span>

                                              <div style="padding-left:5px;display:inline">
                                              	<span class="label label-danger"><?=$arrIncidencia['nombre']?></span>
                                              	
                                              	<?php 
                                              	if(strlen($tooltip) > 5) {?>
                                              		<i class="fa fa-2x fa-info" border="0" data-toggle="tooltip" data-placement="top" data-original-title="Default tooltip" class="" style="" title="<?=$tooltip?>"></i>
                                              	<?php } ?>

                                              	<a href="javascript:detalle('<?=$items['id']?>')"><i class="fa fa-2x fa-eye"></i></a>
                                              	<a href="javascript:papelera('<?=$items['id']?>')"><i class="fa fa-2x fa-trash-o"></i></a>
																							
																								<?php //+ PDF // ?>
																								<?php if(strlen($items['imagen_dni_frente_g']) > 11 && strlen($items['imagen_dni_posterior_g']) > 11) { ?>
		                                            	<a href="javascript:generar_pdf('<?=$items['imagen_dni_frente_g']?>','<?=$items['imagen_dni_posterior_g']?>')"><img src="icon_pdf.png" width="25" title="Generar PDF Dueno"></a>
  	                                            <?php } ?>

			                                          <?php if(strlen($items['imagen_factura_g']) > 11 ) { ?>
	    	                                        	<a href="javascript:generar_pdf('<?=$items['imagen_factura_g']?>','')"><img src="icon_pdf.png" width="25" title="Generar PDF Factura"></a>
        	                                      <?php } ?>
  
          	                                  	<?php if($items['dueno'] == 0) { ?>
	          	                                  	<?php if(strlen($items['imagen_dni_duenio_frente_g']) > 11 && strlen($items['imagen_dni_duenio_posterior_g']) > 11) { ?>
  	          	                                		<a href="javascript:generar_pdf('<?=$items['imagen_dni_duenio_frente_g']?>','<?=$items['imagen_dni_duenio_posterior_g']?>')"><img src="icon_pdf.png" width="15" title="Generar PDF Arrendador"></a>
    	          	                                <?php } ?>
	                	                            	<?php if(strlen($items['imagen_duenio_garante_g']) > 11) { ?>
	                  	                          		<a href="javascript:generar_pdf('<?=$items['imagen_duenio_garante_g']?>','')"><img src="icon_pdf.png" width="25" title="Carta de Autorizacion"></a>
	  	                	                          <?php } ?>

																								<?php //- PDF // ?>

	                                            <?php } ?>
																							</div>
                                            </td>
                                        </tr>
                                  <?php
                                  } // f pedidos
                                  ?>     
                                         <tr>
                                          <td colspan="4"></td>
                                          <td colspan="5" align="right">
		                                         	<button type="button" class="btn btn-default btn-sm" onClick="estado_documentacion_agendar()">2 Agendar</button> 
		                                         	<button type="button" class="btn btn-default btn-sm" onClick="estado_documentacion_despacho()">4 Despacho</button> 

                                              <button type="button" class="btn btn-danger" onClick="incidencia('4')">Agregar Incidencia</button>
                                          </td>
                                       </tr>
                                    </tbody>
                                </table>

				          	                 	    	
				          	                 	    	
				          	                 	    	<?php //- ESTADO 4 ?>
				          	                 	    	
        				    	               	  	</div>                        	
                      					  	   </div>
                        	 					 </div>
                    						</fieldset>   
				                 		 	</div>

															<div>
															  <fieldset>
																  <legend class="pull-left width-full">4. Despacho </legend>
									                   <div class="row">
                					           	 <div class="col-md-12"> 
                	          				 	    <div class="table-responsive">
				          	                 	    	
				          	                 	    	<?php //+ ESTADO 5 DESPACHO // ?>
				          	                 	    	
				          	                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="2%">Id</th>
                                            <th width="10%">Cedula Identidad</th>
                                            <th width="10%">Nombre</th>
                                            <th width="10%">Apellido</th>
                                            <th width="20%">Producto Solicitado</th>
                                            <th width="10%">Fecha Ingreso</th>
                                            <th width="10%">Fecha Modificacion</th>
                                            <th width="15%">Vendedor</th>
                                            <th width="5%" align="center"><input type="checkbox" id="selck5" onClick="seleccionar_todo('5')"></th>
                                            <th width="15%">Estado</th>
                                        </tr>
                                    </thead>
                                   <tbody>

																	<?php
					   											for($k=1; $k <= $filas_todos_5; $k++) {
						 												$items = @mysql_fetch_array($result_todos_5);
																			// busca la inicidencia del estado
																			$arrIncidencia = $Pedido->obtener_incidencia_by_estadov2($items['id'],5);
                                      $tooltip = "";
																			if(strlen($arrIncidencia['contenido']) > 0) {
																					$tooltip =  $arrIncidencia['contenido'];
																			}
																	?>
																	  <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td>
                                            	<a href="javascript:agendar('<?=$items['id']?>')"><strong><?=$items['cliente_dni']?></strong></a></td>
                                            <td><?=$items['cliente_nombre']?></td>
                                            <td><?=$items['cliente_apellido']?></td>
                                            <td><?=$items['modelo']?> <?=$items['marca']?> <?=$items['color']?></td>
                                            <td>
	                                            	<?=GetFechaTexto($items['fecha_alta'])?>
                                           	</td>
                                            <td>
	                                            	<?=GetFechaTexto($items['fecha_mod'])?>
                                           	</td>
                                           	<td><?=$items['vendedor_nombre']?></td>
                                            <td><input type="checkbox" id="arrSeleccion5<?=$k?>" name="arrSeleccion5[]" value="<?=$items['id']?>"></td>
                                            <td>
                                              <span class="label label-primary">Despacho</span>
                                             	<span class="label label-danger"><?=$arrIncidencia['nombre']?></span>

                                              	<?php 
                                              	if(strlen($tooltip) > 5) {?>
                                              		<i class="fa fa-2x fa-info" border="0" data-toggle="tooltip" data-placement="top" data-original-title="Default tooltip" class="" style="" title="<?=$tooltip?>"></i>
                                              	<?php } ?>

		                                              <a href="javascript:detalle('<?=$items['id']?>')"><img src="iconos/icono_detalles.jpg" border="0"></a>

                                            </td>
                                        </tr>
                                  <?php
                                  } // f pedidos
                                  ?>     
                                         <tr>
                                          <td colspan="4"></td>
                                          <td colspan="5" align="right">
                                           <button type="button" class="btn btn-default btn-sm" onClick="estado_despacho_documentacion('4')">4 Generación Documentacion</button> 
                                           <button type="button" class="btn btn-danger" onClick="incidencia('5')">Agregar Incidencia</button>

                                          </td>
                                         </tr>
                                    </tbody>
                                </table>

				          	                 	    	
				          	                 	    	<?php // - ESTADO ?>
        				    	               	  	</div>                        	
                      					  	   </div>
                        	 					 </div>
                    						</fieldset>   
				                 		 	</div>
        				          </div> 	
								
											
														</form>
                        </div>
                    </div>
                    <!-- end panel -->
                </div>
                <!-- end col-12 -->
            </div>
            <!-- end row -->
		</div>
		<!-- end #content -->
		
		
        <!-- begin theme-panel -->
      
        <!-- end theme-panel -->
				</form>

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
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
	<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
	<script src="assets/plugins/masked-input/masked-input.min.js"></script>
	<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="assets/plugins/password-indicator/js/password-indicator.js"></script>
	<script src="assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
	<script src="assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
	<script src="assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/moment.js"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
    <script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<script src="assets/js/form-plugins.demo.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/bootstrap-wizard/js/bwizard.js"></script>
	<script src="assets/js/form-wizards.demo.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
		<script src="assets/plugins/colorbox/jquery.colorbox.js"></script>

	<script>
		$(document).ready(function() {
			App.init();
			FormWizard.init();
			FormPlugins.init();
		});

			function subir_excel() {
					//$('.myCheckbox')[0].checked = true;
					/*	
					var chk_arr =  document.getElementsByName("arrSeleccion2[]");
					var chklength = chk_arr.length;   
					var chk_arr_seleccionados = [];          

					for(k=0; k < chklength;k++) {
				    if(chk_arr[k].checked == true) {
				    	chk_arr_seleccionados.push(chk_arr[k].value);
				    }
					} 
          
          if(chk_arr_seleccionados == 0) {
            alert("Debe seleccionar al menos un pedido");
          } else {
           var idx =  chk_arr_seleccionados.join(",")
					 window.open("ifr_subir_excel.php?idx="+idx, "Subir Excel", "height=900,width=700, scroll=yes");
					//$(".estado").colorbox({iframe:true, width:"800px", height:"800px", left: "30%"});
					}
					*/
					 window.open("ifr_subir_excel.php", "Subir Excel", "height=900,width=700,scrollbars=1");
			}

			function agendar(idx) {
					 window.open("ifr_estado.php?id="+idx+"&estado=3", "Agendar", "height=900,width=700,scrollbars=1");
			}

			function detalle(idx) {
					 window.open("ifr_detalle.php?id="+idx, "Detalle", "height=900,width=700,scrollbars=1");
			}


			function generar_documentacion(idx) {
					 window.open("ifr_estado.php?id="+idx+"&estado=4", "Generar Documentacion", "height=900,width=700,scrollbars=1");
			}
			
			function recepcion_documentacion(idx) {
					 window.open("ifr_estado.php?id="+idx+"&estado=6", "Recepcion Documentacion", "height=900,width=700,scrollbars=1");
			}
			

	</script>

</body>
</html>
