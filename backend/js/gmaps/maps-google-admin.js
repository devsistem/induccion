// SINOPSIS
// Prueba de concepto de Custom Maps 
// CREADO POR
// infopins.com
// KNOWN BUGS
// BITACORA 
// GB 22-09-2009 Creacion en base a oasibatest/maps-google-admin-9.js
// GB 22-09-2009 Junto con oasisbatest/maps-google-common-9.js y simplifico

// CONSTANTES
var debug = 0;

// gb 14-8-2009 CODIGOS DE CIUDAD
var BAIRES = 1;

// gb 18-8-2009 COORDENADAS DE CIUDADES
var BAIRES_LATITUD = -34.603682;
var BAIRES_LONGITUD = -58.381004;

// gb 18-8-2009 Agrego nivel de zoom default para cada ciudad
var ZOOM_LEVEL_BAIRES = 11;

// GLOBALES
var map;

// SINOPSIS
// Carga el mapa
// KNOWN BUGS
// BITACORA 
// GB 16-12-2007 Creacion
function initialize() 
{
	
  	if (GBrowserIsCompatible()) 
  	{
		map = new GMap2( document.getElementById("map"), { mapTypes : [ G_NORMAL_MAP,G_HYBRID_MAP ] } );
		var position = new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,10));
		map.addControl(new GSmallMapControl(), position);
		// map.addControl(new GLargeMapControl3D(), position);
		map.addControl(new GMapTypeControl());
		map.enableContinuousZoom();
		// map.enableScrollWheelZoom();		
		// Centro el mapa
		centerMap();
		// Add markers and polylines
		initMapEvent();
		// Muestra la propiedad
		showProperty();		
	}	
}

// SINOPSIS
// Inicializa eventos del mapa
// KNOWN BUGS
// BITACORA 
// GB 22-09-2009 Creacion
function initMapEvent()
{
}


// SINOPSIS
// Mueve mapa al centro elegido
// BITACORA
// GB 22-09-2009 Creacion
function centerMap()
{
	// alert( "On centerMap()");
	var point = new GLatLng(BAIRES_LATITUD, BAIRES_LONGITUD);
	map.setCenter( point , ZOOM_LEVEL_BAIRES);
}

// SINOPSIS
// Auxiliares para acceder a variables del documento
// KNOWN BUGS
// BITACORA 
// GB 22-09-2009 Creacion
function getLatitude()
{
	return document.frm_dashboard_mapa.latitude.value;
	// return BAIRES_LATITUD;
}

function getLongitude()
{
	return document.frm_dashboard_mapa.longitude.value;
	// return BAIRES_LONGITUD;
}

function setLatitude( latitude )
{
	document.frm_dashboard_mapa.latitude.value = latitude;
}

function setLongitude( longitude )
{
	document.frm_dashboard_mapa.longitude.value = longitude;
}

// SINOPSIS
// En admin, showProperties solo trae datos de la propiedad que se esta editando (una sola y no todas)
// PARAM map
// KNOWN BUGS
// BITACORA 
// GB 16-12-2007 Creacion
// AG 06-01-2008 Agregado de llamado a funcion AJAX para traer 1 propiedad
// Nota - En el caso de querer cargar todos los markers, cambiar la llamada AJAX
// GB 22-09-2009 Revision para JLL... falta
function showProperty()
{	
	// gb 22-9 adapto
	var IA = document.frm_dashboard_mapa.idx.value;
	
	// alert (IA);
	property = new PropiedadMapa();
	property.propiedad_id = IA;
	// GB 21-8-2009 en el form ya estan seteadas las coordenadas de la propiedad si existe, o del centro de la ciudad si es nueva
	property.latitud = getLatitude();
	property.longitud = getLongitude();
	// GB Por seguridad va este chequeo. Si no hay coordenadas quiere decir que se perdieron en algun lado
	// if ( isNaN( property.latitud ) || isNaN( property.longitud ) )
	if ( isNaN( property.latitud ) || isNaN( property.longitud ) || property.latitud == 0.0 || property.logitud == 0.0 )
	{
		// 22-9
		// alert("No hay coordenadas en el form");
		// return;
		property.latitud = BAIRES_LATITUD;
		property.longitud = BAIRES_LONGITUD;
	}
	addPropertyToMap( map, property );
}

// SINOPSIS
// Agrega una propiedad al mapa (marker).
// KNOWN BUGS
// BITACORA 
// GB 17-12-2007 Creacion
// function addPropertyToMap( map, point, idx, direccion_publica )
// GB 22-09-2009 Revision para JLL
function addPropertyToMap( map, property ) {
	//alert( "Entrando a admin/addPropertyToMap: latitud = " + property.latitud + " longitud = " + property.longitud);
	var	point = new GLatLng( property.latitud, property.longitud );

  // propiedades
  var dragable = document.getElementById("dragable").value;

	map.clearOverlays();
	var marker = createMarker( point, dragable );
	map.addOverlay( marker );
	map.panTo( point );
}


function grabarDireccion()
{ 

	var element = document.getElementById("direccion_perfil");
	if ( element != null )
	{
		var address = element.value;
		address = address + ', Buenos Aires';
	}	
}

