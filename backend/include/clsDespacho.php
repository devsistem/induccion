<?php
class Despacho {
	
function obtener_all($order_by=null, $dia_actual, $mes_actual, $anio_actual, $activo=null, $estado=null, $filtro_id_vendedor=null) {
	global $link;
	
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
	    $order_by = " ORDER BY d.$filtro DESC ";
   elseif($filtro && $order=='a')
	    $order_by = " ORDER BY d.$filtro ASC ";
	 else
 	    $order_by = " ORDER BY d.id DESC ";
  }	 
  	 
 $q = "SELECT d.*, pro.nombre as producto_nombre "
    . "FROM pedidos AS d "
    . "LEFT JOIN productos pro ON pro.id=d.id_producto "
    . "WHERE 1 "
	  . ($dia_actual ?   "AND d.recepcion_confirmada_dia='".$dia_actual."' " : null)
	  . ($mes_actual ?   "AND d.recepcion_confirmada_mes='".$mes_actual."' " : null)
	  . ($anio_actual ?  "AND d.recepcion_confirmada_anio='".$anio_actual."' " : null)
	  . ($activo ?  "AND d.activo='".$activo."' " : null)
	  . ($estado ?  "AND d.estado='".$estado."' " : null)
	  . "".$order_by.""
    . ($limite	? " limit ".$limite." " :null)	  
	  . "";
	return @mysql_query($q, $link);  
}

 function obtener($id) {
	global $link;
	$q = "SELECT pe.* "
     . "FROM pedidos AS pe "
     . "WHERE 1 "
     . "AND pe.id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }

 function obtener_full($id) {
	global $link;
	$q = "SELECT pe.*, pro.nombre as nombre_provincia, ca.nombre as nombre_canton "
     . "FROM pedidos AS pe "
     . "LEFT JOIN provincias pro ON pro.id=pe.id_provincia "
     . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
     . "WHERE 1 "
     . "AND pe.id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }
 
 function grabar( $campos=null ) {
	 loadClasses('Vendedor');
	 global $Vendedor;
	 global $link;

	 //datos basicos	

	 $duenio = escapeSQLFull($campos['duenio']);
	 $cliente_cuen = escapeSQLFull($campos['registro']['cliente_cuen']);
	 $cliente_dni = escapeSQLFull($campos['registro']['dni']);
	 $cliente_nombre = escapeSQLFull($campos['registro']['nombre']);
	 $cliente_apellido = escapeSQLFull($campos['registro']['apellido']);
	 $cliente_telefono = escapeSQLFull($campos['registro']['cliente_telefono']);
	 $cliente_celular = escapeSQLFull($campos['registro']['cliente_celular']);
	 
	 $cliente_email = escapeSQLFull($campos['registro']['email']);
	 $id_provincia = escapeSQLFull($campos['registro']['id_provincia']);
	 $id_canton = escapeSQLFull($campos['registro']['id_canton']);
	 $parroquia = escapeSQLFull($campos['registro']['parroquia']);
	 $barrio = escapeSQLFull($campos['registro']['barrio']);	
	 $cliente_calle = escapeSQLFull($campos['registro']['cliente_calle']);
	 $cliente_calle_numero = escapeSQLFull($campos['registro']['cliente_calle_numero']);	
	 $cliente_calle_secundaria = escapeSQLFull($campos['registro']['cliente_calle_secundaria']);	
	 $cliente_referencia = escapeSQLFull($campos['registro']['cliente_referencia']);	
	 $latitude = escapeSQLFull($campos['latitude']);	
	 $longitude = escapeSQLFull($campos['longitude']);	
	 
	 // COCINA 
	 $id_producto = escapeSQLFull($campos['registro']['id_producto']);	
	 $modelo = escapeSQLFull($campos['registro']['modelo']);
	 $marca = escapeSQLFull($campos['registro']['marca']);
	 $color = escapeSQLFull($campos['registro']['color']);
	
	 $cuotas = escapeSQLFull($campos['registro']['cuotas']);
	 $forma_pago = escapeSQLFull($campos['registro']['forma_pago']);
	 
	 // OLLAS
	 $caracteristicas_olla = $campos['caracteristicas_olla'];
	 $color_olla = $campos['color_olla'];
	 
	 $forma_pago_ollas = escapeSQLFull($campos['registro']['forma_pago_ollas']);
	 $horario_recepcion = escapeSQLFull($campos['registro']['horario_recepcion']);

   // estado
	 $activo	= escapeSQLFull($campos['activo']);	 
	 $estado	= escapeSQLFull($campos['estado']);	 

	 // vendedor
	 $id_vendedor = $Vendedor->obtener_vendedor_id();
	 $vendedor_nombre = $Vendedor->obtener_nombre();
   
   // checks
   $medidor220 = escapeSQLFull($campos['medidor220']);
   $circuito_interno = escapeSQLFull($campos['circuito_interno']);
   $ducha_electrica = escapeSQLFull($campos['ducha_electrica']);

   $q = "INSERT INTO pedidos (cliente_cuen, duenio, id_vendedor, vendedor_nombre, cliente_dni, cliente_nombre, cliente_apellido, cliente_telefono, cliente_celular, cliente_email, id_provincia, id_canton, parroquia, barrio, cliente_calle,cliente_calle_numero,cliente_calle_secundaria,cliente_referencia, latitude, longitude, id_producto, caracteristicas, modelo, marca, color, cuotas, forma_pago, horario_recepcion, activo, estado, fecha_alta, ip,    medidor220,circuito_interno,ducha_electrica
) VALUES ('".$cliente_cuen."', '".$duenio."', '".$id_vendedor."', '".$vendedor_nombre."', '".$cliente_dni."', '".$cliente_nombre."', '".$cliente_apellido."', '".$cliente_telefono."', '".$cliente_celular."', '".$cliente_email."', '".$id_provincia."', '".$id_canton."', '".$parroquia."', '".$barrio."', '".$cliente_calle."', '".$cliente_calle_numero."', '".$cliente_calle_secundaria."', '".$cliente_referencia."', '".$latitude."', '".$longitude."', '".$id_producto."', '".$caracteristicas."', '".$modelo."', '".$marca."', '".$color."', '".$cuotas."', '".$forma_pago."', '".$horario_recepcion."', 1, 0, NOW(), '".$_SERVER['REMOTE_ADDR']."',  '".$medidor220."', '".$circuito_interno."', '".$ducha_electrica."') ";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
		 for($i=0; $i < count($caracteristicas_olla); $i++) {
		  $q = "INSERT INTO pedidos_ollas (id_pedido,olla_caracteristicas,olla_color,olla_modelo,olla_marca, fecha_alta) VALUES ('".$last_id."', '".$caracteristicas_olla[$i]."', '".$color_olla[$i]."', '".$modelo_olla[$i]."', '".$marca_olla[$i]."', NOW()) ";  
			@mysql_query($q,$link);	
	   }
	 } 

	 return $last_id;
}

 function editar_pedido( $id, $campos=null, $contenido ) {
	 global $link;

   // confirmacion de horario
		$recepcion_confirmada_dia	 = escapeSQLFull($campos['recepcion_confirmada_dia']);
		$recepcion_confirmada_mes	 = escapeSQLFull($campos['recepcion_confirmada_mes']);
		$recepcion_confirmada_desde	 = escapeSQLFull($campos['recepcion_confirmada_desde']);
		$recepcion_confirmada_hasta	 = escapeSQLFull($campos['recepcion_confirmada_hasta']);

 		$q 		= "UPDATE pedidos SET recepcion_confirmada_dia='".$recepcion_confirmada_dia."', recepcion_confirmada_mes='".$recepcion_confirmada_mes."', recepcion_confirmada_desde='".$recepcion_confirmada_desde."', recepcion_confirmada_hasta='".$recepcion_confirmada_hasta."', estado='4', fecha_mod=NOW() WHERE id='".$id."'";
  	$r = @mysql_query($q,$link);
 		
 		// dispara mails? 			   
	 return $id;
 }


 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE pedidos SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function estado($id,$campo) {
 	global $link;
 	
 	if($campo == 2) {
 	 $q = "UPDATE pedidos SET estado='3', fecha_mod=NOW() WHERE id='".$id."'";
   $r = @mysql_query($q,$link);
	} else {
 	 $q = "UPDATE pedidos SET estado='".$campo."', fecha_mod=NOW() WHERE id='".$id."'";
   $r = @mysql_query($q,$link);
	}
  return $id;
 }
 
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM pedidos WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

 function dni_existe($dni) {
	global $link;
	$q  = "SELECT * FROM pedidos  WHERE dni='".$dni."' ";
  $r	= @mysql_query($q,$link);
	return @mysql_num_rows($r);
 } 

