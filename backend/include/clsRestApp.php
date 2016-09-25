<?php
class RestApp  {

 function activar_desde_app($usuario=null,$clave=null) {
  global $link;
 
  //  buscar el usuario
  $q = "SELECT b.* FROM ".DBSYS_BACKENDUSER." AS b WHERE b._usuario='".$usuario."' AND b._clave='".md5($clave)."' LIMIT 1";
  $r = @mysql_query($q,$link);
  $a = @mysql_fetch_array($r);	 
 
  if($a['id'] > 0) {
	  $q = "UPDATE  ".DBSYS_BACKENDUSER." SET app_activada = '1' WHERE id='".$a['id']."'";
	 
	  $r = @mysql_query($q,$link);

	  if($r != NULL) {
	   return $a['id'];
	  }
  }
  
  return 0;
 }
 
 function acceso_desde_app($usuario=null,$clave=null) {
  global $link;
 
  //  buscar el usuario
  $q = "SELECT b.* FROM ".DBSYS_BACKENDUSER." AS b WHERE b._usuario='".$usuario."' AND b._clave='".md5($clave)."' LIMIT 1";
  $r = @mysql_query($q,$link);
  $a = @mysql_fetch_array($r);	 	
 }
 
 // inserta el pedido desde la app
 // faltan  cuota_entrada, promocion, duenio, id_producto, cuotas,  forma_pago
 function sincronizar_desde_app($token,$id_vendedor,$idx,$cliente_cuen,$cliente_dni, $cliente_nombre, $cliente_apellido, $cliente_telefono, $cliente_celular, $cliente_email, $id_provincia, $id_canton, $parroquia, $barrio, $cliente_calle, $cliente_calle_numero, $cliente_calle_secundaria, $cliente_referencia, $id_marca, $id_modelo,$forma_pago,$cuotas,$cuota_entrada, $horario_recepcion, $imagen_serial, $imagen_factura, $imagen_dni_frente, $imagen_dni_posterior) {
	loadClasses('Vendedor');
	loadClasses('BackendUsuario');
	loadClasses('Producto');
	global $BackendUsuario;
	global $Producto;
	 
  global $link;
  
  // armar el array
  $arr_idx = explode("|", $idx);
  $cliente_cuen = explode("|", $cliente_cuen);
  $cliente_dni = explode("|", $cliente_dni);
  $cliente_nombre = explode("|", $cliente_nombre);
  $cliente_apellido = explode("|", $cliente_apellido);
  $cliente_telefono = explode("|", $cliente_telefono);
  $cliente_celular = explode("|", $cliente_celular);
  $cliente_email = explode("|", $cliente_email);
  $id_provincia = explode("|", $id_provincia);
  $id_canton = explode("|", $id_canton);
  $parroquia = explode("|", $parroquia);
  $barrio = explode("|", $barrio);
  $cliente_calle = explode("|", $cliente_calle);
  $cliente_calle_numero = explode("|", $cliente_calle_numero);
  $cliente_calle_secundaria = explode("|", $cliente_calle_secundaria);
  $cliente_referencia = explode("|", $cliente_referencia);
  $id_marca = explode("|", $id_marca);
  $id_modelo = explode("|", $id_modelo);
  $marca = explode("|", $marca);
  $modelo = explode("|", $modelo);
  
  // nuevos
  $forma_pago = explode("|", $forma_pago);
  $cuota_entrada = explode("|", $cuota_entrada);
  $cuotas = explode("|", $cuotas);
  $horario_recepcion = explode("|", $horario_recepcion);
  $promocion = explode("|", $promocion);

  // 03/07/2016 22:43:41
  $imagen_serial = explode("|", $imagen_serial);
  // 27/07/2016 1:00:28
  $imagen_factura = explode("|", $imagen_factura);
  $imagen_dni_frente = explode("|", $imagen_dni_frente);
  $imagen_dni_posterior = explode("|", $imagen_dni_posterior);
  

  
  $arrLastId = array();
  
  // estado inicial
  $activo = 1;
  $estado = 1;
  
  $id_vendedor = $id_vendedor;
  
  for($i=0; $i < count($arr_idx); $i++ ) {
	 if($arr_idx[$i] > 0) {
	 	 
		 // limpiar las carpetas de las imagenes
  	 $arr_imagen_factura = explode("/", $imagen_factura[$i]);
 		 $indice = count($arr_imagen_factura) - 1;
  	 $imagen_factura_db = $arr_imagen_factura[$indice];

  	 $arr_imagen_dni_frente = explode("/", $imagen_dni_frente[$i]);
 		 $indice = count($arr_imagen_dni_frente) - 1;
  	 $imagen_dni_frente_db = $arr_imagen_dni_frente[$indice];

  	 $arr_imagen_dni_posterior = explode("/", $imagen_dni_posterior[$i]);
 		 $indice = count($arr_imagen_dni_posterior) - 1;
  	 $imagen_dni_posterior_db = $arr_imagen_dni_posterior[$indice];
  	  
	 	 // traer marca y modelo
	 	 
	 	 // por compatibilidad se inserta el nombre del vendedor
		 $arrVendedor     = $BackendUsuario->obtener($id_vendedor);
		 $vendedor_nombre = $arrVendedor['nombre'] . " " . $arrVendedor['apellido'];
		 
		 // por cada pedido valida q no existe un cuen
		 // en el caso de q exista, se actualiza el pedido
		 $existe_cuen = $this->existe_cuen($cliente_cuen[$i]);
		 
  	 if( $existe_cuen > 0) {
  	 	$q = "UPDATE pedidos SET imagen_factura_g='".$imagen_factura_db."', imagen_dni_frente_g='".$imagen_dni_frente_db."', imagen_dni_posterior_g='".$imagen_dni_posterior_db."', id_marca='".$id_marca[$i]."', id_modelo='".$id_modelo[$i]."', marca='".$marca[$i]."', modelo='".$modelo[$i]."', cuota_entrada='".$cuota_entrada[$i]."', promocion='".$promocion[$i]."',  cliente_dni='".$cliente_dni[$i]."', cliente_nombre='".$cliente_nombre[$i]."', cliente_apellido='".$cliente_apellido[$i]."', cliente_telefono='".$cliente_telefono[$i]."', cliente_celular='".$cliente_celular[$i]."', cliente_email='".$cliente_email[$i]."', id_provincia='".$id_provincia[$i]."', id_canton='".$id_canton[$i]."', parroquia='".$parroquia[$i]."', barrio='".$barrio[$i]."', cliente_calle='".$cliente_calle[$i]."',cliente_calle_numero='".$cliente_calle_numero[$i]."', cliente_calle_secundaria='".$cliente_calle_secundaria[$i]."', cliente_referencia='".$cliente_referencia[$i]."', cuotas='".$cuotas[$i]."', forma_pago='".$forma_pago[$i]."', horario_recepcion='".$horario_recepcion[$i]."', medidor220='".$medidor220[$i]."',circuito_interno='".$circuito_interno[$i]."',ducha_electrica='".$ducha_electrica[$i]."' WHERE id='".$arr_idx[$i]."' ";
 		 	$r = @mysql_query($q,$link);	 
  	 } else {
  	 	$q = "INSERT INTO pedidos (imagen_factura_g, imagen_dni_frente_g, imagen_dni_posterior_g, imagen_serial, id_marca, id_modelo, marca, modelo, cuota_entrada, promocion, cliente_cuen, duenio, id_vendedor, vendedor_nombre, cliente_dni, cliente_nombre, cliente_apellido, cliente_telefono, cliente_celular, cliente_email, id_provincia, id_canton, parroquia, barrio, cliente_calle,cliente_calle_numero,cliente_calle_secundaria,cliente_referencia, id_producto, caracteristicas, color, cuotas, forma_pago, horario_recepcion, activo, estado, fecha_alta, fecha_venta, ip, medidor220,circuito_interno,ducha_electrica, tipo, id_pedido_app ) VALUES ('".$imagen_factura_db."', '".$imagen_dni_frente_db."', '".$imagen_dni_posterior_db."', '".$imagen_serial[$i]."', '".$id_marca[$i]."', '".$id_modelo[$i]."', '".$marca[$i]."', '".$modelo[$i]."', '".$cuota_entrada[$i]."', '".$promocion[$i]."', '".$cliente_cuen[$i]."', '".$duenio."', '".$id_vendedor."', '".$vendedor_nombre."',  '".$cliente_dni[$i]."', '".$cliente_nombre[$i]."', '".$cliente_apellido[$i]."', '".$cliente_telefono[$i]."', '".$cliente_celular[$i]."', '".$cliente_email[$i]."', '".$id_provincia[$i]."', '".$id_canton[$i]."', '".$parroquia[$i]."', '".$barrio[$i]."', '".$cliente_calle[$i]."', '".$cliente_calle_numero[$i]."', '".$cliente_calle_secundaria[$i]."', '".$cliente_referencia[$i]."', '".$id_producto."', '".$caracteristicas."', '".$color."', '".$cuotas[$i]."', '".$forma_pago[$i]."', '".$horario_recepcion[$i]."',  1, '".$estado."', NOW(), '".$fecha_venta."', '".$_SERVER['REMOTE_ADDR']."', '".$medidor220."', '".$circuito_interno."', '".$ducha_electrica."', 'app', '".$arr_idx[$i]."') ";
 		 	$r = @mysql_query($q,$link);	 
		 }
		 

		 $last_id = @mysql_insert_id($link);
		 $valor = $arr_idx[$i]."-".$last_id;
		 array_push($arrLastId,$valor);
		}
  }
  
  $id_sincronizados = implode(",",$arrLastId);
  return trim($id_sincronizados); 
 } // f
 
