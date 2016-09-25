<?php
include_once("config/conn.php");
declareRequest('accion','usuario','clave');
loadClasses('BackendUsuario', 'Tipo', 'Localizacion', 'Item');
global $BackendUsuario, $Tipo, $Localizacion, $Item;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_REQUEST['id']) ? $_REQUEST['id'] : 0; // item activo
$accion = ($_POST['accion']) ? $_POST['accion'] : null; // accion

switch($accion) {
   case 'grabar':
 		$latitude  = $_POST['latitude'];
		$longitude = $_POST['longitude'];
   	print "<script>window.parent.cargarMapa('".$latitude."','".$longitude."');</script>";
	  print "<script>window.parent.jQuery.fancybox.close();</script>";
   break;
}

if($id > 0) {
  $arrActual = $Item->obtener($id);
}
?>

<html>
	<head><title>Ubicacion en el Mapa</title>
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->
	<!-- Carlos Pellegrini 200 - Pinamar - Buenos Aires Costa Atlántica -->
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
  <script type="text/javascript">
  	 // generico
		var SAN_MIGUEL_LATITUD  = -37.0191310;
		var SAN_MIGUEL_LONGITUD = -56.9132805;
		var iconLugares =  'assets/img/maps/icon_lugar.png';
		var map;
		var geocoder;
		var puntoInicial;
		
		function initialize() {
		 
		 geocoder = new google.maps.Geocoder();
		 // tratar de localizar
		 // PENDIENTE	
		 var formParent = parent.document.forms['frm_dashboard_mapa'];
		 var calle  =  window.parent.$("#item_calle").val();	
		 var numero =  window.parent.$("#item_calle_numero").val();	
	   var ciudad =  window.parent.$("#item_id_ciudad option:selected").text(); 		//
	   var provincia = "Buenos Aires";
	   var direccion_mapa = calle + ", " + numero + ", " + ciudad + ", " + provincia + ", Argentina"; 
     //alert(direccion_mapa);
			     
		 var form = document.forms['frm_dashboard_mapa'];
		 var lat = form['latitude'].value;
		 var lon = form['longitude'].value;
		  
		 if(lat.length >=8 && lon.length  >= 8  ) {
		 	 puntoInicial = new google.maps.LatLng(lat,lon);
		 } else {
			 puntoInicial = new google.maps.LatLng(SAN_MIGUEL_LATITUD,SAN_MIGUEL_LONGITUD);
		 }
	
		 var myOptions = {
		  zoom: 12,
		  center: puntoInicial,
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		 }
		
		    map = new google.maps.Map(document.getElementById("map"), myOptions);

		    geocoder.geocode( { 'address': direccion_mapa}, function(results, status) {
  		  if (status == google.maps.GeocoderStatus.OK) {
 		  	 puntoInicial = results[0].geometry.location;
      	 //map.setCenter(results[0].geometry.location);
         /*
         var marker = new google.maps.Marker({
          map: map,
          position: puntoInicial
	       });
	       */
  	 	  } else {
      		alert('Geocode was not successful for the following reason: ' + status);
    		}
  		});
			
			/*
			puntoInicial = puntoInicial.toString();
			puntoInicial = puntoInicial.replace("(", ""); 
			puntoInicial = puntoInicial.replace(")", ""); 
			var temp = puntoInicial.split(',');
			var new_lat = parseFloat(temp[0]);
			var new_lon = parseFloat(temp[1]);
			puntoInicial = new google.maps.LatLng(new_lat,new_lon);
			*/
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
		/* 
  */
		} // fin f		

function addPropertyToMapByAddress()
{
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

function addPropertyToMapByAddressCallback(response)
{
	// Retrieve the object
	place = response.Placemark[0];
	// Retrieve the latitude and longitude
	var latitud = place.Point.coordinates[1];
	var longitud = place.Point.coordinates[0];
	

}

function grabar() {
	 var form = document.forms['frm_dashboard_mapa'];
	 form.submit();
}
</script>
</head>
<body onLoad="initialize();">

     <form name="frm_dashboard_mapa" id="frm_dashboard_mapa" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">
      <input type="hidden" name="id" value="<?=$id?>">
      <input type="hidden" name="ia" value="<?=$ia?>">
      <input type="hidden" name="idx" value="<?=$idx?>">

      <?php // usuario registrado o anonimo // ?>
      <input type="hidden" name="id_usuario">

      <input type="hidden" name="accion" value="grabar">				
      <input type="hidden" name="estado" value="1">		
      <input type="hidden" name="activo" value="1">		
      <input type="hidden" name="zoom_inicial" value="8">		
      <input type="hidden" name="dragable" id="dragable" value="true">		
      <input type="hidden" name="modo" value="enviar">	
     
     <input type="hidden" id="ciudad_mapa" name="ciudad_mapa" value="Quito">	
     <input type="hidden" id="pais_mapa" name="pais_mapa" value="Ecuador">	
  
		 
		 <div style="padding:10px">

		  <?php // + Google Maps // ?>
			Latitud: <input type="text" id="latitude" name="latitude" value="<?=$arrActual['latitude']?>" 	maxlength="10"  size="20">		
			Longitud: <input type="text" id="longitude"  name="longitude"  value="<?=$arrActual['longitude']?>"  maxlength="10" size="20">
		  <input type="button" value="Grabar" onClick="grabar()" />
		  <div id="map" style="width:100%; height:600px; BORDER: #a7a6aa 1px solid; overflow:hidden; display:block"></div>
			<?php // - Google Maps // ?>

		 </div>			
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
	<script>
		$(document).ready(function() {
			//MapGoogle.init();
			FormPlugins.init();
		});
		
		$('#search').live('click', function() {
	
	    var address = $('#item_direccion').val();
	    var ciudad = $('#ciudad_mapa').val();
	    var pais = $('#pais_mapa').val();
	    var address_mapa = address + ", " + ciudad + ", " + pais;
	     
	    var geocoder = new google.maps.Geocoder();
	    geocoder.geocode({ 'address': address_mapa}, geocodeResult);
	});

	function geocodeResult(results, status) {
	    if (status == 'OK') {
			map.setCenter(results[0].geometry.location);
	        $('#latitude').text(results[0].geometry.location.lat());
					$('#longitude').text(results[0].geometry.location.lng());
	        map.fitBounds(results[0].geometry.viewport);
	        marker.setPosition(results[0].geometry.location);
	    } else {
	        alert("Geocoding no tuvo éxito debido a: " + status);
	    }
	}
	</script>
</body>
</html>