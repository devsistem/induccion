<?php
class Seguimiento {

 ///////////////////////////////////
 // ENGREGAS
 ///////////////////////////////////

 function obtener_entregas_all($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $id_cliente=null, $id_camion=null, $id=null) {
 	 global $link;
 	 
   if(strlen($order_by) == 0) {
    if($filtro && $order=='d')
 	    $order_by = "ORDER BY se.$filtro DESC";
    elseif($filtro && $order=='a')
 	    $order_by = "ORDER BY se.$filtro ASC";
 	 else
  	  $order_by = "ORDER BY se.id DESC";
   }	 
 
   	 
  $q = "SELECT se.*, sd.nombre as nombre_camion "
     . "FROM plugin_seguimiento_entrega AS se "
   //. "LEFT JOIN ciudades ciu ON ciu.id=i.id_ciudad "
   	 . "LEFT JOIN plugin_seguimiento_dispositivo sd ON sd.id=se.id_camion "
     . "WHERE 1 "  
 	   . ($activo ?  "AND se.activo='".$activo."' " : null)
 	   . ($estado ?  "AND se.estado='".$estado."' " : null)
 	   . ($id_cliente ?  "AND se.id_cliente='".$id_cliente."' " : null)
 	   . ($id_camion  ?  "AND se.id_camion='".$id_camion."' " : null)
 	   . ($id  ?  "AND se.id='".$id."' " : null)
 	   . " ".$order_by." "
     . ($limite	? " limit ".$limite." " :null)	  
 	   . "";
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
   	 
	$codigo = escapeSQLFull($campos['entrega']['codigo']);
	$empresa = escapeSQLFull($campos['entrega']['empresa']);
	$conductor_nombre = escapeSQLFull($campos['entrega']['conductor_nombre']);
	$conductor_cedula = escapeSQLFull($campos['entrega']['conductor_cedula']);
	$direccion_calle = escapeSQLFull($campos['entrega']['direccion_calle']);
	$direccion_numero = escapeSQLFull($campos['entrega']['calle_numero']);
	$direccion_numero = escapeSQLFull($campos['entrega']['direccion_numero']);
	$direccion_piso = escapeSQLFull($campos['entrega']['direccion_piso']);
	$direccion_dtp = escapeSQLFull($campos['entrega']['direccion_dtp']);
	$id_ciudad_origen = escapeSQLFull($campos['entrega']['id_ciudad_origen']);
	$id_ciudad_destino = escapeSQLFull($campos['entrega']['id_ciudad_destino']);
	$cliente_nombre = escapeSQLFull($campos['entrega']['cliente_nombre']);
	$cliente_contacto = escapeSQLFull($campos['entrega']['cliente_contacto']);
	$cliente_email = escapeSQLFull($campos['entrega']['cliente_email']);
	$cliente_telefono = escapeSQLFull($campos['entrega']['cliente_telefono']);
	$contenido = escapeSQLFull($campos['entrega']['contenido']);
	$productos_total = escapeSQLFull($campos['entrega']['productos_total']);
  
  // estado
	$activo	= escapeSQLFull($campos['activo']);
	$estado	= escapeSQLFull($campos['estado']);
	 
	$q = "INSERT INTO plugin_seguimiento_entrega (codigo, empresa, conductor_nombre, conductor_cedula, direccion_calle, direccion_numero, direccion_piso, direccion_dtp, id_ciudad_origen, id_ciudad_destino, cliente_nombre, cliente_contacto, cliente_email, cliente_telefono, contenido, productos_total, activo, estado, fecha_alta) VALUES ('".$codigo."', '".$empresa."', '".$conductor_nombre."', '".$conductor_cedula."', '".$direccion_calle."', '".$direccion_numero."', '".$direccion_piso."', '".$direccion_dtp."', '".$id_ciudad_origen."', '".$id_ciudad_destino."', '".$cliente_nombre."', '".$cliente_contacto."', '".$cliente_email."', '".$cliente_telefono."', '".$contenido."', '".$productos_total."','".$activo."', '".$estado."', NOW())";
	$r = @mysql_query($q,$link); 
	$last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
		
		return $last_id;
	 } 
 }
 
 function editar_entrega($id, $campos) {
  global $link;
   
	$codigo = escapeSQLFull($campos['entrega']['codigo']);
	$empresa = escapeSQLFull($campos['entrega']['empresa']);
	$conductor_nombre = escapeSQLFull($campos['entrega']['conductor_nombre']);
	$conductor_cedula = escapeSQLFull($campos['entrega']['conductor_cedula']);
	$direccion_calle = escapeSQLFull($campos['entrega']['direccion_calle']);
	$direccion_numero = escapeSQLFull($campos['entrega']['direccion_numero']);
	$direccion_piso = escapeSQLFull($campos['entrega']['direccion_piso']);
	$direccion_dtp = escapeSQLFull($campos['entrega']['direccion_dtp']);
	$id_ciudad_origen = escapeSQLFull($campos['entrega']['id_ciudad_origen']);
	$id_ciudad_destino = escapeSQLFull($campos['entrega']['id_ciudad_destino']);
	$cliente_nombre = escapeSQLFull($campos['entrega']['cliente_nombre']);
	$cliente_contacto = escapeSQLFull($campos['entrega']['cliente_contacto']);
	$cliente_email = escapeSQLFull($campos['entrega']['cliente_email']);
	$cliente_telefono = escapeSQLFull($campos['entrega']['cliente_telefono']);
	$contenido = escapeSQLFull($campos['entrega']['contenido']);
	$productos_total = escapeSQLFull($campos['entrega']['productos_total']);
  
  // estado
	$activo	= escapeSQLFull($campos['activo']);
	$estado	= escapeSQLFull($campos['estado']);

	$q = "UPDATE plugin_seguimiento_entrega SET empresa='".$empresa."', conductor_nombre='".$conductor_nombre."', conductor_cedula='".$conductor_cedula."', direccion_calle='".$direccion_calle."', direccion_numero='".$direccion_numero."', direccion_piso='".$direccion_piso."', direccion_dtp='".$direccion_dtp."', id_ciudad_origen='".$id_ciudad_origen."', id_ciudad_destino='".$id_ciudad_destino."', cliente_nombre='".$cliente_nombre."', cliente_contacto='".$cliente_contacto."', cliente_email='".$cliente_email."', cliente_telefono='".$cliente_telefono."', contenido='".$contenido."', productos_total='".$productos_total."', activo='".$activo."', fecha_mod=NOW() WHERE id='".$id."' ";
	$r = @mysql_query($q,$link);	 
 }

