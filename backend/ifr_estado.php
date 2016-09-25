<?php
// 18/09/2015 18:23:53
// ifr_estado.php

// TESTEAR
// 1 PEDIDOS SIN STOCK
// solo puede editar el usuario de nivel +4

function closeForm() {
    echo "<script type='text/javascript'>";
    echo "window.opener.location.href='PedidosV2.php';";
	echo "window.close();";
	echo "</script>";

    return true;
} 



header("X-Frame-Options: GOFORIT");
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Localizacion', 'Producto', 'Incidencia');
global $BackendUsuario, $Pedido, $Localizacion, $Producto, $Incidencia;

$id = ($_REQUEST['id']) ? $_REQUEST['id'] : null; // id pedido
$estado = ($_REQUEST['estado']) ? $_REQUEST['estado'] : null;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$BackendUsuario->EstaLogeadoBackend();

// fecha y dia actual
$dia_actual = date("d");
$mes_actual = date("m");

$fecha_hora_actual = date("F j, Y, g:i a"); 

$error_comprueba_agenda = null;

switch ($accion) {
	
 case 'estado':
 	//estado==5 1722295126
    if($estado == 3 || $estado==5 || $estado==7) { // COMPRUEBA AGENDA
    	
  	    $last_id = $Pedido->editar_pedido($id, $_POST, $_POST['estado']);
  	    
 	   	 if($last_id > 0) {
 	   	 	
	 	  	  $estado = 4;
	 	  	   closeForm();
	 	  	 
	   	 } else {
	   	 	$accion = "error-stock";
	 	  	$error_comprueba_agenda = "NO SE ENCONTRARON COCINAS DISPONIBLES";
	   	 }

	  } elseif($estado == 4) { // GENERACION DOCUMENTACION SIPEC
	 	  
	   $resultado = $Pedido->genera_documentacion($id, $_POST);
   	 //print "<script>window.parent.cargarEstado('".$id."');</script>";
	   //print "<script>window.parent.jQuery.colorbox.close();</script>";
	   $estado = 4;

	 } 
	 
 break;

 case 'grabar-documentacion':
     $resultado = $Pedido->genera_documentacion($id, $_POST);
 	   $estado = 5;
	   //print "<script>window.parent.cargar_estado_5();</script>";
	   //print "<script>window.opener.location.reload();</script>";
	   print "<script>window.close();</script>";
 break;

 case 'grabar-recepcion':
     $resultado = $Pedido->recepcion_documentacion($id, $_POST);
     $estado = 7;
	   print "<script>window.parent.cargarEstado('".$id."');</script>";
	   print "<script>window.parent.close();</script>";

 break;

 case 'imprimir':
    $Pedido->estado($id, 5);
 break;
 
 case 'incidencia':
	   $Pedido->incidencia($_POST);
	   $accion = "incidencia-agregada";
 break;
}

// modelos cocina
$result_modelos_cocina = $Producto->obtener_modelos( ACTIVO, 'cocina');
$filas_modelos_cocina = @mysql_num_rows($result_modelos_cocina);

// modelos olla
$result_modelos_olla = $Producto->obtener_modelos( ACTIVO, 'olla');
$filas_modelos_olla = @mysql_num_rows($result_modelos_olla);

// modelos color
$result_modelos_color = $Producto->obtener_colores( ACTIVO, null);
$filas_modelos_color = @mysql_num_rows($result_modelos_color);

// modelos marca
$result_modelos_marca = $Producto->obtener_marcas( ACTIVO, 'cocina');
$filas_modelos_marca = @mysql_num_rows($result_modelos_marca);

// marca olla
$result_modelos_marca_olla = $Producto->obtener_marca( ACTIVO, 'olla');
$filas_modelos_marca_olla = @mysql_num_rows($result_modelos_marca_olla);

// provincias
$result_provincias = $Localizacion->obtener_provincias(1, ACTIVO, null, null, null);
$filas_provincias = @mysql_num_rows($result_provincias);

// productos
$result_productos = $Producto->obtener_all(null, null, null,null,null,null,ACTIVO,null,null,null,null);
$filas_productos = @mysql_num_rows($result_productos);

$arrPedido = $Pedido->obtener($id);

// actualizado el nuevo estado del pedido con el
// numero de serie reservado
if($arrPedido['id_producto'] > 0) {
	$arrProducto = $Producto->obtener($arrPedido['id_producto']);
}

// ollas
$result_ollas = $Pedido->obtener_pedido_ollas($id);
$filas_ollas = @mysql_num_rows($result_ollas);
?>

<?php include("meta.php");?>

<style>
	body {
	 padding:0px;
	}
 .contenedor_estado {
  background-color: #ffffff 
 }
 .mensaje-usuario {
  padding:20px;
  font-weight: bold;
 }
 .divhorariorecepcion {
   background-color: #FFFFE8;
    padding:10px;
    border: 1px solid #A4A4A4;
 }
 .titulo-form {
 	width:160px;
 }
 .imprimirfactura {
  background-color: #ffffff 
  border: 1px solid #000000; 
 }
</style>

