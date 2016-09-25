var mapa = null;
var base_url = '';
var version = 3;

// 23/05/2012 23:24:27
function inicializarMapa(valoresMapa) {

    var latlng = new google.maps.LatLng(-34.397, 150.644);

    var myOptions = {
      zoom: 8,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

// 23/05/2012 23:24:20
function centrarMapa(latitud, longitud, zoom) {
	var point = new GLatLng(latitud, longitud);
	map.setCenter( point , zoom);
}

// 23/05/2012 23:26:50
function mostrarItem() {
	}

// 23/05/2012 23:26:47	
function agregarItemMapa( map, property ) {
	var	point = new GLatLng( property.latitud, property.longitud );
  
  if (!isNaN( property.latitud ) && !isNaN( property.longitud ))  {
  	 
  // propiedades
  var dragable = document.getElementById("dragable").value;
  
  if(dragable=='') {
   dragable = 'false';
  }
  
	map.clearOverlays();
	var marker = createMarker( point, dragable );
	map.addOverlay( marker );
	map.panTo( point );
  }
}	

function obtenerMarkerToArray(marker) {
 	 google.maps.event.addListener(marker, "drag", function(event) {
  	var point = marker.getPosition();

    var lat = point.lat();
		var lon = point.lng();
    return  point;
    
	 });
}

function addPropertyToMapByFullAddress() {
	//alert("En addPropertyToMapByAddress()");
	var full_address =  document.frm_dashboard_mapa.mapa_direccion.value;
	  alert("addPropertyToMapByFullAddress" + full_address);
	  // Create new geocoding object
		geocoder = new GClientGeocoder();
		// Retrieve location information, pass it to addToMap()
		geocoder.getLocations(full_address, addPropertyToMapByAddressCallback);
}	