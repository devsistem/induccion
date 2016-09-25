<?php
class Item {
	
function obtener_all($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_tipo=null, $filtro_id_tipo_operacion=null, $filtro_id_provincia=null, $filtro_id_ciudad=null,$filtro_id_barrio=null,$filtro_id_inmobiliaria=null,$precio_desde=null,$precio_hasta=null,$conprecio=null, $cantidadhabitaciones=null ,$cantidadbanos=null ,$metros_interior=null ,$antiguedad=null) {
	global $link;
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
	    $order_by = "ORDER BY i.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY i.$filtro ASC";
	 else
 	    $order_by = "ORDER BY i.orden ASC, i.id DESC";
  }	 
  
  // crear los rangos
  //$metros_interior=null ,$antiguedad=null
  	 
 $q = "SELECT i.*, tope.nombre as tipo_operacion_nombre, tp.nombre as tipo_propiedad_nombre, c.razon_social as inmobiliaria_nombre, ciu.nombre as ciudad_nombre "
    . "FROM item AS i "
    . "LEFT JOIN tipo_operacion tope ON tope.id=i.id_tipo_operacion "
    . "LEFT JOIN tipo_propiedad tp ON tp.id=i.id_tipo "
    . "LEFT JOIN cliente c ON c.id=i.id_inmobiliaria "
    . "LEFT JOIN ciudades ciu ON ciu.id=i.id_ciudad "
    . "WHERE 1 "  
	  . ($activo ?  "AND i.activo='".$activo."' " : null)
	  . ($estado ?  "AND i.estado='".$estado."' " : null)
	  . ($destacado ?  "AND i.destacado='".$destacado."' " : null)
	  . ($filtro_id_ciudad ?  "AND i.id_ciudad='".$filtro_id_ciudad."' " : null)
	  . ($filtro_id_tipo ?  "AND i.id_tipo='".$filtro_id_tipo."' " : null)
	  . ($filtro_id_tipo_operacion ?  "AND i.id_tipo_operacion='".$filtro_id_tipo_operacion."' " : null)
	  . ($precio_desde && $precio_hasta ?  "AND i.precio >= ".$precio_desde." AND i.precio <= ".$precio_hasta." " : null)
	  . ($conprecio ?  "AND i.precio > 0 " : null)
	  . ($cantidadhabitaciones ?  "AND i.cantidad_de_habitaciones = ".$cantidad_de_habitaciones." " : null)
	  . ($cantidadbanos ?  "AND i.cantidad_de_banos = ".$cantidadbanos." " : null)
	  . "".$order_by.""
    . ($limite	? " limit ".$limite." " :null)	  
	  . "";
	//print $q;
	return @mysql_query($q, $link);  
}

function obtener_all_front($id_ciudad=null,  $id_tipo_operacion=null, $id_tipo_propiedad=null, $moneda=null, $ambientes=null, $periodo=null, $porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_provincia=null, $filtro_id_localidad=null,$filtro_id_barrio=null,$filtro_id_inmobiliaria=null,$precio_desde=null,$precio_hasta=null,$conprecio=null, $cantidadhabitaciones=null ,$cantidadbanos=null ,$metros_interior=null ,$antiguedad=null) {
	global $link;
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
	    $order_by = "ORDER BY i.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY i.$filtro ASC";
	 else
 	    $order_by = "ORDER BY i.orden ASC, i.id DESC";
  }	 
  
  // crear los rangos
  //$metros_interior=null ,$antiguedad=null
  	 
 $q = "SELECT i.*, tope.nombre as tipo_operacion_nombre, tp.nombre as tipo_propiedad_nombre, ciu.nombre as nombre_ciudad "
    . "FROM item AS i "
    . "LEFT JOIN tipo_operacion tope ON tope.id=i.id_tipo_operacion "
    . "LEFT JOIN tipo_propiedad tp ON tp.id=i.id_tipo "
    . "LEFT JOIN ciudades ciu ON ciu.id=i.id_ciudad "
    . "WHERE 1 "  
	  . ($id_tipo_operacion ?  "AND i.id_tipo_operacion='".$id_tipo_operacion."' " : null)
	  . ($id_tipo_propiedad ?  "AND i.id_tipo='".$id_tipo_propiedad."' " : null)
	  . ($activo ?  "AND i.activo='".$activo."' " : null)
	  . ($estado ?  "AND i.estado='".$estado."' " : null)
	  . ($destacado ?  "AND i.destacado='".$destacado."' " : null)
	  . ($id_ciudad ?  "AND i.id_ciudad='".$id_ciudad."' " : null)
	  . ($moneda ?  "AND i.precio_moneda='".$moneda."' " : null)
	  . ($precio_desde && $precio_hasta ?  "AND i.precio >= ".$precio_desde." AND i.precio <= ".$precio_hasta." " : null)
	  . ($ambientes ?  "AND i.ambientes = ".$ambientes." " : null)
	  . ($periodo ?  "AND i.periodo = '".$periodo."' " : null)
    . (($palabra) 		? "AND  ( i.titulo LIKE '%$palabra%' OR i.tag1 LIKE '%$palabra%' OR i.tag2 LIKE '%$palabra%' OR i.tag3 LIKE '%$palabra%' OR i.tag4 LIKE '%$palabra%' OR i.tag5 LIKE '%$palabra%' ) " :null)
	  . "".$order_by.""
    . ($limite	? " limit ".$limite." " :null)	  
	  . "";
	//print $q;
	return @mysql_query($q, $link);  
}

