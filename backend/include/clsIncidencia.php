<?php
class Incidencia {

 // propiedades
 public $nombre;


 // contructor
 function __construct() {
       
 }
 
 // ABM
 function obtener($id) {
  global $link;
 	$q = "SELECT i.* FROM pedidos_incidencias_tags AS i WHERE i.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 function obtener_all($OrderBy=null,  $activo=null) {
  global $link;

   $q = "SELECT  i.* "
      . "FROM pedidos_incidencias_tags AS i "
      . "WHERE 1 "
      . (($activo) ?  "AND i.activo='".$activo."' " : null)
      . " ORDER BY i.nombre ASC "
      . "";
 	return @mysql_query($q,$link);
 }
 
 // insert
 function grabar( $campos=null ) {
	 global $link;
	 
	 $nombre = escapeSQLFull($campos['nombre']);
 	 $contenido = escapeSQLFull($campos['contenido']);

   $q = "INSERT INTO pedidos_incidencias_tags (nombre, contenido, fecha_alta ) VALUES ('".$nombre."', '".$contenido."', NOW() )";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
 }
  
 // update
 function editar($id, $campos=null) {
   global $link;

	 $nombre = escapeSQLFull($campos['nombre']);
 	 $contenido = escapeSQLFull($campos['contenido']);
 	 
   $q = "UPDATE pedidos_incidencias_tags SET nombre='".$nombre."', contenido='".$contenido."' WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	 return $r;
 }

 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE pedidos_incidencias_tags SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 // a la papelera
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM pedidos_incidencias_tags WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }
 
 function cantidad_by_tag($id) {
  global $link;
  $q = "SELECT COUNT(*) AS Cantidad FROM pedidos_incidencias WHERE id_incidencia='".$id."' ORDER BY Cantidad DESC ";
  $r = @mysql_query($q,$link);
  $a = @mysql_fetch_array($r);		
  return $a['Cantidad']; 
 }
} // end class
?>