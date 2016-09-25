<?php
class SeguimientoDispositivo {


 function obtener_all($porPagina=null, $paginacion=null, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $id_cliente=null) {
 	 global $link;
 	 
   if(strlen($order_by) == 0) {
    if($filtro && $order=='d')
 	    $order_by = "ORDER BY d.$filtro DESC";
    elseif($filtro && $order=='a')
 	    $order_by = "ORDER BY d.$filtro ASC";
 	 else
  	  $order_by = "ORDER BY d.orden ASC, d.id DESC";
   }	 
   	 
  $q = "SELECT d.* "
     . "FROM plugin_seguimiento_dispositivo AS d "
   //. "LEFT JOIN ciudades ciu ON ciu.id=i.id_ciudad "
     . "WHERE 1 "  
 	   . ($activo 		?  "AND d.activo='".$activo."' " : null)
 	   . ($id_cliente ?  "AND d.id_cliente='".$id_cliente."' " : null)
 	   . " ".$order_by." "
     . ($limite	? " limit ".$limite." " :null)	  
 	   . "";
 	//print $q;
 	return @mysql_query($q, $link);  
 }

 function obtener($id) {
  global $link;
 	$q = "SELECT se.* FROM plugin_seguimiento_entrega AS se WHERE se.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 function grabar_entrega($campos) {
   global $link;
 }
 
 function editar_entrega($id, $campos) {
   global $link;
 }
 
 ////////////////////////////////////////////
 // DISPOSITIVOS
 ////////////////////////////////////////////
 function obtener_dispositivo_all($porPagina=null, $paginacion=null, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $codigo=null) {
 	 global $link;
 	 
   if(strlen($order_by) == 0) {
    if($filtro && $order=='d')
 	    $order_by = "ORDER BY di.$filtro DESC";
    elseif($filtro && $order=='a')
 	    $order_by = "ORDER BY di.$filtro ASC";
 	 else
  	  $order_by = "ORDER BY di.nombre ASC";
   }	 
   	 
  $q = "SELECT di.* "
     . "FROM plugin_seguimiento_dispositivo AS di "
     . "WHERE 1 "  
 	   . ($activo 		?  "AND di.activo='".$activo."' " : null)
 	   . ($codigo ?  "AND di.codigo='".$codigo."' " : null)
 	   . " ".$order_by." "
     . ($limite	? " limit ".$limite." " :null)	  
 	   . "";
 	//print $q;
 	return @mysql_query($q, $link);  
 }
 
 function obtener_dispositivo($id) {
  global $link;
 	$q = "SELECT di.* FROM plugin_seguimiento_dispositivo AS di WHERE di.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }
 
 function grabar_dispositivo($campos) {
   global $link;
   
   $codigo = escapeSQLFull($campos['dispositivo']['codigo']);
 	 $nombre = escapeSQLFull($campos['dispositivo']['nombre']);
 	 $carga = escapeSQLFull($campos['dispositivo']['carga']);
 	 $contenido = escapeSQLFull($campos['dispositivo']['contenido']);
 	 $activo = escapeSQLFull($campos['dispositivo']['activo']);
 	 
 	 // IMAGEN
 	 $imagen = $campos['imagen'];
 	 
 	 $q = "INSERT INTO plugin_seguimiento_dispositivo (imagen, codigo, nombre, contenido, carga, fecha_alta, activo) VALUES ('".$imagen."', '".$codigo."', '".$nombre."', '".$contenido."', '".$carga."', NOW(), '".$activo."')";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 return $last_id; 
 }
 
 function editar_dispositivo($id, $campos) {
   global $link;

   $codigo = escapeSQLFull($campos['dispositivo']['codigo']);
 	 $nombre = escapeSQLFull($campos['dispositivo']['nombre']);
 	 $carga = escapeSQLFull($campos['dispositivo']['carga']);
 	 $contenido = escapeSQLFull($campos['dispositivo']['contenido']);
 	 $activo = escapeSQLFull($campos['dispositivo']['activo']);

 	 // IMAGEN
 	 $imagen = $campos['imagen'];
 	  	 
 	 $q = "UPDATE plugin_seguimiento_dispositivo SET codigo='".$codigo."', nombre='".$nombre."', contenido='".$contenido."', carga='".$carga."', fecha_mod=NOW(), activo='".$activo."' WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	    
 }
 
 function publicar_dispositivo($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE plugin_seguimiento_dispositivo SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
}

function eliminar_dispositivo($id) {
 global $link;
 $q = "DELETE FROM plugin_seguimiento_dispositivo WHERE id='".$id."'";
 $r = @mysql_query($q,$link);
}
} // end class
?>