function obtener_all_relacionados($id_tipo_operacion=null, $idno=null) {
	global $link;
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
	    $order_by = "ORDER BY i.$filtro DESC";
   elseif($filtro && $order=='a')
	    $order_by = "ORDER BY i.$filtro ASC";
	 else
 	    $order_by = "ORDER BY i.id DESC";
  }	 
  
  // crear los rangos
  //$metros_interior=null ,$antiguedad=null
  	 
 $q = "SELECT i.*, tope.nombre as tipo_operacion_nombre, tp.nombre as tipo_propiedad_nombre, ciu.nombre as nombre_ciudad "
    . "FROM item AS i "
    . "LEFT JOIN tipo_operacion tope ON tope.id=i.id_tipo_operacion "
    . "LEFT JOIN tipo_propiedad tp ON tp.id=i.id_tipo "
    . "LEFT JOIN ciudades ciu ON ciu.id=i.id_ciudad "
    . "WHERE 1 AND i.activo=1 " 
	  . ($id_tipo_operacion ?  "AND i.id_tipo_operacion='".$id_tipo_operacion."' " : null)
	  . ($idno ?  "AND i.id != '".$idno."' " : null)
	  . " ORDER BY i.id DESC LIMIT 2 "
	  . "";
	//print $q;
	return @mysql_query($q, $link);  
}

function obtener($id) {
	global $link;
	$q = "SELECT i.*, tope.nombre as tipo_operacion_nombre, tp.nombre as tipo_propiedad_nombre "
     . "FROM item AS i "
     . "LEFT JOIN tipo_operacion tope ON tope.id=i.id_tipo_operacion "
     . "LEFT JOIN tipo_propiedad tp ON tp.id=i.id_tipo "
     . "WHERE 1 "
     . "AND i.id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
}


function obtener_ciudad($id) {
	global $link;
	$q = "SELECT * "
     . "FROM ciudades "
     . "WHERE 1 "
     . "AND id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
}

function obtener_tipo($id) {
	global $link;
	$q = "SELECT * "
     . "FROM tipo_propiedad "
     . "WHERE 1 "
     . "AND id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
}