<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<script>
 function factura_word(tipo, id_pedido, id_producto) {
   if(confirm('Confirma la factura?')) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['id_pedido'].value = id_pedido;
	 form['id_producto'].value = id_producto;	 
	 form.action = "crear_factura_word.php";
	 form.target = "_blank";
	 form.submit();
  }
 }

 function factura_excel(tipo, id_pedido, id_producto) {
   if(confirm('Confirma la factura?')) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['id_pedido'].value = id_pedido;
	 form['id_producto'].value = id_producto;	 
	 form.action = "crear_factura_excel.php";
	 form.target = "_blank";
	 form.submit();
  }
 }

 function factura_html(tipo, id_pedido, id_producto) {
   if(confirm('Confirma la factura?')) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['id_pedido'].value = id_pedido;
	 form['id_producto'].value = id_producto;	 
	 form.action = "crear_factura_html.php";
	 /*form.target = "_blank";*/
	 form.submit();
  }
 }	
  	
 function foto_cuen(foto) {
 	window.open("ifr_foto.php?img="+foto, "sipec", "scrolling=yes");
 }
 function foto_cedula(foto) {
 	window.open("ifr_foto.php?img="+foto, "sipec", "scrolling=yes");
 }

 function generar_pdf(imagen1,imagen2) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['imagen1'].value = imagen1;
	 form['imagen2'].value = imagen2;
	 form.action = "crear_pdf.php";
	 form.target = "_blank";
	 form['accion'].value = 'generar';
 	 form.submit();
 } 
 function	agregar_incidencia() {
  if(confirm('Confirma el envio de una incidencia en el pedido?')) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['accion'].value = 'incidencia';
	 form.submit();
  }
 }
 
 function grabar_documentacion() {
  if(confirm('Confirma el Grabar Documentacion?')) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['accion'].value = 'grabar-documentacion';
	 form.submit();
  }
 }
 
 function cancelar_incidencia() {
  document.getElementById("divincidencia").style.display = 'none';
 }
 function incidencia(estado) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['accion'].value = 'incidencia';
	 form['estado'].value = estado;
	 //form.submit(); 	
	 
 	document.getElementById("divincidencia").style.display = 'block';
 }
 
 function agregarincidencia(estado) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['accion'].value = 'incidencia';
	 form['estado'].value = estado;
 	 document.getElementById("divagregarincidencia").style.display = 'block';
 }

 function paso(estado) {
  if(confirm('Confirma el cambio de estado del pedido?')) {
	  var form = document.forms['frm_dashboard_mapa'];
	  form['accion'].value = 'estado';
	  form['estado'].value = estado;
	  form.submit();
  }
 }
 
 function grabar_recepcion() {
  if(confirm('Confirma el cambio de estado del pedido?')) {
	  var form = document.forms['frm_dashboard_mapa'];
	  form['accion'].value = 'grabar-recepcion';
	  form.submit();
  }
 }
 
 function cerrar() {
   window.opener.location.reload();
	 window.close();
 }

 function finalizar() {
  if(confirm('Confirma la impresion de la factura?')) {
	  var form = document.forms['frm_dashboard_mapa'];
	  form['accion'].value = 'imprimir';
	  form.submit();
  }
 }
 function imprimir() {
  window.print();
 }
