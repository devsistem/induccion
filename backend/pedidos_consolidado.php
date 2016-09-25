<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Incidencia', 'Localizacion', 'Vendedor');
global $BackendUsuario, $Pedido, $Incidencia, $Localizacion, $Vendedor;

$BackendUsuario->EstaLogeadoBackend();


if(!$BackendUsuario->esGerenteVentas() && !$BackendUsuario->esGerenteLogistica() && !$BackendUsuario->esRoot()) { 
 die;
}	

// id del pedido q trae la alerta
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$id = ($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$e  = $_REQUEST['e'];
$filtro_id_vendedor = ($_REQUEST['filtro_id_vendedor']) ? $_REQUEST['filtro_id_vendedor'] : null;
$mes  = (!isset($_REQUEST['mes'])) ? null : $_REQUEST['mes'];
$anio = (!isset($_REQUEST['anio'])) ? null : $_REQUEST['anio'];


$arrVendedor = $Vendedor->obtener($filtro_id_vendedor);

if($e > 0) {  
	$result_todos = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, NULL, $destacado, $filtro_id_tipo, $filtro_id_categoria, $filtro_id, $filtro_id_vendedor, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio);
	$filas_todos = @mysql_num_rows($result_todos);
} else {
	$result_todos = $Pedido->obtener_all_baja($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, NULL, 1, $destacado, $filtro_id_tipo, $filtro_id_categoria, null, $filtro_id_vendedor, null, null, null, null, null, $filtro_cedula, $filtro_id_provincia, $filtro_nombre, $filtro_apellido, $filtro_id_vendedor, $con_incidencias_graves, $mes, $anio);
	$filas_todos = @mysql_num_rows($result_todos);
}
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Pedidos por vendedor - Consolidado -  Induccion</title>
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
				<li><a href="javascript:;">Pedidos</a></li>
				<li class="active">Listado de pedidos vendedor <strong><?=$arrVendedor['nombre']?> <?=$arrVendedor['apellido']?></strong></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Pedidos <small> listado de pedidos realizados por el vendedor</small></h1>
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
                            <h4 class="panel-title"><strong><?=$arrVendedor['nombre']?> <?=$arrVendedor['apellido']?></strong></h4>
                        </div>
                        <div class="panel-body">


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
                                            <th width="15%">Vendedor</th>
                                            <th width="15%">Estado</th>
                                            <th width="15%">Papelera</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($k=1; $k <= $filas_todos; $k++) {
						 												$items = @mysql_fetch_array($result_todos);
																			$arrIncidencia = $Pedido->obtener_incidencia_by_estadov2($items['id'],1);
                                      $tooltip = "";
																			if(strlen($arrIncidencia['contenido']) > 0) {
																					$tooltip =  $arrIncidencia['contenido'];
																			}
							 									?>                                     	
                                     <?php if($items['id'] > 0) { ?>
                                       
                                        <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td>
                                            
                                            <a href="javascript:detalle('<?=$items['id']?>')">
                                            	<?=$items['cliente_dni']?></a>
                                            </a>
                                            </td>
                                            <td><?=$items['cliente_nombre']?></td>
                                            <td><?=$items['cliente_apellido']?></td>
                                            <td>
                                            	Cocina: <br> 
                                            	<strong><?=$items['modelo']?> <?=$items['marca']?> <?=$items['color']?></strong>
                                            	
  
                                            	</td>
                                            <td>
                          									<?php if(strlen($items['fecha_mod']) > 11) { ?>
                                            		<?=GetFechaTexto($items['fecha_mod'])?>
	                                            <?php } else { ?>
	                                            	<?=GetFechaTexto($items['fecha_alta'])?>
                                              <?php } ?>
                                            	</td>
                                            	<td><?=$items['vendedor_nombre']?></td>
                                        
                                            <td>
                                            
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
                                              </span>

                                              <div style="padding-left:5px;display:inline">
                                              	<span class="label label-danger"><?=$arrIncidencia['nombre']?></span>
                                              	
                                              	<?php 
                                              	if(strlen($tooltip) > 5) {?>
                                              		<i class="fa fa-2x fa-info" border="0" data-toggle="tooltip" data-placement="top" data-original-title="Default tooltip" class="" style="" title="<?=$tooltip?>"></i>
                                              	<?php } ?>                                              
																								
																							
																						 	
                                            </td>
                                            <td>
                                              <?php if($items['activo'] == 0) { ?>
    	                                          <h4>SI</h4>
	                                            <?php } else { ?>
  	                                            <h4>NO</h4>
	                                             <?php } ?>
                                            </td>
                                        </tr>
                                  <?php
                                     }
                                  } // f pedidos
                                  ?>     
                                         <tr>
                                          <td colspan="7"></td>
                                          <td colspan="3" align="right">
	                                         
	                                        </td>
                                         </tr>
                                    </tbody>
                                </table>
                              </div>  
                            </div>
                           </div>
	                      	 <!-- end row -->
							
						
									
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
