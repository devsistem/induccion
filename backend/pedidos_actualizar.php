<?php
// pedidos_actualizar.php
// formulario de edicion de un pedido
// actualiza
// tabla pedidos
// 28/07/2016 12:56:25
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario','Producto','Categoria');
global $BackendUsuario,$Producto, $Categoria;

loadClasses('Pedido', 'Vendedor', 'Localizacion', 'Producto');
global $Pedido, $Vendedor, $Localizacion, $Producto;

$BackendUsuario->EstaLogeadoBackend();

/*
if(!$BackendUsuario->esta_logeado_vendedor()) {
 print "No existen permisos de visualizacion";
 header("Location: acceso.php");
 die;
}
*/

$action = (!isset($_POST['action'])) ? null : $_POST['action'];	
$id = (!isset($_POST['id'])) ? null : $_POST['id'];	 // id del pedido

// valores
$errores = 0;
$str_errors = "";

// configuracion del form

// provincias
$result_provincias = $Localizacion->obtener_provincias(1, ACTIVO, null, null, null);
$filas_provincias = @mysql_num_rows($result_provincias);

// productos
$result_productos = $Producto->obtener_all(null, null, null,null,null,null,ACTIVO,null,null,null,null);
$filas_productos = @mysql_num_rows($result_productos);

// modelos cocina
$result_modelos_cocina = $Producto->obtener_modelos( ACTIVO, 'cocina');
$filas_modelos_cocina = @mysql_num_rows($result_modelos_cocina);

// modelos color
$result_modelos_color = $Producto->obtener_colores( ACTIVO, null);
$filas_modelos_color = @mysql_num_rows($result_modelos_color);

// modelos marca
$result_modelos_marca = $Producto->obtener_marcas( ACTIVO, 'cocina');
$filas_modelos_marca = @mysql_num_rows($result_modelos_marca);

// editar el pedido
$item = $Pedido->obtener($id);

switch($action) {
	
	 case 'editar':
	 
		// datos
		$cuen = escapeSQLTags($_POST['registro']['cuen']);
		$cedula = escapeSQLTags($_POST['registro']['dni']);
		$nombre = escapeSQLTags($_POST['registro']['nombre']);

		$cliente_telefono = escapeSQLTags($_POST['registro']['cliente_telefono']);
		$cliente_celular = escapeSQLTags($_POST['registro']['cliente_celular']);
		
		/*
		//  dueño
		$imagen_factura = escapeSQLTags($_POST['imagen_factura']);	
		$imagen_dni_frente = escapeSQLTags($_POST['imagen_dni_frente']);	
		$imagen_dni_posterior = escapeSQLTags($_POST['imagen_dni_posterior']);

		//  alquila
		$imagen_dni_duenio_frente = escapeSQLTags($_POST['imagen_dni_duenio_frente']);	
		$imagen_dni_duenio_posterior = escapeSQLTags($_POST['imagen_dni_duenio_posterior']);
		$imagen_dni_duenio_garante = escapeSQLTags($_POST['imagen_dni_duenio_garante']);		
	  	*/  		 
		// errores de campos
		
		if($item['cliente_cuen'] != $cuen) {	
    	if(strlen($cuen) !=  10 ) {
			 $str_errors  .= "El CUEN ingresado no es válido<br/>";
			 $css_cuen = "error";
			 $errores++;
		  }
		}		 
	
		
		if(strlen($cedula) !=  10 ) {
			 $str_errors  .= "Cedula no es valida";
			 $css_cedula = "error";
			 $errores++;
		}
		
		
		 
	   /*
	   // imagenes
	   if(strlen($imagen_factura) ==  0 ) {
			 $str_errors  .= "Debe adjuntar una imagen de la factura<br/>";
			 $css_imagen_factura = "error_div";
			 $errores++;
		 }	

     if(strlen($imagen_dni_frente) ==  0 ) {
			 $str_errors  .= "Debe adjuntar una imagen de la cedula<br/>";
			 $css_imagen_dni_frente = "error_div";
			 $errores++;
		 }	

     if(strlen($imagen_dni_posterior) ==  0 ) {
			 $str_errors  .= "Debe adjuntar una imagen posterior<br/>";
			 $css_imagen_dni_posterior = "error_div";
			 $errores++;
		 }
		 */
		 
		 // un telefono
     if(strlen($cliente_telefono) ==  0  && strlen($cliente_celular) ==  0) {
			 $css_telefonocelular = "error";
			 $errores++;
		 }

		 // si no selecciono contacto, valida cuotas
		 
		 $forma_pago = escapeSQLTags($_POST['registro']['forma_pago']);
		 $cuotas = escapeSQLTags($_POST['registro']['cuotas']);
		 
		 if($forma_pago == "CREDITO DEL ESTADO" || $forma_pago == "TARJETA DE CREDITO") {
		       if(empty($cuotas)) {
					  $str_errors  .= "Debe seleccionar los plazos de pago<br/>";
						$css_imagen_cuotas = "color:#D20000";
			 			$errores++;
					 }
		 }
		 
		 /*
		 $existe = $Pedido->dni_existe($cedula);

     if($existe >  0 ) {
			 $str_existe_cedula  .= "Ya existe un pedido con esa Cedula<br/>";
			 $css_existe_cedula = "error";
			 $errores++;
		 }
		 */   		 

			/*
		 $existe_cuen = $Pedido->cuen_existe($cuen);

     if($existe_cuen >  0 ) {
			 $str_existe_cuen  .= "Ya existe un pedido con es CUEN<br/>";
			 $css_existe_cuen = "error";
			 $errores++;
		 }
		 */

		 if($errores == 0) {
			 
			 // todas las validaciones ok, graba
			 $last_id = $Pedido->editar($id, $_POST);
	     $last_id = $id;
	     //die;

		 	 $fecha_post = time();

			 if($last_id > 0) {
			 	
				 $accion  = 'grabado';
				 
				 // se envian los mails
				 //$Pedido->enviar_pedido_admin($last_id);
				 //$Pedido->grabar_alerta($last_id);
				 //$Pedido->enviar_pedido_vendedor($last_id);
				 //$Pedido->enviar_pedido_cliente($last_id);
			 }
		 }	 
	 
	break;
 }
 