function grabar( $campos=null, $contenido ) {
	 global $link;

	 // tabs	
	 $id_tipo	= escapeSQLFull($campos['id_tipo']);
	 $id_tipo_operacion	= escapeSQLFull($campos['id_tipo_operacion']);

	 //datos basicos	
	 $titulo	= escapeSQLFull($campos['item']['titulo']);
	 $descripcion	= escapeSQLFull($campos['item']['descripcion']);
	 $antiguedad	= escapeSQLFull($campos['item']['antiguedad']);
	 $orientacion	= escapeSQLFull($campos['item']['orientacion']);
	 $ocupada	= escapeSQLFull($campos['item']['ocupada']);
	 $condicion	= escapeSQLFull($campos['item']['condicion']);
	 $cantidad_de_banos	= escapeSQLFull($campos['item']['cantidad_de_banos']);
	 $cantidad_de_habitaciones	= escapeSQLFull($campos['item']['cantidad_de_habitaciones']);
	 $metros_interior	= escapeSQLFull($campos['item']['metros_interior']);
	 $metros_exterior	= escapeSQLFull($campos['item']['metros_exterior']);
	 $metros_semi	= escapeSQLFull($campos['item']['metros_semi']);
   $ambientes	= escapeSQLFull($campos['item']['ambientes']);

	 // localizacion	
	 $id_pais 	= escapeSQLFull($campos['item']['id_pais']);
	 $id_provincia 	= escapeSQLFull($campos['item']['id_provincia']);
	 $id_localidad 	= escapeSQLFull($campos['item']['id_localidad']);
	 $id_barrio 	= escapeSQLFull($campos['item']['id_barrio']);
	 $id_ciudad 	= escapeSQLFull($campos['item']['id_ciudad']);

	 $calle	= escapeSQLFull($campos['item']['calle']);
	 $calle_numero	= escapeSQLFull($campos['item']['calle_numero']);
	 $calle_piso	= escapeSQLFull($campos['item']['calle_piso']);
	 $calle_dto	= escapeSQLFull($campos['item']['calle_dto']);
	 
	 //google maps
	 $latitude	= escapeSQLFull($campos['mapa_latitude']);
	 $longitude	= escapeSQLFull($campos['mapa_longitude']);
	 
	 // tags
	 $tag1	= escapeSQLFull($campos['tag1']);	 
	 $tag2	= escapeSQLFull($campos['tag2']);	 
	 $tag3	= escapeSQLFull($campos['tag3']);	 
	 $tag4	= escapeSQLFull($campos['tag4']);	 
	 $tag5	= escapeSQLFull($campos['tag5']);	 
	 
	 // texto libre
   $contenido = $contenido;	
   
   // estado
	 $activo	= escapeSQLFull($campos['activo']);	 
	 $estado	= escapeSQLFull($campos['estado']);	 
	 
	 // personalizadas
   $expensas	= escapeSQLFull($campos['item']['expensas']);
   
   // inversion
	 $precio = escapeSQLFull($campos['item']['precio']);
	 $moneda = escapeSQLFull($campos['item']['moneda']);
	 $indicadores = escapeSQLFull($campos['item']['indicadores']);
	 $densidad = escapeSQLFull($campos['item']['densidad']);
	 $fot = escapeSQLFull($campos['item']['fot']);
	 $fos = escapeSQLFull($campos['item']['fos']);
	 $superficie_construir = escapeSQLFull($campos['item']['superficie_construir']);
	 
	 // alquiler
 	 $periodo = escapeSQLFull($campos['item']['periodo']);
 	 $precio_mes = escapeSQLFull($campos['item']['precio_mes']);
   $precio_quincena1 = escapeSQLFull($campos['item']['precio_quincena1']);
   $precio_quincena2 = escapeSQLFull($campos['item']['precio_quincena2']);
   $precio_semana1 = escapeSQLFull($campos['item']['precio_semana1']);	
   $precio_semana2 = escapeSQLFull($campos['item']['precio_semana2']);
   $precio_semana3 = escapeSQLFull($campos['item']['precio_semana3']);
   $precio_semana4 = escapeSQLFull($campos['item']['precio_semana4']);	 
   $lavadero = escapeSQLFull($campos['item']['lavadero']);	 
   $alarma_monitoreada = escapeSQLFull($campos['item']['alarma_monitoreada']);	 
   $alarma_sin_monitorear = escapeSQLFull($campos['item']['alarma_sin_monitorear']);	 
   $distancia_mar = escapeSQLFull($campos['item']['distancia_mar']);	 
   $distancia_centro = escapeSQLFull($campos['item']['distancia_centro']);	 
   
   // orden
	 $orden	= escapeSQLFull($campos['orden']);	 

   // imagen
	 $imagen = escapeSQLFull($campos['imagen']);
	 $imagen_th = "";
	 
	 if(strlen($imagen) > 3) {
	  $imagen_th = crearImagenResampleada(480, 360, $imagen, FILE_PATH."/adj/items/",  $imagen_th);
	  // marca de agua
	  //insertarmarcadeagua(FILE_PATH."/images/marcadeagua.png", FILE_PATH."/adj/items/".$imagen, 10);
	 } 

	 // servicios
	 $ck_servicios = implode(",",$campos['ck_servicios']);

	 // ambientes
	 $ck_ambientes2 = implode(",",$campos['ck_ambientes2']);

	 // adicionales
	 $ck_adicionales = implode(",",$campos['ck_adicionales']);
	 	
   $q = "INSERT INTO item (metros_semi, adicionales, servicios, ambientes2, orden, ambientes, imagen, imagen_th, id_tipo, id_tipo_operacion, titulo, descripcion, contenido, antiguedad,orientacion,ocupada,condicion,cantidad_de_banos,cantidad_de_habitaciones,metros_interior,metros_exterior,id_pais, id_provincia, id_localidad, id_barrio, id_ciudad, calle, calle_numero, calle_piso, calle_dto, activo, estado, fecha_alta, tag1, tag2, tag3, tag4, tag5, latitude, longitude, expensas, precio, precio_moneda, indicadores, densidad, fot, fos, superficie_construir, periodo, precio_mes, precio_quincena1, precio_quincena2, precio_semana1, precio_semana2, precio_semana3, precio_semana4,lavadero,alarma_monitoreada,alarma_sin_monitorear,distancia_mar,distancia_centro) VALUES ('".$metros_semi."', '".$ck_adicionales."', '".$ck_servicios."', '".$ck_ambientes2."', '".$orden."', '".$ambientes."', '".$imagen."', '".$imagen_th."','".$id_tipo."', '".$id_tipo_operacion."', '".$titulo."','".$descripcion."','".$contenido."','".$antiguedad."','".$orientacion."','".$ocupada."','".$condicion."','".$cantidad_de_banos."','".$cantidad_de_habitaciones."','".$metros_interior."','".$metros_exterior."','".$id_pais."', '".$id_provincia."', '".$id_localidad."', '".$id_barrio."', '".$id_ciudad."', '".$calle."', '".$calle_numero."', '".$calle_piso."', '".$calle_dto."','".$activo."' , '".$estado."',  NOW(), '".$tag1."', '".$tag2."', '".$tag3."', '".$tag4."', '".$tag5."',  '".$latitude."',  '".$longitude."',  '".$expensas."', '".$precio."', '".$moneda."', '".$indicadores."', '".$densidad."', '".$fot."', '".$fos."', '".$superficie_construir."', '".$periodo."', '".$precio_mes."', '".$precio_quincena1."', '".$precio_quincena2."', '".$precio_semana1."', '".$precio_semana2."', '".$precio_semana3."', '".$precio_semana4."', '".$lavadero."', '".$alarma_monitoreada."', '".$alarma_sin_monitorear."','".$distancia_mar."', '".$distancia_centro."')";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
		
		// campos extras
		for($i=1;$i < 19; $i++) {
			$campo = "item_campo_extra_".$i;
			$item_campo_extra = $campos[$campo];
      if(strlen($item_campo_extra) > 0) {
		   $q = "INSERT INTO item_extras_valor (id_item_extra, id_item, valor, orden) VALUES ('".$i."',  '".$last_id."', '".$item_campo_extra."', '".$orden."')";
			 $r = @mysql_query($q,$link);			
			}
		}
			 	
	 	$arr_ambientes = $campos['ambientes_valor'];
	 	//1 ambientes
	 	foreach ($arr_ambientes as $key => $value) {
	 		 if(strlen($value) > 0) {
 	  	   $q = "INSERT INTO item_campos (id_tipo_campo, campo, valor, id_item, orden) VALUES (1, '', '".$value."', '".$last_id."', '".$orden."')";
				 $r = @mysql_query($q,$link);
			 }
	 	}

	 	$arr_instalaciones_label = $campos['instalaciones_label'];
	 	$arr_instalaciones = $campos['instalaciones_valor'];

	 	//2 instalaciones
	 	$i = 0;
	 	foreach ($arr_instalaciones as $key => $value) {
	 		 if(strlen($value) > 0) {
 	  	   $q = "INSERT INTO item_campos (id_tipo_campo, campo, valor, id_item, orden) VALUES (2, '".$arr_instalaciones_label[$i]."', '".$value."', '".$last_id."', '".$orden."')";
				 $r = @mysql_query($q,$link);
			 }
				 $i++;
	 	}
	 	
	 	// 3 servicios
	 	$arr_servicios_label = $campos['servicios_label'];
	 	$arr_servicios = $campos['servicios_valor'];
	 		 	
	 	$i = 0;
	 	foreach ($arr_servicios as $key => $value) {
	 		if(strlen($value) > 0) {
 	  	   $q = "INSERT INTO item_campos (id_tipo_campo, campo, valor, id_item, orden) VALUES (3, '".$arr_servicios_label[$i]."', '".$value."', '".$last_id."', '".$orden."')";
				 $r = @mysql_query($q,$link);
			}	 
				 $i++;
 	  }

	 	// 4 servicios
	 	$arr_caracteristicas_label = $campos['caracteristicas_label'];
	 	$arr_caracteristicas = $campos['caracteristicas_valor'];
	 		 	
	 	$i = 0;
	 	foreach ($arr_caracteristicas as $key => $value) {
			if(strlen($value) > 0) {
 	  	   $q = "INSERT INTO item_campos (id_tipo_campo, campo, valor, id_item, orden) VALUES (4, '".$arr_caracteristicas_label[$i]."', '".$value."', '".$last_id."', '".$orden."')";
				 $r = @mysql_query($q,$link);
	 	  }
				 $i++;
	  }
	 } 
	 return $last_id;
}

