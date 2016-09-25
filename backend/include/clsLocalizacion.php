<?php
class Localizacion { 	
// paises
function obtener_paises($activo=null, $order_by=null, $order=null,$filtro='id') {
 global $link;	

 if(strlen($order_by) == 0) {

   if($filtro && $order=='d')
	    $order_by = "ORDER BY P.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY P.$filtro ASC";
	 else
 	    $order_by = "ORDER BY P.activo DESC";
 }	 
   	
 $q = "SELECT P.* "
    . "FROM paises AS P WHERE 1 "  
	  . ($activo ?  "AND P.activo=".$activo." " : null)
	  . "".$order_by."";
	return @mysql_query($q, $link);  
}

function obtener_pais($id=null) {
 global $link;	

 $q = "SELECT P.* "
    . "FROM paises AS P " 
    . "WHERE 1 " 
	  . ($id ?  "AND P.id=".$id." " : null)
	  . " LIMIT 1 ";
  $r = @mysql_query($q, $link);
  return @mysql_fetch_array($r); 
}

function grabar_pais($campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $codigo = limpiar_string($campos['codigo']);
 $codigo2 = limpiar_string($campos['codigo2']);
 
 $q = "INSERT INTO paises (nombre, codigo, codigo2) VALUES ('".escapeSQL($nombre)."', '".escapeSQL($codigo)."', '".escapeSQL($codigo2)."') ";
 $r = @mysql_query($q, $link);
 return @mysql_insert_id($link);
} 

function editar_pais($id=null,$campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $codigo = limpiar_string($campos['codigo']);
 $codigo2 = limpiar_string($campos['codigo2']);
 
 $q = "UPDATE paises SET nombre='".escapeSQL($nombre)."', codigo='".escapeSQL($codigo)."', codigo2='".escapeSQL($codigo2)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
} 

function eliminar_pais($id=null) {
 global $link;	
 $q = "DELETE FROM paises WHERE id='".$id."'";
 $result = $db->query($q);
 return $id;
}

function publicar_pais($id=null,$campo=null) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q 		= "UPDATE paises SET activo='".$campo."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
}
 
// provincias
function obtener_provincias($id_pais=null, $activo=null, $order_by=null, $order=null,$filtro='id') {
 global $link;	

 if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
	    $order_by = " ORDER BY l.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = " ORDER BY l.$filtro ASC";
	 else
 	    $order_by = " ORDER BY l.activo DESC";
 } else {
 	    $order_by = " ORDER BY l.nombre ASC";
 }
   	
 $q = "SELECT l.* "
    . "FROM provincias AS l "  
    . "WHERE 1 "
	  . ($activo ?  "AND l.activo='".$activo."' " : null)
	  . "".$order_by."";
	//  print $q;
 return @mysql_query($q, $link);  	  
}

function obtener_provincia($id=null) {
 global $link;	
 $q = "SELECT P.* FROM provincias AS P " 
    . "WHERE 1 " 
	  . ($id ?  "AND P.id=".$id." " : null)
	  . " LIMIT 1 ";
     $r = @mysql_query($q, $link);
     return @mysql_fetch_array($r);	  
}

function grabar_provincia($campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $codigo = limpiar_string($campos['codigo']);
 $id_pais = limpiar_string($campos['id_pais']);
 
 $q = "INSERT INTO provincias (nombre, codigo, id_pais) VALUES ('".escapeSQL($nombre)."', '".escapeSQL($codigo)."', '".escapeSQL($id_pais)."') ";
 $r = @mysql_query($q, $link);
 return @mysql_insert_id($link);
} 

function editar_provincia($id=null,$campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $codigo = limpiar_string($campos['codigo']);
 $id_pais = limpiar_string($campos['id_pais']);
 
 $q = "UPDATE provincias SET nombre='".escapeSQL($nombre)."', codigo='".escapeSQL($codigo)."', id_pais='".escapeSQL($id_pais)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
} 

function eliminar_provincia($id=null) {
 global $link;	
 $q = "DELETE FROM provincias WHERE id='".$id."'";
 $r = @mysql_query($q, $link);
 return $r;
}

function publicar_provincia($id=null,$campo=null) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q 		= "UPDATE provincias SET activo='".escapeSQL($campo)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
}