 function bajar_pedidos_by_vendedor($token,$id_vendedor,$idx) {
   global $link;

   $q = "SELECT pe.*, prov.nombre as cliente_provincia, ca.nombre as cliente_canton, ba.nombre as backend_nombre, ba.apellido as backend_apellido "
	    . "FROM pedidos AS pe "
	    . "LEFT JOIN productos pro ON pro.id=pe.id_producto "
  	  . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
  	  . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
    	. "LEFT JOIN sys_backendusuario ba ON ba.id=pe.id_vendedor "
    	. "WHERE 1 "
	  	. "AND pe.activo = 1 "
	  	. "AND pe.tipo = 'app' "
	  	. ($estado ? "AND pe.estado='".$estado."' " : null)
	  	. ($id_vendedor ? "AND pe.id_vendedor='".$id_vendedor."' " : null)
	  	. "".$order_by.""
    	. ($limite	? " limit ".$limite." " :null)	  
	  	. "";
		 //print $q; 
	return @mysql_query($q, $link);  
 }
 
 function existe_cuen($cliente_cuen) {
  global $link;
	$q  = "SELECT COUNT(*) AS Cantidad  FROM pedidos  WHERE cliente_cuen='".$cliente_cuen."' ";
  $r  = @mysql_query($q, $link);
  $a  = @mysql_fetch_array($r);
  return $a['Cantidad'];
 }
} // end class
?>