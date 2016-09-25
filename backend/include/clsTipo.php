<?php
class Tipo {

function obtener_all($inversion=null, $venta=null, $alquiler=null, $porPagina=null, $paginacion=null, $palabra=null, $OrderBy=null, $filtro=null, $activo=null, $destacado=null) {
  global $link;

    $q = "SELECT  tp.* FROM tipo_propiedad AS tp "
       . "WHERE 1 "
	  	 . ($activo 	  ? "AND tp.activo='".$activo."' " :null)
	  	 . ($destacado 	? "AND tp.destacado='".$destacado."' " :null)
		   . ($inversion  ? "AND tp.inversion='".$inversion."' " : null)
	  	 . ($venta 		  ? "AND tp.venta='".$venta."' " : null)
	  	 . ($alquiler   ? "AND tp.alquiler='".$alquiler."' " : null)	 	  	 
	   .	" ORDER BY tp.orden ASC  " 
	  // . ($limite			? "LIMIT ".$limite." " :null)	
		   . "";
	  return @mysql_query($q,$link);
}

function obtener($id) {
  global $link;
 	$q = "SELECT tp.* FROM tipo_propiedad AS tp WHERE tp.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
}

function grabar( $campos=null ) {
	 global $link;
	 $nombre = escapeSQLFull($campos['tipo']['nombre']);
	 $activo = $campos['tipo']['activo'];
 	 $orden = $campos['tipo']['orden'];

 	 $inversion = ($campos['inversion']=='1') ? '1' : '0';
 	 $venta = ($campos['venta']=='1') ? '1' : '0';
 	 $alquiler = ($campos['alquiler']=='1') ? '1' : '0';
 	 
   $q = "INSERT INTO tipo_propiedad (inversion, venta, alquiler, nombre, activo, orden) VALUES ('".$inversion."', '".$venta."', '".$alquiler."', '".$nombre."','".$activo."','".$orden."')";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
}
  
// Actualiza un registro
function editar($id, $campos=null) {
   global $link;
	 $nombre = escapeSQLFull($campos['tipo']['nombre']);
	 $activo = $campos['tipo']['activo'];
 	 $orden = $campos['tipo']['orden'];
 	 
 	 $inversion = ($campos['inversion']=='1') ? '1' : '0';
 	 $venta = ($campos['venta']=='1') ? '1' : '0';
 	 $alquiler = ($campos['alquiler']=='1') ? '1' : '0';
  	 
	 $q = "UPDATE tipo_propiedad SET alquiler='".escapeSQL($alquiler)."', venta='".escapeSQL($venta)."', inversion='".escapeSQL($inversion)."', nombre='".$nombre."', activo='".$activo."', orden='".$orden."' WHERE id='".$id."' ";
   @mysql_query($q,$link);
}

function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE tipo_propiedad SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
}

function eliminar($id) {
 global $link;
 $q = "DELETE FROM tipo_propiedad WHERE id='".$id."'";
 $r = @mysql_query($q,$link);
}


function obtener_operacion_all($inversion=null, $venta=null, $alquiler=null, $porPagina=null, $paginacion=null, $palabra=null, $OrderBy=null, $filtro=null, $activo=null, $destacado=null) {
  global $link;
    $q = "SELECT  tp.* FROM tipo_operacion AS tp "
       . "WHERE 1 "
	  	 . ($activo 	  ? "AND tp.activo='".$activo."' " :null)
	  	 . ($destacado 	? "AND tp.destacado='".$destacado."' " :null)
		   . ($inversion ? "AND c.inversion='".$inversion."' " : null)
	  	 . ($venta ? "AND c.venta='".$venta."' " : null)
	  	 . ($alquiler ? "AND c.venta='".$venta."' " : null)	  	 
	  	 .	" ORDER BY tp.orden ASC  " 
	    . "";
	  return @mysql_query($q,$link);
}

function obtener_operacion($id) {
  global $link;
 	$q = "SELECT tp.* FROM tipo_operacion AS tp WHERE tp.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
}

function grabar_operacion( $campos=null ) {
	 global $link;
	 $nombre = escapeSQLFull($campos['tipo']['nombre']);
	 $activo = $campos['tipo']['activo'];
 	 $orden = $campos['tipo']['orden'];

   $q = "INSERT INTO tipo_operacion (nombre, activo, orden) VALUES ('".$nombre."','".$activo."','".$orden."')";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
}
  
// Actualiza un registro
function editar_operacion($id, $campos=null) {
   global $link;
	 $nombre = escapeSQLFull($campos['tipo']['nombre']);
	 $activo = $campos['tipo']['activo'];
 	 $orden = $campos['tipo']['orden'];
	 $q = "UPDATE tipo_operacion SET nombre='".$nombre."', activo='".$activo."', orden='".$orden."' WHERE id='".$id."' ";
   @mysql_query($q,$link);
}

function publicar_operacion($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE tipo_operacion SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
}


} // end class
?>