<?php
class Tag {

 // propiedades
 public $nombre;


 // contructor
 function __construct() {
       
 }
 
 // ABM
 function obtener($id) {
  global $link;
 	$q = "SELECT t.* "
 		 . "FROM productos_tags AS t "
 		 . "WHERE t.id='".$id."' "
 		 . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 function obtener_all($porPagina=null, $pagina=null,  $limite=null, $order_by=null, $activo=null) {
  global $link;

   $q = "SELECT  t.* "
      . "FROM productos_tags AS t "
      . "WHERE 1 "
      . (($activo) ?  "AND t.activo='".$activo."' " : null)
      . " $order_by "
      . ($porPagina	? "limit ".$pagina*$porPagina.",".$porPagina :null)
      . ($limite	? "limit ".$limite :null)
      . "";
      print $q;
 	return @mysql_query($q,$link);
 }
 

 // insert
 function grabar( $campos=null) {
	 global $link;

	 $activo	= escapeSQLFull($campos['activo']);
	 $nombre	= escapeSQLFull($campos['tag']['nombre']);
	 $orden	= escapeSQLFull($campos['tag']['orden']);

   $q = "INSERT INTO productos_tags (nombre, orden, tipo, activo, fecha_alta) VALUES ( '".$nombre."', '".$orden."', '".$tipo."', '".$activo."', NOW())";
	 $r = @mysql_query($q,$link);
	 $last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
		
	 } 

	 return $last_id;
}

 // update
 function editar( $id, $campos=null ) {
	 global $link;

	 $activo	= escapeSQLFull($campos['activo']);
	 $nombre	= escapeSQLFull($campos['tag']['nombre']);
	 $orden	= escapeSQLFull($campos['tag']['orden']);

   $q = "UPDATE productos_tags SET nombre='".$nombre."', orden='".$orden."', activo='".$activo."' WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);

	 return $id;
 }

function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE tags SET activo='".$campo."' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
}

// a la papelera
function eliminar($id) {
  global $link;
  $q = "DELETE FROM productos_tags WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
}
 
   
 
} // end class
?>