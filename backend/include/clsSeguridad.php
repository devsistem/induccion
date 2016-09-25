<?php

class Seguridad {

// Ip

/**
 * lista las ips en la bd 27/06/2009 14:59
 *
 * @table sys_ip
 * @access public
 * @param int estado
*/
function obtener_todas($estado=null) {
 global $link;
 $q = "SELECT I.* FROM ip AS I "
		. (!is_null($estado) ? "WHERE estado = '".$estado."' "  :null)
	  .	"ORDER BY I.idx, I.idx ASC";
 return @mysql_query($q,$link);
}

function obtener($idx) {
 global $link;
 $q = "SELECT I.* FROM ip AS I WHERE idx=".$idx." LIMIT 1";
 $r = @mysql_query($Q,$link);
 return @mysql_fetch_array($R);
}
 
function grabar($ip, $descripcion) {
 global $link;
 $q = "INSERT INTO ip (ip, descripcion,estado) VALUES ('$ip','$descripcion', 1)";
 $r = @mysql_query($q,$link);	 
 return @mysql_insert_id($link);
}
  
function editar($idx, $ip, $descripcion) {
 global $link;
 $q  = "UPDATE ip SET ip='".$ip."', descripcion='".$descripcion."' WHERE idx=".$Idx."";
 return @mysql_query($q,$link))
}
 
function eliminar($id){
 global $link;
 $q  = "DELETE FROM ip WHERE idx='".$id."' ";
 return @mysql_query($q,$link);	 
}

function publicar($id,$campo) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q 		= "UPDATE ip SET estado='".$campo."' WHERE idx='".$id."' ";
 return @mysql_query($q,$link);	 
}
   
} // end class
?>	