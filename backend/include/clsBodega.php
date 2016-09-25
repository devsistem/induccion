<?php
class Bodega {

function obtener_all($order_by=null, $activo=null) {
  global $link;
  $q = "SELECT  b.* FROM bodegas AS b "
     . "WHERE 1 "
	 	 . ($activo 	  ? "AND b.activo='".$activo."' " :null)
	 	 .	" $order_by " 
	   . "";

	  return @mysql_query($q,$link);
}

function obtener($id) {
  global $link;
 	$q = "SELECT b.* FROM bodegas AS b WHERE b.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
}
				// bodega[nombre]
													// bodega[contenido]
													// activo
function grabar( $campos=null ) {
	 global $link;
	 $nombre = escapeSQLFull($campos['bodega']['nombre']);
	 $descripcion = $campos['bodega']['descripcion'];
	 $tipo = $campos['bodega']['tipo'];
	 $activo = $campos['activo'];

   $q = "INSERT INTO bodegas (nombre, descripcion, activo, principal, tipo, fecha_alta) VALUES ('".$nombre."','".$descripcion."', '1', '0', '".$tipo."', NOW())";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
}
  
// Actualiza un registro
function editar($id, $campos=null) {
   global $link;
	 $nombre 	  = escapeSQLFull($campos['bodega']['nombre']);
	 $descripcion = $campos['bodega']['descripcion'];
	 $tipo = $campos['bodega']['tipo'];
	 $activo 	  = $campos['activo'];
	 $q = "UPDATE bodegas SET nombre='".$nombre."', descripcion='".$descripcion."', activo='".$activo."', fecha_mod=NOW() WHERE id='".$id."' ";
   @mysql_query($q,$link);
}

function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE bodegas SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
}

function eliminar($id) {
 global $link;
 $q = "DELETE FROM bodegas WHERE id='".$id."'";
 $r = @mysql_query($q,$link);
}

//////////////////////////////////////////////////////////////
// EXTRAS
//////////////////////////////////////////////////////////////  
  
 function cantidad_by_bodega($id) {
  global $link;
   $q = "SELECT COUNT(*) AS Cantidad FROM productos AS p WHERE p.idx='".$id."' ";
   $r = @mysql_query($q,$link);
   $a = @mysql_fetch_array($r);		
  return $a['Cantidad'];
 }
} // end class
?>