// version 12/04/2014 14:46:30
// CONSTANTES
var debug = 0;

// gb 14-8-2009 CODIGOS DE CIUDAD
var BAIRES = 1;

// gb 18-8-2009 COORDENADAS DE CIUDADES
var BAIRES_LATITUD = -34.603712;
var BAIRES_LONGITUD = -58.381585;

// gb 18-8-2009 Agrego nivel de zoom default para cada ciudad
var ZOOM_LEVEL_BAIRES = 10;

// GLOBALES
var map;

//element =   document.getElementById("zoom_inicial"); 
//zoom_incial = element.value;

var zoom_inicial = 10;

var geocoder;

// SINOPSIS
// Carga el mapa
// KNOWN BUGS
function initialize() {
	  
 	if (GBrowserIsCompatible()) {
		map = new GMap2( document.getElementById("map"), { mapTypes : [ G_NORMAL_MAP,G_HYBRID_MAP ] } );
		var position = new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,10));
		map.addControl(new GSmallMapControl(), position);
		// map.addControl(new GLargeMapControl3D(), position);
		map.addControl(new GMapTypeControl());
		map.enableContinuousZoom();
		map.enableScrollWheelZoom();		
		// Centro el mapa
		centerMap();
		// Add markers and polylines
		initMapEvent();
		// Muestra la propiedad
		showProperty();		
	}	
}

// inicializa el mapa en una ciudad
function inicializarCiudad(zoom_inicial, latitude, longitude) {

	//var zoom_incial_local = document.frm_dashboard_mapa.zoom_inicial.value;	
  //var latitude_local = document.frm_dashboard_mapa.latitude_incial.value;	
	//var longitude_local = document.frm_dashboard_mapa.longitude_incial.value;	
			  
 	if (GBrowserIsCompatible()) {
		
		map = new GMap2( document.getElementById("map"), { mapTypes : [ G_NORMAL_MAP,G_HYBRID_MAP ] } );
		var position = new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,10));
		map.addControl(new GSmallMapControl(), position);
		// map.addControl(new GLargeMapControl3D(), position);
		map.addControl(new GMapTypeControl());
		map.enableContinuousZoom();
		map.enableScrollWheelZoom();		

		// Centro el mapa
		//alert(zoom_inicial);
		centerMapCiudad(zoom_inicial, latitude, longitude);
    // Agrega marker distinto para la ciudad
		//agregarMarkerCiudad(latitude, longitude);
		// Add markers and polylines
		initMapEvent();
		// Muestra los items
		showPropertyCiudad();		
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
	var zoom_incial_local = document.frm_dashboard_mapa.zoom_inicial.value;
	var point = new GLatLng(BAIRES_LATITUD, BAIRES_LONGITUD);
	alert("zoom" + zoom_incial_local);
	map.setCenter( point , zoom_incial_local);
}

function centerMapCiudad(zoom_incial, latidude, longitude)
{
	var point = new GLatLng(latidude, longitude);
	map.setCenter( point , zoom_incial);
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
function showProperty()
{	
	// gb 22-9 adapto
	var IA = document.frm_dashboard_mapa.ia.value;
	
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
		property.latitud = BAIRES_LATITUD;
		property.longitud = BAIRES_LONGITUD;
	}
	addPropertyToMap( map, property );
}

function showPropertyCiudad() {	
	var idx = document.frm_dashboard_mapa.ia.value;
	
	/*
	property = new PropiedadMapa();
	property.propiedad_id = idx;
	property.latitud = getLatitude();
	property.longitud = getLongitude();
	if ( isNaN( property.latitud ) || isNaN( property.longitud ) || property.latitud == 0.0 || property.logitud == 0.0 )
	{
		property.latitud = BAIRES_LATITUD;
		property.longitud = BAIRES_LONGITUD;
	}
	addPropertyToMap( map, property );
	*/
}

