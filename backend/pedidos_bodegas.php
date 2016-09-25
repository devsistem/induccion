<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Incidencia', 'Localizacion');
global $BackendUsuario, $Pedido, $Incidencia, $Localizacion;

$BackendUsuario->EstaLogeadoBackend();

// id del pedido q trae la alerta
$id = ($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$e = ($_REQUEST['e']) ? $_REQUEST['e'] : null;

$filtro_id_provincia = ($_REQUEST['filtro_id_provincia']) ? $_REQUEST['filtro_id_provincia'] : null;
$filtro_id_vendedor = ($_REQUEST['filtro_id_vendedor']) ? $_REQUEST['filtro_id_vendedor'] : null;
$filtro_cedula = ($_REQUEST['filtro_cedula']) ? $_REQUEST['filtro_cedula'] : null;
$filtro_nombre = ($_REQUEST['filtro_nombre']) ? $_REQUEST['filtro_nombre'] : null;
$filtro_apellido = ($_REQUEST['filtro_apellido']) ? $_REQUEST['filtro_apellido'] : null;
$filtro_id_tipo = ($_REQUEST['filtro_id_tipo']) ? $_REQUEST['filtro_id_tipo'] : null;
$filtro_plataforma = ($_REQUEST['filtro_plataforma']) ? $_REQUEST['filtro_plataforma'] : null;
$con_incidencias_graves = ($_REQUEST['con_incidencias_graves']) ? 1 : null;


if($con_incidencias_graves == 1) {
  $con_incidencias_graves = "'4,9,16'";
}

$fecha_desde  = (!isset($_POST['fecha_desde'])) ? null : $_POST['fecha_desde'];
$fecha_hasta  = (!isset($_POST['fecha_hasta'])) ? null : $_POST['fecha_hasta'];

$mes  = (!isset($_REQUEST['mes'])) ? null : $_REQUEST['mes'];
$anio = (!isset($_REQUEST['anio'])) ? null : $_REQUEST['anio'];

$fecha_rango_desde = date("d/m/Y");  
$fecha_rango_hasta = date("d/m/Y");
$fecha_rango_desde = null;  
$fecha_rango_hasta = null;

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
	   $accion = "papelera";
 break;
 
 case 'publicar':
	   $Pedido->publicar($_POST['id'], $_POST['campo']);
 break;
 case 'estado':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Pedido->estado($idx, $_POST['campo']);
   }
 break;
 case 'estado-pedido':
  if(is_array($_POST['arrSeleccion2'])) foreach($_POST['arrSeleccion2'] as $idx) {
 	  $Pedido->estado_pedido($idx, 1);
   }
 break;
 case 'estado-predespacho':
  if(is_array($_POST['arrSeleccion3'])) foreach($_POST['arrSeleccion3'] as $idx) {
 	  $Pedido->estado_predespacho($idx, 2);
   }
 break; 
 case 'estado-pedido-predespacho':
  if(is_array($_POST['arrSeleccion1'])) foreach($_POST['arrSeleccion1'] as $idx) {
 	  $Pedido->estado_predespacho($idx, 2);
   }
 break; 

 case 'estado-pedido-despacho-recepcion':
  if(is_array($_POST['arrSeleccion5'])) foreach($_POST['arrSeleccion5'] as $idx) {
 	  $Pedido->estado_recepcion($idx, 5);
   }
 break; 

 case 'estado-pedido-despacho-generacion':
  if(is_array($_POST['arrSeleccion5'])) foreach($_POST['arrSeleccion5'] as $idx) {
 	  $Pedido->estado_generacion($idx, 5);
   }
 break; 

 case 'estado-recepcion-despacho':
  if(is_array($_POST['arrSeleccion6'])) foreach($_POST['arrSeleccion6'] as $idx) {
 	  $Pedido->estado_despacho($idx, 6);
   }
 break; 

 case 'estado-pedido-entrega-recepcion':
  if(is_array($_POST['arrSeleccion7'])) foreach($_POST['arrSeleccion7'] as $idx) {
 	  $Pedido->estado_recepcion($idx, 6);
   }
 break; 

 case 'estado-generacion-despacho':
  if(is_array($_POST['arrSeleccion4'])) foreach($_POST['arrSeleccion4'] as $idx) {
 	  $Pedido->estado_despacho($idx, 4);
   }
 break; 

 case 'estado-recepcion-entrega':
  if(is_array($_POST['arrSeleccion6'])) foreach($_POST['arrSeleccion6'] as $idx) {
 	  $Pedido->estado_entrega($idx, 7);
   }
 break; 

 case 'estado-agendar':
  if(is_array($_POST['arrSeleccion2'])) foreach($_POST['arrSeleccion2'] as $idx) {
 	  $Pedido->estado_agendar($idx, 3);
   }
 break; 
 case 'estado-documentacion':
  if(is_array($_POST['arrSeleccion4'])) foreach($_POST['arrSeleccion4'] as $idx) {
 	  $Pedido->estado_agendar($idx, 3);
   }
 break; 
 
 case 'estado-pedido-agendar-generacion':
   if(is_array($_POST['arrSeleccion3'])) foreach($_POST['arrSeleccion3'] as $idx) {
 	  $Pedido->estado_generacion($idx, 4);
   }
 break; 
}

