<?php
class Galeria {
	
function obtener_all($porPagina, $paginacion, $palabra=null, $order_by=null, $activo=null, $destacado=null, $filtro_id_categoria=null) {
	global $link;
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
	    $order_by = " ORDER BY g.$filtro DESC ";
   elseif($filtro && $order=='a')
	    $order_by = " ORDER BY g.$filtro ASC ";
	 else
 	    $order_by = " ORDER BY g.id DESC ";
  }	 
  	 
 $q = "SELECT g.*, c.nombre as categoria_nombre "
    . "FROM galerias AS g "
    . "LEFT JOIN categorias c ON c.id=g.id_categoria "
    . "WHERE 1 "
	  . ($activo ?  "AND g.activo='".$activo."' " : null)
	  . ($filtro_id_categoria ?  "AND g.id_categoria='".$filtro_id_categoria."' " : null)
	  . "".$order_by.""
    . ($limite	? " limit ".$limite." " :null)	  
	  . "";
	return @mysql_query($q, $link);  
}

function obtener($id) {
	global $link;
	$q = "SELECT g.*, c.nombre as categoria_nombre "
     . "FROM galerias AS g "
     . "LEFT JOIN categorias c ON c.id=g.id_categoria "
     . "WHERE 1 "
     . "AND g.id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
}


function grabar( $campos=null, $contenido ) {
	 global $link;
	 
	 // basicos	
	 $id_categoria	= escapeSQLFull($campos['id_categoria']);
	 $nombre	= escapeSQLFull($campos['galeria']['nombre']);
	 $descripcion	= escapeSQLFull($campos['galeria']['descripcion']);
   
   // estado
	 $activo	= escapeSQLFull($campos['activo']);	 
   
   // orden
	 $orden	= escapeSQLFull($campos['galeria']['orden']);	 
	 
	 // usuarios?
	 
   $q = "INSERT INTO galerias (id_categoria, nombre, descripcion,  activo, orden, fecha_alta) VALUES ('".$id_categoria."', '".$nombre."', '".$descripcion."',  '".$activo."', '".$orden."', NOW())";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
		
	 } 

	 return $last_id;
}

 function editar( $id, $campos=null, $contenido ) {
	 global $link;

	 // basicos	
	 $id_categoria	= escapeSQLFull($campos['id_categoria']);
	 $nombre	= escapeSQLFull($campos['galeria']['nombre']);
	 $descripcion	= escapeSQLFull($campos['galeria']['descripcion']);
   
   // estado
	 $activo	= escapeSQLFull($campos['activo']);	 
   
   // orden
	 $orden	= escapeSQLFull($campos['galeria']['orden']);	 
	 
	 // usuarios?

   $q = "UPDATE galerias SET  id_categoria='".$id_categoria."', nombre='".$nombre."', descripcion='".$descripcion."', activo='".$activo."',  fecha_mod=NOW(), orden='".$orden."' WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	 
	 return @mysql_insert_id($link);
 }

 function destacado($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE galerias SET destacado='".$campo."' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE galerias SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM galerias WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

 function cantidad($id) {
  global $link;
  $q = "SELECT COUNT(*) AS Cantidad FROM galerias_fotos AS g WHERE g.id_galeria='".$id."' ";
  $r = @mysql_query($q,$link);
  $a = @mysql_fetch_array($r);		
  return $a['Cantidad'];
 }
 
 //////////////////////////////////////////////////////////
 // CAMPOS EXTRAS
 //////////////////////////////////////////////////////////

} // end class
?>