function editar( $id, $campos=null, $contenido ) {
	 global $link;

	 // tabs	
	 $id_tipo	= escapeSQLFull($campos['id_tipo']);
	 $id_tipo_operacion	= escapeSQLFull($campos['id_tipo_operacion']);
		
	 //datos basicos	
	 $titulo	= escapeSQLFull($campos['item']['titulo']);
	 $descripcion	= escapeSQLFull($campos['item']['descripcion']);
	 $antiguedad	= escapeSQLFull($campos['item']['antiguedad']);
	 $orientacion	= escapeSQLFull($campos['item']['orientacion']);
	 $ocupada	= escapeSQLFull($campos['item']['ocupada']);
	 $condicion	= escapeSQLFull($campos['item']['condicion']);
	 $cantidad_de_banos	= escapeSQLFull($campos['item']['cantidad_de_banos']);
	 $cantidad_de_habitaciones	= escapeSQLFull($campos['item']['cantidad_de_habitaciones']);
	 $metros_interior	= escapeSQLFull($campos['item']['metros_interior']);
	 $metros_exterior	= escapeSQLFull($campos['item']['metros_exterior']);
	 $metros_semi	= escapeSQLFull($campos['item']['metros_semi']);
	 $ambientes	= escapeSQLFull($campos['item']['ambientes']);

	 // localizacion	
	 $calle	= escapeSQLFull($campos['item']['calle']);
	 $calle_numero	= escapeSQLFull($campos['item']['calle_numero']);
	 $calle_piso	= escapeSQLFull($campos['item']['calle_piso']);
	 $calle_dto	= escapeSQLFull($campos['item']['calle_dto']);

	 $id_pais 	= escapeSQLFull($campos['item']['id_pais']);
	 $id_provincia 	= escapeSQLFull($campos['item']['id_provincia']);
	 $id_localidad 	= escapeSQLFull($campos['item']['id_localidad']);
	 $id_barrio 	= escapeSQLFull($campos['item']['id_barrio']);
	 $id_ciudad 	= escapeSQLFull($campos['item']['id_ciudad']);
	 	 
	 //google maps
	 $latitude	= escapeSQLFull($campos['mapa_latitude']);
	 $longitude	= escapeSQLFull($campos['mapa_longitude']);
	 
	 // tags
	 $tag1	= escapeSQLFull($campos['tag1']);	 
	 $tag2	= escapeSQLFull($campos['tag2']);	 
	 $tag3	= escapeSQLFull($campos['tag3']);	 
	 $tag4	= escapeSQLFull($campos['tag4']);	 
	 $tag5	= escapeSQLFull($campos['tag5']);	 
	 
   // estado
	 $activo	= escapeSQLFull($campos['activo']);	 
	 $estado	= escapeSQLFull($campos['estado']);	 

	 // personalizadas
   $expensas	= escapeSQLFull($campos['item']['expensas']);
   
   // inversion
	 $precio = escapeSQLFull($campos['item']['precio']);
	 $precio_moneda = escapeSQLFull($campos['item']['moneda']);
	 $indicadores = escapeSQLFull($campos['item']['indicadores']);
	 $densidad = escapeSQLFull($campos['item']['densidad']);
	 $fot = escapeSQLFull($campos['item']['fot']);
	 $fos = escapeSQLFull($campos['item']['fos']);
	 $superficie_construir = escapeSQLFull($campos['item']['superficie_construir']);
	 
	 // alquiler
 	 $periodo = escapeSQLFull($campos['item']['periodo']);
 	 $precio_mes = escapeSQLFull($campos['item']['precio_mes']);
   $precio_quincena1 = escapeSQLFull($campos['item']['precio_quincena1']);
   $precio_quincena2 = escapeSQLFull($campos['item']['precio_quincena2']);
   $precio_semana1 = escapeSQLFull($campos['item']['precio_semana1']);	
   $precio_semana2 = escapeSQLFull($campos['item']['precio_semana2']);
   $precio_semana3 = escapeSQLFull($campos['item']['precio_semana3']);
   $precio_semana4 = escapeSQLFull($campos['item']['precio_semana4']);	 
   $lavadero = escapeSQLFull($campos['item']['lavadero']);	 
   $alarma_monitoreada = escapeSQLFull($campos['item']['alarma_monitoreada']);	 
   $alarma_sin_monitorear = escapeSQLFull($campos['item']['alarma_sin_monitorear']);	 
   $distancia_mar = escapeSQLFull($campos['item']['distancia_mar']);	 
   $distancia_centro = escapeSQLFull($campos['item']['distancia_centro']);	 

   // orden
	 $orden	= escapeSQLFull($campos['orden']);	 
   
   // imagen
	 $imagen = escapeSQLFull($campos['imagen']);	 
	 
	 $marcadeagua = "../images/marcadeagua.png";
	 $margen = 10;
		
	 if(strlen($imagen) > 3) {
	 	 // marca de agua
	   //insertarmarcadeagua("../images/marcadeagua.jpg","../adj/items/".$imagen, 10);
	   //file_get_contents('../admin/test.php?imagen='.$imagen);	 
	   $imagen_th = crearImagenResampleada(480, 360, $imagen, FILE_PATH."/adj/items/",  $imagen_th);
		 $imagen_sql = "imagen='$imagen',imagen_th='$imagen_th', ";
	 }

	 // servicios
	 $ck_servicios = implode(",",$campos['ck_servicios']);

	 // ambientes
	 $ck_ambientes2 = implode(",",$campos['ck_ambientes2']);

	 // adicionales
	 $ck_adicionales = implode(",",$campos['ck_adicionales']);
	 	 
   $q = "UPDATE  item SET $imagen_sql metros_semi='".$metros_semi."', servicios='".$ck_servicios."', ambientes2='".$ck_ambientes2."', adicionales='".$ck_adicionales."', orden='".$orden."', ambientes='".$ambientes."', id_tipo='".$id_tipo."', id_tipo_operacion='".$id_tipo_operacion."', titulo='".$titulo."', descripcion='".$descripcion."', contenido='".$contenido."', antiguedad='".$antiguedad."', orientacion='".$orientacion."', ocupada='".$ocupada."',condicion='".$condicion."',cantidad_de_banos='".$cantidad_de_banos."',cantidad_de_habitaciones='".$cantidad_de_habitaciones."',metros_interior='".$metros_interior."',metros_exterior='".$metros_exterior."',id_pais='".$id_pais."', id_provincia='".$id_provincia."', id_localidad='".$id_localidad."', id_barrio='".$id_barrio."', id_ciudad='".$id_ciudad."', calle='".$calle."', calle_numero='".$calle_numero."', calle_piso='".$calle_piso."', calle_dto='".$calle_dto."', fecha_mod=NOW(), tag1='".$tag1."', tag2='".$tag2."', tag3='".$tag3."', tag4='".$tag4."', tag5='".$tag5."', latitude='".$latitude."', longitude='".$longitude."', expensas='".$expensas."', precio='".$precio."', precio_moneda='".$precio_moneda."', indicadores='".$indicadores."', densidad='".$densidad."', fot='".$fot."', fos='".$fos."', superficie_construir='".$superficie_construir."', precio_mes='".$precio_mes."', precio_quincena1='".$precio_quincena1."', precio_quincena2='".$precio_quincena2."', precio_semana1='".$precio_semana1."', precio_semana2='".$precio_semana2."', precio_semana3='".$precio_semana3."', precio_semana4='".$precio_semana4."', lavadero='".$lavadero."', alarma_monitoreada='".$alarma_monitoreada."', alarma_sin_monitorear='".$alarma_sin_monitorear."', distancia_mar='".$distancia_mar."',distancia_centro='".$distancia_centro."' WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	 
	 return @mysql_insert_id($link);
}

