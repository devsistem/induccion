<?php
class Producto {
	
function obtener_all($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_tipo=null, $filtro_id_categoria=null) {
	global $link;
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
	    $order_by = " ORDER BY p.$filtro DESC ";
   elseif($filtro && $order=='a')
	    $order_by = " ORDER BY p.$filtro ASC ";
	 else
 	    $order_by = " ORDER BY p.id DESC ";
  }	 
  	 
 $q = "SELECT p.*, c.nombre as categoria_nombre "
    . "FROM productos AS p "
    . "LEFT JOIN categorias c ON c.id=p.id_categoria "
    . "WHERE 1 "
	  . ($activo ?  "AND p.activo='".$activo."' " : null)
	  . ($estado ?  "AND p.estado='".$estado."' " : null)
	  . ($filtro_id_tipo ?  "AND p.tipo='".$filtro_id_tipo."' " : null)
	  . ($filtro_id_categoria ?  "AND p.id_categoria='".$filtro_id_categoria."' " : null)
	  . ($precio_desde && $precio_hasta ?  "AND i.precio >= ".$precio_desde." AND i.precio <= ".$precio_hasta." " : null)
	  . ($conprecio ?  "AND i.precio > 0 " : null)
	  . "".$order_by.""
    . ($limite	? " limit ".$limite." " :null)	  
	  . "";
	 // print $q;
	return @mysql_query($q, $link);  
}

 function obtener($id) {
	global $link;
	$q = "SELECT p.*, c.nombre as categoria_nombre "
     . "FROM productos AS p "
     . "LEFT JOIN categorias c ON c.id=p.id_categoria "
     . "WHERE 1 "
     . "AND p.id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }

 function pedir_producto_by_modelo($modelo,$marca,$color,$estado) {
	global $link;
	$q = "SELECT p.*, c.nombre as categoria_nombre "
     . "FROM productos AS p "
     . "LEFT JOIN categorias c ON c.id=p.id_categoria "
     . "WHERE 1 "
     . "AND p.modelo='".$modelo."' "
     . "AND p.marca='".$marca."' "
     . "AND p.color='".$color."' "
     . "AND p.estado='".$estado."' "
     . "ORDER BY p.id ASC "
	   . "LIMIT 1";
	//   print $q;
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }

 function pedir_producto_by_serial($serial, $estado) {
	global $link;
	$q = "SELECT p.*, c.nombre as categoria_nombre "
     . "FROM productos AS p "
     . "LEFT JOIN categorias c ON c.id=p.id_categoria "
     . "WHERE 1 "
     . "AND p.serie='".$serie."' "
     . "AND p.estado='".$estado."' "
     . "ORDER BY p.id ASC "
	   . "LIMIT 1";
	//   print $q;
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }
 
 function grabar( $campos=null ) {
	 global $link;


	 $id_modelo =	$campos['registro']['modelo'];
	 $id_marca =	$campos['registro']['marca'];
	 $arrModelo = $this->obtener_modelo($id_modelo);
	 $arrMarca = $this->obtener_marca($id_marca);
	 $color =	$campos['registro']['color'];
	 
	 $tipo = escapeSQLFull($campos['tipo']);
   $caracteristicas	= escapeSQLFull($campos['caracteristicas']);
   $fabrica	= escapeSQLFull($campos['fabrica']);
   $serie	= escapeSQLFull($campos['serie']); 
   $ruc_fabricante	= escapeSQLFull($campos['ruc_fabricante']); 
   $precio_fabrica	= escapeSQLFull($campos['precio_fabrica']); 
   $precio_contado	= escapeSQLFull($campos['precio_contado']);
   $precio_tarjeta	= escapeSQLFull($campos['precio_tarjeta']);
   $factura_ingreso	= escapeSQLFull($campos['factura_ingreso']);
   
   // productos generales
   $id_categoria =	$campos['id_categoria']; 
   $nombre =	$campos['producto']['nombre']; 
   $descripcion =	$campos['producto']['descripcion']; 
   $contenido =	$campos['producto']['contenido']; 
   $precio_tienda =	$campos['producto']['precio_tienda']; 
   $precio_entrada =	$campos['producto']['precio_entrada']; 
   $precio_anterior =	$campos['producto']['precio_anterior'];

   // imagen principal
   $imagen =	$campos['imagen']; 
   // crear thumb
   $imagen_th =	$campos['imagen']; 
   // 12/07/2016 17:46:36
   $costo_envio =	$campos['producto']['costo_envio']; 
   
   $q = "INSERT INTO productos (costo_envio, precio_entrada, id_categoria, nombre, descripcion, contenido, precio_anterior, precio_tienda, imagen, imagen_th, id_marca, id_modelo, factura_ingreso, tipo, marca, modelo, color, caracteristicas, fabrica, serie, ruc_fabricante, precio_fabrica, precio_contado, precio_tarjeta, estado, activo, fecha_alta) VALUES ('".$costo_envio."', '".$precio_entrada."', '".$id_categoria."', '".$nombre."', '".$descripcion."', '".$contenido."', '".$precio_anterior."', '".$precio_tienda."', '".$imagen."', '".$imagen_th."', '".$id_marca."', '".$id_modelo."', '".$factura_ingreso."', '".$tipo."', '".$arrMarca['nombre']."', '".$arrModelo['nombre']."', '".$color."', '".$caracteristicas."', '".$fabrica."', '".$serie."', '".$ruc_fabricante."', '".$precio_fabrica."', '".$precio_contado."', '".$precio_tarjeta."', '1','1', NOW())";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
	 	
	 	// espeficifaciones
	 	// purga
	 	$q = "DELETE FROM productos_especificaciones WHERE id_producto='".$last_id."' ";
    $r = @mysql_query($q,$link);
   
	 	for($i=0; $i < count($campos['especificacion_nombre']) ;$i++) {
	 	 if(strlen($campos['especificacion_nombre'][$i]) > 0) {	
			$q = "INSERT INTO productos_especificaciones (id_producto, nombre, detalle, descripcion, activo, orden) VALUES ('".$last_id."', '".$campos['especificacion_nombre'][$i]."', '".$campos['especificacion_detalle'][$i]."', '".$campos['especificacion_valor'][$i]."',1,0) ";
		  $r = @mysql_query($q,$link);	 
     }
    }

	 	// caracteristicas
	 	// purga
	 	$q = "DELETE FROM productos_caracteristicas WHERE id_producto='".$last_id."' ";
    $r = @mysql_query($q,$link);
   
	 	for($i=0; $i < count($campos['caracteristica_nombre']) ;$i++) {
	 	 if(strlen($campos['caracteristica_nombre'][$i]) > 0) {	
			$q = "INSERT INTO productos_caracteristicas (id_producto, nombre, valor,activo, orden) VALUES ('".$last_id."', '".$campos['caracteristica_nombre'][$i]."', '".$campos['caracteristica_valor'][$i]."',1,0) ";
		  $r = @mysql_query($q,$link);	 
     }
    }
	 } 

	 return $last_id;
 }

 function editar( $id, $campos=null) {
	 global $link;
   
   $codigo =	$campos['producto']['codigo'];
	 $id_modelo =	$campos['registro']['modelo'];
	 $id_marca =	$campos['registro']['marca'];
	 $arrModelo = $this->obtener_modelo($id_modelo);
	 $arrMarca = $this->obtener_marca($id_marca);
	 $color =	$campos['registro']['color'];
	 
	 $tipo = escapeSQLFull($campos['tipo']);
   $caracteristicas	= escapeSQLFull($campos['caracteristicas']);
   $fabrica	= escapeSQLFull($campos['fabrica']);
   $serie	= escapeSQLFull($campos['serie']); 
   $ruc_fabricante	= escapeSQLFull($campos['ruc_fabricante']); 
   $precio_fabrica	= escapeSQLFull($campos['precio_fabrica']); 
   $precio_contado	= escapeSQLFull($campos['precio_contado']);
   $precio_tarjeta	= escapeSQLFull($campos['precio_tarjeta']);
   $factura_ingreso	= escapeSQLFull($campos['factura_ingreso']);

   // productos generales
   $id_categoria =	$campos['id_categoria']; 
   $nombre =	$campos['producto']['nombre']; 
   $descripcion =	$campos['producto']['descripcion']; 
   $contenido =	$campos['producto']['contenido']; 
   $precio_tienda =	$campos['producto']['precio_tienda']; 
   $precio_entrada =	$campos['producto']['precio_entrada'];
   $precio_anterior =	$campos['producto']['precio_anterior'];
   
   // imagen principal
   $imagen =	$campos['imagen']; 
   // crear thumb
   $imagen_th =	$campos['imagen']; 

   // 12/07/2016 17:46:36
   $costo_envio =	$campos['producto']['costo_envio']; 
     
   $q = "UPDATE productos SET codigo='".$codigo."',  costo_envio='".$costo_envio."', precio_entrada='".$precio_entrada."', id_categoria='".$id_categoria."', nombre='".$nombre."', descripcion='".$descripcion."', contenido='".$contenido."', precio_anterior='".$precio_anterior."', precio_tienda='".$precio_tienda."', imagen='".$imagen."',  imagen_th='".$imagen_th."',factura_ingreso='".$factura_ingreso."',  id_marca='".$id_marca."', id_modelo='".$id_modelo."' ,  marca='".$marca."', modelo='".$modelo."', color='".$color."', caracteristicas='".$caracteristicas."', fabrica='".$fabrica."', serie='".$serie."', ruc_fabricante='".$ruc_fabricante."', precio_fabrica='".$precio_fabrica."', precio_contado='".$precio_contado."', precio_tarjeta='".$precio_tarjeta."', fecha_mod=NOW() WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);
	 
	 	 //extras
	 if($id > 0) {
	 	
	 	// espeficifaciones
	 	// purga
	 	$q = "DELETE FROM productos_especificaciones WHERE id_producto='".$id."' ";
    $r = @mysql_query($q,$link);
   
	 	for($i=0; $i < count($campos['especificacion_nombre']) ;$i++) {
	 	 if(strlen($campos['especificacion_nombre'][$i]) > 0) {	
			$q = "INSERT INTO productos_especificaciones (id_producto, nombre, detalle, descripcion, activo, orden) VALUES ('".$id."', '".$campos['especificacion_nombre'][$i]."', '".$campos['especificacion_detalle'][$i]."', '".$campos['especificacion_valor'][$i]."',1,0) ";
		  $r = @mysql_query($q,$link);	 
     }
    }

	 	// caracteristicas
	 	// purga
	 	$q = "DELETE FROM productos_caracteristicas WHERE id_producto='".$last_id."' ";
    $r = @mysql_query($q,$link);
   
	 	for($i=0; $i < count($campos['caracteristica_nombre']) ;$i++) {
	 	 if(strlen($campos['caracteristica_nombre'][$i]) > 0) {	
			$q = "INSERT INTO productos_caracteristicas (id_producto, nombre, valor,activo, orden) VALUES ('".$id."', '".$campos['caracteristica_nombre'][$i]."', '".$campos['caracteristica_valor'][$i]."',1,0) ";
		  $r = @mysql_query($q,$link);	 
     }
    }
	 } 

	 return  $r;
 }

 function destacado($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE productos SET destacado='".$campo."' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function home($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE productos SET home='".$campo."' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 } 

 function oferta($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE productos SET oferta='".$campo."' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 } 
 
 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE productos SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function estado($id,$campo) {
 	global $link;

 	if($campo==0) {
 		$campo = 1;
 		$fecha_aprobado = ", fecha_aprobado=NOW()";
 	} else if($campo==1) {
 		$campo = 2;
 		$fecha_rechazado = ", fecha_rechazado=NOW()";
 	} else if($campo==2) {
 		$campo = 0;
 	}
 	
 	$q = "UPDATE productos SET estado=".$campo." $fecha_aprobado $fecha_rechazado  WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM productos WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }


 //////////////////////////////////////////////////////////
 // MODULOS
 //////////////////////////////////////////////////////////

 function grabar_modelo( $campos=null ) {
	 global $link;

	 $id_marca	= escapeSQLFull($campos['id_marca']);
   $nombre	= escapeSQLFull($campos['nombre']);
   $tipo = escapeSQLFull($campos['tipo']);
   $comicion = escapeSQLFull($campos['comicion']);

   $q = "INSERT INTO productos_modelo (comicion, id_marca, nombre, tipo, activo) VALUES ('".$comicion."', '".$id_marca."', '".$nombre."', '".$tipo."', '1')";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
		
	 } 

	 return $last_id;
 }

 function editar_modelo( $id, $campos=null ) {
	 global $link;

   $id_marca	= escapeSQLFull($campos['id_marca']);
   $nombre	= escapeSQLFull($campos['nombre']);
   $tipo = escapeSQLFull($campos['tipo']);
   $comicion = escapeSQLFull($campos['comicion']);

   $q = "UPDATE productos_modelo SET comicion='".$comicion."', id_marca='".$id_marca."', nombre='".$nombre."', tipo='".$tipo."'  WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	 return  $r;
 }
 
 function obtener_modelos($activo=null, $filtro_id_tipo=null, $id_marca=null) {
  global $link;
  	 
 $q = "SELECT m.* "
    . "FROM productos_modelo AS m "
    . "WHERE 1 "
	  . ($activo ?  "AND m.activo='".$activo."' " : null)
	  . ($filtro_id_tipo ?  "AND m.tipo='".$filtro_id_tipo."' " : null)
	  . ($id_marca ?  "AND m.id_marca='".$id_marca."' " : null)
	  . "".$order_by.""
	  . "";
	return @mysql_query($q, $link);  
 }

 function obtener_modelo($id) {
	global $link;
	$q = "SELECT m.* "
     . "FROM productos_modelo AS m "
     . "WHERE 1 "
     . "AND m.id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }
 
 function eliminar_modelo($id) {
  global $link;
  $q = "DELETE FROM productos_modelo WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

 function publicar_modelo($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE productos_modelo SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 //////////////////////////////////////////////////////////
 // MARCAS
 //////////////////////////////////////////////////////////

 function grabar_marca( $campos=null ) {
	 global $link;

   $nombre	= escapeSQLFull($campos['nombre']);
   $tipo = escapeSQLFull($campos['tipo']);

   $q = "INSERT INTO productos_marca (nombre, tipo, activo) VALUES ('".$nombre."', '".$tipo."', '1')";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 //extras
	 if($last_id > 0) {
		
	 } 

	 return $last_id;
 }

 function editar_marca( $id, $campos=null ) {
	 global $link;

   $nombre	= escapeSQLFull($campos['nombre']);
   $tipo = escapeSQLFull($campos['tipo']);

   $q = "UPDATE productos_marca SET nombre='".$nombre."', tipo='".$tipo."'  WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	 return  $r;
 }
 
 function obtener_marcas($activo=null, $filtro_id_tipo=null) {
  global $link;
  	 
 $q = "SELECT m.* "
    . "FROM productos_marca AS m "
    . "WHERE 1 "
	  . ($activo ?  "AND m.activo='".$activo."' " : null)
	  . ($filtro_id_tipo ?  "AND m.tipo='".$filtro_id_tipo."' " : null)
	  . "".$order_by.""
	  . "";
	 // print $q;
	return @mysql_query($q, $link);  
 }

 function obtener_marca($id) {
	global $link;
	$q = "SELECT m.* "
     . "FROM productos_marca AS m "
     . "WHERE 1 "
     . "AND m.id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }
 
 function eliminar_marca($id) {
  global $link;
  $q = "DELETE FROM productos_marca WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

 function publicar_marca($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE productos_marca SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }


 //////////////////////////////////////////////////////////
 // COLORES
 //////////////////////////////////////////////////////////

 function grabar_color( $campos=null ) {
	 global $link;

   $nombre	= escapeSQLFull($campos['nombre']);
   $tipo = escapeSQLFull($campos['tipo']);

   $q = "INSERT INTO productos_color (nombre, tipo, activo) VALUES ('".$nombre."', '".$tipo."', '1')";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 return $last_id;
 }

 function editar_color( $id, $campos=null ) {
	 global $link;

   $nombre	= escapeSQLFull($campos['nombre']);
   $tipo = escapeSQLFull($campos['tipo']);

   $q = "UPDATE productos_color SET nombre='".$nombre."', tipo='".$tipo."'  WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	 return  $r;
 }
 
 function obtener_colores($activo=null, $filtro_id_tipo=null) {
  global $link;
  	 
 $q = "SELECT c.* "
    . "FROM productos_color AS c "
    . "WHERE 1 "
	  . ($activo ?  "AND c.activo='".$activo."' " : null)
	  . ($filtro_id_tipo ?  "AND c.tipo='".$filtro_id_tipo."' " : null)
	  . "".$order_by.""
	  . "";
	 // print $q;
	return @mysql_query($q, $link);  
 }

 function obtener_color($id) {
	global $link;
	$q = "SELECT c.* "
     . "FROM productos_color AS c "
     . "WHERE 1 "
     . "AND c.id='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }
 
 function eliminar_color($id) {
  global $link;
  $q = "DELETE FROM productos_color WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

 function publicar_color($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE productos_color SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

//////////////////////////////////////////////////////////
// CAMPOS EXTRAS
//////////////////////////////////////////////////////////

 function obtener_pedido_asignado($id) {
	global $link;
	$q = "SELECT p.*"
     . "FROM pedidos AS p "
     . "WHERE 1 "
     . "AND p.id_producto='".$id."' "
	   . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);	
 }
 
 function liberar($id) {
	global $link;
 	$q = "UPDATE pedidos SET id_producto='0'  WHERE id_producto='".$id."'";
 	$r = @mysql_query($q,$link);
  
  // la log
  
  return $r; 	
 }
 
 /////////////////////////////////////////////////////////
 // ESPECIFICACIONES
 /////////////////////////////////////////////////////////
 
 function obtener_espeficaciones($order_by, $id) {
    global $link;
 		$q = "SELECT e.* "
  	   . "FROM productos_especificaciones AS e "
    	 . "WHERE 1 "
    	 . "AND e.id_producto='".$id."' "
	  	 . "".$order_by.""
	     . "";
	  // print $q;
	  return @mysql_query($q, $link); 
 }
 
 
 function obtener_caracteristicas($order_by, $id) {
    global $link;
 		$q = "SELECT e.* "
  	   . "FROM productos_caracteristicas AS e "
    	 . "WHERE 1 "
    	 . "AND e.id_producto='".$id."' "
	  	 . "".$order_by.""
	     . "";
	  // print $q;
	  return @mysql_query($q, $link); 
 
 }
} // end class
?>