// ciudad
function obtener_ciudades( $id_provincia=null, $activo=null, $order_by=null, $order=null, $filtro='id') {
 global $link;	

 if(strlen($order_by) == 0) {

   if($filtro && $order=='d')
	    $order_by = "ORDER BY c.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY c.$filtro ASC";
	 else
 	    $order_by = "ORDER BY c.activo DESC, c.nombre ASC";
 }	 
   	
 $q = "SELECT c.*, p.nombre AS provincia "
    . "FROM ciudades AS c "  
    . "LEFT JOIN provincias p ON p.id=c.id_provincia "  
    . "WHERE 1 "   
	  . ($activo ?  "AND c.activo='".$activo."' " : null)
	  . ($id_provincia ? "AND c.id_provincia='".$id_provincia."' " : null)
	  . "".$order_by."";
	return @mysql_query($q, $link);  	  
}

function obtener_ciudad($id=null) {
 global $link;	

 $q = "SELECT c.* "
    . "FROM ciudades AS c " 
    . "WHERE 1 " 
	  . ($id ?  "AND c.id=".$id." " : null)
	  . " LIMIT 1 ";
     $r = @mysql_query($q, $link);
     return @mysql_fetch_array($r);  
}

function grabar_ciudad($campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $id_pais = $campos['id_pais'];
 $id_provincia = $campos['id_provincia'];
 $id_region = $campos['id_region'];
 $activo = $campos['activo'];

 $inversion = ($campos['inversion']=='1') ? '1' : '0';
 $venta = ($campos['venta']=='1') ? '1' : '0';
 $alquiler = ($campos['alquiler']=='1') ? '1' : '0';
    
 $q = "INSERT INTO ciudades (inversion, venta, alquiler, nombre, id_provincia, id_region, id_pais, activo) VALUES ('".escapeSQL($inversion)."', '".escapeSQL($venta)."', '".escapeSQL($alquiler)."', '".escapeSQL($nombre)."', '".escapeSQL($id_provincia)."', '".escapeSQL($id_region)."',  '".escapeSQL($id_pais)."', '".escapeSQL($activo)."') ";
 $r = @mysql_query($q, $link);
 return @mysql_insert_id($link);
} 

function editar_ciudad($id=null,$campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $id_provincia = $campos['id_provincia'];
 $activo = $campos['activo'];
 
 $inversion = ($campos['inversion']=='1') ? '1' : '0';
 $venta = ($campos['venta']=='1') ? '1' : '0';
 $alquiler = ($campos['alquiler']=='1') ? '1' : '0';
 
 $q = "UPDATE ciudades SET alquiler='".escapeSQL($alquiler)."', venta='".escapeSQL($venta)."', inversion='".escapeSQL($inversion)."', nombre='".escapeSQL($nombre)."', id_region='".escapeSQL($id_region)."', id_provincia='".escapeSQL($id_provincia)."', id_pais='".escapeSQL($id_pais)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
} 

function eliminar_ciudad($id=null) {
 global $link;	
 $q = "DELETE FROM sys_ciudad WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
}

function publicar_ciudad($id=null,$campo=null) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q 		= "UPDATE ciudades SET activo='".escapeSQL($campo)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
}

// zona

// barrio
function obtener_barrios_con_propiedades($porPagina=null, $paginacion=null, $palabra=null, $order=null, $filtro=null, $activo=null, $estado=null, $id_localidad=null, $id_zona=null, $tiene_zona=null, $id_provincia=null) {
 global $link;	

 if(strlen($order_by) == 0) {

   if($filtro && $order=='d')
	    $order_by = "ORDER BY l.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY l.$filtro ASC";
	 else
 	    $order_by = "ORDER BY l.activo DESC, l.nombre ASC";
 }	 
   	
// $q = "SELECT L.*, P.nombre AS provincia, (SELECT COUNT(*) FROM item as cantidad WHERE id_barrio=L.id) "
 $q = "SELECT l.*, p.nombre AS provincia, C.nombre AS ciudad, Z.nombre AS zona, lo.nombre AS localidad "
    . "FROM barrios AS l "  
    . "LEFT JOIN provincias p ON p.id=l.id_provincia "    
    . "LEFT JOIN ciudades C ON C.id=l.id_ciudad "    
    . "LEFT JOIN zonas Z ON Z.id=l.id_zona "    
    . "LEFT JOIN localidades lo ON lo.id=l.id_localidad " 
	  . "WHERE 1 "
		. "AND l.id IN (SELECT I.id_barrio FROM item I) "
	  . ($activo ?  "AND l.activo='".$activo."' " : null)
	  . ($id_pais ? "AND l.id_pais='".$id_pais."' " : null)
	  . ($id_ciudad ? "AND l.id_ciudad='".$id_ciudad."' " : null)
	  . ($id_provincia ? "AND l.id_provincia='".$id_provincia."' " : null)
	  . ($zona_id ? "AND l.zona_id='".$zona_id."' " : null)
	  . ($tiene_zona ? "AND l.zona_id=0 " : null)
	  . "".$order_by."";
	  //print $q;
	return @mysql_query($q, $link);    
}