// SINOPSIS
// Agrega una propiedad al mapa en forma de marker dada una direccion.
// KNOWN BUGS
// BITACORA 
// GB 5-10-2009 Creacion
function addPropertyToMapByAddress()
{
	//alert("En addPropertyToMapByAddress()");
	var element = document.getElementById("direccion");
	var ciudad_valor =  document.frm_dashboard_mapa.id_ciudad.value;
	
	ciudad_temp = ciudad_valor.split("|");
  ciudad_valor = ciudad_temp[1]

	if ( element != null )
	{
		var address = element.value;
		address = address + ', ' + ciudad_valor;
	  // Create new geocoding object
		geocoder = new GClientGeocoder();
		// Retrieve location information, pass it to addToMap()
		geocoder.getLocations(address, addPropertyToMapByAddressCallback);
	}
}

function addPropertyToMapByFullAddress()
{
	//alert("En addPropertyToMapByAddress()");
	var full_address =  document.frm_dashboard_mapa.full_address.value;
	
	  // Create new geocoding object
		geocoder = new GClientGeocoder();
		// Retrieve location information, pass it to addToMap()
		geocoder.getLocations(full_address, addPropertyToMapByAddressCallback);
}

// SINOPSIS
// Callback de addPropertyToMapByAddress. Obtiene las coordenadas o punto en el mapa dada una direccion.
// KNOWN BUGS
// BITACORA 
// GB 5-10-2009 Creacion
function addPropertyToMapByAddressCallback(response)
{
	// Retrieve the object
	place = response.Placemark[0];
	// Retrieve the latitude and longitude
	var latitud = place.Point.coordinates[1];
	var longitud = place.Point.coordinates[0];
	
	// alert("En addPropertyToMapByAddressCallback()");

	// creo propiedad para agregar a mapa
	p = new PropiedadMapa();
	p.latitud = latitud;
	p.longitud = longitud;
	addPropertyToMap( map, p );
	
	// seteo coordenadas en form
	setLatitude(latitud);
  setLongitude(longitud);
}


// SINOPSIS
// Crea un marker en el punto indicado
// KNOWN BUGS
// BITACORA 
// GB 22-09-2009 Revision para JLL
function createMarker( point, dragable ) 
{
	/* gb 22-9 no personalizamos marker por ahora
	var icon = new GIcon();
	icon.image = "admin/images/map-pin-red.gif";
	icon.iconSize = new GSize(15, 33);
	icon.iconAnchor = new GPoint(7, 28);
	var markerOptions = { icon:icon, draggable: true };
	var marker = new GMarker(point, markerOptions);
	*/
	if(dragable=='false')
	var markerOptions = { draggable: false };
	else
	var markerOptions = { draggable: true };
		
	var marker = new GMarker(point, markerOptions);
	// GB 15-1-2008 cuando suelto el marker actualizo las coordenadas
	GEvent.addListener(marker, "dragend", function() 
	{
		var newpoint = marker.getPoint();
		map.panTo(newpoint);
		setLatitude(newpoint.y);
      	setLongitude(newpoint.x);
	});
	
	return marker;	
}
 
// SINOPSIS
// Constructor de objeto Propiedad
// KNOWN BUGS
// BITACORA 
// AG 18-12-2007 Creacion
// GB 18-12-2007 Revision, le faltaria pasar parametros en la creacion
// GB 21-08-2009 *** OJO CON ESTA QUE SE REPITE EN maps-google-common.js!
// GB 22-09-2009 Revision para JLL
function PropiedadMapa() 
{ 
	var propiedad_id;
	var calle_publica;
	var latitud;
	var longitud;
	this.propiedad_id = propiedad_id;   
	this.calle_publica = calle_publica; 
	this.latitud = latitud;   
	this.longitud = longitud; 
}	
	
// SINOPSIS
// Mueve el mapa centrandolo en la propiedad
// KNOWN BUGS
// BITACORA 
// gb 22-4-2008 le cambiamos el nombre. hay otra en ...user_2.js
// function centerMap()
// GB 22-09-2009 Revision para JLL
function centerMapToProperty()
{
	var	point = new GLatLng( getLatitude(), getLongitude() );
	map.panTo( point );
}


function Propiedad(propiedad_id) {   

  this.propiedad_id = propiedad_id;
    
  // metodos
  // Este metodo obtiene del servidor la entidad propiedad
  this.ObtenerPropiedad = function () 
  {   
  var propiedad_div = document.getElementById("propiedad");
  ajax = creaAjax();	

  // Va al servidor y trae la info de la propiedad sin reload
  ajax.open("POST", "AXPropiedad.php?ia="+this.propiedad_id, true);
  ajax.onreadystatechange = function() {
     
      if (ajax.readyState==1) {

       } else if (ajax.readyState==4){
          
  	      arrInfoWin = ajax.responseText.split(","); 
  	      propiedad_div.innerHTML=arrInfoWin;
  	      return arrInfoWin;
        } 
    }
    ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    ajax.send(null);
    return;	     
  }   
}