function cantidad_by_inmo($id_inmobiliaria) {
  global $link;
  $q  = "SELECT COUNT(*) as cantidad FROM item c WHERE  1 "
      . (($id_inmobiliaria) ?  "AND c.id_inmobiliaria='".$id_inmobiliaria."' " : null)
      . "";
  $r	= @mysql_query($q,$link);
  $a	= @mysql_fetch_array($r);	
  return $a['cantidad'];
}

function cantidad_by_barrio($id_barrio) {
  global $link;
  $q  = "SELECT COUNT(*) as cantidad FROM item c WHERE  1 "
      . (($id_barrio) ?  "AND c.id_barrio='".$id_barrio."' " : null)
      . "";
  $r	= @mysql_query($q,$link);
  $a	= @mysql_fetch_array($r);	
  return $a['cantidad'];
}

function cantidad_by_ciudad($id_ciudad) {
  global $link;
  $q  = "SELECT COUNT(*) as cantidad FROM item c WHERE  1 "
      . (($id_ciudad) ?  "AND c.id_ciudad='".$id_ciudad."' " : null)
      . "";
  $r	= @mysql_query($q,$link);
  $a	= @mysql_fetch_array($r);	
  return $a['cantidad'];
} 

 function destacado($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE item SET destacado='".$campo."' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE item SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function estado($id,$campo) {
 	global $link;

 	if($campo==0) {
 		$campo = 1;
 		$fecha_aprobado = ", fecha_aprobado=NOW()";
 	} else if($campo==1) {
 		$campo = 2;
 		$fecha_rechazado = ", fecha_rechazado=NOW()";
 	} else if($campo==2) {
 		$campo = 0;
 	}
 	
 	$q = "UPDATE item SET estado=".$campo." $fecha_aprobado $fecha_rechazado  WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM item WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

//////////////////////////////////////////////////////////
// CAMPOS EXTRAS
//////////////////////////////////////////////////////////

function obtener_campos_extras($id, $id_tipo_campo) {
	global $link;
 $q = "SELECT ce.* "
    . "FROM item_campos AS ce "
//  . "LEFT JOIN cliente c ON c.id=i.id_inmobiliaria "
    . "WHERE 1 "  
	  . "AND ce.id_tipo_campo='".$id_tipo_campo."' " 
	  . "AND ce.id_item='".$id."' "
	  . "";
  //print $q;
	return @mysql_query($q, $link);
}

function obtener_extras($id_tipo_campo, $id) {
 global $link;
 
 $q = "SELECT ce.* "
    . "FROM item_campos AS ce "
    . "WHERE 1 "  
	  . "AND ce.id_tipo_campo='".$id_tipo_campo."' " 
	  . "AND ce.id_item='".$id."' "
	  . "";
 return @mysql_query($q, $link);
}

function enviar_contacto($nombre,$email,$contenido) {
 loadClasses('Item');
 require_once(FILE_PATH.'/include/clsMailer.php');
 global $link;

 $q = "SELECT * FROM  sys_conf AS C WHERE C.id='1' LIMIT 1";
 $arrConf  = @mysql_query($q,$link);
 $ResConf  = @mysql_fetch_array($arrConf);	

 // registro
 $q = "INSERT INTO contacto (nombre, email, contenido, fecha_alta, tipo) VALUES ('".escapeSQLFull($nombre)."', '".escapeSQLFull($email)."', '".escapeSQLFull($contenido)."', NOW(), 'contacto' ) ";
 @mysql_query($q,$link);
 
 $Mailer = new phpmailer();
 $Mailer->Host     = MAIL_SMTP; // SMTP servers
 $Mailer->Mailer   = "mail";
 $Mailer->From     = $ResConf['mail_admin'];
 $Mailer->FromName = "Mingrone Propieades  ";
 $Mailer->AddAddress($ResConf['mail_admin']); 
 $Mailer->IsHTML(true); 
 $Mailer->Subject  =  " Nuevo contacto en el sitio ";
	
 $HTML =  "<br>DATOS DEL USUARIO: <br>
    <br/><br/>             
    <br/> nombre:  ". $nombre . "
    <br/> email:   ". $email . "
    <br/> mensaje: ". $contenido . "
    <br/> <br/><br/>";
    
    $Mailer->Body = $HTML;

 	  if(!$Mailer->Send()) {
    	print "ERROR. ";
    	die; 
   	} else {
   		
   		// limpiar la clave
   		return 1;
  	}
  	
}
} // end class
?>