$fecha_actual = date("Y/m/d H:i");  

include("meta.php");
?>

<link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
  	
<script type="text/javascript">
      var cantidad_ollas = 0;
      function es_cedula_valida() { 
			 var errores = 0;	  	 
	  	 var cedula = $("#registro_dni" ).val(); 

	  	 //Preguntamos si la cedula consta de 10 digitos
  	   if(cedula.length == 10){
        
        //Obtenemos el digito de la region que sonlos dos primeros digitos
        var digito_region = cedula.substring(0,2);
        
        //Pregunto si la region existe ecuador se divide en 24 regiones
        if( digito_region >= 1 && digito_region <=24 ){
          
          // Extraigo el ultimo digito
          var ultimo_digito   = cedula.substring(9,10);

          //Agrupo todos los pares y los sumo
          var pares = parseInt(cedula.substring(1,2)) + parseInt(cedula.substring(3,4)) + parseInt(cedula.substring(5,6)) + parseInt(cedula.substring(7,8));

          //Agrupo los impares, los multiplico por un factor de 2, si la resultante es > que 9 le restamos el 9 a la resultante
          var numero1 = cedula.substring(0,1);
          var numero1 = (numero1 * 2);
          if( numero1 > 9 ){ var numero1 = (numero1 - 9); }

          var numero3 = cedula.substring(2,3);
          var numero3 = (numero3 * 2);
          if( numero3 > 9 ){ var numero3 = (numero3 - 9); }

          var numero5 = cedula.substring(4,5);
          var numero5 = (numero5 * 2);
          if( numero5 > 9 ){ var numero5 = (numero5 - 9); }

          var numero7 = cedula.substring(6,7);
          var numero7 = (numero7 * 2);
          if( numero7 > 9 ){ var numero7 = (numero7 - 9); }

          var numero9 = cedula.substring(8,9);
          var numero9 = (numero9 * 2);
          if( numero9 > 9 ){ var numero9 = (numero9 - 9); }

          var impares = numero1 + numero3 + numero5 + numero7 + numero9;

          //Suma total
          var suma_total = (pares + impares);

          //extraemos el primero digito
          var primer_digito_suma = String(suma_total).substring(0,1);

          //Obtenemos la decena inmediata
          var decena = (parseInt(primer_digito_suma) + 1)  * 10;

          //Obtenemos la resta de la decena inmediata - la suma_total esto nos da el digito validador
          var digito_validador = decena - suma_total;

          //Si el digito validador es = a 10 toma el valor de 0
          if(digito_validador == 10)
            var digito_validador = 0;

          //Validamos que el digito validador sea igual al de la cedula
          if(digito_validador == ultimo_digito){
            //console.log('la cedula:' + cedula + ' es correcta');
          }else{
          	errores++;
            //console.log('la cedula:' + cedula + ' es incorrecta');
          }
          
        }else{
          // imprimimos en consola si la region no pertenece
         	errores++;
          //console.log('Esta cedula no pertenece a ninguna region');
        }
     }else{
        //imprimimos en consola si la cedula tiene mas o menos de 10 digitos
       	errores++;
        //console.log('Esta cedula tiene menos de 10 Digitos');
     }
     
     if(errores > 0) {
    	  $("#cedula_error" ).html("El formato ingresado no es valido"); 
    	  $("#cedula_label" ).css('color', 'red'); 
   	 } else {
    	  $("#cedula_error" ).html(""); 
    	  $("#cedula_label" ).css('color', 'black'); 
   	 }
    } 
    
    function es_cuen_valida() {
    
    }