</script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
  <script type="text/javascript">
  	 // generico
		var ECUADOR_LATITUD  = -1.7864639;
		var ECUADOR_LONGITUD = -78.1368874;
		var iconLugares =  '<?=URL_PATH_FRONT?>/images/maps/icon_lugar.png';
		var map;
		var geocoder;
		
		function initialize() {
		 var form = document.forms['frm_dashboard_mapa'];
		 var lat  = form['latitude'].value;
		 var lon  = form['longitude'].value;
		 var zoom_nuevo = form['zoom'].value;
		 var zoom_inicial = form['zoom_inicial'].value;
	   
	   if(zoom_nuevo == "" ) {
	   	zoom_nuevo = zoom_inicial;
	   } 

	   zoom_nuevo = parseInt(zoom_nuevo);

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
		  zoom: zoom_nuevo,
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

	function provincias() {
		  var id_provincia = $("#registro_id_provincia").val();
		  var _url =  'ax_cantones_json.php?id_provincia='+id_provincia;
      //alert(_url);
      $("#divcantonestxt").text("Cargando...");	
			$.post(_url,function(result){
			  dataItem = $.parseJSON(result);
			 	var html = "";
			 	html += '<select name="registro[id_canton]" id="registro_id_canton">';
			 	 html += ' <option value="0">Sin Especificar</option>';
			 	for(i=0; i < dataItem['cantidad'][0]; i++) {
	  			 html += ' <option value="'+dataItem['id'][i]+'">'+dataItem['nombre'][i]+'</option>';
			  }
			 	html += '</select>';
			 	
	      $("#divcantonestxt").text("");	
		  	$("#divcantones").append(html);	 	
			});
	}

	function cantones(id_canton, id_provincia) {
		  //var id_provincia = $("#registro_id_provincia").val();
		  var _url =  'ax_cantones_json.php?id_provincia='+id_provincia;
      //alert(id_canton);
      $("#divcantonestxt").text("Cargando...");	
			$.post(_url,function(result){
			  dataItem = $.parseJSON(result);
			 	var html = "";
			 	html += '<select name="registro[id_canton]" id="registro_id_canton">';
			 	 html += ' <option value="0">Sin Especificar</option>';
			 	for(i=0; i < dataItem['cantidad'][0]; i++) {
			 		 var seleccionado = (id_canton == dataItem['id'][i]) ? 'selected="selected"' : '';
	  			 
	  			 html += ' <option value="'+dataItem['id'][i]+'" '+seleccionado+'>'+dataItem['nombre'][i]+'</option>';
			     //alert(html);
			  }
			 	html += '</select>';
			 	
	      $("#divcantonestxt").text("");	
		  	$("#divcantones").append(html);	 	
			});
	}
	
  function validar()	{
		  var serial = $("#registro_serial").val();
		  var _url =  'ax_serial.php?serial='+serial;
      $("#registro_serial").val("Buscando...");
      $.post(_url,function(result){
   
      	if(result == "0") {
      		$("#registro_serial").css('color', 'red');
      		$("#registro_serial").val("No disponible");
      	} else {
      		$("#registro_serial").css('color', 'green');
      		$("#registro_serial").val(serial);
      	}
			});
  }
  
  function grabar() {
	 var form = document.forms['frm_dashboard_mapa'];
	 //form.submit();
  }
  
  function otro_producto() {
  
    // ajax para buscar productos relacionados
    
    
  }
</script>
</head>

<body>
<form name="frm_dashboard_mapa" id="frm_dashboard_mapa" enctype="multipart/form-data" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">
<input type="hidden" name="zoom_inicial" value="8">		
<input type="hidden" name="zoom" id="zoom" value="<?=$arrPedido['zoom']?>">		
<input type="hidden" name="dragable" id="dragable" value="true">		
<input type="hidden" name="modo" value="enviar">	
<input type="hidden" id="ciudad_mapa" name="ciudad_mapa" value="Quito">	
<input type="hidden" id="pais_mapa" name="pais_mapa" value="Ecuador">	

<input type="hidden" name="accion">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="campo">
<input type="hidden" name="estado">		
<input type="hidden" name="id_pedido">	
<input type="hidden" name="id_producto">	

<div class="contenedor_estado"  style="padding:15px">  

<?php if($accion == "incidencia-agregada") { ?>
  
  <div class="mensaje-usuario">Se agregó correctamente la incidencia.<br/><br/>
  <button type="button" class="btn btn-sm btn-default" onClick="cerrar()">Cerrar</button>
  </div>

<?php }else if($accion == "error-stock") { ?>

  <div class="mensaje-usuario">No hay stock disponible.<br/><br/>
  <button type="button" class="btn btn-sm btn-default" onClick="cerrar()">Cerrar</button>
  </div>

<?php } else { ?>
<?php }  ?>

<?php //+ ESTADOS /////////////////////////////// ?>

<?php 
// ESTADO DE COMPRUEBA AGENDA
// estado==5 1722295126
 if($estado == "3" || $estado=="5" || $estado=="7") { ?>

	<div style="padding-top:5px"></div><br/>
	<div class="caja_estado" style="background-color:#D9D8BA" title="ESTADO DE COMPRUEBA AGENDA"></div> 
	<strong>&nbsp;&nbsp;AGENDAR</strong>
	<div style="padding-top:5px"></div><br/>
	Vendedor: <strong><?=$arrPedido['vendedor_nombre']?></strong>
	<div style="padding-top:5px"></div><br/>


	<?php 
	// de agrega el boton para asociar otro pedido a este 
	// con la misma cedula // ?> 
	
	<div id="producto-seleccionado">
	
	</div>
	
	<input type="button" value="Otro Producto" onClick="otro_producto('<?=$arrPedido['id']?>''<?=$arrPedido['cliente_dni']?>')" />
		
	<div class="contenedor_estado_3">  
  
	<div><h3>Datos Principales</h3></div>
			
			 <?php //+ TEXTO  // ?>

		   <div class="form-group">
				<label class="titulo-form">Es dueño del suministro</label>
				<input type="checkbox" name="duenio" id="registro_duenio"  value="1"  <?=($arrPedido['duenio']==1) ? 'checked' : ''?> /></div>
			 </div>

				<div style="padding-top:5px"></div>
						
			<?php //+ CUEN  // ?>
			<div class="form-group">
				<label for="cuen_label <?=$css_cuen?>" class="titulo-form" id="cuen_label">CUEN  <span class="required">(*)</span></label>
				<input required="required" id="registro_cuen" name="registro[cuen]" style="width:20%"  maxlength="10" type="text" value="<?=$arrPedido['cliente_cuen']?>">
				<?php if(strlen($arrPedido['imagen_factura_g']) > 5) { ?>
					<input type="button" value="Foto" onClick="foto_cuen('<?=$arrPedido['imagen_factura_g']?>')" />
				<?php } else { ?>
					<input type="button" value="Foto" onClick="foto_cuen('<?=$arrPedido['imagen_factura']?>')" />
				<?php } ?>


			</div>	

			<div style="padding-top:5px"></div>

			<?php //+ DNI  // ?>
			<div class="control-group">
				<label for="cedula_label <?=$css_cedula?>" class="titulo-form" id="cedula_label">Cédula de Indentidad * <span class="required">(*)</span></label>
				<input required="required" id="registro_dni" name="registro[dni]" style="width:20%"  maxlength="15" type="text" value="<?=$arrPedido['cliente_dni']?>">
				<?php if(strlen($arrPedido['imagen_dni_frente_g']) > 5) { ?>
					<input type="button" value="Foto Frente" onClick="foto_cedula('<?=$arrPedido['imagen_dni_frente_g']?>')" />
				<?php } else { ?>
					<input type="button" value="Foto Frente" onClick="foto_cedula('<?=$arrPedido['imagen_dni_frente']?>')" />
				<?php } ?>

				<?php if(strlen($arrPedido['imagen_dni_posterior_g']) > 5) { ?>
					<input type="button" value="Foto Posterior" onClick="foto_cedula('<?=$arrPedido['imagen_dni_posterior_g']?>')" />
				<?php } else { ?>
					<input type="button" value="Foto Posterior" onClick="foto_cedula('<?=$arrPedido['imagen_dni_posterior']?>')" />
				<?php } ?>

				<span id="cedula_error"></span>
			</div>	

			<div style="padding-top:5px"></div>

			<!--1722295126-->
			<?php //+ Factura // ?>
			<!--<div class="control-group">
				<label class="titulo-form">Factura <span class="required">*</span></label>
				<input class="" id="registro_factura" name="registro[factura]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['factura']?>">
			</div>-->
			<div class="control-group">
			    <label class="titulo-form">Estado SIPEC <span class="required">*</span></label>
				<select name="registro[estado_sipec]" id="mes" value="<?=$arrPedido['estado_sipec'];?>">
				    <option value="SIN FACTURAR" <?=($arrPedido['estado_sipec']=='SIN FACTURAR') ? 'selected' : ''?>>SIN FACTURAR</option>
					<option value="ENTREGADO" <?=($arrPedido['estado_sipec']=='ENTREGADO') ? 'selected' : ''?>>ENTREGADO</option>
					<option value="CANCELADO" <?=($arrPedido['estado_sipec']=='CANCELADO') ? 'selected' : ''?>>CANCELADO</option>
					<option value="ANULADO" <?=($arrPedido['estado_sipec']=='ANULADO') ? 'selected' : ''?>>ANULADO</option>
					<option value="PENDIENTE" <?=($arrPedido['estado_sipec']=='PENDIENTE') ? 'selected' : ''?>>PENDIENTE</option>
									   
				</select>
									

			</div>



            <?php //+ Fecha Factura // 
            if ($estado==3){ ?>
				<div class="control-group">
					<label class="titulo-form">Fecha SIPEC <span class="required">*</span></label>
					<input class="" id="registro_fecha_sipec"  name="registro[fecha_sipec]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['fecha_sipec']?>">
				</div>	


				<div class="control-group hidden">
					<label class="titulo-form ">Fecha Induccion <span class="required">*</span></label>
					<input class="" id="registro_fecha_induccion"  name="registro[fecha_induccion]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['fecha_sipec']?>">
				</div>	
			<?php
			} else
			{ ?>
			  <div class="control-group hidden">
					<label class="titulo-form">Fecha SIPEC <span class="required">*</span></label>
					<input class="" id="registro_fecha_sipec"  name="registro[fecha_sipec]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['fecha_sipec']?>">
				</div>	


				<div class="control-group">
					<label class="titulo-form">Fecha Induccion <span class="required">*</span></label>
					<input class="" id="registro_fecha_induccion"  name="registro[fecha_induccion]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['fecha_induccion']?>">
				</div>	

			<?php
			} ?>




			<!--1722295126-->

			<?php //+ Nombre  // ?>
			<div class="control-group">
				<label for="<?=$css_nombre?>" class="titulo-form">Nombre * <span class="required">(*)</span></label>
				<input required="required" class="" id="register_name" name="registro[nombre]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['cliente_nombre']?>">
			</div>	

			<div style="padding-top:5px"></div>

			<?php //+ Apellidos  // ?>
			<div class="control-group">
				<label for="<?=$css_nombre?>" class="titulo-form">Apellidos  <span class="required">(*)</span></label>
				<input required="required" class="" id="register_apellido" name="registro[apellido]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['cliente_apellido']?>">
			</div>	

			<div style="padding-top:5px"></div>
			
			<?php // TLEFONO FIJO ?>		
			<div class="control-group size1">
				<label for="<?=$css_telefono?>" class="titulo-form">Teléfono <span class="required">(*)</span></label>
				<input class="field" id="registro_telefono" name="registro[cliente_telefono]" size="30" type="text" class="" required="required" maxlength="10" value="<?=$arrPedido['cliente_telefono']?>">
			</div>

			<div style="padding-top:5px"></div>

			<?php // TLEFONO CELULAR ?>		
			<div class="control-group size1">
				<label for="<?=$css_cliente_celular?>" class="titulo-form">Celular</label>
				<input class="field" id="registro_cliente_celular" name="registro[cliente_celular]" size="30" type="text"  maxlength="10" value="<?=$arrPedido['cliente_celular']?>">
			</div>

			<div style="padding-top:5px"></div>

			<?php // EMAIL ?>		
			<div class="control-group size1">
				<label for="email <?=$css_email?>" class="titulo-form">Correo <span class="required">(*)</span></label>
				<input class="field" id="registro_email" name="registro[email]"  type="email" class="" style="width:50%" required="required" maxlength="30" value="<?=$arrPedido['cliente_email']?>">
			</div>

			<div style="padding-top:5px"></div>

			<div class="control-group size1">
			</div>
			
			<?php //+ LOCALIZACION ?>
			<div class="control-group size1">
				<label for="<?=$css_id_provincia?>" class="titulo-form">Provincia <span class="required">(*)</span></label>
				
			<select id="registro_id_provincia" name="registro[id_provincia]" onChange="provincias()">
		 	<option value="">-Seleccionar-</option>
			<?php // PROVINCIAS
				for($i=0; $i < $filas_provincias; $i++) {
					$items_provincias = @mysql_fetch_array($result_provincias); ?>						 	
			 		 <option value="<?=$items_provincias['id']?>"  <?=($arrPedido['id_provincia']==$items_provincias['id']) ? 'selected' : ''?>><?=$items_provincias['nombre']?></option>
			<?php } ?>  
			</select>
			</div>

			<div style="padding-top:5px"></div>

			<div class="control-group size1">
				<label for="" class="<?=$css_id_canton?>" style="width:160px">Canton</label>
					<div style="display:inline" id="divcantones"></div>
					<div id="divcantonestxt" class="cargando"></div>
					<input type="hidden" id="registro_id_canton" />
			</div>

			<div style="padding-top:5px"></div>

			<?php //+ Parroquia  // ?>
			<div class="control-group">
				<label for="<?=$css_nombre?>" class="titulo-form">Parroquia  </label>
				<input  class="" id="register_name" name="registro[parroquia]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['parroquia']?>">
			</div>

			<div style="padding-top:5px"></div>

			<?php //+ Barrio  // ?>
			<div class="control-group">
				<label for="<?=$css_barrio?>" class="titulo-form">Barrio/Urbanización </label>
				<input  class="" id="registro_barrio" name="registro[barrio]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['barrio']?>">
			</div>		

			<div style="padding-top:5px"></div>

			<?php //+ Calle  // ?>
			<div class="control-group">
				<label for="<?=$css_cliente_calle?>" class="titulo-form">Calle Principal <span class="required"></span></label>
				<input  class="" id="registro_cliente_calle" name="registro[cliente_calle]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['cliente_calle']?>">
			</div>		

			<div style="padding-top:5px"></div>

			<?php //+ Calle numero  // ?>
			<div class="control-group">
				<label for="<?=$css_cliente_calle_numero?>" class="titulo-form">Número <span class="required"></span></label>
				<input  class="" id="registro_cliente_calle_numero" name="registro[cliente_calle_numero]" style="width:20%"  maxlength="50" type="text" value="<?=$arrPedido['cliente_calle_numero']?>">
			</div>		

			<div style="padding-top:5px"></div>

			<?php //+ Calle Secundaria  // ?>
			<div class="control-group">
				<label for="<?=$css_cliente_calle_secundaria?>" class="titulo-form">Calle Secundaria <span class="required"></span></label>
				<input  class="" id="registro_cliente_calle_secundaria" name="registro[cliente_calle_secundaria]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['cliente_calle_secundaria']?>">
			</div>		

			<div style="padding-top:5px"></div>

			<?php //+ Referencia  // ?>
			<div class="control-group">
				<label for="<?=$css_cliente_referencia?>" class="titulo-form">Referencia <span class="required"></span></label>
				<input  class="" id="registro_cliente_referencia" name="registro[cliente_referencia]" style="width:50%"  maxlength="250" type="text" value="<?=$arrPedido['cliente_referencia']?>">
			</div>		

			<div style="padding-top:5px"></div>


			<?php //+ Mapa  // ?>
			<?php // + Google Maps // ?>
			<div><h3>Localización en el Mapa</h3></div>	
			
			<input type="hidden" id="latitude" name="latitude" value="<?=$arrPedido['latitude']?>" 	maxlength="10"  size="20">		
			<input type="hidden" id="longitude"  name="longitude"  value="<?=$arrPedido['longitude']?>"  maxlength="10" size="20">

		  <div id="map" style="width:100%; height:400px; BORDER: #a7a6aa 1px solid; overflow:hidden; display:block"></div>
			
			<?php // - Google Maps // ?>
			<?php //- Mapa  // ?>			
	
			
			<div style="clear:both"></div><br/>
			
			<?php //+ segunda parte // ?>
			<div class="control-group">
				<label for="shop_name <?=$css_marca?>" class="titulo-form"> <strong>Cocina</strong></label>
	
					<select id="registro_marca" name="registro[marca]" required="true">
					 <?php	
						for($i=1; $i <= $filas_modelos_marca; $i++) {
							$items_modelos_marca = @mysql_fetch_array($result_modelos_marca); ?>
					 		 <option value="<?=$items_modelos_marca['nombre']?>"   <?=($_POST['registro']['marca']==$arrPedido['marca']) ? 'selected' : ''?>><?=$items_modelos_marca['nombre']?></option>
					 	<?php } ?>	
					</select>
					
					<select id="registro_modelo" name="registro[modelo]" required="true">
		 			<option value="">-Seleccionar-</option>
				 <?php	
						for($i=1; $i <= $filas_modelos_cocina; $i++) {
							$items_modelos_cocina = @mysql_fetch_array($result_modelos_cocina); ?>
					 		 <option value="<?=$items_modelos_cocina['nombre']?>"   <?=($arrPedido['modelo']==$items_modelos_cocina['nombre']) ? 'selected' : ''?>><?=$items_modelos_cocina['nombre']?></option>
					 	<?php } ?>
					</select>

			<select id="registro_color" name="registro[color]" required="true">
			 	<option value="">-Seleccionar-</option>
			 		 <option value="INOX"   <?=($arrPedido['color']=="INOX") ? 'selected' : ''?>>INOX</option>
			 		 <option value="BLANCO" <?=($arrPedido['color']=="BLANCO") ? 'selected' : ''?>>BLANCO</option>
				</select>
			</div>

	
			<?php //- segunda parte // ?>

			<?php //+ Plazos // ?>
			<div class="control-group">
				<label for="shop_name <?=$css_plazos?>" class="titulo-form"> Plazos de Crédito</label>
				<select name="registro[cuotas]" id="registro[cuotas]">
					 <option value="72" <?=($arrPedido['cuotas']=="72") ? 'selected' : ''?>>72 Meses</option>
					 <option value="60" <?=($arrPedido['cuotas']=="60") ? 'selected' : ''?>>60 Meses</option>
					 <option value="48" <?=($arrPedido['cuotas']=="48") ? 'selected' : ''?>>48 Meses</option>
					 <option value="36" <?=($arrPedido['cuotas']=="36") ? 'selected' : ''?>>36 Meses</option>
					 <option value="24" <?=($arrPedido['cuotas']=="24") ? 'selected' : ''?>>24 Meses</option>
					 <option value="12" <?=($arrPedido['cuotas']=="12") ? 'selected' : ''?>>12 Meses</option>

				</select>
			</div>	

		<?php // + COCINAS // ?>
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Forma pago</label>
				<select name="registro[forma_pago]" id="registro[forma_pago]">
					 <option value="Efectivo" 					<?=($arrPedido['forma_pago']=="Efectivo") ? 'selected' : ''?>>Efectivo</option>
					 <option value="Tarjeta de Credito" <?=($arrPedido['forma_pago']=="Tarjeta de Credito") ? 'selected' : ''?>>Tarjeta de Crédito</option>
				</select>
			</div>				

			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Cuota Entrada</label>
				<input class="" id="registro_cuota_entrada" name="registro[cuota_entrada]"  maxlength="15" type="text" value="<?=$arrPedido['cuota_entrada']?>">
			</div>
			
			<div style="clear:both"></div><br/>
			<?php // + Ollas ///////////////////////////////////////////// ?>
				
			<?php if($filas_ollas > 0) { ?>
			
		  <div class="control-group">
				<label for="shop_name <?=$css_id_producto?>"> <strong>Ollas</strong></label>
			</div>
			
			<?php //+ div de ollas completo // ?>
			<div id="divollas" style="display:block">
			<?php
				for($k=1; $k <= $filas_ollas; $k++) {
					$items_ollas = @mysql_fetch_array($result_ollas); ?>

				<div class="control-group">
					<label for="" class="titulo-form"> </label>
					<?=$items_ollas['olla_caracteristicas']?> - <?=$items_ollas['olla_color']?> 
				</div>		
					
			<?php } ?>		
			 
			 <div id="divollaspega"></div>
			</div>
			<?php } ?>
			<?php //- div de ollas completo // ?>

		 <div style="clear:both"></div><br/>

			
			<?php // + OLLAS // ?>
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Forma pago ollas</label>
				<select name="registro[forma_pago_ollas]" id="registro[forma_pago_ollas]">
					 <option value="Efectivo" 					<?=($arrPedido['forma_pago_ollas']=="Efectivo") ? 'selected' : ''?>>Efectivo</option>
					 <option value="Tarjeta de Credito" <?=($arrPedido['forma_pago_ollas']=="Tarjeta de Credito") ? 'selected' : ''?>>Tarjeta de Crédito</option>
				</select>
			</div>	

		 <div style="clear:both"></div><br/><br/>

			<div class="control-group">
				<label for="<?=$css_promocion?>" class="titulo-form"> Promocion</label>
				<input type="checkbox" name="promocion" id="registro_promocion" value="1" <?=($arrPedido['promocion']==1) ? 'checked' : ''?> />
			</div>

			
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Medidor 220</label>
				<input type="checkbox" name="medidor220" id="registro_medidor220" value="1" <?=($arrPedido['medidor220']==1) ? 'checked' : ''?> />
			</div>

			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Circuito interno</label>
				<input type="checkbox" name="circuito_interno" id="registro_circuito_interno" value="1"  <?=($arrPedido['circuito_interno']==1) ? 'checked' : ''?> />
			</div>

			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Ducha electrica</label>
				<input type="checkbox" name="ducha_electrica" id="registro_ducha_electrica" value="1"  <?=($arrPedido['ducha_electrica']==1) ? 'checked' : ''?> />
			</div>

			<?php // HORARIO // ?>
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Horario de recepción sugerido</label>
				<select name="registro[horario_recepcion]" id="registro_orario_recepcion">
					 <option value="De lunes a sabado de 07h00 a 14h00"    <?=($arrPedido['horario_recepcion']=="De lunes a sabado de 07h00 a 14h00") ? 'selected' : ''?>>De lunes a sábado de 07h00 a 14h00</option>
					 <option value="Solo lunes a viernes de 07h00 a 14h00" <?=($arrPedido['horario_recepcion']=="Solo lunes a viernes de 07h00 a 14h00") ? 'selected' : ''?>>Sólo lunes a viernes de 07h00 a 14h00</option>
					 <option value="Solo sabados de 07h00 a 14h00" 				 <?=($arrPedido['horario_recepcion']=="Solo sabados de 07h00 a 14h00") ? 'selected' : ''?>>Sólo sábados de 07h00 a 14h00</option>
				</select>
			</div>				
											
			<div style="clear:both"></div><br/>

			<?php //+ ESTABLECER HORARIO - FECHA FINAL DE ENTREGA // ?>
			<div class="divhorariorecepcion">
			<div><h3>Confirma Horario de Recepción</h3></a></div>
			<div class="control-group">
			<label for="<?=$css_forma_pago?>"> <strong>Día</span></label>			
			<select name="registro[recepcion_confirmada_dia]" id="recepcion_confirmada_dia">
					 <option value="01" <?=($dia_actual=='01') ? 'selected' : ''?>>01</option>
					 <option value="02" <?=($dia_actual=='02') ? 'selected' : ''?>>02</option>
					 <option value="03" <?=($dia_actual=='03') ? 'selected' : ''?>>03</option>
					 <option value="04" <?=($dia_actual=='04') ? 'selected' : ''?>>04</option>
					 <option value="05" <?=($dia_actual=='05') ? 'selected' : ''?>>05</option>
					 <option value="06" <?=($dia_actual=='06') ? 'selected' : ''?>>06</option>
					 <option value="07" <?=($dia_actual=='07') ? 'selected' : ''?>>07</option>
					 <option value="08" <?=($dia_actual=='08') ? 'selected' : ''?>>08</option>
					 <option value="09" <?=($dia_actual=='09') ? 'selected' : ''?>>09</option>
					 <option value="10" <?=($dia_actual=='11') ? 'selected' : ''?>>10</option>
					 <option value="11" <?=($dia_actual=='11') ? 'selected' : ''?>>11</option>
					 <option value="12" <?=($dia_actual=='12') ? 'selected' : ''?>>12</option>
					 <option value="13" <?=($dia_actual=='13') ? 'selected' : ''?>>13</option>
					 <option value="14" <?=($dia_actual=='14') ? 'selected' : ''?>>14</option>
					 <option value="15" <?=($dia_actual=='15') ? 'selected' : ''?>>15</option>
					 <option value="16" <?=($dia_actual=='16') ? 'selected' : ''?>>16</option>
					 <option value="17" <?=($dia_actual=='17') ? 'selected' : ''?>>17</option>
					 <option value="18" <?=($dia_actual=='18') ? 'selected' : ''?>>18</option>
					 <option value="19" <?=($dia_actual=='19') ? 'selected' : ''?>>19</option>
					 <option value="20" <?=($dia_actual=='20') ? 'selected' : ''?>>20</option>
					 <option value="21" <?=($dia_actual=='21') ? 'selected' : ''?>>21</option>
					 <option value="22" <?=($dia_actual=='22') ? 'selected' : ''?>>22</option>
					 <option value="23" <?=($dia_actual=='23') ? 'selected' : ''?>>23</option>
					 <option value="24" <?=($dia_actual=='24') ? 'selected' : ''?>>24</option>
					 <option value="25" <?=($dia_actual=='25') ? 'selected' : ''?>>25</option>
					 <option value="26" <?=($dia_actual=='26') ? 'selected' : ''?>>26</option>
					 <option value="27" <?=($dia_actual=='27') ? 'selected' : ''?>>27</option>
					 <option value="28" <?=($dia_actual=='28') ? 'selected' : ''?>>28</option>
					 <option value="29" <?=($dia_actual=='29') ? 'selected' : ''?>>29</option>
					 <option value="30" <?=($dia_actual=='30') ? 'selected' : ''?>>30</option>
					 <option value="31" <?=($dia_actual=='31') ? 'selected' : ''?>>31</option>
				</select>
				&nbsp;
			<select name="registro[recepcion_confirmada_mes]" id="recepcion_confirmada_mes">
					 <option value="01" <?=($mes_actual=='01') ? 'selected' : ''?>>Enero</option>
					 <option value="02" <?=($mes_actual=='02') ? 'selected' : ''?>>Febrero</option>
					 <option value="03" <?=($mes_actual=='03') ? 'selected' : ''?>>Marzo</option>
					 <option value="04" <?=($mes_actual=='04') ? 'selected' : ''?>>Abril</option>
					 <option value="05" <?=($mes_actual=='05') ? 'selected' : ''?>>Mayo</option>
					 <option value="06" <?=($mes_actual=='06') ? 'selected' : ''?>>Junio</option>
					 <option value="07" <?=($mes_actual=='07') ? 'selected' : ''?>>Julio</option>
					 <option value="08" <?=($mes_actual=='08') ? 'selected' : ''?>>Agosto</option>
					 <option value="09" <?=($mes_actual=='09') ? 'selected' : ''?>>Septiembre</option>
					 <option value="10" <?=($mes_actual=='10') ? 'selected' : ''?>>Octubre</option>
					 <option value="11" <?=($mes_actual=='11') ? 'selected' : ''?>>Noviembre</option>
					 <option value="12" <?=($mes_actual=='12') ? 'selected' : ''?>>Diciembre</option>
				</select>
				 desde: 
			<select name="registro[recepcion_confirmada_desde]" id="recepcion_confirmada_desde">
					 <option value="07:00">07:00 AM</option>
					 <option value="08:00">08:00 AM</option>
					 <option value="09:00">09:00 AM</option>
					 <option value="10:00">10:00 AM</option>
					 <option value="11:00">11:00 AM</option>
					 <option value="12:00">12:00 AM</option>
					 <option value="13:00">13:00 PM</option>
					 <option value="14:00">14:00 PM</option>
					 <option value="15:00">15:00 PM</option>
					 <option value="16:00">16:00 PM</option>
					 <option value="17:00">17:00 PM</option>
					 <option value="18:00">18:00 PM</option>
				</select>
				hasta:
			<select name="registro[recepcion_confirmada_hasta]" id="recepcion_confirmada_hasta">
					 <option value="10:00">10:00 AM</option>
					 <option value="11:00">11:00 AM</option>
					 <option value="12:00">12:00 AM</option>
					 <option value="13:00">13:00 PM</option>
					 <option value="14:00">14:00 PM</option>
					 <option value="15:00">15:00 PM</option>
					 <option value="16:00">16:00 PM</option>
					 <option value="17:00">17:00 PM</option>
					 <option value="18:00">18:00 PM</option>
				</select>	
			</div>
			</div>  
		
			<div style="clear:both"></div><br/>
		
			<?php //+ Contacto con:  // ?>
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Contacto con</label>
				<input placeholder=""  id="registro_contacto_nombre" name="registro[contacto_nombre]" style="width:50%"  maxlength="100" type="text" value="<?=$arrPedido['contacto_nombre']?>">
			</div>

			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Parentesco</label>
				<select id="registro_contacto_parentesco" name="registro[contacto_parentesco]">
				 <option value="Propietario" <?=($arrPedido['contacto_parentesco']=="Propietario") ? 'selected' : ''?>>Propietario</option>
				 <option value="Conyugue"  <?=($arrPedido['contacto_parentesco']=="Conyugue") ? 'selected' : ''?>>Conyugue</option>
				 <option value="Hijos"  <?=($arrPedido['contacto_parentesco']=="Hijos") ? 'selected' : ''?>>Hijos</option>
				 <option value="Tio"  <?=($arrPedido['contacto_parentesco']=="Tio") ? 'selected' : ''?>>Tío</option>
				 <option value="Primo"  <?=($arrPedido['contacto_parentesco']=="Primo") ? 'selected' : ''?>>Primo</option>
				 <option value="Abuelos"  <?=($arrPedido['contacto_parentesco']=="Abuelos") ? 'selected' : ''?>>Abuelos</option>
				 <option value="Otro"  <?=($arrPedido['contacto_parentesco']=="Otro") ? 'selected' : ''?>> Otro</option>
				</select>
			</div>
									
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Fecha y Hora del Contacto</label>
				<input placeholder="Fecha y Hora del Contacto"  id="registro_contacto_fecha" name="registro[contacto_fecha]" style="width:50%"  maxlength="50" type="text" value="<?=(strlen($arrPedido['contacto_fecha']) > 4) ? $arrPedido['contacto_fecha'] : $fecha_hora_actual ?>">
			</div>
			

		<?php //if($BackendUsuario->esSupervisorCordinador())	{ ?>	
			<div style="clear:both"></div><br/>
			<div class="actions">
			<?php 
				//if 1722295126
	 			if($estado=="5") { ?>
			 			<input type="button" value="Continuar"  onClick="paso('5')"/>
		  			    <!--<input type="button" value="Incidencia" onClick="incidencia('7')" />-->
			 <?php } else if($estado=="3"){?>
			       		 <input type="button" value="Continuar"  onClick="paso('3')"/>
		  			    <input type="button" value="Incidencia" onClick="incidencia('3')" />
		  	 <?php } else if($estado=="7"){?>
			       		 <input type="button" value="Continuar"  onClick="paso('7')"/>
		  			    <!--<input type="button" value="Incidencia" onClick="incidencia('7')" />-->
	
			 <?php }?>
 		   <input type="button" value="Imprimir" onClick="imprimir()" />
			  
			
			</div>
		<?php //} ?>	
			
			<div style="clear:both"></div><br/>
</div>	    
<?php
// GENERAR DOCUMENTACION - SIPEC
}	 else if($estado == 4) { ?>

	<div style="padding-top:5px"></div><br/>
	<strong>&nbsp;&nbsp;GENERACION DOCUMENTACION</strong>
	<div style="padding-top:5px"></div><br/>

	 <?php // tabs ?>
	 					 <ul class="nav nav-pills">
						<li class="active"><a href="#nav-pills-tab-1" data-toggle="tab">Documentacion</a></li>
						<li><a href="#nav-pills-tab-2" data-toggle="tab">Factura Cocina</a></li>
						<?php if($filas_ollas > 0) { ?>			
							<li><a href="#nav-pills-tab-3" data-toggle="tab">Factura Ollas</a></li>
						<?php } ?>	
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade active in" id="nav-pills-tab-1">
             
             			<div class="control-group">
				<label for="cedula_label <?=$css_cuen?>" class="titulo-form" id="cedula_label">CUEN</label>
				<?=$arrPedido['cliente_cuen']?>
			</div>	

			<div class="control-group">
				<label for="shop_name <?=$css_marca?>" class="titulo-form"> Marca</label>
			 		 <?=$arrPedido['marca']?>
			</select>
			</div>

			<div class="control-group">
				<label for="shop_name <?=$css_marca?>" class="titulo-form"> Modelo</label>
				<?=$arrPedido['modelo']?>
			</div>
			
			<div class="control-group">
				<label for="shop_name <?=$css_marca?>" class="titulo-form"> Color</label>
					<?=$arrPedido['color']?>
			</div>

			<div class="control-group">
				<label class="titulo-form">Serial <span class="required">*</span></label>
				<input class="" id="registro_serial" name="registro[serial]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['serial']?>">
			  <input type="button" value="Validar" onClick="validar()"/>
			</div>				
			<div class="control-group">
				<label class="titulo-form">Cuota Entrada </label>
				<input class="" id="registro_cuota_entrada" name="registro[cuota_entrada]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['cuota_entrada']?>">
			</div>
			<div class="control-group">
				<label class="titulo-form">Factura <span class="required">*</span></label>
				<input class="" id="registro_factura" name="registro[factura]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['factura']?>">
			</div>
			<div class="control-group">
				<label class="titulo-form">Fecha Factura <span class="required">*</span></label>
				<input class="" id="registro_factura"  name="registro[contacto_fecha]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['contacto_fecha']?>">
			</div>			
	 	 <!--
			<div class="control-group">
				<label for="cedula_label <?=$css_cedula?>" class="titulo-form" id="cedula_label">Cédula de Indentidad </label>
				<?=$arrPedido['cliente_dni']?>
			</div>	

			<?php //+ Nombre  // ?>
			<div class="control-group">
				<label for="<?=$css_nombre?>" class="titulo-form">Nombre </label>
				<?=$arrPedido['cliente_nombre']?>
			</div>	

			<?php //+ Apellidos  // ?>
			<div class="control-group">
				<label for="<?=$css_nombre?>" class="titulo-form">Apellidos</label>
				<?=$arrPedido['cliente_apellido']?>
			</div>	
			
			<?php // TLEFONO FIJO ?>		
			<div class="control-group size1">
				<label for="<?=$css_telefono?>" class="titulo-form">Teléfono</label>
				<?=$arrPedido['cliente_telefono']?>
			</div>				

			<div class="control-group">
				<label for="cedula_label <?=$css_cedula?>" class="titulo-form" id="cedula_label">Número Serie Reservado </label>
				<strong><?=$arrProducto['serie']?></strong>
			</div>	

			<div class="control-group">
				<label for="shop_name <?=$css_marca?>" class="titulo-form"> Marca</label>
			 		 <?=$arrPedido['marca']?>
			</select>
			</div>

			<div class="control-group">
				<label for="shop_name <?=$css_marca?>" class="titulo-form"> Modelo</label>
				<?=$arrPedido['modelo']?>
			</div>
			
			<div class="control-group">
				<label for="shop_name <?=$css_marca?>" class="titulo-form"> Color</label>
					<?=$arrPedido['color']?>
			</div>		
			<?php //- segunda parte // ?>

			<?php //+ Plazos // ?>
			<div class="control-group">
				<label for="shop_name <?=$css_plazos?>" class="titulo-form"> Plazos de Crédito</label>
					 <?=$arrPedido['cuotas']?>
			</div>	

				<div style="clear:both"></div><br/>

			<div class="control-group">
				<label for="<?=$css_cliente_referencia?>" class="titulo-form"><strong>Marcar la documentación</strong></label>
				<br/>
        <input type="checkbox" name="ck_pagare" value="1" /> Pagare <br/>
        <input type="checkbox" name="ck_acta" value="1" /> Acta <br/>
        <input type="checkbox" name="ck_peticion" value="1" /> Petición <br/>
			</div>
        
			<div class="control-group">
				<label class="titulo-form">Factura Número <span class="required">*</span></label>
				<input class="" id="registro_factura" name="registro[factura]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['factura']?>">
			</div>	
       -->


			<div style="clear:both"></div><br/>	
			
			<div>
			 <input type="button" class="btn" value="Continuar"  onClick="grabar_documentacion()"/>
			 <input type="button" class="btn" value="Incidencia" onClick="incidencia('4')" />
			</div>
    
		  <div style="clear:both"></div><br/>	

			</div>
						<div class="tab-pane fade" id="nav-pills-tab-2">
								<input type="button" value="Crear Factura EXCEL" onClick="factura_excel('xls', '<?=$arrPedido['id']?>', '<?=$arrProducto['id']?>')" />
								<input type="button" value="Crear Factura WORD" onClick="factura_word('doc', '<?=$arrPedido['id']?>', '<?=$arrProducto['id']?>')" />
								<input type="button" value="Crear Factura HTML" onClick="factura_html('html', '<?=$arrPedido['id']?>', '<?=$arrProducto['id']?>')" />
						</div>
						<div class="tab-pane fade" id="nav-pills-tab-3">

						   FACTURA OLLAS

						</div>
					</div>
		<?php // - tabs // ?>			
			 

<?php
// RECEPCION DOCUMENTACION
}	else if($estado == 6) { ?>

	<div style="padding-top:5px"></div><br/>
	<strong>RECEPCIÓN DOCUMENTACIÓN</strong>
	<div style="padding-top:5px"></div><br/>
		<!--
		<div class="control-group">
				<label class="titulo-form">Factura <span class="required">*</span></label>
				<input class="" id="registro_factura" name="registro[factura]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['factura']?>">
			</div>
			<div class="control-group">
				<label class="titulo-form">Fecha Factura <span class="required">*</span></label>
				<input class="" id="registro_factura"  name="registro[contacto_fecha]" style="width:30%" required="true" maxlength="30" type="text" value="<?=$arrPedido['contacto_fecha']?>">
			</div>	

			<div class="control-group">
				<label for="<?=$css_cliente_referencia?>" class="titulo-form"><strong>Marcar la documentación</strong></label>
		    <br/>-->
				
        <input type="checkbox" name="ck_factura" value="1" <?=($arrPedido['ck_factura']) ? 'checked' : ''?>/> Factura <br/>
        <input type="checkbox" name="ck_pagare" value="1" <?=($arrPedido['pagare']) ? 'checked' : ''?>/> Pagare <br/>
        <input type="checkbox" name="ck_acta_de_entrega" value="1" <?=($arrPedido['acta_de_entrega']) ? 'checked' : ''?>/> Acta de Entrega <br/>
        <input type="checkbox" name="ck_incentivo" value="1" <?=($arrPedido['incentivo']) ? 'checked' : ''?>/> Incentivo <br/>
        <input type="checkbox" name="ck_cedula_dueno" value="1" <?=($arrPedido['cedula_dueno']) ? 'checked' : ''?>/> Cédula Dueño <br/>
        <?php if($arrPedido['duenio'] == 0) { ?>
        <input type="checkbox" name="ck_cedula_arrendador" value="1" <?=($arrPedido['cedula_arrendador']) ? 'checked' : ''?>/> Cédula Arrendador <br/>
				<?php } ?>

			</div>
        
				
	 	 <div style="clear:both"></div><br/>	

			<div>
			 <input type="button" class="btn" value="Grabar"  onClick="grabar_recepcion()"/>
			 <input type="button" class="btn" value="Incidencia" onClick="incidencia('6')" />
			</div>

	  <div style="clear:both"></div><br/>	
					
<?php

}

?>

	<?php //+ form de incidencia // ?>	
	<div id="divincidencia" style="display:none">
	 <div class="panel-body">
          <fieldset>
              <legend>Agregar una incidencia</legend>

              <div class="form-group">
              	
              	<?php // + listado ?>
								<?php
								// todos 
								$result_todos = $Incidencia->obtener_all(null, null);
								$filas_todos = @mysql_num_rows($result_todos);
									for($i=1; $i <= $filas_todos; $i++) {
											$items = @mysql_fetch_array($result_todos);
								?>
								
										<div class="label label-danger" style="padding:5px">
											<input type="radio" name="rd_incidencia" value="<?=$items['id']?>" />
											<?=$items['nombre']?>
										</div>
										<div style="clear:both;padding-top:2px"></div>
								
								<?php
									} 
								?>
								
								<label>Observaciones</label>
								<textarea name="contenido_incidencia" id="contenido_incidencia" style="width:600px;height:100px"></textarea>

              </div>
              
               <div style="clear:both"></div><br/>	
               
              <button type="button" class="btn btn-sm btn-primary m-r-5" onClick="agregar_incidencia()">Grabar</button>
              <button type="button" class="btn btn-sm btn-default" onClick="cancelar_incidencia()">Cancelar</button>
          
          </fieldset>
   </div>
	</div>
	<?php //- form de incidencia // ?>	
</div>
</form>

<?php
if($estado == 3 || $estado==5 || $estado==7) {
	print "<script>cantones('".$arrPedido['id_canton']."', '".$arrPedido['id_provincia']."');</script>";
	print "<script>initialize();</script>";
} 
?>
</body>
</html>