// dependiendo del perfil del usuario se muestran los epdidos
// VENDEDORES
if($BackendUsuario->esVendedor() || $BackendUsuario->esASistente() ) {

	// estado 1
	$result_todos_1 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 1, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_1 = @mysql_num_rows($result_todos_1);

	// estado 2
	$result_todos_2 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 2, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_2 = @mysql_num_rows($result_todos_2);

	// estado 3
	$result_todos_3 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 3, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_3 = @mysql_num_rows($result_todos_3);

	// estado 4
	$result_todos_4 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 4, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_4 = @mysql_num_rows($result_todos_4);

	// estado 5
	$result_todos_5 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 5, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_5 = @mysql_num_rows($result_todos_5);
	
	// despachos
	$result_todos_6 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 6, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_6 = @mysql_num_rows($result_todos_6);

	// entrega
	$result_todos_7 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 7, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $BackendUsuario->getUsuarioId(), null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_7 = @mysql_num_rows($result_todos_7);

} else if( $BackendUsuario->esSupervisor()) {

  if($id > 0 ) {
	  $UsuarioId = $id;
  } else { 
  	$UsuarioId = $BackendUsuario->getUsuarioId();
  }
  
	// estado 1
	$result_todos_1 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 1, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_1 = @mysql_num_rows($result_todos_1);

	// estado 2
	$result_todos_2 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 2, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_2 = @mysql_num_rows($result_todos_2);

	// estado 3
	$result_todos_3 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 3, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_3 = @mysql_num_rows($result_todos_3);

	// estado 4
	$result_todos_4 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 4, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_4 = @mysql_num_rows($result_todos_4);

	// estado 5
	$result_todos_5 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 5, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_5 = @mysql_num_rows($result_todos_5);
	
	// despachos
	$result_todos_6 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 6, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_6 = @mysql_num_rows($result_todos_6);

	// entrega
	$result_todos_7 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 7, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $UsuarioId, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_7 = @mysql_num_rows($result_todos_7);

} else {

	$result_todos_1 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 1, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_1 = @mysql_num_rows($result_todos_1);

	// estado 2
	$result_todos_2 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 2, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_2 = @mysql_num_rows($result_todos_2);

	// estado 3
	$result_todos_3 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 3, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_3 = @mysql_num_rows($result_todos_3);

	// estado 4
	$result_todos_4 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 4, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_4 = @mysql_num_rows($result_todos_4);

	// estado 5
	$result_todos_5 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 5, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_5 = @mysql_num_rows($result_todos_5);

	// estado 6
	$result_todos_6 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 6, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_6 = @mysql_num_rows($result_todos_6);

	// estado 7
	$result_todos_7 = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 7, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio, $filtro_plataforma);
	$filas_todos_7  = @mysql_num_rows($result_todos_7);
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
	<title>Pedidos -  Induccion</title>
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
 	function papelera(idx) {
 	 if(confirm('Esta seguro de querer enviar el pedido a la papelera?')) {
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'papelera';
  		  form['id'].value = idx;
   		  form.submit();
 	  }
 	 }
 	 
 	 function estado_recepcion_entrega() {
 	 if(confirm('Esta seguro de querer cambiar a estado ENTREGA los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion6[]");
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
	  	  form['accion'].value = 'estado-recepcion-entrega';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}

 	function estado_pedido_a_predespacho() {
 	 if(confirm('Esta seguro de querer cambiar a estado PRE-DESPACHO los items seleccionados?')) {
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
	  	  form['accion'].value = 'estado-pedido-predespacho';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}

  // de 5 a 4
 	function estado_despacho_documentacion() {
 	 if(confirm('Esta seguro de querer cambiar a estado GENERACION DE DOCUMENTACION los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion5[]");
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
	  	  form['accion'].value = 'estado-pedido-despacho-generacion';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}

 	function estado_despacho_recepcion() {
 	 if(confirm('Esta seguro de querer cambiar a estado RECEPCION DE DOCUMENTACION los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion5[]");
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
	  	  form['accion'].value = 'estado-pedido-despacho-recepcion';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}
  
  function estado_agendar_generacion() {

   if(confirm('Esta seguro de querer cambiar a estado GENERACION DE DOCUMENTACION los items seleccionados?')) {
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
	  	  form['accion'].value = 'estado-pedido-agendar-generacion';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
		}
 	 }
  }
  
 	function estado_entrega_recepcion() {
 	 if(confirm('Esta seguro de querer cambiar a estado RECEPCION DE DOCUMENTACION los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion7[]");
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
	  	  form['accion'].value = 'estado-pedido-entrega-recepcion';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}
 	  	 	
 	function estado_pedido() {
 	 if(confirm('Esta seguro de querer cambiar a estado PEDIDO los items seleccionados?')) {
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
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'estado-pedido';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}
 	function estado_predespacho() {
 	 if(confirm('Esta seguro de querer cambiar a estado PRE-DESPACHO los items seleccionados?')) {
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
	  	  form['accion'].value = 'estado-predespacho';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	} 	

 	function estado_agendar() {
 	 if(confirm('Esta seguro de querer cambiar a estado AGENDAR los items seleccionados?')) {
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
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'estado-agendar';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}

 	function estado_recepcion_despacho() {
 	 if(confirm('Esta seguro de querer cambiar a estado DESPACHO los items seleccionados?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion6[]");
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
	  	  form['accion'].value = 'estado-recepcion-despacho';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}
 	
 	function estado_generacion_despacho() {
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
	  	  form['accion'].value = 'estado-generacion-despacho';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}

 	function estado_documentacion() {
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
	  	  form['accion'].value = 'estado-documentacion';
  		  form['pedidos_idx'].value = idx;
   		  form.submit();
			}
 	 }
 	}
 	
 	function estado(estado) {
  	if(confirm('Esta seguro de querer cambiar a PRE-DESPACHO los items seleccionados?')) {
   		var form = document.forms['frmPrincipal'];
   		form['accion'].value = 'estado';
   		form['campo'].value = estado;
   		form.submit();
  	}
 	}
 	
 	function filtrar() {
   		var form = document.forms['frmPrincipal'];
   		form['accion'].value = 'filtrar';
   		form.submit();
 	}
 	
 	function bajar() {
 	 if(confirm('Esta seguro de querer bajar el excel?')) {
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
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'bajar';
  		  form['pedidos_idx'].value = idx;
  		  form.action = "_bajar_despacho.php";
  		  form.submit();
			}
 	 }
 	} 

 	function bajar_5() {
 	 if(confirm('Esta seguro de querer bajar el excel?')) {
 		var chk_arr =  document.getElementsByName("arrSeleccion5[]");
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
	  	  form['accion'].value = 'bajar';
  		  form['pedidos_idx'].value = idx;
  		  form.action = "_bajar_despacho.php";
  		  form.submit();
			}
 	 }
 	} 
 	
 	function cargarEstadoAgenda(idx) {
 		 //alert("cargarEstadoAgenda");
 	   location.href = "pedidosv2.php#step3";
 	   /*
  	 var form = document.forms['frmPrincipal'];
  	 form['accion'].value = 'bajar';
  	 form.action = "pedidosv2.php";
  	 form.submit();
		 */
 	}

 	function cargar_estado_5(idx) {
 		 alert("cargar_estado_5");
 	   //location.href = "pedidosv2.php#step5";
 	}
 	
  function generar_pdf(imagen1,imagen2) {
	 var form = document.forms['frmPrincipal'];
	 form['imagen1'].value = imagen1;
	 form['imagen2'].value = imagen2;
	 form.action = "crear_pdf.php";
	 form.target = "_blank";
	 form['accion'].value = 'generar';
 	 form.submit();
  }
 
  function seleccionar_todo(idx) {
    
    // campo todos
    var campo_all = "selck"+idx;
    var chk_campo_all =  document.getElementById(campo_all);
 		
 		// select all
 		
 		if(chk_campo_all.checked == true) {
    	var chk_arr =  document.getElementsByName("arrSeleccion"+idx+"[]");
			var chklength = chk_arr.length;   
			var chk_arr_seleccionados = [];          
 			 for(k=0; k <= chklength;k++) {
				var campo = "#arrSeleccion"+idx+k;
		  	$(campo).prop("checked", true);
		 	 }   
	  } else {
    	var chk_arr =  document.getElementsByName("arrSeleccion"+idx+"[]");
			var chklength = chk_arr.length;   
			var chk_arr_seleccionados = [];          
 			 for(k=0; k <= chklength;k++) {
				var campo = "#arrSeleccion"+idx+k;
		  	$(campo).prop("checked", false);
		 	 }   
	  }
  }
 
  function incidencia(estado) {
   
   if(estado == "1") {
	 		var chk_arr =  document.getElementsByName("arrSeleccion1[]");
 	 } else if(estado == "2") {
	 		var chk_arr =  document.getElementsByName("arrSeleccion2[]");
 	 } else if(estado == "3") {
	 		var chk_arr =  document.getElementsByName("arrSeleccion3[]");
 	 } else if(estado == "4") {
	 		var chk_arr =  document.getElementsByName("arrSeleccion4[]");
 	 } else if(estado == "5") {
	 		var chk_arr =  document.getElementsByName("arrSeleccion5[]");
 	 } else if(estado == "6") {
	 		var chk_arr =  document.getElementsByName("arrSeleccion6[]");
 	 } else if(estado == "7") {
	 		var chk_arr =  document.getElementsByName("arrSeleccion7[]");
 	 }
 	  
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
  		  window.open("ifr_incidencia.php?id_pedidos="+idx+"&estado="+estado, "incidencia", "height=600,width=700,scrollbars=1");
		}
 	} 
 	
 	function actualizar_foto(id_pedido, imagen) {
 		  window.open("ifr_imagen.php?id_pedido="+id_pedido+"&imagen="+imagen, "Actualizar Imagen", "height=600,width=700,scrollbars=1");
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
				<li><a href="javascript:;">Clientes</a></li>
				<li class="active">Listado de pedido</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Clientes <small> listado de pedidos realizados</small></h1>
			<!-- end page-header -->
			
		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_item">	
			<input type="hidden" name="pedidos_idx"  id="pedidos_idx">	
			<input type="hidden" name="imagen1">
			<input type="hidden" name="imagen2">		
			
			<?php if($accion == "papelera") { ?>
         <div class="alert alert-success fade in m-b-15">
								<strong>Pedido en Papelera</strong>
								El pedido ha sido enviado a la papelera
								<span class="close" data-dismiss="alert">&times;</span>
							</div>
			<?php } ?>
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
                            <h4 class="panel-title">Listado de Pedidos</h4>
                        </div>
                        <div class="panel-body">

														<div id="wizard">
															<ol>
																<li>
																    Ingresa Pedido&nbsp;&nbsp;(<?=($filas_todos_1) ? $filas_todos_1 : '0'?>)
																    <small>El vendedor esta en obligación de verificar la información que envía por el sistema</small>
																     
																</li>
																<li>
																    Pre-Despacho&nbsp;&nbsp;(<?=($filas_todos_2) ? $filas_todos_2 : '0'?>)
																    <small>Pedidos en el estado Pre-Despacho.</small>
																</li>
																<li class="active">
																    Agendar&nbsp;&nbsp;(<?=($filas_todos_3) ? $filas_todos_3 : '0'?>)
																    <small>Pedidos en el estado Agendar.</small>
																</li>
																<li>
																    Generación de Documentación&nbsp;&nbsp;(<?=($filas_todos_4) ? $filas_todos_4 : '0'?>)
																    <small>Pedidos en el estado Generación de Documentación.</small>
																</li>
																<li>
																    Despacho&nbsp;&nbsp;(<?=($filas_todos_5) ? $filas_todos_5 : '0'?>)
																    <small>Pedidos en el estado Despacho.</small>
																</li>
																<li>
																    Recepción Documentación&nbsp;&nbsp;(<?=($filas_todos_6) ? $filas_todos_6 : '0'?>)
																    <small>Pedidos en el estado Recepción Documentación.</small>
																</li>
																<li>
																    Entrega a Fábrica&nbsp;&nbsp;(<?=($filas_todos_7) ? $filas_todos_7 : '0'?>)
																    <small>Pedidos en el estado Entrega a Fábrica.</small>
																</li>
								
						
															</ol>
						
									<!-- begin wizard step-1 -->
									<!--+ filtros-->
					  		

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

                   									<select name="filtro_plataforma" id="filtro_plataforma" class="form-control">
						 													<option value="">-Tipo-</option>
						 													<option value="web" <?=($_POST['filtro_plataforma']=='web') ? 'selected' : ''?>>Web</option>
						 													<option value="app" <?=($_POST['filtro_plataforma']=='app') ? 'selected' : ''?>>App</option>

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
								  
									<!--- filtros-->
									<!-- end wizard step-1 -->
									<!-- begin wizard step-2 -->
									<div>
										<fieldset>
											<legend class="pull-left width-full">1. Ingresa Pedido </legend>

	                         <!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

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
                                            <th width="5%" align="center"><input type="checkbox" id="selck1" onClick="seleccionar_todo('1')"></th>
                                            <th width="20%">Estado</th>
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
							 												// ollas
							 												$result_ollas = $Pedido->obtener_pedido_ollas($items['id']);
																			$filas_ollas = @mysql_num_rows($result_ollas);
																	?>                                     	
                                     <?php if($items['id'] > 0) { ?>
                                       
                                        <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td><?=$items['cliente_dni']?></td>
                                            <td><?=$items['cliente_nombre']?></td>
                                            <td><?=$items['cliente_apellido']?></td>
                                            <td>
                                            	Cocina: <br> 
                                            	<strong><?=$items['modelo']?> <?=$items['marca']?> <?=$items['color']?></strong>
                                            		
                                            		<?php if($filas_ollas > 0) { ?>

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
                                            
	                                          <?php 
	                                           // se hace una validacion de que fisicamente existen
	                                           // las 3 imagenes para pasar al pre-despacho
																						 if(file_exists(FILE_PATH_FRONT_ADJ."/pedidos/".$items['imagen_factura_g']) && file_exists(FILE_PATH_FRONT_ADJ."/pedidos/".$items['imagen_dni_frente_g']) && file_exists(FILE_PATH_FRONT_ADJ."/pedidos/".$items['imagen_dni_posterior_g'])) { ?>
                                            	<input type="checkbox" id="arrSeleccion1<?=$k?>" name="arrSeleccion1[]" value="<?=$items['id']?>">
		                                         <?php } ?> 

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

																								<?php if(!file_exists("../adj/pedidos/".$items['imagen_factura_g'])) { ?>
			                                          	<button type="button" class="btn btn-danger" onClick="actualizar_foto('<?=$items['id']?>', 'imagen_factura')">Actualizar Foto Factura</button>
																								<?php } ?>

																								<div style="padding-top:2px"></div>

																								<?php if(!file_exists("../adj/pedidos/".$items['imagen_dni_frente_g'])) { ?>
			                                          	<button type="button" class="btn btn-danger" onClick="actualizar_foto('<?=$items['id']?>', 'imagen_dni_frente')">Actualizar Foto DNI Frente</button>
																								<?php }  ?>

																								<div style="padding-top:2px"></div>

																								<?php if(!file_exists("../adj/pedidos/".$items['imagen_dni_posterior_g'])) { ?>
			                                          	<button type="button" class="btn btn-danger" onClick="actualizar_foto('<?=$items['id']?>', 'imagen_dni_posterior')">Actualizar Foto DNI Posterior</button>
																								<?php } ?>
																						 	
                                            </td>
                                        </tr>
                                  <?php
                                     }
                                  } // f pedidos
                                  ?>     
                                         <tr>
                                          <td colspan="7"></td>
                                          <td colspan="2" align="right">
	                                          
		                                          <button type="button" class="btn btn-default m-r-5 m-b-5" onClick="estado_pedido_a_predespacho()">Pre-Despacho</button>
	                                          	<button type="button" class="btn btn-danger" onClick="incidencia('1')">Agregar Incidencia</button>

	                                        </td>
                                         </tr>
                                    </tbody>
                                </table>
                              </div>  
                            </div>
                           </div>
	                      	 <!-- end row -->
										</fieldset>
									</div>
									<!-- end wizard step-2 -->
									<!-- begin wizard step-3 -->
									<div>
										<fieldset>
											<legend class="pull-left width-full">2. Pre-Despacho</legend>

                           <!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="2%">Id</th>
                                            <th width="5%">Cedula Identidad</th>
                                            <th width="10%">Nombre</th>
                                            <th width="10%">Apellido</th>
                                            <th width="10%">Provincia</th>
                                            <th width="10%">Canton</th>
                                            <th width="15%">Transporte</th>
                                            <th width="5%" align="center"><input type="checkbox" id="selck2" onClick="seleccionar_todo('2')"></th>
                                            <th width="20%">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($k=1; $k <= $filas_todos_2; $k++) {
							 												$items = @mysql_fetch_array($result_todos_2);
																			$arrIncidencia = $Pedido->obtener_incidencia_by_estadov2($items['id'],2);
                                      $tooltip = "";
																			if(strlen($arrIncidencia['contenido']) > 0) {
																					$tooltip =  $arrIncidencia['contenido'];
																			}
																	?>                                     	
                                        <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td><?=$items['cliente_dni']?></td>
                                            <td><?=$items['cliente_nombre']?></td>
                                            <td><?=$items['cliente_apellido']?></td>
                                            <td><?=$items['cliente_provincia']?></td>
                                            <td><?=$items['cliente_canton']?></td>
                                            <td>
                                              <select name="transporte_<?=$items['id']?>" id="transporte_<?=$items['id']?>">
                                               <option value="Tramaco">Tramaco</option>
                                               <option value="Interno">Interno</option>
                                               <option value="Fabian">Fabian</option>
                                               <option value="Otro">Otro</option>
                                              </select>
                                             </td>
                                            <td><input type="checkbox" id="arrSeleccion2<?=$k?>" name="arrSeleccion2[]" value="<?=$items['id']?>"></td>
                                            <td>
                                              <span class="label label-primary">Pre-Despacho</span>
                                              

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
                                          <td colspan="7"></td>
                                          <td colspan="2" align="right">
                                          
                                          	<button type="button" class="btn btn-default btn-sm" onClick="estado_pedido()">1 Pedido</button> 
                                          	<button type="button" class="btn btn-default btn-sm" onClick="estado_agendar()">3 Agendar</button> 
                                          
	                                          | 
				 																	 <a href="javascript:bajar('2')" class="">
				 																	 	Bajar Excel
				 																	 </a>				 																	 
				 																	 |
				 																	 <a href="javascript:subir_excel()" class="estado">
				 																	 	Subir Excel
				 																	 </a>
			                                    
			                                     <button type="button" class="btn btn-danger" onClick="incidencia('2')">Agregar Incidencia</button>

                                          </td>
                                         </tr>
                                    </tbody>
                                </table>
                              </div>  
   
                            </div>
                           	 
                           	 
                           </div>
	                       <!-- end row -->
	
                      <!-- end row -->
                    </fieldset>
									</div>
									<!-- end wizard step-2 -->
									<!-- begin wizard step-3 -->
									<div>
										<fieldset>
											<legend class="pull-left width-full">3. Agendar</legend>

                           <!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

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
                                            <th width="5%" align="center"><input type="checkbox" id="selck3" onClick="seleccionar_todo('3')"></th>
                                            <th width="20%">Estado</th>
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
                                          	
                                         	<button type="button" class="btn btn-default btn-sm" onClick="estado_predespacho()">2 Pre-Despacho</button> 
                                         	<button type="button" class="btn btn-default btn-sm" onClick="estado_agendar_generacion()">4 Generacion de Documentacion</button> 
                                          <button type="button" class="btn btn-danger" onClick="incidencia('3')">Agregar Incidencia</button>

                                          <!--<button type="button" class="btn btn-default btn-sm" onClick="estado('1')">Pedido</button> -->
		
                                          </td>
                                         </tr>
                                    </tbody>
                                </table>
                              </div>  
   
                            </div>
                           	 
                           	 
                           </div>
	                       <!-- end row -->
	
                      <!-- end row -->
                    </fieldset>
									</div>
									<!-- end wizard step-4 -->
									<!-- begin wizard step-3 -->
									<div>
										<fieldset>
											<legend class="pull-left width-full">4. Generación de Documentación</legend>

                           <!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

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
		                                         	<button type="button" class="btn btn-default btn-sm" onClick="estado_documentacion()">3 Agendar</button> 
		                                         	<button type="button" class="btn btn-default btn-sm" onClick="estado_generacion_despacho()">5 Despacho</button> 

                                              <button type="button" class="btn btn-danger" onClick="incidencia('4')">Agregar Incidencia</button>
                                          </td>
                                       </tr>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
	                       <!-- end row -->
	
                      <!-- end row -->
                    </fieldset>
									</div>
									<!-- end wizard step-3 -->
									<!-- begin wizard step-3 -->
									<div>
										<fieldset>
											<legend class="pull-left width-full">5. Despacho</legend>

                           <!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

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
                                            <th width="5%" align="center"><input type="checkbox" id="selck5" onClick="seleccionar_todo('5')"></th>
                                            <th width="20%">Estado</th>
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
	  	                	                       <?php } ?>

																								<?php //- PDF // ?>
                                            </td>
                                        </tr>
                                  <?php
                                  } // f pedidos
                                  ?>     
                                         <tr>
                                          <td colspan="4"></td>
                                          <td colspan="5" align="right">
                                           <button type="button" class="btn btn-default btn-sm" onClick="estado_despacho_documentacion('4')">4 Generación Documentacion</button> 
                                           <button type="button" class="btn btn-default btn-sm" onClick="estado_despacho_recepcion('6')">6 Recepcion de Documentacion</button> 
                                          
                                           <a href="javascript:bajar_5('5')" class="">
				 																	 	Bajar Excel
				 																	 </a>				 																	 
                                              <button type="button" class="btn btn-danger" onClick="incidencia('5')">Agregar Incidencia</button>

                                          </td>
                                         </tr>
                                    </tbody>
                                </table>
                              </div>  
                            </div>
                         </div>
	                       <!-- end row -->
	
                      <!-- end row -->
                    </fieldset>
									</div>
									<!-- end wizard step-5 -->

									<!-- begin wizard step-6 -->
									<div>
										<fieldset>
											<legend class="pull-left width-full">6. Recepción de Documentación</legend>

                           <!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

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
                                            <th width="5%" align="center"><input type="checkbox" id="selck6" onClick="seleccionar_todo('6')"></th>
                                            <th width="15%">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($k=1; $k <= $filas_todos_6; $k++) {
						 												$items = @mysql_fetch_array($result_todos_6);
																			// busca la inicidencia del estado
																			$arrIncidencia = $Pedido->obtener_incidencia_by_estadov2($items['id'],6);
                                      $tooltip = "";
																			if(strlen($arrIncidencia['contenido']) > 0) {
																					$tooltip =  $arrIncidencia['contenido'];
																			}
																			
																	?>
																					<tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td>
                                            	<a href="javascript:recepcion_documentacion('<?=$items['id']?>')"><strong><?=$items['cliente_dni']?></strong></a>
                                            </td>
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
                                            <td><input type="checkbox" id="arrSeleccion6<?=$k?>" name="arrSeleccion6[]" value="<?=$items['id']?>"></td>
                                            <td>
                                              <span class="label label-primary">Recepción Documentación</span>

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
                                          <td colspan="4"></td>
                                          <td colspan="5" align="right">
	                                          <button type="button" class="btn btn-default btn-sm" onClick="estado_recepcion_despacho('5')">5 Despacho</button> 
	                                          <button type="button" class="btn btn-default btn-sm" onClick="estado_recepcion_entrega('7')">7 Entrega Fabrica</button> 
	                                          <button type="button" class="btn btn-danger" onClick="incidencia('6')">Agregar Incidencia</button>
                                          </td>
                                         </tr>
                                    </tbody>
                                </table>
                              </div>  
   
                            </div>
                           	 
                           	 
                           </div>
	                       <!-- end row -->
	
                      <!-- end row -->
                    </fieldset>
									</div>
									<!-- end wizard step-4 -->
									<div>
										<fieldset>
											<legend class="pull-left width-full">7. Entrega a Fábrica</legend>

                           <!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

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
                                            <th width="5%" align="center"><input type="checkbox"></th>
                                            <th width="15%">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($k=1; $k <= $filas_todos_7; $k++) {
						 												$items = @mysql_fetch_array($result_todos_7);
						 														// busca la inicidencia del estado
																			$arrIncidencia = $Pedido->obtener_incidencia_by_estado($items['id'],7);
																			$incidencia = null;
																			if(strlen($arrIncidencia['contenido']) > 0) {
																					$incidencia = "Incidencia: " . $arrIncidencia['contenido'];
																			}
																			$tooltip =  "\n\n".$incidencia;
																	?>                                     	
                                        <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td>
                                            	<a href="javascript:entrega_fabrica('<?=$items['id']?>')"><strong><?=$items['cliente_dni']?></strong></a></td>
                                            </td>
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
                                            <td><input type="checkbox" id="arrSeleccion7<?=$k?>" name="arrSeleccion7[]" value="<?=$items['id']?>"></td>
                                            <td>
                                              <span class="label label-primary">Despacho</span>
                                              <div style="padding-left:25px;display:inline">
                                              	<?php if(strlen($tooltip) > 3) {?>
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
                                          <td colspan="4"></td>
                                          <td colspan="5" align="right">
  	                                        <button type="button" class="btn btn-default btn-sm" onClick="estado_entrega_recepcion()">6 Recepcion de Documentacion</button> 
	                                          <button type="button" class="btn btn-danger" onClick="incidencia('7')">Agregar Incidencia</button>
                                          </td>
                                         </tr>
                                    </tbody>
                                </table>
                             </div>  
                           </div>
                        </div>
                      <!-- end row -->
                      <!-- end row -->
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
        <div class="theme-panel">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <h5 class="m-t-0">Color Theme</h5>
                <ul class="theme-list clearfix">
                    <li class="active"><a href="javascript:;" class="bg-green" data-theme="default" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Default">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-red" data-theme="red" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Red">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-blue" data-theme="blue" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Blue">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-purple" data-theme="purple" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Purple">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-orange" data-theme="orange" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Orange">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-black" data-theme="black" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Black">&nbsp;</a></li>
                </ul>
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Header Styling</div>
                    <div class="col-md-7">
                        <select name="header-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">inverse</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label">Header</div>
                    <div class="col-md-7">
                        <select name="header-fixed" class="form-control input-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Sidebar Styling</div>
                    <div class="col-md-7">
                        <select name="sidebar-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">grid</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label">Sidebar</div>
                    <div class="col-md-7">
                        <select name="sidebar-fixed" class="form-control input-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Sidebar Gradient</div>
                    <div class="col-md-7">
                        <select name="content-gradient" class="form-control input-sm">
                            <option value="1">disabled</option>
                            <option value="2">enabled</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Content Styling</div>
                    <div class="col-md-7">
                        <select name="content-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">black</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <a href="#" class="btn btn-inverse btn-block btn-sm" data-click="reset-local-storage"><i class="fa fa-refresh m-r-3"></i> Reset Local Storage</a>
                    </div>
                </div>
            </div>
        </div>
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
