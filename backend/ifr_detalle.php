<?php
// 08/10/2015 21:06:23
// ifr_detalle.php


header("X-Frame-Options: GOFORIT");
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Localizacion', 'Producto');
global $BackendUsuario, $Pedido, $Localizacion, $Producto;

$id = ($_REQUEST['id']) ? $_REQUEST['id'] : null; // id pedido
$estado = ($_REQUEST['estado']) ? $_REQUEST['estado'] : null;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$BackendUsuario->EstaLogeadoBackend();

// fecha y dia actual
$dia_actual = date("d");
$mes_actual = date("m");

$fecha_hora_actual = date("F j, Y, g:i a"); 
$error_comprueba_agenda = null;

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

// canton
$arrCanton = $Localizacion->obtener_canton($arrPedido['id_canton']);
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


<?php //+ ESTADOS /////////////////////////////// ?>

	<div style="padding-top:5px"></div><br/>

	
	<div class="contenedor_estado_3">  
  
	<div><h3>DETALLES</h3></div>
			
	<div style="padding-top:5px"></div><br/>
			 <?php //+ TEXTO  // ?>

		   <div class="form-group">
				<label class="titulo-form">#ID</label>
				 <?=$arrPedido['id']?>
			 </div>

		   <div class="form-group">
				<label class="titulo-form">Fecha Alta</label>
				 <?=$arrPedido['fecha_alta']?>
			 </div>

		   <div class="form-group">
				<label class="titulo-form">Fecha Modificación</label>
				 <?=$arrPedido['fecha_mod']?>
			 </div>

		   <div class="form-group">
				<label class="titulo-form">Es dueño del suministro</label>
				<input type="checkbox" name="duenio" READONLY id="registro_duenio"  value="1"  <?=($arrPedido['duenio']==1) ? 'checked' : ''?> /></div>
			 </div>

				<div style="padding-top:5px"></div>
						
			<?php //+ CUEN  // ?>
			<div class="form-group">
				<label for="cuen_label <?=$css_cuen?>" class="titulo-form" id="cuen_label">CUEN  <span class="required">(*)</span></label>
				<input required="required" id="registro_cuen" name="registro[cuen]" style="width:20%"  maxlength="10" type="text" value="<?=$arrPedido['cliente_cuen']?>">
				<?php /* ?>
				<?php if(strlen($arrPedido['imagen_factura_g']) > 5) { ?>
					<input type="button" value="Foto" onClick="foto_cuen('<?=$arrPedido['imagen_factura_g']?>')" />
				<?php } else { ?>
					<input type="button" value="Foto" onClick="foto_cuen('<?=$arrPedido['imagen_factura']?>')" />
				<?php } ?>
				<?php */ ?>
			</div>	

			<div style="padding-top:5px"></div>

			<?php //+ DNI  // ?>
			<div class="control-group">
				<label for="cedula_label <?=$css_cedula?>" class="titulo-form" id="cedula_label">Cédula de Indentidad * <span class="required">(*)</span></label>
				<input required="required" id="registro_dni" READONLY name="registro[dni]" style="width:20%"  maxlength="15" type="text" value="<?=$arrPedido['cliente_dni']?>">
				<?php /* ?>
				<?php if(strlen($arrPedido['imagen_dni_frente_g']) > 5) { ?>
					<input type="button" value="Foto" onClick="foto_cedula('<?=$arrPedido['imagen_dni_frente_g']?>')" />
				<?php } else { ?>
					<input type="button" value="Foto" onClick="foto_cedula('<?=$arrPedido['imagen_dni_frente']?>')" />
				<?php } ?>
				<?php */?>
				<span id="cedula_error"></span>
			</div>	

			<div style="padding-top:5px"></div>

			<?php //+ Nombre  // ?>
			<div class="control-group">
				<label for="<?=$css_nombre?>" class="titulo-form">Nombre * <span class="required">(*)</span></label>
				<input required="required" class="" READONLY id="register_name" name="registro[nombre]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['cliente_nombre']?>">
			</div>	

			<div style="padding-top:5px"></div>

			<?php //+ Apellidos  // ?>
			<div class="control-group">
				<label for="<?=$css_nombre?>" class="titulo-form">Apellidos  <span class="required">(*)</span></label>
				<input required="required" class="" READONLY id="register_apellido" name="registro[apellido]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['cliente_apellido']?>">
			</div>	

			<div style="padding-top:5px"></div>
			
			<?php // TLEFONO FIJO ?>		
			<div class="control-group size1">
				<label for="<?=$css_telefono?>" class="titulo-form">Teléfono <span class="required">(*)</span></label>
				<input class="field" id="registro_telefono" READONLY name="registro[cliente_telefono]" size="30" type="text" class="" required="required" maxlength="10" value="<?=$arrPedido['cliente_telefono']?>">
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
				<input class="field" id="registro_email" READONLY name="registro[email]"  type="email" class="" style="width:50%" required="required" maxlength="30" value="<?=$arrPedido['cliente_email']?>">
			</div>

			<div style="padding-top:5px"></div>

			<div class="control-group size1">
			</div>
			
			<?php //+ LOCALIZACION ?>
			<div class="control-group size1">
				<label for="<?=$css_id_provincia?>" class="titulo-form">Provincia <span class="required">(*)</span></label>
				
			<select id="registro_id_provincia" READONLY name="registro[id_provincia]" onChange="provincias()">
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
				<select id="registro_id_canton" READONLY name="registro[id_canton]">
		 		 <option value="<?=$arrCanton['id']?>"><?=$arrCanton['nombre']?></option>
			</div>

			<div style="padding-top:5px"></div>

			<?php //+ Parroquia  // ?>
			<div class="control-group">
				<label for="<?=$css_nombre?>" class="titulo-form">Parroquia  </label>
				<input  class="" id="register_name" READONLY name="registro[parroquia]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['parroquia']?>">
			</div>

			<div style="padding-top:5px"></div>

			<?php //+ Barrio  // ?>
			<div class="control-group">
				<label for="<?=$css_barrio?>" class="titulo-form">Barrio/Urbanización </label>
				<input  class="" id="registro_barrio" READONLY name="registro[barrio]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['barrio']?>">
			</div>		

			<div style="padding-top:5px"></div>

			<?php //+ Calle  // ?>
			<div class="control-group">
				<label for="<?=$css_cliente_calle?>" class="titulo-form">Calle Principal <span class="required"></span></label>
				<input  class="" id="registro_cliente_calle" READONLY name="registro[cliente_calle]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['cliente_calle']?>">
			</div>		

			<div style="padding-top:5px"></div>

			<?php //+ Calle numero  // ?>
			<div class="control-group">
				<label for="<?=$css_cliente_calle_numero?>" class="titulo-form">Número <span class="required"></span></label>
				<input  class="" id="registro_cliente_calle_numero" READONLY name="registro[cliente_calle_numero]" style="width:20%"  maxlength="50" type="text" value="<?=$arrPedido['cliente_calle_numero']?>">
			</div>		

			<div style="padding-top:5px"></div>

			<?php //+ Calle Secundaria  // ?>
			<div class="control-group">
				<label for="<?=$css_cliente_calle_secundaria?>" class="titulo-form">Calle Secundaria <span class="required"></span></label>
				<input  class="" id="registro_cliente_calle_secundaria" READONLY name="registro[cliente_calle_secundaria]" style="width:50%"  maxlength="150" type="text" value="<?=$arrPedido['cliente_calle_secundaria']?>">
			</div>		

			<div style="padding-top:5px"></div>

			<?php //+ Referencia  // ?>
			<div class="control-group">
				<label for="<?=$css_cliente_referencia?>" class="titulo-form">Referencia <span class="required"></span></label>
				<input  class="" id="registro_cliente_referencia" READONLY name="registro[cliente_referencia]" style="width:50%"  maxlength="250" type="text" value="<?=$arrPedido['cliente_referencia']?>">
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
					<select id="registro_marca" READONLY name="registro[marca]">
			 		 <option value="ECOLINE"  <?=($arrPedido['marca']=="ECOLINE") ? 'selected' : ''?>>ECOLINE</option>
					</select>
			<select id="registro_modelo" READONLY name="registro[modelo]">
		 	<option value="">-Seleccionar-</option>
			 		 <option value="SAFIRA"   <?=($arrPedido['modelo']=="SAFIRA") ? 'selected' : ''?>>SAFIRA</option>
			 		 <option value="IVANNA"   <?=($arrPedido['modelo']=="IVANNA") ? 'selected' : ''?>>IVANNA</option>
			 		 <option value="ELECKTRA" <?=($arrPedido['modelo']=="ELECKTRA") ? 'selected' : ''?>>ELECKTRA</option>
			 		 <option value="ATENAS"   <?=($arrPedido['modelo']=="ATENAS") ? 'selected' : ''?>>ATENAS</option>
			 		 <option value="ELISA"    <?=($arrPedido['modelo']=="ELISA") ? 'selected' : ''?>>ELISA</option>
			</select>
			<select id="registro_color" READONLY name="registro[color]" required="true">
			 	<option value="">-Seleccionar-</option>
			 		 <option value="INOX"   <?=($arrPedido['color']=="INOX") ? 'selected' : ''?>>INOX</option>
			 		 <option value="BLANCO" <?=($arrPedido['color']=="BLANCO") ? 'selected' : ''?>>BLANCO</option>
				</select>
			</div>

	
			<?php //- segunda parte // ?>

			<?php //+ Plazos // ?>
			<div class="control-group">
				<label for="shop_name <?=$css_plazos?>" class="titulo-form"> Plazos de Crédito</label>
				<select name="registro[cuotas]" READONLY id="registro[cuotas]">
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
				<label for="<?=$css_forma_pago?>" READONLY class="titulo-form"> Forma pago</label>
				<select name="registro[forma_pago]" id="registro[forma_pago]">
					 <option value="Efectivo" 					<?=($arrPedido['forma_pago']=="Efectivo") ? 'selected' : ''?>>Efectivo</option>
					 <option value="Tarjeta de Credito" <?=($arrPedido['forma_pago']=="Tarjeta de Credito") ? 'selected' : ''?>>Tarjeta de Crédito</option>
				</select>
			</div>				

			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Cuota Entrada</label>
				<input class="" id="registro_cuota_entrada" READONLY name="registro[cuota_entrada]"  maxlength="15" type="text" value="<?=$arrPedido['cuota_entrada']?>">
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
				<select name="registro[forma_pago_ollas]" READONLY id="registro[forma_pago_ollas]">
					 <option value="Efectivo" 					<?=($arrPedido['forma_pago_ollas']=="Efectivo") ? 'selected' : ''?>>Efectivo</option>
					 <option value="Tarjeta de Credito" <?=($arrPedido['forma_pago_ollas']=="Tarjeta de Credito") ? 'selected' : ''?>>Tarjeta de Crédito</option>
				</select>
			</div>	

		 <div style="clear:both"></div><br/><br/>

			<div class="control-group">
				<label for="<?=$css_promocion?>" class="titulo-form"> Promocion</label>
				<input type="checkbox" name="promocion" READONLY id="registro_promocion" value="1" <?=($arrPedido['promocion']==1) ? 'checked' : ''?> />
			</div>

			
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Medidor 220</label>
				<input type="checkbox" name="medidor220" READONLY id="registro_medidor220" value="1" <?=($arrPedido['medidor220']==1) ? 'checked' : ''?> />
			</div>

			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Circuito interno</label>
				<input type="checkbox" name="circuito_interno" READONLY  id="registro_circuito_interno" value="1"  <?=($arrPedido['circuito_interno']==1) ? 'checked' : ''?> />
			</div>

			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Ducha electrica</label>
				<input type="checkbox" name="ducha_electrica" READONLY id="registro_ducha_electrica" value="1"  <?=($arrPedido['ducha_electrica']==1) ? 'checked' : ''?> />
			</div>

			<?php // HORARIO // ?>
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Horario de recepción sugerido</label>
				<select name="registro[horario_recepcion]"  READONLY id="registro_orario_recepcion">
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
			<select name="registro[recepcion_confirmada_dia]" READONLY id="recepcion_confirmada_dia">
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
			<select name="registro[recepcion_confirmada_mes]" READONLY id="recepcion_confirmada_mes">
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
			<select name="registro[recepcion_confirmada_desde]" READONLY id="recepcion_confirmada_desde">
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
			<select name="registro[recepcion_confirmada_hasta]" READONLY id="recepcion_confirmada_hasta">
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
				<label for="<?=$css_forma_pago?>"  READONLYclass="titulo-form"> Contacto con</label>
				<input placeholder=""  id="registro_contacto_nombre" name="registro[contacto_nombre]" style="width:50%"  maxlength="100" type="text" value="<?=$arrPedido['contacto_nombre']?>">
			</div>

			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Parentesco</label>
				<select id="registro_contacto_parentesco" READONLY name="registro[contacto_parentesco]">
				 <option value="Propietario" <?=($arrPedido['contacto_parentesco']=="Propietario") ? 'selected' : ''?>>Propietario</option>
				 <option value="Conyugue"  <?=($arrPedido['contacto_parentesco']=="Conyugue") ? 'selected' : ''?>>Conyugue</option>
				 <option value="Hijos"  <?=($arrPedido['contacto_parentesco']=="Hijos") ? 'selected' : ''?>>Hijos</option>
				 <option value="Tío"  <?=($arrPedido['contacto_parentesco']=="Tio") ? 'selected' : ''?>>Tío</option>
				 <option value="Primo"  <?=($arrPedido['contacto_parentesco']=="Primo") ? 'selected' : ''?>>Primo</option>
				 <option value="Abuelos"  <?=($arrPedido['contacto_parentesco']=="Abuelos") ? 'selected' : ''?>>Abuelos</option>
				 <option value="Otro"  <?=($arrPedido['contacto_parentesco']=="Otro") ? 'selected' : ''?>> Otro</option>
				</select>
			</div>
									
			<div class="control-group">
				<label for="<?=$css_forma_pago?>" class="titulo-form"> Fecha y Hora del Contacto</label>
				<input placeholder="Fecha y Hora del Contacto" READONLY  id="registro_contacto_fecha" name="registro[contacto_fecha]" style="width:50%"  maxlength="50" type="text" value="<?=(strlen($arrPedido['contacto_fecha']) > 4) ? $arrPedido['contacto_fecha'] : $fecha_hora_actual ?>">
			</div>
			


			<div style="clear:both"></div><br/>
</div>	    

	<?php //- form de incidencia // ?>	
</div>
</form>

<?php
print "<script>cantones('".$arrPedido['id_canton']."', '".$arrPedido['id_provincia']."');</script>";
print "<script>initialize();</script>";

?>
</body>
</html>