//////////////////////////////////////////////////////////
// ALERTAS
//////////////////////////////////////////////////////////

 function grabar_alerta($last_id) {
   global $link;
	
	 // pedido
	 $arrPedido = $this->obtener($last_id);
   $id_vendedor = $arrPedido['id_vendedor'];
   $asunto = "Nuevo Pedido, vendedor " . $arrPedido['vendedor_nombre'];
   
   $activo = 1;
   $estado = 1; // NUEVO
   	  
   $q = "INSERT INTO alertas (id_callcenter,id_pedido,id_vendedor,asunto,descripcion, fecha_alta, activo, estado) VALUES ('".$id_callcenter."', '".$last_id."', '".$id_vendedor."', '".$asunto."', '".$descripcion."', NOW(), '".$activo."', '".$estado."') ";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
		
	 } 

	 return $last_id; 
 }

 function obtener_alertas_all($limite=null, $order_by=null, $activo=null, $estado_nuevas=null, $estado_leidas=null,  $id_operador=null) {
	global $link;
  	 
 $q = "SELECT a.* "
    . "FROM alertas AS a "
    . "WHERE 1 "
	  . ($activo ?  "AND a.activo='".$activo."' " : null)
	  . ($estado_nuevas ?  "AND a.estado='0' " : null)
	  . ($estado_leidas ?  "AND a.estado='1' " : null)
	  . "".$order_by.""
    . ($limite	? " limit ".$limite." " :null)	  
	  . "";
	 // print $q;
	return @mysql_query($q, $link);  
 } 
 
 