function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE plugin_seguimiento_entrega SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function estado($id,$campo) {
 	global $link;

 	if($campo==0) {
 		$campo = 1;
 		$fecha_estado = ", fecha_estado=NOW()";
 	} else if($campo==1) {
 		$campo = 2;
 		$fecha_estado = ", fecha_estado=NOW()";
 	} else if($campo==2) {
 		$campo = 3;
 		$fecha_estado = ", fecha_estado=NOW()";
 	} else if($campo==3) {
 		$campo = 4;
 		$fecha_estado = ", fecha_estado=NOW()";
 	} else if($campo==4) {
 		$fecha_estado = ", fecha_estado=NOW()";
 		$campo = 0;
 	}
 	
 	$q = "UPDATE plugin_seguimiento_entrega SET estado=".$campo." $fecha_estado WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function ver_estado($campo) {
 	global $link;
  $estado = "";
 	if($campo==0) {
 		 $estado = "NUEVO";
 	} else if($campo==1) {
 		 $estado = "EN PROCESO";
 	} else if($campo==2) {
 		 $estado = "EN CAMION";
 	} else if($campo==3) {
 		 $estado = "ENTREGADO";
 	} else if($campo==4) {
 		 $estado = "RECHAZADO";
 	}

   print $estado;
 } 
 
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM plugin_seguimiento_entrega WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 } 
} // end class
?>