// barrio
function obtener_barrios($porPagina=null, $paginacion=null, $palabra=null, $order=null, $filtro=null, $activo=null, $estado=null, $id_localidad=null, $id_zona=null, $tiene_zona=null, $id_provincia=null) {
 global $link;	

 if(strlen($order_by) == 0) {

   if($filtro && $order=='d')
	    $order_by = "ORDER BY l.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY l.$filtro ASC";
	 else
 	    $order_by = "ORDER BY l.activo DESC, l.nombre ASC";
 }	 
   	
// $q = "SELECT L.*, P.nombre AS provincia, (SELECT COUNT(*) FROM item as cantidad WHERE id_barrio=L.id) "
 $q = "SELECT l.*, p.nombre AS provincia, C.nombre AS ciudad, Z.nombre AS zona, lo.nombre AS localidad "
    . "FROM barrios AS l "  
    . "LEFT JOIN provincias p ON p.id=l.id_provincia "    
    . "LEFT JOIN ciudades C ON C.id=l.id_ciudad "    
    . "LEFT JOIN zonas Z ON Z.id=l.id_zona "    
    . "LEFT JOIN localidades lo ON lo.id=l.id_localidad " 
	  . "WHERE 1 "
	  . ($activo ?  "AND l.activo='".$activo."' " : null)
	  . ($id_pais ? "AND l.id_pais='".$id_pais."' " : null)
	  . ($id_ciudad ? "AND l.id_ciudad='".$id_ciudad."' " : null)
	  . ($id_provincia ? "AND l.id_provincia='".$id_provincia."' " : null)
	  . ($zona_id ? "AND l.zona_id='".$zona_id."' " : null)
	  . ($tiene_zona ? "AND l.zona_id=0 " : null)
	  . "".$order_by."";
	  //print $q;
	return @mysql_query($q, $link);    
}

function obtener_barrio($id=null) {
 global $link;	
 
 if($id > 0)
 {
 $q = "SELECT P.* FROM barrios AS P " 
    . "WHERE 1 " 
	  . ($id ?  "AND P.id=".$id." " : null)
	  . " LIMIT 1 ";
     $r = @mysql_query($q, $link);
     return @mysql_fetch_array($r); 
 }
}

function grabar_barrio($campos=null) {
 global $link;	

 $nombre = limpiar_string($campos['barrio']['nombre']);
 $latitude = limpiar_string($campos['latitude']);
 $longitude = limpiar_string($campos['longitude']);
 $id_pais = $campos['barrio']['id_pais'];
 $id_provincia = $campos['barrio']['id_provincia'];
 $id_ciudad = $campos['barrio']['id_ciudad'];
 $id_localidad = $campos['barrio']['id_localidad'];
 $id_zona = $campos['barrio']['id_zona'];
 $activo = $campos['barrio']['activo'];
   
 $q = "INSERT INTO barrios (nombre, id_localidad, id_provincia, id_ciudad, id_pais, id_zona, latitude, longitude, activo) VALUES ('".escapeSQL($nombre)."', '".escapeSQL($id_localidad)."', '".escapeSQL($id_provincia)."', '".escapeSQL($id_ciudad)."',  '".escapeSQL($id_pais)."', '".escapeSQL($id_zona)."', '".escapeSQL($latitude)."', '".escapeSQL($longitude)."', '".escapeSQL($activo)."') ";
 $r = @mysql_query($q, $link);
 return @mysql_insert_id($link);
} 