</script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">
  	 // generico
		var ECUADOR_LATITUD  = -1.7864639;
		var ECUADOR_LONGITUD = -78.1368874;
		var iconLugares =  'http://www.induccion.ec/backend/frontend/cocinas/images/maps/icon_lugar.png';
		var map;
		var geocoder;
		
		function initialize() {
			
		 var form = document.forms['frm_dashboard_mapa'];
		 var lat = form['latitude'].value;
		 var lon = form['longitude'].value;
		 
		 // tratar de localizar
		 // privicnia
		 var item_id_localidad = document.getElementById("registro_id_provincia").value;
		 var item_id_canton = document.getElementById("registro_id_canton").value;
		 

		 if(lat.length >=8 && lon.length  >= 8  ) {
		 	var puntoInicial = new google.maps.LatLng(lat,lon);
		 } else {
			var puntoInicial = new google.maps.LatLng(ECUADOR_LATITUD,ECUADOR_LONGITUD);
		 }
		
		 var myOptions = {
		  zoom: 10,
		  center: puntoInicial,
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		 }
		
		 map = new google.maps.Map(document.getElementById("map"), myOptions);
		
		   marker = new google.maps.Marker({
		         map:map,
		         draggable:true,
		         animation: google.maps.Animation.DROP,
		         position: puntoInicial,
		         icon:iconLugares
		       });
		
		// agregar el marker
		 marker.setMap(map);
		 marker.setDraggable(true);
		 
		 google.maps.event.addListener(marker, "drag", function(event) {
		 	var point = marker.getPosition();
		  var lat = point.lat();
			var lon = point.lng();
		
		  // actualizar el form
		  form['latitude'].value = lat;
		  form['longitude'].value = lon;
		  form['zoom'].value = map.getZoom();
		
		 });
		 
		} // fin f		

		function centrar_ciudad() {
  		geocoder = new google.maps.Geocoder();  
  		var address = '100 Murray St, Pyrmont NSW, Australia';   
  		geocoder.geocode( { 'address': address}, function(results, status) {
  	 	 if (status == google.maps.GeocoderStatus.OK) {
    	  map.setCenter(results[0].geometry.location);        
    	} else {
      alert('Geocode was not successful for the following reason: ' + status);
    	}
  	 });
	  }

		function addPropertyToMapByAddress() {
		//alert("En addPropertyToMapByAddress()");
		var element = document.getElementById("item_direccion");
		var ciudad_mapa =  document.frm_dashboard_mapa.ciudad_mapa.value;
		var pais_mapa =  document.frm_dashboard_mapa.pais_mapa.value;
		if ( element != null )	{
			var address = element.value;
			address = address + ', ' + ciudad_mapa  + ', ' + pais_mapa;
		  // Create new geocoding object
			geocoder = new GClientGeocoder();
			// Retrieve location information, pass it to addToMap()
			geocoder.getLocations(address, addPropertyToMapByAddressCallback);
		}
	}

	function provincias(id_provincia,id_canton) {
		  $("#divcantones").text("");
		  var id_provincia = $("#registro_id_provincia").val();
		  var _url =  'ax_cantones_json.php?id_provincia='+id_provincia;
      $("#divcantonestxt").text("Cargando...");	
			$.post(_url,function(result){
			  dataItem = $.parseJSON(result);
			 	var html = "";
			 	html += '<select class="select_cocina" data-size="10" data-live-search="true" data-style="btn-white" name="registro[id_canton]" style="width:160px" id="registro_id_canton">';
			 	 html += ' <option value="0">Sin Especificar</option>';
			 	for(i=0; i < dataItem['cantidad'][0]; i++) {

				 	 // marca el canton seleccionado
				 	 var canton_select = (dataItem['id'][i]==id_canton) ? 'selected' : '';
	  			 html += ' <option value="'+dataItem['id'][i]+'" '+canton_select+'>'+dataItem['nombre'][i]+'</option>';
			  }
			 	html += '</select>';
			 	
	      $("#divcantonestxt").text("");	
		  	$("#divcantones").append(html);	 	
			});
	}
	
	function es_duenio() {
		var es = $('#registro_duenio').is(':checked');
		if(es == true) {
			$("#divfotosalquiler").hide();
		} else {
			$("#divfotosalquiler").show();
		}
	}

	function modelos(id_marca,id_modelo) {
		  $("#divmodelos").text("");
		  var id_marca = $("#registro_marca").val();
		  var _url =  'ax_modelos_json.php?id_marca='+id_marca;
      
      $("#divmodelostxt").text("Cargando...");	
			$.post(_url,function(result){

			  dataItem = $.parseJSON(result);
			 	var html = "";
			 	html += '<select class="select_cocina"  name="registro[modelo]" style="width:160px" id="registro_id_modelo">';
			 	for(i=0; i < dataItem['cantidad'][0]; i++) {
				 	 var modelo_select = (dataItem['id'][i]==id_modelo) ? 'selected' : '';
	  			 html += ' <option value="'+dataItem['id'][i]+'" '+modelo_select+'>'+dataItem['nombre'][i]+'</option>';
			  }
			 	html += '</select>';
			 	
	      $("#registro_modelo_lectura").hide();
	      $("#divmodelostxt").text("");	
		  	$("#divmodelos").append(html);	 	
			});
	}	