//////////////////////////////////////////////////////////
// CAMPOS EXTRAS
//////////////////////////////////////////////////////////

 function obtener_estados($activo=null) {
 	global $link;
  $q = "SELECT e.* "
     . "FROM estados AS e "
     . "WHERE 1 "
	   . ($activo ?  "AND e.activo='".$activo."' " : null)
	   . "ORDER BY e.id ASC "
	   . "";
	 // print $q;
	return @mysql_query($q, $link);   	
 }

//////////////////////////////////////////////////////////
// INCIDENCIAS
//////////////////////////////////////////////////////////

 function incidencia($campos) {
	 global $link;   
 	 // id pedido
 	 $id	= escapeSQLFull($campos['id']);
 	 $contenido	= escapeSQLFull($campos['contenido']);

   $q = "UPDATE pedidos SET incidencia='".$contenido."', fecha_mod=NOW() WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	 
	 //extras
	 if($r) {
		
		 // mails de incidencia
		 $this->enviar_incidencia_cliente($id);
		 $this->enviar_incidencia_vendedor($id);

	 } 

	 return $last_id;
   
 }
 
//////////////////////////////////////////////////////////
// EMAILS
//////////////////////////////////////////////////////////
 
 function enviar_pedido_cliente($last_id) {
    global $link;
    require_once('clsMailer.php');
	
	  // pedido
	  $arrPedido = $this->obtener($last_id);

    $Mailer = new phpmailer();
 	  $Mailer->Host     = MAIL_SMTP; // SMTP servers
 	  $Mailer->Mailer   = "mail";
   	$Mailer->From     = "info@induccion.ec";
   	$Mailer->FromName = "Induccion";
   	$Mailer->AddAddress($arrPedido['cliente_email']); 
   	$Mailer->IsHTML(true); 
   	$Mailer->Subject  =  "[" . $arrPedido['cliente_dni'] . "], " .  $arrPedido['cliente_nombre'] . " " . $arrPedido['cliente_apellido'];
	
    $HTML =  " El vendedor " . $arrPedido['vendedor_nombre'] . "  acaba de ingresar una venta con el siguiente detalle
							 <br><br>	
							 Fecha de ingreso: " . $arrPedido['fecha_alta'] . "
							 Vendedor: " . $arrPedido['vendedor_nombre'] . "
							 <br><br>
							 Nombre:   " . $arrPedido['cliente_nombre'] . "
							 Apellido: " . $arrPedido['cliente_apellido'] . "
							 Cédula:   " . $arrPedido['cliente_dni'] . "
							 <br><br>
							 Cocina:   " . $arrPedido['producto_nombre'] . "
							 Ollas:    " . $ollas . "
							 <br><br>
							 Ha pasado al proceso de verificación de datos y asignar una fecha de entrega a call center
							 ";

    
    $Mailer->Body = $HTML;

 	  if(!$Mailer->Send()) {
    print "ERROR. ";
    die; 
   	} else {
   	return 1;
  	}
 }

 function enviar_pedido_admin($last_id) {
   global $link;
   require_once('clsMailer.php');
	
	  // pedido
	  $arrPedido = $this->obtener($last_id);

    $Mailer = new phpmailer();
 	  $Mailer->Host     = MAIL_SMTP; // SMTP servers
 	  $Mailer->Mailer   = "mail";
   	$Mailer->From     = "info@induccion.ec";
   	$Mailer->FromName = "Induccion";
   	//$Mailer->AddAddress('ventas@induccion.ec'); 
   	//$Mailer->AddAddress('coordinacion@linkear.net'); 
   	$Mailer->AddAddress('mgassmann@gmail.com'); 
   	$Mailer->IsHTML(true); 
   	$Mailer->Subject  =  "[" . $arrPedido['cliente_dni'] . "], " .  $arrPedido['cliente_nombre'] . " " . $arrPedido['cliente_apellido'];
	
    $HTML =  " El vendedor " . $arrPedido['vendedor_nombre'] . "  acaba de ingresar una venta con el siguiente detalle
							 <br><br>	
							 Fecha de ingreso: " . $arrPedido['fecha_alta'] . "
							 Vendedor: " . $arrPedido['vendedor_nombre'] . "
							 <br><br>
							 Nombre:   " . $arrPedido['cliente_nombre'] . "
							 Apellido: " . $arrPedido['cliente_apellido'] . "
							 Cédula:   " . $arrPedido['cliente_dni'] . "
							 <br><br>
							 Cocina:   " . $arrPedido['producto_nombre'] . "
							 Ollas:    " . $ollas . "
							 <br><br>
							 Ha pasado al proceso de verificación de datos y asignar una fecha de entrega a call center
							 ";

    
    $Mailer->Body = $HTML;

 	  if(!$Mailer->Send()) {
    print "ERROR. ";
    die; 
   	} else {
   	return 1;
  	}
 }

 function enviar_pedido_vendedor($last_id) {
   global $link;
   require_once('clsMailer.php');
	 loadClasses( 'Vendedor');
   global $Vendedor;
	
	  // pedido
	  $arrPedido = $this->obtener($last_id);

	  // vendedor
	  $arrVendedor = $Vendedor->obtener($arrPedido['id_vendedor']);
	  
    $Mailer = new phpmailer();
 	  $Mailer->Host     = MAIL_SMTP; // SMTP servers
 	  $Mailer->Mailer   = "mail";
   	$Mailer->From     = "info@induccion.ec";
   	$Mailer->FromName = "Induccion";
   	$Mailer->AddAddress('coordinacion@linkear.net'); 
   	$Mailer->AddAddress('mgassmann@gmail.com'); 
   	$Mailer->IsHTML(true); 
   	$Mailer->Subject  =  "Venta  " . $arrPedido['cliente_nombre'] . " " . $arrPedido['cliente_apellido'];
	
    $HTML =  " Gracias " . $arrVendedor['nombre'] . " " . $arrVendedor['apellido'] . " tu registro del cliente " . $arrPedido['cliente_nombre'] . " " . $arrPedido['cliente_apellido'] . " fue ingresada la venta con éxito, estamos en la etapa de confirmación de datos.
							 <br><br>
							 Gracias por ser parte de la venta de cocinas de inducción, donde esperamos hacer un verdadero cambio en los ciudadanos no solo mejorando el ambiente sino un cambio tecnológico, te animamos a que continues en las ventas
							 <br><br>
								Mes: xxxx
								Acumulado de Ventas: xxxx							 
						 ";

    
    $Mailer->Body = $HTML;

 	  if(!$Mailer->Send()) {
    print "ERROR. ";
    die; 
   	} else {
   	return 1;
  	}
 }

 function enviar_incidencia_cliente($id_pedido) {
   global $link;
   require_once('clsMailer.php');
	 loadClasses( 'Vendedor');
   global $Vendedor;
	
	  // pedido
	  $arrPedido = $this->obtener($last_id);

	  // vendedor
	  $arrVendedor = $Vendedor->obtener($arrPedido['id_vendedor']); 
 }
 
 function enviar_incidencia_vendedor($id_pedido) {
   global $link;
   require_once('clsMailer.php');
	 loadClasses( 'Vendedor');
   global $Vendedor;
	
	 // pedido
	 $arrPedido = $this->obtener($last_id);

	 // vendedor
	 $arrVendedor = $Vendedor->obtener($arrPedido['id_vendedor']);
 }
		 

} // end class
?>