function editar_barrio($id=null,$campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['barrio']['nombre']);
 $latitude = limpiar_string($campos['latitude']);
 $longitude = limpiar_string($campos['longitude']);
 $id_pais = $campos['barrio']['id_pais'];
 $id_provincia = $campos['barrio']['id_provincia'];
 $id_ciudad = $campos['barrio']['id_ciudad'];
 $id_localidad = $campos['barrio']['id_localidad'];
 $id_zona = $campos['barrio']['id_zona'];
 $activo = $campos['barrio']['activo'];
 
 $q = "UPDATE barrios SET nombre='".escapeSQL($nombre)."', id_localidad='".escapeSQL($id_localidad)."', id_zona='".escapeSQL($id_zona)."',  id_ciudad='".escapeSQL($id_ciudad)."', id_provincia='".escapeSQL($id_provincia)."', id_pais='".escapeSQL($id_pais)."', latitude='".escapeSQL($latitude)."', longitude='".escapeSQL($longitude)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
} 

function eliminar_barrio($id=null) {
 global $link;	
 $q = "DELETE FROM barrios WHERE id='".$id."'";
 $r = @mysql_query($q, $link);
 return $r;
}

function publicar_barrio($id=null,$campo=null) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q 		= "UPDATE barrios SET activo='".escapeSQL($campo)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
}


// poblaciones
function obtener_poblaciones($id_pais=null, $id_ciudad=null, $id_provincia=null, $activo=null, $order_by=null, $order=null,$filtro='id', $tiene_zona=null, $zona_id=null) {
 global $link;	
}

function obtener_poblacion($id=null) {
 global $link;	
}

function grabar_poblaciones($campos=null) {
 global $link;	
} 

function editar_poblacion($id=null,$campos=null) {
 global $link;	
} 

function eliminar_poblacion($id=null) {
 global $link;
}

function publicar_poblacion($id=null,$campo=null) {
 global $link;
}  

// localidades
function obtener_localidades($porPagina=null, $paginacion=null, $palabra=null, $OrderBy=null, $filtro=null, $activo=null, $estado=null, $id_partido=null, $id_provincia=null) {
 global $link;	

 if(strlen($order_by) == 0) {

   if($filtro && $order=='d')
	    $order_by = "ORDER BY l.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY l.$filtro ASC";
	 else
 	    $order_by = "ORDER BY l.activo DESC";
 } else {
 	    $order_by = "ORDER BY l.nombre ASC";
 }

 $q = "SELECT l.*, p.nombre AS nombre_partido "
    . "FROM localidades AS l "  
    . "LEFT JOIN partidos p ON p.id=l.id_partido "
	  . ($activo 		 ?  "AND l.activo='".$activo."' " : null)
	  . ($id_partido ?  "AND l.id_partido='".$id_partido."' " : null)
	  . ($id_provincia ?  "AND l.id_provincia='".$id_provincia."' " : null)
	  . "".$order_by."";
	return @mysql_query($q, $link);  	  
}

// localidades
function obtener_localidades_con_propiedades($porPagina=null, $paginacion=null, $palabra=null, $OrderBy=null, $filtro=null, $activo=null, $estado=null, $id_partido=null, $id_provincia=null) {
 global $link;	

 if(strlen($order_by) == 0) {

   if($filtro && $order=='d')
	    $order_by = "ORDER BY l.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY l.$filtro ASC";
	 else
 	    $order_by = "ORDER BY l.activo DESC";
 } else {
 	    $order_by = "ORDER BY l.nombre ASC";
 }

 $q = "SELECT l.*, p.nombre AS nombre_partido "
    . "FROM localidades AS l "  
    . "LEFT JOIN partidos p ON p.id=l.id_partido "
	  . ($activo 		 ?  "AND l.activo='".$activo."' " : null)
	  . ($id_partido ?  "AND l.id_partido='".$id_partido."' " : null)
	  . ($id_provincia ?  "AND l.id_provincia='".$id_provincia."' " : null)
	  . "".$order_by."";
	return @mysql_query($q, $link);  	  
}
function obtener_localidad($id=null) {
 global $link;	
 $q = "SELECT P.* FROM localidades AS P " 
    . "WHERE 1 " 
	  . ($id ?  "AND P.id=".$id." " : null)
	  . " LIMIT 1 ";
     $r = @mysql_query($q, $link);
     return @mysql_fetch_array($r);	  
}

function grabar_localidad($campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $id_partido = limpiar_string($campos['id_partido']);
 $id_provincia = limpiar_string($campos['id_provincia']);
 
 $q = "INSERT INTO localidades (nombre, id_partido) VALUES ('".escapeSQL($nombre)."', '".escapeSQL($id_partido)."',) ";
 $r = @mysql_query($q, $link);
 return @mysql_insert_id($link);
} 