</script>

<style>
.error { color:#D70000}
.error_div { color:#D70000; border:1px solid #D70000;	}
.container { 
	width: 900px;
  border:1px solid #d8d8d8;	
  padding:10px;
   background-color: #ffffff;
}

.container_ { 
	width: 900px;
  border:1px solid #d8d8d8;	
  background-color: #ffffff;
}
.cargando {
 font: normal 10px Arial, Tahoma,Verdana, Arial; 
 color:#0054A8 
 font-weight: normal;
}
#cedula_error {
 font: 10px Arial, Tahoma,Verdana, Arial;
 color:#D70000;
 font-weight: normal;
}
.vendedor {
 color:#004080;
}
.sep {
	 border:1px solid #d8d8d8;	
	 margin-bottom:10px;
}
.texto_bienvenido {
 font: 28px Verdana, Arial;
 color:#EB2D7C;
 font-weight: normal; 
}
.texto_registro {
 font: 26px Verdana, Arial;
 color:#90288D;
 font-weight: normal;
}

.texto_exp {
 font: 16px Verdana, Arial;
 color:#3A3A44;
 font-weight: normal;
}

.texto_exp14 {
 font: 14px Verdana, Arial;
 color:#3A3A44;
 font-weight: normal;
}
.texto_exp12 {
 font: 12px Verdana, Arial;
 color:#737373;
 font-weight: normal;
}
.campo_check {
 height:40px
}
.texto_cuen {
 font: 26px Verdana, Arial;
 color:#90288D;
 font-weight: normal;
}

.titulo_form {
 color:#737373;
 font-weight: normal
 font: 12px Verdana, Arial;
}

.titulo_form_24 {
 color:#737373;
 font-weight: normal
 font: 21px Verdana, Arial;
}

.select_cocina {
 width:160px;
 height:40px;
}

.select_horario {
 background-color: #737373;
 font: 14px Verdana, Arial; 
 color: #ffffff; 
 width:330px;
 height:40px;
}

.boton_color {
 background-color: #EB2D7C;
 font: 16px Arial;
 color: #ffffff; 
}
.exp {
 font: 11px Arial;
}
</style>

</head>

<body onLoad="initialize();provincias('<?=$item['id_provincia']?>','<?=$item['id_canton']?>');modelos('<?=$item['id_marca']?>','<?=$item['id_modelo']?>')">
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
				<li><a href="javascript:;">Portada</a></li>
				<li><a href="javascript:;">Pedidos</a></li>
				<li class="active">Nuevo Pedido</li>
			</ol>

<!-- begin row -->
			<div class="row">
                <!-- begin col-6 -->
			    <div class="col-md-9">
			        <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Nuevo Registro de venta</h4>
                        </div>
                        <div class="panel-body">
                           
                            	
						<table width="100%" align="left" cellspacing="10" cellpadding="10">
						 <tr>
						 <td   bgcolor="#ffffff">			
									
						  <!-- begin row -->
							<div style="padding:20px">	
							
						  <h3 align="right" class="texto_bienvenido">
								<span class="texto_registro">Registro de Venta</span>
						  </h3>
						
							<div style="clear:both"></div><br/><br/>
						
						<?php 
						if($accion  != 'grabado') { ?>	
							<span class="texto_exp">
							 <font color="#F46180">
							 	Antes</font> de registrar la venta por favor verificar si
							  el cliente puede acceder al Plan de Gobierno.
							  <br>
							  En el caso de tener un problema, solucionarlo antes con el cliente y subir la venta.
							</span>
						
						<?php } ?>
							
							<div style="clear:both"></div><br/><br/>
						
						
						<?php		if(!$last_id && $accion != 'grabado' && $errores > 0) { ?>
								
											<?php if(strlen($str_errors) > 0) { ?>
												<div class="error"><?=$str_errors?></div>
							  			<?php }?>
						
						
										<?php if(strlen($str_existe_cuen) > 0) { ?>
												<div class="error"><?=$str_existe_cuen?></div>
							  			<?php }?>
							  			
						<?php  } ?>	
						
										
						<?php 
							   		if($last_id > 0 && $accion  == 'grabado') { ?>
								
											<div><h2>Pedido Actualizado. </h2></div>
											<div><h3>Su número de referencia es # <?=$id?></h3></div>
								
						<?php   } else { ?>


     <form name="frm_dashboard_mapa" id="frm_dashboard_mapa" enctype="multipart/form-data" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">
      <input type="hidden" name="id" value="<?=$id?>">
      <input type="hidden" name="zoom_inicial" value="8">		
      <input type="hidden" name="dragable" id="dragable" value="true">		
      <input type="hidden" name="modo" value="enviar">	
      <input type="hidden" id="ciudad_mapa" name="ciudad_mapa" value="Quito">	
      <input type="hidden" id="pais_mapa" name="pais_mapa" value="Ecuador">	
			<input type="hidden" name="action" value="editar" />
			<input type="hidden" name="postID" value="<?=md5(uniqid(rand(), true))?>">

			<input type="hidden" id="imagen_factura" name="imagen_factura">		
			<input type="hidden" id="imagen_dni_frente" name="imagen_dni_frente">		
			<input type="hidden" id="imagen_dni_posterior" name="imagen_dni_posterior">		

			<input type="hidden" id="imagen_duenio_garante" name="imagen_duenio_garante">		
			<input type="hidden" id="imagen_dni_duenio_frente" name="imagen_dni_duenio_frente">		
			<input type="hidden" id="imagen_dni_duenio_posterior" name="imagen_dni_duenio_posterior">		

		 <div style="padding:20px">
		  <div class="control-group" align="right">
				<span class="texto_exp"> <strong>Si es dueño del suministro marcar</span></label>
				 <?php if(empty($_POST)) {?>
					<input type="checkbox" name="duenio" id="registro_duenio" value="1" onClick="es_duenio();"  <?=($_POST['duenio'] == 1) ? 'checked' : ''?> checked="checked" />
				 <?php } else { ?>
					<input type="checkbox" name="duenio" id="registro_duenio" value="1" onClick="es_duenio();"  <?=($_POST['duenio'] == 1) ? 'checked' : ''?> />
				 <?php } ?>
					
				<input required="required" class="texto_cuen" id="registro_cuen" placeholder="" name="registro[cuen]" style="width:20%"  maxlength="10" type="text" value="<?=$item['cliente_cuen']?>" onBlur="es_cuen_valida();">
				<span id="cuen_error"></span>
			</div>
		 </div>	
		
		 <hr size="2" color="#737373">			
			
			<table width="100%" border="0">
			 <tr>
			  <td width="50%" valign="top">
			   
			    <table cellpadding="10" cellspacing="5">
					<tr>
			      <td  align="right" >				
			      	<span class="titulo_form <?=$css_fecha_venta?>" id="cedula_label"><strong>FECHA VENTA</strong></span>
					  </td>
			      <td style="padding-left:10px">
							<div class="form-group">
				        <div class="input-group date">
                   <input type="text" class="form-control" name="fecha_venta" id="datetimepicker1" style=""/>
                   <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                </div>
              </div>
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>
			     <tr>
			      <td width="50%" align="right">				
			      	<span class="titulo_form" id="cedula_label"><strong>#Id </strong></span>
					  </td>
			      <td width="50%" style="padding-left:10px">
							<?=$item['id']?>
								      
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>			     
			     <tr>
			      <td width="50%" align="right">				
			      	<span class="titulo_form" id="cedula_label"><strong>Estado </strong></span>
					  </td>
			      <td width="50%" style="padding-left:10px">
							<?php
							$arrEstado = $Pedido->obtener_estado($item['estado']);
							print $arrEstado['nombre'];
							?>
			      </td>

					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>	
					 			     </tr>

			     <tr>
			      <td width="50%" align="right">				
			      	<span class="titulo_form <?=$css_cedula?> <?=$css_existe_cedula?>" id="cedula_label"><strong>Cédula de Ciudadania</strong> <span class="required">(*)</span></span>
					  </td>
			      <td width="50%" style="padding-left:10px">
							<input required="required" class="form-control" style="width:100%" id="registro_dni" name="registro[dni]" style="width:20%"  maxlength="15" type="text" value="<?=$item['cliente_dni']?>">
							<span id="cedula_error"></span>			      
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>
					<tr>
			      <td  align="right" >				
			      	<span class="titulo_form <?=$css_nombre?>" id="cedula_label"><strong>Nombres</strong> <span class="required">(*)</span></span>
					  </td>
			      <td style="padding-left:10px">
							<input required="required" class="form-control" id="register_name" name="registro[nombre]" style="width:100%"  maxlength="150" type="text" value="<?=$item['cliente_nombre']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>
					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_apellido?>" id="cedula_label"><strong>Apellidos</strong> <span class="required">(*)</span></span>
					  </td>
			      <td style="padding-left:10px">
							<input required="required" class="form-control" id="register_name" name="registro[apellido]" style="width:100%"  maxlength="150" type="text" value="<?=$item['cliente_apellido']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_telefono?>" id=""><strong>Teléfono</strong> <span class="required"></span></span>
					  </td>
			      <td style="padding-left:10px">
							<input  class="form-control" id="registro_telefono" name="registro[cliente_telefono]" style="width:100%" type="text" maxlength="30" value="<?=$item['cliente_telefono']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"> </td>
					 </tr>

				  <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_celular?>" id=""><strong>Celular</strong></span>
					  </td>
			      <td style="padding-left:10px">
     					<input  class="form-control" id="registro_cliente_celular" name="registro[cliente_celular]" style="width:100%" type="text"  maxlength="30" value="<?=$item['cliente_celular']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height="10"><span class="exp  <?=$css_telefonocelular?>">Un teléfono o celular debe ser ingresado</span></td>
					 </tr>
				  <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_email?>" id=""><strong>Correo</strong></span>
					  </td>
			      <td style="padding-left:10px">
							<input class="form-control" id="registro_email" name="registro[email]"  type="email" style="width:100%"  maxlength="30" value="<?=$item['cliente_email']?>">
			      </td>
			     </tr>
			     			     
			    </table>
			  
			  
			  </td>
			  
			  <td width="50%" valign="top">
			   <?php /*
			   		<div style="clear:both"></div>
			   
			      <span class="texto_exp12">
			      	Las fotos sólo deben ser escaneadas
			        o tomadas en la mejor resolución, sin espacios en blanco.
			        NO se deberá subir una hoja escaneada completamente.
			      </span>


						<div style="clear:both"></div><br/>

												      
				      <div id="divfotosduenio">
			      	<?php // Fotos 1/6  // ?>
								<div class="control-group size1 <?=$css_imagen_factura?>">
								<iframe id="imagen_factura" src="uploadlib/index.php?img=imagen_factura" frameBorder="0" style="padding:0px;width:100%;height:70px" border="0"></iframe>
							</div>

							<?php // Fotos 2/6 // ?>
							<div class="control-group size1  <?=$css_imagen_dni_frente?>">
								<iframe id="imagen_dni_frente" src="uploadlib/index.php?img=imagen_dni_frente" frameBorder="0" style="padding:0px;width:100%;height:70px" border="0"></iframe>
							</div>

							<?php // Fotos 3/6 // ?>
							<div class="control-group size1 <?=$css_imagen_dni_posterior?>">
								<iframe id="imagen_dni_posterior" src="uploadlib/index.php?img=imagen_dni_posterior" frameBorder="0" style="padding:0px;width:100%;height:70px" border="0"></iframe>
							</div>
				      </div>

				      <div id="divfotosalquiler" style="display:none">
			      	<?php // Fotos 4/6  // ?>
								<div class="control-group size1 <?=$css_imagen_dni_duenio_frente?>">
								<iframe id="imagen_dni_duenio_frente" src="uploadlib/index.php?img=imagen_dni_duenio_frente" frameBorder="0" style="padding:0px;width:100%;height:70px" border="0"></iframe>
							</div>

							<?php // Fotos 5/6 // ?>
							<div class="control-group size1  <?=$css_imagen_dni_duenio_posterior?>">
								<iframe id="imagen_dni_duenio_posterior" src="uploadlib/index.php?img=imagen_dni_duenio_posterior" frameBorder="0" style="padding:0px;width:100%;height:70px" border="0"></iframe>
							</div>

							<?php // Fotos 7/7 // ?>
							<div class="control-group size1 <?=$css_imagen_duenio_garante?>">
								<iframe id="imagen_duenio_garante" src="uploadlib/index.php?img=imagen_duenio_garante" frameBorder="0" style="padding:0px;width:100%;height:70px" border="0"></iframe>
							</div>
				      </div>
			  <?php */ ?>
			  </td>
			 </tr>
			</table>
			
 		  <hr size="2" color="#737373">			

			<table width="100%" border="0">
			 <tr>
			  <td width="48%" valign="top">
			   
			   
			    <table cellpadding="10">
			     <tr>
			      <td width="50%" align="right">				
			      	<span class="titulo_form <?=$css_id_provincia?>" id="cedula_label"><strong>Provincia</strong> <span class="required">(*)</span></span>
					  </td>
			      <td width="50%" style="padding-left:10px">
							
							<select  id="registro_id_provincia" required="true" class="select_cocina" data-size="10" data-live-search="true" data-style="btn-white" style="width:160px" name="registro[id_provincia]" onChange="provincias()">
						 	<option value="">-Seleccionar-</option>
							<?php // PROVINCIAS
								for($i=0; $i < $filas_provincias; $i++) {
									$items_provincias = @mysql_fetch_array($result_provincias); ?>						 	
							 		 <option value="<?=$items_provincias['id']?>"  <?=($item['id_provincia']==$items_provincias['id']) ? 'selected' : ''?>><?=$items_provincias['nombre']?></option>
							<?php } ?>  
							</select>
				
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

					<tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_id_canton?>" id="cedula_label"><strong>Canton</strong> <span class="required">(*)</span></span>
					  </td>
			      <td style="padding-left:10px">
							<div id="divcantones"></div>
							<div id="divcantonestxt" class="cargando"></div>
							<input type="hidden" id="registro_id_canton" />
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_parroquia?>" id=""><strong>Parroquia</strong> <span class="required">(*)</span></span>
					  </td>
			      <td style="padding-left:10px">
							<input  class="form-control" id="register_name" name="registro[parroquia]" style="width:90%"  maxlength="150" type="text" value="<?=$item['parroquia']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_barrio?>" id=""><strong>Barrio/Urbanización</strong> </span>
					  </td>
			      <td style="padding-left:10px">
						<input class="form-control" id="registro_barrio" name="registro[barrio]" style="width:90%"   maxlength="150" type="text" value="<?=$item['barrio']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

				  <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_calle_principal?>" id=""><strong>Calle Principal</strong></span>
					  </td>
			      <td style="padding-left:10px">
							<input class="form-control" id="registro_cliente_calle" name="registro[cliente_calle]" style="width:90%" maxlength="150" type="text" value="<?=$item['cliente_calle']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

				  <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_cliente_calle_numero?>" id=""><strong>Número</strong></span>
					  </td>
			      <td style="padding-left:10px">
							<input class="form-control" id="registro_cliente_calle_numero" name="registro[cliente_calle_numero]" style="width:90%"   maxlength="50" type="text" value="<?=$item['cliente_calle_numero']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

		 				 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_cliente_calle_secundaria?>" id=""><strong>Calle Secundaria</strong></span>
					  </td>
			      <td style="padding-left:10px">
							<input class="form-control" id="registro_cliente_calle_secundaria" name="registro[cliente_calle_secundaria]" style="width:90%"   maxlength="150" type="text" value="<?=$item['cliente_calle_secundaria']?>">
				      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>
			     
			     <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_cliente_referencia?>" id=""><strong>Referencia</strong></span>
					  </td>
			      <td style="padding-left:10px">
							<textarea  class="form-control" id="registro_cliente_referencia" name="registro[cliente_referencia]" maxlength="250" style="width:90%"> <?=$item['cliente_referencia']?></textarea>
			      </td>
			     </tr>
			    </table>
			  </td>
			  
			  <td width="52%" valign="top">
			  
			  			<input type="hidden" id="latitude"   name="latitude" value="<?=$item['latitude']?>" 	maxlength="10"  size="20">		
							<input type="hidden" id="longitude"  name="longitude"  value="<?=$item['longitude']?>"  maxlength="10" size="20">
							<input type="hidden" id="zoom"  name="zoom">

		 					<div id="map" style="width:100%; height:400px; BORDER: #a7a6aa 1px solid; overflow:hidden; display:block"></div>
			
							<?php // - Google Maps // ?>


			  </td>
			 </tr>
			</table>

			<?php //+ COCINAS /////////////////// ?>
  		<hr size="2" color="#737373">			

			<table width="100%" border="0" cellpadding="5">
			 <tr>
			  <td  valign="top" width="0%">
	  	    <img src="<?=URL_PATH_FRONT?>/images/icon_cocina.jpg">
 			  </td>
			  <td  valign="top"  width="25%">

			  	<select id="registro_marca" name="registro[marca]" class="select_cocina" onChange="modelos()">
			  	 <option value="">MARCA</option>	
					 <?php	
						for($i=1; $i <= $filas_modelos_marca; $i++) {
							$items_modelos_marca = @mysql_fetch_array($result_modelos_marca); ?>
					 		 <option value="<?=$items_modelos_marca['id']?>"   <?=($item['id_marca']==$items_modelos_marca['id']) ? 'selected' : ''?>><?=$items_modelos_marca['nombre']?></option>
					 	<?php } ?>	 
					</select>			
 			 </td>
		   <td  valign="top"  width="25%">
			    <select id="registro_modelo_lectura" name="registro[modelo_lectura]"  DISABLED class="select_cocina">
			     	<option>MODELO</option>
			    </select>
							<div id="divmodelos"></div>
							<div id="divmodelostxt" class="cargando"></div>
							<input type="hidden" id="registro_id_modelo" />
				 </td>
			  <td  valign="top"  width="25%">
		  

 			 </td>
			  <td  valign="top"  width="25%">

 			 </td>

			 </tr>
 			
			 <tr>
			 		<td colspan="5"><hr size="2" color="#737373"></td>
			 </tr>

				 <tr>
				 	<td align="left" width="20%">

					 		<table>
					 		<tr>
					 		 <td><span class="titulo_form_24">Cuota Entrada:</span>&nbsp;&nbsp;</td>
					 		 <td><input class="form-control" id="registro_cuota_entrada" name="registro[cuota_entrada]" style="width:50px;height:40px"   maxlength="15" type="text" value="<?=$item['cuota_entrada']?>"></td>
					 		</tr>
					 		</table>
					 		
					</td>

				 	<td width="10%">
				 	<select id="registro_forma_pago" name="registro[forma_pago]"  class="select_cocina">
		 			 <option value="">FORMA PAGO</option>
						 <option value="CREDITO DEL ESTADO" <?=($item['forma_pago']=="CREDITO DEL ESTADO") ? 'selected' : ''?>>CREDITO DEL ESTADO</option>
						 <option value="TARJETA DE CREDITO" <?=($item['forma_pago']=="TARJETA DE CREDITO") ? 'selected' : ''?>>TARJETA DE CREDITO</option>
						 <option value="CONTADO" <?=($item['forma_pago']=="CONTADO") ? 'selected' : ''?>>CONTADO</option>
					</select> 
					
			
					<select name="registro[cuotas]" id="registro[cuotas]" class="select_cocina"  style="width:100px; <?=$css_imagen_cuotas?>">
						 <option value="">PLAZOS</option>
						 <option value="72" <?=($item['cuotas']=="72") ? 'selected' : ''?>>72 Meses</option>
						 <option value="60" <?=($item['cuotas']=="60") ? 'selected' : ''?>>60 Meses</option>
						 <option value="48" <?=($item['cuotas']=="48") ? 'selected' : ''?>>48 Meses</option>
						 <option value="36" <?=($item['cuotas']=="36") ? 'selected' : ''?>>36 Meses</option>
						 <option value="24" <?=($item['cuotas']=="24") ? 'selected' : ''?>>24 Meses</option>
						 <option value="12" <?=($item['cuotas']=="12") ? 'selected' : ''?>>12 Meses</option>
					</select>  
				 	</td>
			 		<td colspan="3" align="center"  width="50%">
						
					 <div style="padding:0px">	
						<span class="titulo_form_24">Promoción</span>
						<input type="checkbox" name="promocion" id="registro_promocion" value="1" <?=($item['promocion']==1) ? 'checked' : ''?> />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="titulo_form_24">Medidor 220</span>
						<input type="checkbox" name="medidor220" id="registro_medidor220" value="1" <?=($item['medidor220']==1) ? 'checked' : ''?> />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="titulo_form_24">Circuito interno</span>
						<input type="checkbox" name="circuito_interno" id="registro_circuito_interno" value="1"  <?=($item['circuito_interno']==1) ? 'checked' : ''?> />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="titulo_form_24">Ducha electrica</span>
						<input type="checkbox" name="ducha_electrica" id="registro_ducha_electrica" value="1"  <?=($item['ducha_electrica']==1) ? 'checked' : ''?> />
					 </div>
			 		</td>
			  </tr>
			 <tr>
			 		<td colspan="5"><hr size="2" color="#737373"></td>
			 </tr>			  
			  <tr>
			   <td colspan="4" align="center">

				<span class="titulo_form">Horario de recepción</span>&nbsp;&nbsp;&nbsp;
				<select name="registro[horario_recepcion]" id="registro_horario_recepcion" class="select_horario">
					 <option value="De lunes a sabado de 07h00 a 14h00" <?=($item['horario_recepcion']=="De lunes a sabado de 07h00 a 14h00") ? 'selected' : ''?>>De lunes a sábado de 07h00 a 14h00</option>
					 <option value="Solo lunes a viernes de 07h00 a 14h00" <?=($item['horario_recepcion']=="Solo lunes a viernes de 07h00 a 14h00") ? 'selected' : ''?>>Solo lunes a viernes de 07h00 a 14h00</option>
					 <option value="Solo sabados de 07h00 a 14h00" <?=($item['horario_recepcion']=="Solo sabados de 07h00 a 14h00") ? 'selected' : ''?>>Sólo sábados de 07h00 a 14h00</option>
				</select>
						   
			   </td>
			   <td colspan="1">
					<input class="submit btn btn-large btn-success_ boton_color" id="bt_grabar" name="bt_grabar" type="submit" value="Actualizar Pedido">
			   </td>
			  </tr>			 	 				 
			</table>
		
		</form>
	</div>
</div>


<?php } ?>


</div>

</td>
 </tr>
</table>
  </div>
  </div>
  </div>
  </div> 
</div>
		<!-- end #content -->
	
  <?php include("footer_meta.php")?>

	
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
  <script src="assets/plugins/bootstrap-daterangepicker/moment.js"></script>
  <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	
	<script src="assets/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
	<script src="assets/plugins/masked-input/masked-input.min.js"></script>
	<script src="assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
	<script src="assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
	<script src="assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>
  <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
  <script src="assets/js/form-plugins.demov4.js"></script>

	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
			//FormWysihtml5.init();
			//FormSliderSwitcher.init();
      FormPlugins.init();
		});
	</script>
</body>
</html>
