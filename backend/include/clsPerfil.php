<?php
class Perfil {

 // propiedades
 public $nombre;


 // contructor
 function __construct() {
       
 }
 
 // ABM
 
 // select all
 function obtener_all($OrderBy=null,  $activo=null) {
  global $link;

   $q = "SELECT  p.* "
      . "FROM perfil AS p "
      . "WHERE 1 "
      . (($activo) ?  "AND p.activo='".$activo."' " : null)
      . " $OrderBy "
      . "";
 	return @mysql_query($q,$link);
 }

 // select one
 function obtener($id) {
  global $link;
 	$q = "SELECT p.* FROM perfil AS p WHERE p.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 // insert
 function grabar( $campos=null ) {
	 global $link;
	 
	 $nombre = escapeSQLFull($campos['perfil']['nombre']);

   $q = "INSERT INTO 	 (nombre, fecha_alta ) VALUES ('".$nombre."', '".$activo."', NOW() )";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
 }
  
 // update
 function editar($id, $campos=null) {
   global $link;
   
	 $nombre = escapeSQLFull($campos['perfil']['nombre']);
	
	 $q = "UPDATE perfil SET nombre='".$nombre."', fecha_alta=NOW() WHERE id='".$id."' ";
   @mysql_query($q,$link);
 }

 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE perfil SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function estado($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q = "UPDATE 	perfil SET estado=".$campo."  WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 // a la papelera
 function eliminar($id) {
  global $link;
 	$q = "UPDATE 	 SET activo=9  WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }


//////////////////////////////////////////////////////////////
// EXTRAS
//////////////////////////////////////////////////////////////  
 
 function asignar_perfil($id_perfil, $id_backend) {
   global $link;
 
 }
 
 function tiene_perfil($id_perfil, $id_backend) {
   global $link;
 	 $q = "SELECT COUNT(*) AS Cantidad  FROM backendusuario_perfil AS P WHERE P.id_backend='".$id_backend."' AND P.id_perfil='".$id_perfil."' ";
 	 $r = @mysql_query($q, $link);
 	 $a = @mysql_fetch_array($r);
   return $a['Cantidad'];
 }

} // end class
?>