// SINOPSIS
// Agrega una propiedad al mapa (marker).
function addPropertyToMap( map, property ) {
	//alert( "Entrando a admin/addPropertyToMap: latitud = " + property.latitud + " longitud = " + property.longitud);
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

// SINOPSIS
// Agrega una propiedad al mapa (marker).
function addPropertyToMapCiudad( map, latitud, longitud, property ) {

	//alert( "Entrando a admin/addPropertyToMap: latitud = " + latitud + " longitud = " + longitud);
  if(latitud && longitud) {
  	
		var	point = new GLatLng( latitud, longitud );
  
  	// propiedades
  	var dragable = document.getElementById("dragable").value;
  	var infowindow = document.getElementById("infowindow").value;
    
		//map.clearOverlays();
		var marker = createMarker( point, false, infowindow, property );
		map.addOverlay( marker );
		map.panTo( point );
  }
}



function grabarDireccion() { 

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

function agregarMarkerCiudad(latitude, longitude) {
  var marker;
  var baseIcon = new GIcon();
  baseIcon.shadow = "http://alojamientoargentina.net/images/mapa.png";
  baseIcon.iconSize = new GSize(30, 49);
  baseIcon.shadowSize = new GSize(30, 30);
  baseIcon.iconAnchor = new GPoint(30, 49);
  baseIcon.infoWindowAnchor = new GPoint(9, 2);
  baseIcon.infoShadowAnchor = new GPoint(18, 49);
	var	point = new GLatLng( latitude, longitude);
	map.clearOverlays();
  marker = new GMarker(point, baseIcon, {draggable: false});
	map.addOverlay( marker );
	map.panTo( point )

	setLatitude(latitude);
  setLongitude(longitude);
    
  // asocio el evento al marker
  GEvent.addListener(marker, "click",function(){
			 map.openInfoWindowHtml(point,"<div style='font-size: 8pt; font-family: verdana'>Holasdsd</div>");
			 }
	 );
}

// carga en el mapa los items
// dependiendo de
// CIUDAD / TIPO
function cargar_item_tipo(id_ciudad,id_tipo) {
    
    var id_ciudad = id_ciudad;
    var id_tipo = id_tipo;

	  //arrTemp = arrId.split(",");		
	  ajax = creaAjax();	

  	// Va al servidor y trae la info de la propiedad sin reload
  	ajax.open("POST", "ajax/ax_items_tipo.php?id_ciudad="+id_ciudad+"&id_tipo="+id_tipo, true);
  	ajax.onreadystatechange = function() {
      
	    // se conecto, devuelve el texto con las propiedades
      if (ajax.readyState==4) 
      {
          
  	      //arrInfoWin = ajax.responseText.split(","); 
  	      var respuesta = ajax.responseText;
      		arrTemp = convertirTextToArray(respuesta);
           alert(respuesta);
           for( i=0; i < arrTemp.length; i++) {
				    var property = new PropiedadMapaUser();
  	  			property.idx = arrTemp[i][0];
  	  			property.titulo = arrTemp[i][1];
  	  			property.imagen = arrTemp[i][2];
  	  			property.direccion = arrTemp[i][3];
  	  			property.mapa_direccion = arrTemp[i][4];
  	  			property.latitude = arrTemp[i][5];
  	  			property.longitude = arrTemp[i][6];
  	  			property.tipo = arrTemp[i][7]; // item
  	  			//property.tipo_clave = arrTemp[i][8]; // tipo de alojamiento
            
            if(arrTemp[i][5] && arrTemp[i][6]) {
             addPropertyToMapCiudad( map, arrTemp[i][5], arrTemp[i][6], property );
            }
            
      	  } // end for    
      	  			
  	       // propiedad_div.innerHTML=arrInfoWin;
  	      // return arrInfoWin;
        
      } // readyState==4
    } // f
    ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    ajax.send(null);
    return;
} // end func


// carga en el mapa los items de las ciudades
function lugaresById() {

	  var form  = document.forms['frm_dashboard_mapa'];
	  var arrId = form['arrId'].value;
	  ajax = creaAjax();	

  	ajax.open("POST", "ajax/ax_lugares.php?id="+arrId, true);
  	ajax.onreadystatechange = function() {
      
         if (ajax.readyState==4) {
          
  	      //arrInfoWin = ajax.responseText.split(","); 
  	      var respuesta = ajax.responseText;
      		arrTemp = convertirTextToArray(respuesta);
          
           alert(ajax.responseText);
          
      		 for( i=0; i < arrTemp.length; i++) {

					  var property = new PropiedadMapaUser();
				
  	  			property.idx = arrTemp[i][0];
  	  			property.titulo = arrTemp[i][1];
  	  			property.imagen = arrTemp[i][2];
  	  			property.direccion = arrTemp[i][3];
  	  			property.mapa_direccion = arrTemp[i][4];
  	  			property.latitude = arrTemp[i][5];
  	  			property.longitude = arrTemp[i][6];
  	  			property.tipo = arrTemp[i][7]; // tipo de alojamiento
  	  			property.barrio = arrTemp[i][8]; //
  	  			property.descripcion_comercio = arrTemp[i][9]; //
  	  			property.categoria = arrTemp[i][10]; // 
  	  			property.links = arrTemp[i][11]; // permanentlink
  	  			//alert(property.tipo);       
  	  			     
            if(arrTemp[i][5] && arrTemp[i][6]) {
             addPropertyToMapCiudad( map, arrTemp[i][5], arrTemp[i][6], property );
            }
                       

      	  } // end for    
      	  			
  	      // propiedad_div.innerHTML=arrInfoWin;
  	      // return arrInfoWin;
      } 
    }
    ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    ajax.send(null);
    return;
} // end func


// SINOPSIS
// Crea un marker en el punto indicado
// KNOWN BUGS
// BITACORA 
// GB 22-09-2009 Revision para JLL
function createMarker( point, dragable, infowindow, property ) 
{
	
	/* gb 22-9 no personalizamos marker por ahora
	var icon = new GIcon();
	icon.image = "admin/images/map-pin-red.gif";
	icon.iconSize = new GSize(15, 33);
	icon.iconAnchor = new GPoint(7, 28);
	var markerOptions = { icon:icon, draggable: true };
	var marker = new GMarker(point, markerOptions);
	*/
	
  
	if(dragable == false) {
		var markerOptions = { draggable: false };
	} else {
		var markerOptions = { draggable: true };
  }
  	
	var marker = new GMarker(point, markerOptions);

	// GB 15-1-2008 cuando suelto el marker actualizo las coordenadas

  if(infowindow=='1') {

  GEvent.addListener(marker, "click", function() 
	{
		map.openInfoWindowHtml(point,  createInfowindowContent( property ));
	});
  
  }	
	
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
// Crea el contenido a mostrar al cliquear en un marker
// KNOWN BUGS
// BITACORA 
// ESTA MOSTRANDO TITULO + DIRECCION + CIUDAD
// SIN FOTO
function createInfowindowContent( property ) {
	var html = "";
	var form = document.forms['frm_dashboard_mapa'];
	var url_path = form['url_path'].value; 
	//alert("createInfowindowContent");
	
  /*
	var basepath = "http://www.alojamientoargentina.net/";
	var imagepath = "http://alojamientoargentina.net/adjuntos/item/";
	var path = "http://alojamientoargentina.net/images/";
	var image_preview;
	var imagen_default = 'default_' + property.tipo_clave + '.gif';
	var campolink = "plink"+property.idx;
  var plink = form[campolink].value;
		
	if(property.imagen.length > 4) {
		image_preview = imagepath + property.imagen;
	} else {
		image_preview = path + imagen_default;
	}
	*/
  
  	
	html += '<div style="width:400;height:100px" align="left">';
	html += "<div> <a href='' class='link-item' align='left'><strong>"+property.titulo+"</strong></a></div>";
	html += "<div class='texto-general11' align='left'>"+property.mapa_direccion+"</div>";
	//html += "<img align='left' style='width:80px; margin:5px; border: 1px solid gray;' src='"+image_preview+"' />";
	html += "<div style='padding-top:5px'></div>";
	html += "<div class='texto-general11' align='left'><strong>"+property.categoria+"</div>";
	html += "<div style='padding-top:10px'></div>";
  html += "<div align='left'><a href='"+property.links+"' class='link-item11' target='_blank'>Ver Detalles</a></div>";
	html += "</div>";
	//alert("3");	
	return html;
}

function makeIcon (image) {
	var icon = new GIcon();
	icon.image = image;
	icon.shadow = "images/shadow.png";
	icon.iconSize = new GSize(16, 16);
	icon.shadowSize = new GSize(24, 16);
	icon.iconAnchor = new GPoint(8, 16);
	icon.infoShadowAnchor = new GPoint(0, 0);
	icon.infoWindowAnchor = new GPoint(8, 1);	
	return icon;
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
  this.ObtenerPropiedad = function ()  {   
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

function convertirTextToArray(texto) {
  	var arrJs = new Array()
  	var arrElemento  = new Array();
  	var temp = new Array();
  	var temp = texto.split("<>");
	
	// gb 1-3-2008 si el texto viene vacio temp.length es 1, evitamos este error. Esto anda OK.
	if ( texto == "" )
	{
		// alert (" arrElemento.length = " + arrElemento.length );
		return arrElemento;
	}
 	 
   	// iterar el array por cada propiedad
   	// alert( "En convertirTextToArray " + temp.length );
   	var n = temp.length;
   	for(i = 0; i < n; i++)
   	{
		arrElemento[i] = temp[i].split("|"); 
   	}
	
   	return arrElemento;
}

function creaAjax()
{
	var xmlhttp=false;
	try
	{
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			// Creacion del objet AJAX para IE
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); }

	return xmlhttp;
}

function PropiedadMapaUser() { 
	var idx;
	var titulo;
	var direccion;
	var imagen;
	var latitud;
	var longitud;
	var mapa_direccion;
	var tipo;
	var tipo_clave;

	var descripcion_comercio;
	var barrio;
	var categoria;
	var links;
					
	this.idx = idx;
	this.latitud = latitud;   
	this.longitud = longitud;
	this.mapa_direccion = mapa_direccion;
	this.titulo = titulo;
	this.imagen = imagen;
	this.tipo = tipo;
	this.tipo_clave = tipo_clave;

	this.descripcion_comercio = descripcion_comercio;
	this.barrio = barrio;
	this.categoria = categoria;
	this.links = links;
}