function editar_localidad($id=null,$campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $id_partido = limpiar_string($campos['id_partido']);
 $id_provincia = limpiar_string($campos['id_provincia']);
 
 $q = "UPDATE localidades SET nombre='".escapeSQL($nombre)."', id_partido='".escapeSQL($id_partido)."', id_provincia='".escapeSQL($id_provincia)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
} 

function eliminar_localidad($id=null) {
 global $link;	
 $q = "DELETE FROM localidades WHERE id='".$id."'";
 $r = @mysql_query($q, $link);
 return $r;
}

function publicar_localidad($id=null,$campo=null) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q 		= "UPDATE localidades SET activo='".escapeSQL($campo)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
}

// partidos
function obtener_partidos($id_provincia=null, $activo=null, $order_by=null, $order=null,$filtro='id') {
 global $link;	

 if(strlen($order_by) == 0) {

   if($filtro && $order=='d')
	    $order_by = "ORDER BY l.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY l.$filtro ASC";
	 else
 	    $order_by = "ORDER BY l.activo DESC";
 } else {
 	    $order_by = "ORDER BY l.nombre ASC";
 }
   	
 $q = "SELECT l.* "
    . "FROM partidos AS l "  
	  . ($activo ?  "AND l.activo='".$activo."' " : null)
	  . ($id_provincia ?  "AND l.id_provincia='".$id_provincia."' " : null)
	  . "".$order_by."";
	return @mysql_query($q, $link);  	  
}

function obtener_partido($id=null) {
 global $link;	
 $q = "SELECT l.* FROM partidos AS l " 
    . "WHERE 1 " 
	  . ($id ?  "AND l.id=".$id." " : null)
	  . " LIMIT 1 ";
     $r = @mysql_query($q, $link);
     return @mysql_fetch_array($r);	  
}

function grabar_partido($campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $id_provincia = limpiar_string($campos['id_provincia']);
 
 $q = "INSERT INTO partidos (nombre, id_provincia) VALUES ('".escapeSQL($nombre)."', '".escapeSQL($id_provincia)."',) ";
 $r = @mysql_query($q, $link);
 return @mysql_insert_id($link);

} 

function editar_partido($id=null,$campos=null) {
 global $link;	
 
 $nombre = limpiar_string($campos['nombre']);
 $id_partido = limpiar_string($campos['id_partido']);
 $id_provincia = limpiar_string($campos['id_provincia']);
 
 $q = "UPDATE partidos SET nombre='".escapeSQL($nombre)."', id_partido='".escapeSQL($id_partido)."', id_provincia='".escapeSQL($id_provincia)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
} 

function eliminar_partido($id=null) {
 global $link;	
 $q = "DELETE FROM partidos WHERE id='".$id."'";
 $r = @mysql_query($q, $link);
 return $r;
}

function publicar_partido($id=null,$campo=null) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q 		= "UPDATE partidos SET activo='".escapeSQL($campo)."' WHERE id='".$id."' ";
 $r = @mysql_query($q, $link);
 return $r;
}

// cantones

function obtener_cantones($order_by, $activo, $id_provincia) {
 global $link;	

 if(strlen($order_by) == 0) {

   if($filtro && $order=='d')
	    $order_by = "ORDER BY l.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY l.$filtro ASC";
	 else
 	    $order_by = "ORDER BY l.activo DESC";
 } else {
 	    $order_by = "ORDER BY l.nombre ASC";
 }
   	
 $q = "SELECT l.* "
    . "FROM cantones AS l " 
    . "WHERE 1 " 
	  . ($activo ?  "AND l.activo='".$activo."' " : null)
	  . ($id_provincia ?  "AND l.id_provincia='".$id_provincia."' " : null)
	  . "".$order_by."";
	return @mysql_query($q, $link);  	
}

function obtener_canton($id_canton) {
 global $link;	
	
 $q = "SELECT l.* "
    . "FROM cantones AS l " 
    . "WHERE 1 " 
	  . ($id_canton ?  "AND l.id='".$id_canton."' " : null)
	  . " LIMIT 1 ";
     $r = @mysql_query($q, $link);
     return @mysql_fetch_array($r);	  	
}
}
?>