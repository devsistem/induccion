<?php
class Pedido {

 function grabar_imagen($id_pedido, $imagen, $campos) {
  global $link;
  
  $arrPedido = $this->obtener($id_pedido);
  $cliente_dni = $arrPedido['cliente_dni'];
  
  $imagen_factura =  $campos['imagen_factura'];   
  $imagen_dni_frente =  $campos['imagen_dni_frente'];   
  $imagen_dni_posterior =  $campos['imagen_dni_posterior']; 
  
  $imagen_duenio_garante =  $campos['imagen_duenio_garante'];   
  $imagen_dni_duenio_frente =  $campos['imagen_dni_duenio_frente'];   
  $imagen_dni_duenio_posterior =  $campos['imagen_dni_duenio_posterior']; 

  // crea las copias de las imagenes
  $imagen_factura_g = $cliente_dni."_".$imagen_factura;
  $imagen_dni_frente_g = $cliente_dni."_".$imagen_dni_frente;
  $imagen_dni_posterior_g = $cliente_dni."_".$imagen_dni_posterior;

  // cambia el estado 
  if($imagen == 'imagen_factura') {

    #1 imagen_factura
    $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_factura;
    $imagen_nueva_con_path_factura = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_factura_g;
    $resultado_factura_g  = crearImagenResampleadaV2(1024, null, $imagen_factura, $imagen_original_con_path, $imagen_nueva_con_path_factura);
    
    // eliminar el fakepath
    $imagen_factura = str_replace("C:\fakepath\\","",$imagen_factura);
    $imagen_factura_g = str_replace("C:\fakepath\\","",$imagen_factura_g);

    $q = "UPDATE pedidos SET imagen_factura='".$imagen_factura."', imagen_factura_g='".$imagen_factura_g."', fecha_mod=NOW() WHERE id='".$id_pedido."' ";
    $r = @mysql_query($q,$link);  
  } 

  if($imagen == 'imagen_dni_frente') {

    #2 imagen_dni_frente
    $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_frente;
    $imagen_nueva_con_path_dni_frente = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_frente_g;
    $resultado_imagen_dni_frente  = crearImagenResampleadaV2(1024, null, $imagen_dni_frente, $imagen_original_con_path, $imagen_nueva_con_path_dni_frente);

    $imagen_dni_frente = str_replace("C:\fakepath\\","",$imagen_dni_frente);
    $imagen_dni_frente_g = str_replace("C:\fakepath\\","",$imagen_dni_frente_g);

    $q = "UPDATE pedidos SET imagen_dni_frente='".$imagen_dni_frente."',  imagen_dni_frente_g='".$imagen_dni_frente_g."', fecha_mod=NOW() WHERE id='".$id_pedido."' ";
    $r = @mysql_query($q,$link);  
  } 

  if($imagen == 'imagen_dni_posterior') {

    #3 imagen_dni_posterior
    $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_posterior;
    $imagen_nueva_con_path_dni_posterior = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_posterior_g;
    $resultado_imagen_dni_posterior  = crearImagenResampleadaV2(1024, null, $imagen_dni_posterior, $imagen_original_con_path, $imagen_nueva_con_path_dni_posterior);

    $imagen_dni_posterior = str_replace("C:\fakepath\\","",$imagen_dni_posterior);
    $imagen_dni_posterior_g = str_replace("C:\fakepath\\","",$imagen_dni_posterior_g);

    $q = "UPDATE pedidos SET imagen_dni_posterior='".$imagen_dni_posterior."', imagen_dni_posterior_g='".$imagen_dni_posterior_g."', fecha_mod=NOW() WHERE id='".$id_pedido."' ";
    $r = @mysql_query($q,$link);  
  } 
  
 }
 
 function obtener_all($porPagina=null, $paginacion=null, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_tipo=null, $filtro_id_categoria=null, $id=null, $id_vendedor=null, $idx=null, $id_asistente=null, $fecha_desde=null, $fecha_hasta=null, $sin_comencionar=null, $filtro_cedula=null, $filtro_id_provincia=null, $filtro_nombre=null, $filtro_apellido=null, $filtro_id_vendedor=null, $con_incidencias_graves=null, $mes=null, $anio=null, $filtro_plataforma=null) {
  global $link;
  
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
      $order_by = " ORDER BY pe.$filtro DESC ";
   elseif($filtro && $order=='a')
      $order_by = " ORDER BY pe.$filtro ASC ";
   else
      $order_by = " ORDER BY pe.id_asistente ASC, pe.id DESC ";
  }  

  // pasar fecha a formato compatible con mysql
  if(strlen($fecha_desde) > 0 && strlen($fecha_hasta) > 0) {
   $fecha_desde = to_mysql($fecha_desde); 
   $fecha_hasta = to_mysql($fecha_hasta); 
  
   $fecha_desde = $fecha_desde . " " . "00:00:00";
   $fecha_hasta = $fecha_hasta . " " . "23:59:59";
  }

  if(strlen($anio) > 0 && strlen($mes) > 0) {
   $fecha_desde = $anio."-".$mes."-01" . " " . "00:00:00";
   $fecha_hasta = $anio."-".$mes."-31" . " " . "23:59:59";
  }
    
  $q = "SELECT DISTINCT pe.*, prov.nombre as cliente_provincia, ca.nombre as cliente_canton "
    . "FROM pedidos AS pe "
    . "LEFT JOIN productos pro ON pro.id=pe.id_producto "
    . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
    . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
    . "LEFT JOIN pedidos_incidencias pi ON pi.id_pedido=pe.id "
    . "WHERE 1 "
    . ($id_vendedor && strlen($filtro_id_vendedor == 0) ?  "AND ( pe.id_vendedor='".$id_vendedor."' OR pe.id_asistente='".$id_vendedor."' ) " : null)
    . ($activo ?  "AND pe.activo='".$activo."' " : null)
    . ($estado ?  "AND pe.estado='".$estado."' " : null)
    . ($id     ?  "AND pe.id='".$id."' " : null)
    . ($filtro_cedula ?  "AND pe.cliente_dni='".$filtro_cedula."' " : null)
    . ($filtro_id_provincia ?  "AND pe.id_provincia='".$filtro_id_provincia."' " : null)
    . ($filtro_id_vendedor ?  "AND ( pe.id_vendedor='".$filtro_id_vendedor."' OR pe.id_asistente='".$filtro_id_vendedor."' ) " : null)
    . ($filtro_nombre ?  "AND pe.cliente_nombre='".$filtro_nombre."' " : null)
    . ($filtro_apellido ?  "AND pe.cliente_apellido='".$filtro_apellido."' " : null)
    . ($filtro_id_tipo ?  "AND pe.tipo_producto='".$filtro_id_tipo."' " : null)
    . ($filtro_plataforma ?  "AND pe.tipo='".$filtro_plataforma."' " : null)
    . ($id_asistente ?  "AND pe.id_asistente='".$id_asistente."' " : null)
    . ($idx      ?  "AND pe.id IN (".$idx.") " : null)
    . (($fecha_desde && $fecha_hasta) ?  "AND ( pe.fecha_alta >= '".$fecha_desde."' AND pe.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . ($sin_comencionar == "sin"   ?  "AND pe.comicionar='0' " : null)
    . ($sin_comencionar == "con"   ?  "AND pe.comicionar='1' " : null)
    . ($con_incidencias_graves   ?  " AND pi.id_incidencia IN ('".$con_incidencias_graves."') " : null)
    . "".$order_by.""
    . ($limite  ? " limit ".$limite." " :null)    
    . "";
  //print $q;
  return @mysql_query($q, $link);  
 }

 function obtener_all_cuen($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_tipo=null, $filtro_id_categoria=null, $id=null, $id_vendedor=null, $filtro_cuen=null) {
  global $link;
  
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
      $order_by = " ORDER BY pe.$filtro DESC ";
   elseif($filtro && $order=='a')
      $order_by = " ORDER BY pe.$filtro ASC ";
   else
      $order_by = " ORDER BY pe.id_asistente ASC, pe.id DESC ";
  }  

  // pasar fecha a formato compatible con mysql
  if(strlen($fecha_desde) > 0 && strlen($fecha_hasta) > 0) {
   $fecha_desde = to_mysql($fecha_desde); 
   $fecha_hasta = to_mysql($fecha_hasta); 
  
   $fecha_desde = $fecha_desde . " " . "00:00:00";
   $fecha_hasta = $fecha_hasta . " " . "23:59:59";
  }
 
  $q = "SELECT pe.*, prov.nombre as cliente_provincia, ca.nombre as cliente_canton "
    . "FROM pedidos AS pe "
    . "LEFT JOIN productos pro ON pro.id=pe.id_producto "
    . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
    . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
    . "WHERE 1 "
    . ($id_vendedor && strlen($filtro_id_vendedor == 0) ?  "AND ( pe.id_vendedor='".$id_vendedor."' OR pe.id_asistente='".$id_vendedor."' ) " : null)
    . ($activo ?  "AND pe.activo='".$activo."' " : null)
    . ($estado ?  "AND pe.estado='".$estado."' " : null)
    . ($id     ?  "AND pe.id='".$id."' " : null)
    . ($filtro_cedula ?  "AND pe.cliente_dni='".$filtro_cedula."' " : null)
    . ($filtro_cuen ?  "AND pe.cliente_cuen='".$filtro_cuen."' " : null)
    . ($filtro_id_provincia ?  "AND pe.id_provincia='".$filtro_id_provincia."' " : null)
    . ($filtro_id_vendedor ?  "AND ( pe.id_vendedor='".$filtro_id_vendedor."' OR pe.id_asistente='".$filtro_id_vendedor."' ) " : null)
    . ($filtro_nombre ?  "AND pe.cliente_nombre='".$filtro_nombre."' " : null)
    . ($filtro_apellido ?  "AND pe.cliente_apellido='".$filtro_apellido."' " : null)
    . ($id_asistente ?  "AND pe.id_asistente='".$id_asistente."' " : null)
    . ($idx      ?  "AND pe.id IN (".$idx.") " : null)
    . (($fecha_desde && $fecha_hasta) ?  "AND ( pe.fecha_alta >= '".$fecha_desde."' AND pe.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . ($sin_comencionar == "sin"   ?  "AND pe.comicionar='0' " : null)
    . ($sin_comencionar == "con"   ?  "AND pe.comicionar='1' " : null)
    . "".$order_by.""
    . ($limite  ? " limit ".$limite." " :null)    
    . "";
  //print $q;
  return @mysql_query($q, $link);  
 }

 function obtener_all_baja($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_tipo=null, $filtro_id_categoria=null, $id=null, $id_vendedor=null, $idx=null, $id_asistente=null, $fecha_desde=null, $fecha_hasta=null, $sin_comencionar=null, $filtro_cedula=null, $filtro_id_provincia=null, $filtro_nombre=null, $filtro_apellido=null, $filtro_id_vendedor=null, $mes=null, $anio=null) {
  global $link;
  
  if(strlen($anio) == 4 && strlen($mes) > 0) {
    $fecha_desde = $anio."-".$mes."-01" . " " . "00:00:00";
    $fecha_hasta = $anio."-".$mes."-31" . " " . "23:59:59";
  }
   
  $q = "SELECT distinct pe.*, prov.nombre as cliente_provincia, ca.nombre as cliente_canton "
    . "FROM pedidos AS pe "
    . "LEFT JOIN productos pro ON pro.id=pe.id_producto "
    . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
    . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
    . "WHERE 1 "
    . " AND pe.activo='0' "
    . ($id_vendedor ?  "AND ( pe.id_vendedor='".$id_vendedor."' OR pe.id_asistente='".$id_vendedor."' ) " : null)
    . (($fecha_desde && $fecha_hasta) ?  "AND ( pe.fecha_mod >= '".$fecha_desde."' AND pe.fecha_mod <= '".$fecha_hasta."' )  " : null)
    . "".$order_by.""
    . ($limite  ? " limit ".$limite." " :null)    
    . "";
  //print $q;
  return @mysql_query($q, $link);  
 }
 
 function obtener_all_filtros($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_tipo=null, $filtro_id_categoria=null, $id=null, $id_vendedor=null, $idx=null, $id_asistente=null, $fecha_desde=null, $fecha_hasta=null, $sin_comencionar=null, $mes=null, $anio=null) {
  global $link;
  
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
      $order_by = " ORDER BY pe.$filtro DESC ";
   elseif($filtro && $order=='a')
      $order_by = " ORDER BY pe.$filtro ASC ";
   else
      $order_by = " ORDER BY pe.id_asistente ASC, pe.id DESC ";
  }  

  // pasar fecha a formato compatible con mysql
  if(strlen($fecha_desde) > 0 && strlen($fecha_hasta) > 0) {
   $fecha_desde = to_mysql_reporte($fecha_desde); 
   $fecha_hasta = to_mysql_reporte($fecha_hasta); 
  
   $fecha_desde = $fecha_desde . " " . "00:00:00";
   $fecha_hasta = $fecha_hasta . " " . "23:59:59";
  }
  
  //2015-10-07 09:48:30
  if(strlen($mes) == 2 && strlen($anio) == 4) {
     $fecha_mes_desde = $anio."-".$mes."-"."01" . " " . "00:00:00";
     $fecha_mes_hasta = $anio."-".$mes."-"."31" . " " . "23:59:59";

  }

  $q = "SELECT pe.*, prov.nombre as cliente_provincia, ca.nombre as cliente_canton, ba.nombre as backend_nombre, ba.apellido as backend_apellido "
    . "FROM pedidos AS pe "
    . "LEFT JOIN productos pro ON pro.id=pe.id_producto "
    . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
    . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
    . "LEFT JOIN sys_backendusuario ba ON ba.id=pe.id_vendedor "
    . "WHERE 1 "
    . ($activo ?  "AND pe.activo='".$activo."' " : null)
    . ($estado ?  "AND pe.estado='".$estado."' " : null)
    . ($id     ?  "AND pe.id='".$id."' " : null)
    . ($id_vendedor ?  "AND ( pe.id_vendedor='".$id_vendedor."' OR pe.id_asistente='".$id_vendedor."' ) " : null)
    . ($id_asistente ?  "AND pe.id_asistente='".$id_asistente."' " : null)
    . ($idx      ?  "AND pe.id IN (".$idx.") " : null)
    . (($fecha_desde && $fecha_hasta) ?  "AND ( pe.fecha_alta >= '".$fecha_desde."' AND pe.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . ($sin_comencionar == "sin"   ?  "AND pe.comicionar='0' " : null)
    . ($sin_comencionar == "con"   ?  "AND pe.comicionar='1' " : null)
    . (($fecha_mes_desde && $fecha_mes_hasta) ?  "AND ( pe.fecha_alta >= '".$fecha_mes_desde."' AND pe.fecha_alta <= '".$fecha_mes_hasta."' )  " : null)
    . "".$order_by.""
    . ($limite  ? " limit ".$limite." " :null)    
    . "";
  //print $q; 
  return @mysql_query($q, $link);  
 }
 
  function obtener_ollas_all($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_tipo=null, $filtro_id_categoria=null, $id=null, $id_vendedor=null, $idx=null, $id_asistente=null, $fecha_desde=null, $fecha_hasta=null, $sin_comencionar=null) {
  global $link;
  
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
      $order_by = " ORDER BY pe.$filtro DESC ";
   elseif($filtro && $order=='a')
      $order_by = " ORDER BY pe.$filtro ASC ";
   else
      $order_by = " ORDER BY  pe.id DESC ";
  }  

  // pasar fecha a formato compatible con mysql
  if(strlen($fecha_desde) > 0 && strlen($fecha_hasta) > 0) {
   $fecha_desde = to_mysql($fecha_desde); 
   $fecha_hasta = to_mysql($fecha_hasta); 
  
   $fecha_desde = $fecha_desde . " " . "00:00:00";
   $fecha_hasta = $fecha_hasta . " " . "23:59:59";
  }
 
  $q = "SELECT pe.*, prov.nombre as cliente_provincia "
    . "FROM pedido_ollas AS pe "
    . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
    . "WHERE 1 "
    . ($id     ?  "AND pe.id='".$id."' " : null)
    . ($id_vendedor ?  "AND ( pe.id_vendedor='".$id_vendedor."' OR pe.id_asistente='".$id_vendedor."' ) " : null)
    . ($idx      ?  "AND pe.id IN (".$idx.") " : null)
    . (($fecha_desde && $fecha_hasta) ?  "AND ( pe.fecha_alta >= '".$fecha_desde."' AND pe.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . "".$order_by.""
    . ($limite  ? " limit ".$limite." " :null)    
    . "";
  return @mysql_query($q, $link);  
 }
 
 function obtener_all_asignados($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_tipo=null, $filtro_id_categoria=null, $id=null, $id_vendedor=null, $idx=null, $id_asistente=null) {
  global $link;
  if(strlen($order_by) == 0) {
   if($filtro && $order=='d')
      $order_by = " ORDER BY pe.$filtro DESC ";
   elseif($filtro && $order=='a')
      $order_by = " ORDER BY pe.$filtro ASC ";
   else
      $order_by = " ORDER BY pe.id_asistente ASC, pe.id DESC ";
  }  
     
 $q = "SELECT pe.*, prov.nombre as cliente_provincia, ca.nombre as cliente_canton "
    . "FROM pedidos AS pe "
    . "LEFT JOIN productos pro ON pro.id=pe.id_producto "
    . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
    . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
    . "WHERE 1 "
    . "AND pe.id_asistente >  0 "
    . ($activo ?  "AND pe.activo='".$activo."' " : null)
    . ($estado ?  "AND pe.estado='".$estado."' " : null)
    . ($id     ?  "AND pe.id='".$id."' " : null)
    . ($id_vendedor ?  "AND ( pe.id_vendedor='".$id_vendedor."' OR pe.id_asistente='".$id_vendedor."' ) " : null)
    . ($idx      ?  "AND pe.id IN (".$idx.") " : null)
    . "".$order_by.""
    . ($limite  ? " limit ".$limite." " :null)    
    . "";
    //print $q;
  return @mysql_query($q, $link);  
 }
 
 function obtener($id) {
  global $link;
  $q = "SELECT pe.*, prov.nombre as cliente_provincia, ca.nombre as cliente_canton "
     . "FROM pedidos AS pe "
     . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
     . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
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
   loadClasses('BackendUsuario');
   loadClasses('Producto');
   global $BackendUsuario;
   global $Producto;
   global $link;

   //datos basicos  
   $duenio = escapeSQLFull($campos['duenio']);
   $cliente_cuen = escapeSQLFull($campos['registro']['cuen']);
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
   $marca = escapeSQLFull($campos['registro']['marca']);
   $modelo = escapeSQLFull($campos['registro']['modelo']);
   $color = escapeSQLFull($campos['registro']['color']);
   
   // obtiene el modelo con el id
   $arrModelo = $Producto->obtener_modelo($modelo);
   $arrMarca = $Producto->obtener_marca($marca);
   
   // PAGOS   
   $cuotas = escapeSQLFull($campos['registro']['cuotas']);
   $forma_pago = escapeSQLFull($campos['registro']['forma_pago']);
   $promocion = escapeSQLFull($campos['registro']['promocion']);
   
   // horario 
   $horario_recepcion = escapeSQLFull($campos['registro']['horario_recepcion']);

   // estado
   $activo  = escapeSQLFull($campos['activo']);  
   $estado  = 1;   

   // vendedor
   /*
   $id_vendedor     = $Vendedor->obtener_vendedor_id();
   $vendedor_nombre = $Vendedor->obtener_nombre();
   */

   $id_vendedor     = $BackendUsuario->getUsuarioId();
   $vendedor_nombre = $BackendUsuario->obtenerNombreCompleto();
   
   // checks
   $medidor220 = escapeSQLFull($campos['medidor220']);
   $circuito_interno = escapeSQLFull($campos['circuito_interno']);
   $ducha_electrica = escapeSQLFull($campos['ducha_electrica']);
   
   // imagenes
   $imagen_factura = escapeSQLFull($campos['imagen_factura']);
   $imagen_dni_frente = escapeSQLFull($campos['imagen_dni_frente']);
   $imagen_dni_posterior = escapeSQLFull($campos['imagen_dni_posterior']);   

   $imagen_dni_duenio_frente = escapeSQLFull($campos['imagen_dni_duenio_frente']);
   $imagen_dni_duenio_posterior = escapeSQLFull($campos['imagen_dni_duenio_posterior']);   
   $imagen_duenio_garante = escapeSQLFull($campos['imagen_duenio_garante']);
   
   // crea las copias de las imagenes
   $imagen_factura_g = $cliente_dni."_".$imagen_factura;
   $imagen_dni_frente_g = $cliente_dni."_".$imagen_dni_frente;
   $imagen_dni_posterior_g = $cliente_dni."_".$imagen_dni_posterior;

   $imagen_duenio_garante_g = $cliente_dni."_".$imagen_duenio_garante;
   $imagen_dni_duenio_frente_g = $cliente_dni."_".$imagen_dni_duenio_frente;
   $imagen_dni_duenio_posterior_g = $cliente_dni."_".$imagen_dni_duenio_posterior;
   
   #1 imagen_factura
   $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_factura;
   $imagen_nueva_con_path_factura = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_factura_g;
   $resultado_factura_g  = crearImagenResampleadaV2(1024, null, $imagen_factura, $imagen_original_con_path, $imagen_nueva_con_path_factura);
   
   #2 imagen_dni_frente
   $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_frente;
   $imagen_nueva_con_path_dni_frente = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_frente_g;
   $resultado_imagen_dni_frente  = crearImagenResampleadaV2(1024, null, $imagen_dni_frente, $imagen_original_con_path, $imagen_nueva_con_path_dni_frente);

   #3 imagen_dni_posterior
   $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_posterior;
   $imagen_nueva_con_path_dni_posterior = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_posterior_g;
   $resultado_imagen_dni_posterior  = crearImagenResampleadaV2(1024, null, $imagen_dni_posterior, $imagen_original_con_path, $imagen_nueva_con_path_dni_posterior);

   // Arrendadores
   #4 imagen_duenio_garante
   $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_duenio_garante;
   $imagen_nueva_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_duenio_garante_g;
   crearImagenResampleadaV2(1024, null, $imagen_duenio_garante, $imagen_original_con_path, $imagen_nueva_con_path);

   #5 imagen_dni_duenio_frente
   $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_duenio_frente;
   $imagen_nueva_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_duenio_frente_g;
   crearImagenResampleadaV2(1024, null, $imagen_dni_duenio_frente, $imagen_original_con_path, $imagen_nueva_con_path);

   #6 imagen_dni_duenio_posterior
   $imagen_original_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_duenio_posterior;
   $imagen_nueva_con_path = FILE_PATH_FRONT_ADJ."/pedidos/".$imagen_dni_duenio_posterior_g;
   crearImagenResampleadaV2(1024, null, $imagen_dni_duenio_posterior, $imagen_original_con_path, $imagen_nueva_con_path);

   # zoom
   $zoom = escapeSQLFull($campos['zoom']);
   $cuota_entrada = escapeSQLFull($campos['registro']['cuota_entrada']);
  
   // sin fecha
   $fecha_venta  = $campos['fecha_venta'];
   
   // parsear
   $imagen_duenio_garante_g = str_replace("C:\fakepath\\","",$imagen_duenio_garante_g);
   $imagen_dni_duenio_frente_g = str_replace("C:\fakepath\\","",$imagen_dni_duenio_frente_g);
   $imagen_dni_duenio_posterior_g = str_replace("C:\fakepath\\","",$imagen_dni_duenio_posterior_g);
   $imagen_factura_g = str_replace("C:\fakepath\\","",$imagen_factura_g);
   $imagen_dni_frente_g = str_replace("C:\fakepath\\","",$imagen_dni_frente_g);
   $imagen_dni_posterior_g = str_replace("C:\fakepath\\","",$imagen_dni_posterior_g);

   $q = "INSERT INTO pedidos (id_marca, id_modelo, cuota_entrada, zoom, promocion, imagen_duenio_garante_g, imagen_dni_duenio_frente_g, imagen_dni_duenio_posterior_g, imagen_duenio_garante, imagen_dni_duenio_frente, imagen_dni_duenio_posterior,imagen_factura_g, imagen_dni_frente_g, imagen_dni_posterior_g, imagen_factura, imagen_dni_frente, imagen_dni_posterior, cliente_cuen, duenio, id_vendedor, vendedor_nombre, cliente_dni, cliente_nombre, cliente_apellido, cliente_telefono, cliente_celular, cliente_email, id_provincia, id_canton, parroquia, barrio, cliente_calle,cliente_calle_numero,cliente_calle_secundaria,cliente_referencia, latitude, longitude, id_producto, caracteristicas, modelo, marca, color, cuotas, forma_pago, horario_recepcion, activo, estado, fecha_alta, fecha_venta, ip, medidor220,circuito_interno,ducha_electrica
      ) VALUES ('".$marca."', '".$modelo."', '".$cuota_entrada."', '".$zoom."', '".$promocion."', '".$imagen_duenio_garante_g."', '".$imagen_dni_duenio_frente_g."', '".$imagen_dni_duenio_posterior_g."', '".$imagen_duenio_garante."', '".$imagen_dni_duenio_frente."', '".$imagen_dni_duenio_posterior."', '".$imagen_factura_g."', '".$imagen_dni_frente_g."', '".$imagen_dni_posterior_g."', '".$imagen_factura."', '".$imagen_dni_frente."', '".$imagen_dni_posterior."', '".$cliente_cuen."', '".$duenio."', '".$id_vendedor."', '".$vendedor_nombre."', '".$cliente_dni."', '".$cliente_nombre."', '".$cliente_apellido."', '".$cliente_telefono."', '".$cliente_celular."', '".$cliente_email."', '".$id_provincia."', '".$id_canton."', '".$parroquia."', '".$barrio."', '".$cliente_calle."', '".$cliente_calle_numero."', '".$cliente_calle_secundaria."', '".$cliente_referencia."', '".$latitude."', '".$longitude."', '".$id_producto."', '".$caracteristicas."', '".$arrModelo['nombre']."', '".$arrMarca['nombre']."', '".$color."', '".$cuotas."', '".$forma_pago."', '".$horario_recepcion."', 1, '".$estado."', NOW(), '".$fecha_venta."', '".$_SERVER['REMOTE_ADDR']."',  '".$medidor220."', '".$circuito_interno."', '".$ducha_electrica."') ";
   
   $r = @mysql_query($q,$link);  
   $last_id = @mysql_insert_id($link);

   return $last_id;
 }


 // edita el pedido en el mismo form q el de registro
 function editar( $id, $campos=null ) {
   global $link;
   loadClasses('Producto');
   global $Producto;
   
   // edicion de datos
   //datos basicos  
   $duenio = escapeSQLFull($campos['duenio']);
   $cliente_cuen = escapeSQLFull($campos['registro']['cuen']);
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
   $marca = escapeSQLFull($campos['registro']['marca']);
   $modelo = escapeSQLFull($campos['registro']['modelo']);
   $color = escapeSQLFull($campos['registro']['color']);
   
   // obtiene el modelo con el id
   $arrModelo = $Producto->obtener_modelo($modelo);
   $arrMarca = $Producto->obtener_marca($marca);
   
   // PAGOS   
   $cuotas = escapeSQLFull($campos['registro']['cuotas']);
   $forma_pago = escapeSQLFull($campos['registro']['forma_pago']);
   $promocion = escapeSQLFull($campos['registro']['promocion']);
   
   // horario 
   $horario_recepcion = escapeSQLFull($campos['registro']['horario_recepcion']);

   // actualiza los datos
   $q = "UPDATE pedidos SET  id_modelo='".$arrModelo['id']."', id_marca='".$arrMarca['id']."', modelo='".$modelo."', marca='".$marca."', color='".$color."', cliente_email='".$cliente_email."', cuota_entrada='".$cuota_entrada."', cliente_cuen='".$cliente_cuen."', duenio='".$duenio."', cliente_dni='".$cliente_dni."', cliente_nombre='".$cliente_nombre."', cliente_apellido='".$cliente_apellido."', cliente_telefono='".$cliente_telefono."', cliente_celular='".$cliente_celular."', id_provincia='".$id_provincia."', id_canton='".$id_canton."', parroquia='".$parroquia."', barrio='".$barrio."', cliente_calle='".$cliente_calle."', cliente_calle_numero='".$cliente_calle_numero."', cliente_calle_secundaria='".$cliente_calle_secundaria."', cliente_referencia='".$cliente_referencia."', latitude='".$latitude."', longitude='".$longitude."', fecha_mod=NOW()  WHERE id='".$id."'";
   $r = @mysql_query($q,$link);
 }

 function grabar_olla( $campos=null ) {
  
   loadClasses('Vendedor');
   loadClasses('BackendUsuario');
   loadClasses('Producto');
   global $BackendUsuario;
   global $Producto;
   global $link;

   //datos basicos  
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
   
   // OLLAS
   $marca_olla = $campos['registro_marca'];
   $modelo_olla = $campos['registro_modelo'];
   $cantidad_olla = $campos['registro_cantidad'];
   
   // no se usa
   $caracteristicas_olla = $campos['caracteristicas_olla'];

   // estado
   $activo  = escapeSQLFull($campos['activo']);  
   $estado  = 1;   

   $id_vendedor     = $BackendUsuario->getUsuarioId();
   $vendedor_nombre = $BackendUsuario->obtenerNombreCompleto();

   $forma_pago = escapeSQLFull($campos['registro']['forma_pago']);


   $q = "INSERT INTO pedido_ollas (forma_pago, id_vendedor, dni, nombre, apellido, telefono, celular, email, id_provincia,  cliente_calle,cliente_calle_numero,cliente_calle_secundaria,cliente_referencia,fecha_alta, ip) VALUES ('".$forma_pago."', '".$id_vendedor."', '".$cliente_dni."', '".$cliente_nombre."', '".$cliente_apellido."', '".$cliente_telefono."', '".$cliente_celular."', '".$cliente_email."', '".$id_provincia."',  '".$cliente_calle."', '".$cliente_calle_numero."', '".$cliente_calle_secundaria."', '".$cliente_referencia."', NOW(), '".$_SERVER['REMOTE_ADDR']."') ";
   $r = @mysql_query($q,$link);  
   $last_id = @mysql_insert_id($link);
   
   if($last_id > 0) {
     for($i=0; $i < count($marca_olla); $i++) {
      if(strlen($marca_olla[$i]) > 0) {
        $q = "INSERT INTO pedidos_ollas (id_pedido, olla_caracteristicas,olla_color,olla_modelo,olla_marca, forma_pago, fecha_alta, cantidad) VALUES ('".$last_id."',  '".$caracteristicas_olla[$i]."', '".$color_olla[$i]."', '".$modelo_olla[$i]."', '".$marca_olla[$i]."', '".$forma_pago_ollas[$i]."',  NOW(), '".$cantidad_olla[$i]."' ) ";  
          @mysql_query($q,$link); 
      }
     }
   }
  
   return $last_id;
 } 
 // PASO 2
 function editar_pedido( $id, $campos=null, $contenido ) {
   global $link;
   loadClasses('Producto');
   global $Producto;
   
   // confirmacion de horario
   $recepcion_confirmada_dia   = escapeSQLFull($campos['registro']['recepcion_confirmada_dia']);
   $recepcion_confirmada_mes   = escapeSQLFull($campos['registro']['recepcion_confirmada_mes']);
   $recepcion_confirmada_desde   = escapeSQLFull($campos['registro']['recepcion_confirmada_desde']);
   $recepcion_confirmada_hasta   = escapeSQLFull($campos['registro']['recepcion_confirmada_hasta']);

   // contacto con
   $contacto_nombre    = escapeSQLFull($campos['registro']['contacto_nombre']);
   $contacto_parentesco    = escapeSQLFull($campos['registro']['contacto_parentesco']);
   $contacto_fecha   = escapeSQLFull($campos['registro']['contacto_fecha']);
  
   // cambia el estado  
   $q = "UPDATE pedidos SET contacto_nombre='".$contacto_nombre."', contacto_parentesco='".$contacto_parentesco."', contacto_fecha='".$contacto_fecha."', recepcion_confirmada_dia='".$recepcion_confirmada_dia."', recepcion_confirmada_mes='".$recepcion_confirmada_mes."', recepcion_confirmada_desde='".$recepcion_confirmada_desde."', recepcion_confirmada_hasta='".$recepcion_confirmada_hasta."', estado='".$contenido."', fecha_mod=NOW() WHERE id='".$id."'";
   $r = @mysql_query($q,$link);

   // edicion de datos
   $duenio = escapeSQLFull($campos['duenio']);
   $cliente_cuen = escapeSQLFull($campos['registro']['cuen']);
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
   // DATOS DE FACTURA 1722295126
   $estado_sipec=escapeSQLFull($campos['registro']['estado_sipec']);
   
   //$fecha_factura= date('d/m/y', strtotime(escapeSQLFull($campos['registro']['fecha_factura'])));
   $fecha_induccion = escapeSQLFull($campos['registro']['fecha_induccion']);
   $fecha_sipec= escapeSQLFull($campos['registro']['fecha_sipec']);
   if($fecha_induccion==null||$contenido<7||$fecha_induccion=='0000-00-00 00:00:00')
    $fecha_induccion=$fecha_sipec;
   // COCINA 
   $modelo = escapeSQLFull($campos['registro']['modelo']);
   $marca = escapeSQLFull($campos['registro']['marca']);
   $color = escapeSQLFull($campos['registro']['color']);
  
   $cuotas = escapeSQLFull($campos['registro']['cuotas']);
   $forma_pago = escapeSQLFull($campos['registro']['forma_pago']);
   $cuota_entrada = escapeSQLFull($campos['registro']['cuota_entrada']);
     
   // OLLAS
   $caracteristicas_olla = $campos['caracteristicas_olla'];
   $color_olla = $campos['color_olla'];
   $forma_pago_ollas = escapeSQLFull($campos['registro']['forma_pago_ollas']);
   $horario_recepcion = escapeSQLFull($campos['registro']['horario_recepcion']);
   
   // checks
   $promocion = escapeSQLFull($campos['promocion']);
   $medidor220 = escapeSQLFull($campos['medidor220']);
   $circuito_interno = escapeSQLFull($campos['circuito_interno']);
   $ducha_electrica = escapeSQLFull($campos['ducha_electrica']);

   // actualiza los datos
   $q = "UPDATE pedidos SET  modelo='".$modelo."', marca='".$marca."' , estado_sipec='".$estado_sipec."', fecha_sipec='".$fecha_sipec."', fecha_induccion='".$fecha_induccion."', color='".$color."', cliente_email='".$cliente_email."', cuota_entrada='".$cuota_entrada."', cliente_cuen='".$cliente_cuen."', duenio='".$duenio."', cliente_dni='".$cliente_dni."', cliente_nombre='".$cliente_nombre."', cliente_apellido='".$cliente_apellido."', cliente_telefono='".$cliente_telefono."', cliente_celular='".$cliente_celular."', id_provincia='".$id_provincia."', id_canton='".$id_canton."', parroquia='".$parroquia."', barrio='".$barrio."', cliente_calle='".$cliente_calle."', cliente_calle_numero='".$cliente_calle_numero."', cliente_calle_secundaria='".$cliente_calle_secundaria."', cliente_referencia='".$cliente_referencia."', latitude='".$latitude."', longitude='".$longitude."'  WHERE id='".$id."'";
   
   $r = @mysql_query($q,$link);
   
   // ESTO PASA AL PASO DE FACTURA
   // busca una cocina con las caracteristicas solicitadas
   $arrProductoPedido = $Producto->pedir_producto_by_modelo($modelo,$marca,$color,1);
    
   // si encuentra una cocina con esas carac y q esta en estado (estado=1), la reserva
   if($arrProductoPedido['id'] > 0) {
    
    // pasa a documentacion
    //$q = "UPDATE productos SET estado='2' WHERE id='".$arrProductoPedido['id']."'";
    //$r = @mysql_query($q,$link);
    
    // agrega el producto en el pedido
    //$q = "UPDATE pedidos SET id_producto='".$arrProductoPedido['id']."' WHERE id='".$id."' ";
    //$r = @mysql_query($q,$link);

    // saca la alerta
    $q = "UPDATE alertas SET estado='1' WHERE id_pedido='".$id."' ";
    $r = @mysql_query($q,$link);
    
    return $arrProductoPedido['id'];
    
   } else {
    // no existe
    return 0;
   }
 }
 
 // PASO 4 
 function genera_documentacion($id,$campos) {
   loadClasses('Producto');
   global $link;
   global $Producto;
   
   $ck_pagare   = ($campos['ck_pagare']==1) ? '1' : '0' ; 
   $ck_peticion = ($campos['ck_peticion']==1) ? '1' : '0' ;  
   $ck_acta     = ($campos['ck_acta']==1) ? '1' : '0' ;  
   $factura = escapeSQLFull($campos['registro']['factura']); 
   $serial = escapeSQLFull($campos['registro']['serial']); 
   
   $q = "UPDATE pedidos SET factura='".$factura."',  serial='".$serial."',  pagare='".$ck_pagare."', peticion='".$ck_peticion."',acta='".$ck_acta."', factura='".$factura."', estado='5' WHERE id='".$id."' ";
   $r = @mysql_query($q,$link);
   
   if($r) {
    // con el serial, saco el producto que se esta reservando
    $arrProductoPedido = $Producto->pedir_producto_by_serial($serial, 1);

    $q = "UPDATE productos SET estado='2' WHERE id='".$arrProductoPedido['id']."' ";
    $r = @mysql_query($q,$link);
    
    // agrega el producto en el pedido
    $q = "UPDATE pedidos SET id_producto='".$arrProductoPedido['id']."' WHERE id='".$id."' ";
    $r = @mysql_query($q,$link);
   }
   
   return $r;
 }

 // PASO 6 - RECEPCION DOCUMENTACION 
 function recepcion_documentacion($id,$campos) {
   loadClasses('Producto');
   global $link;
   global $Producto;
   
   $ck_factura  = ($campos['ck_factura']==1) ? '1' : '0' ; 
   $pagare   = ($campos['ck_pagare']==1) ? '1' : '0' ; 
   $peticion = ($campos['ck_peticion']==1) ? '1' : '0' ;  
   $acta_de_entrega     = ($campos['ck_acta_de_entrega']==1) ? '1' : '0' ;  
   $incentivo = ($campos['ck_incentivo']==1) ? '1' : '0' ;  
   $cedula_dueno = ($campos['ck_cedula_dueno']==1) ? '1' : '0' ;  
   $cedula_arrendador = ($campos['ck_cedula_arrendador']==1) ? '1' : '0' ;  

   $q = "UPDATE pedidos SET cedula_arrendador='".$cedula_arrendador."', cedula_dueno='".$cedula_dueno."', incentivo='".$incentivo."', pagare='".$pagare."', peticion='".$peticion."',acta_de_entrega='".$acta_de_entrega."', ck_factura='".$ck_factura."', estado='7' WHERE id='".$id."' ";
   $r = @mysql_query($q,$link);
   
   return $r;
 }
 // 3719
 function papelera($id,$campo) {
  global $link;
  $campo = ($campo == 0) ? 1 : 0;
  $q = "UPDATE pedidos SET activo=0, fecha_mod=NOW() WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  
  // se avisa el vendedor que su pedido
  // se ha dado de baja
  $this->enviar_baja_vendedor($id);
  return $id;
 }
 
 function despapelera($id, $campo, $campos) {
  loadClasses('BackendUsuario');
  global $link, $BackendUsuario;
  
  $campo = ($campo == 0) ? 1 : 0;
  
  // asigna mismo o cambia vendedor
  $campo_vendedor = "id_vendedor_".$id;
  $id_vendedor = $campos[$campo_vendedor];
  
  // busca el vendedor
  $arrVendedor = $BackendUsuario->obtener($id_vendedor);

  $q = "UPDATE pedidos SET activo=1, estado=1, id_vendedor='".$id_vendedor."', vendedor_nombre='" . $arrVendedor['nombre'] . " " . $arrVendedor['apellido'] . "', fecha_mod=NOW(), fecha_alta=NOW() WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  
  $this->enviar_alta_vendedor($id);
  
  return $id;
 }
 
 function asignar($id_asistente, $id_pedido) {
  global $link;
  $campo = ($campo == 0) ? 1 : 0;
  $q = "UPDATE pedidos SET id_asistente=".$id_asistente." WHERE id='".$id_pedido."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function publicar($id,$campo) {
  global $link;
  $campo = ($campo == 0) ? 1 : 0;
  $q = "UPDATE pedidos SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function estado($id,$campo) {
  global $link;

  if($campo == 1) {

   $q = "UPDATE pedidos SET estado='1', fecha_mod=NOW() WHERE id='".$id."'";
   $r = @mysql_query($q,$link);
  
  } else if($campo == 2) {

   $q = "UPDATE pedidos SET estado='".$campo."', fecha_mod=NOW() WHERE id='".$id."'";
   $r = @mysql_query($q,$link);

  } else {

   $q = "UPDATE pedidos SET estado='".$campo."', fecha_mod=NOW() WHERE id='".$id."'";
   $r = @mysql_query($q,$link);

  }
  return $id;
 }
 
 function estado_pedido($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='1', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 }

 function estado_agendar($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='3', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 }

 function estado_predespacho($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='2', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 }

 function estado_recepcion($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='6', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 }

 function estado_generacion($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='4', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 }
 
 
 function estado_entrega($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='7', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 }

 function estado_despacho($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='5', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 }

 /*
 function estado_agendar($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='3', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 }
 */

 // PRODUCTOS
 // 13/05/2016 17:37:00
 function estado_pedido_agendar($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='3', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 } 
  
 function estado_agendar_documentacion($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='4', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 } 

 function estado_documentacion_agendar($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='3', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 } 
 
 function estado_documentacion_despacho($id,$campo) {
    global $link;
    $q = "UPDATE pedidos SET estado='5', fecha_mod=NOW() WHERE id='".$id."'";
    $r = @mysql_query($q,$link);
    return $id;
 } 
   
 function leido($id) {
   global $link;
   $q = "UPDATE pedidos SET leido='1', fecha_mod=NOW() WHERE id='".$id."'";
   $r = @mysql_query($q,$link);
 }
 
 function eliminar($id) {
  global $link;
  $q = "UPDATE pedidos SET estado='-1', activo='-1' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  
  //$q = "DELETE FROM pedidos WHERE id='".$id."'";
  //$r = @mysql_query($q,$link);
 }

 function dni_existe($dni) {
  global $link;
  $q  = "SELECT * FROM pedidos  WHERE cliente_dni='".$dni."' ";
  $r  = @mysql_query($q,$link);
  return @mysql_num_rows($r);
 } 


 function cuen_existe($cliente_cuen) {
  global $link;
  $q  = "SELECT * FROM pedidos  WHERE cliente_cuen='".$cliente_cuen."' ";
  $r  = @mysql_query($q,$link);
  return @mysql_num_rows($r);
 } 

 function disponible_serial($serial) {
  global $link;
  $q  = "SELECT COUNT(*) AS Cantidad  FROM productos  WHERE serie='".$serial."' AND estado=1 ";
  $r  = @mysql_query($q, $link);
  $a  = @mysql_fetch_array($r);
  return $a['Cantidad'];
 } 
 
/////////////////////////////////////////////////////////////////////
// ESTADISTICAS
/////////////////////////////////////////////////////////////////////

function cantidad_pedidos_by_vendedor($id_vendedor=null, $estado=null, $activo=null) {
 global $link;
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='1' " : null)
    . "";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

// 2015-10-07 09:48:30
function cantidad_pedidos_by_vendedor_mes($id_vendedor=null, $mes=null, $anio=null, $estado=null) {
 global $link;
 
 $fecha_desde = $anio."-".$mes."-01" . " " . "00:00:00";
 $fecha_hasta = $anio."-".$mes."-31" . " " . "23:59:59";
   
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_alta >= '".$fecha_desde."' AND p.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . "";
    //print $q;
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}



function cantidad_pedidos_by_mes($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null) {
 global $link;
 
 $fecha_desde = $fecha_desde . " " . "00:00:00";
 $fecha_hasta = $fecha_hasta . " " . "23:59:59";
   
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . "INNER JOIN sys_backendusuario u ON u.id=p.id_vendedor "
    . " WHERE 1 "
    . "AND u.activo='1' "
    . ($id_vendedor ?  "AND p.id_vendedor='".$id_vendedor."' " : null)
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_alta >= '".$fecha_desde."' AND p.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . "";
    //print $q;
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

function cantidad_pedidos_by_semana_encimera($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null) {
 global $link;
 
 $fecha_desde = $fecha_desde . " " . "00:00:00";
 $fecha_hasta = $fecha_hasta . " " . "23:59:59";
   
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . " AND (( p.id_marca = 1  AND p.id_modelo = 4 ) or ( p.id_marca = 2  AND p.id_modelo = 6 )) "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( cast(p.fecha_mod as date) between '".$fecha_desde."' AND '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . "";
   // print $q . "<br>";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}



function cantidad_pedidos_by_semana($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null) {
 global $link;
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( cast(p.fecha_alta as date) between '".$fecha_desde."' AND '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . "";
 //   print $q . "<br>";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}
/////////////////////////////
//funciones 1722295126
///////////////////////////
function pedidos_encimera_facturados_fechas($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null,$estado_sipec=null) {
 global $link;
 
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( cast(p.fecha_induccion as date) between '".$fecha_desde."' AND '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . ($estado_sipec ?  "AND p.estado_sipec='".$estado_sipec."' " : null)
    . "and p.modelo like '%ENCIMERA%'"
    . "";
   // print $q . "<br>";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

function pedidos_hornos_facturados_fechas($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null,$estado_sipec=null) {
 global $link;
    $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( cast(p.fecha_induccion as date) between '".$fecha_desde."' AND '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . ($estado_sipec ?  "AND p.estado_sipec='".$estado_sipec."' " : null)

    . "and p.modelo  not like '%ENCIMERA%' and p.tipo_producto like '%cocina%'"
    . "";
   // print $q . "<br>";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}
function pedidos_facturados_fechas($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null,$estado_sipec=null) {
 global $link;
    $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( cast(p.fecha_induccion as date) between '".$fecha_desde."' AND '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . ($estado_sipec ?  "AND p.estado_sipec='".$estado_sipec."' " : null)
    . "AND p.tipo_producto like '%cocina%'"
    . "";
   

   //print $q . "<br>";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

function pedidos_baja_fechas($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $estado=null) {
 global $link;

 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( cast(p.fecha_mod as date) between '".$fecha_desde."' AND '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . "AND p.activo='0'"
    . "";
 //   print $q . "<br>";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
 

}
function pedidos_facturados_fechas_no_estado_sipec($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null,$estado_sipec=null) {
    global $link;
    $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( cast(p.fecha_induccion as date) between '".$fecha_desde."' AND '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . ($estado_sipec ?  "AND p.estado_sipec<>'".$estado_sipec."' " : null)
    . "AND p.tipo_producto like '%cocina%'"
    . "";

 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}


 function obtener_by_fecha_induccion_vendedor($idVendedor=null, $fechaDesde=null, $fechaHasta=null,$activo=null,$tipo_cocina=null,$estadoSipec=null,$estado=null,$bandera_estado_sipec=null){
   global $link;
  
   $q = "SELECT DISTINCT pe.*, prov.nombre as cliente_provincia, ca.nombre as cliente_canton "
    . "FROM pedidos AS pe "
    . "LEFT JOIN productos pro ON pro.id=pe.id_producto "
    . "LEFT JOIN provincias prov ON prov.id=pe.id_provincia "
    . "LEFT JOIN cantones ca ON ca.id=pe.id_canton "
    . "LEFT JOIN pedidos_incidencias pi ON pi.id_pedido=pe.id "
    . "WHERE 1 "
    . ($idVendedor ?  "AND ( pe.id_vendedor='".$idVendedor."') " : null)
    . ($activo ?  "AND pe.activo='".$activo."' " : null)
    . ($estado ?  "AND pe.estado='".$estado."' " : null)
    . (($estadoSipec && $bandera_estado_sipec=='IGUAL')?  "AND pe.estado_sipec='".$estadoSipec."' " : null)
    . (($estadoSipec && $bandera_estado_sipec=='NO IGUAL')?  "AND pe.estado_sipec<>'".$estadoSipec."' " : null)
    . (($fechaDesde && $fechaHasta) ?  "AND ( cast(pe.fecha_induccion as date) between '".$fechaDesde."' AND '".$fechaHasta."' )  " : null)
    . ($tipo_cocina=='horno' ?  "and pe.modelo  not like '%ENCIMERA%' and pe.tipo_producto like '%cocina%' " : null)
    . ($tipo_cocina=='encimera' ?  "and pe.modelo like '%ENCIMERA%' " : null)
    . " order by fecha_induccion, pe.activo,pe.estado_sipec";
  //echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>.................jkjjhalfuhadsjkhfdlkjsghflhkjfdgslsjfkgl==>>>>>'.$q;
  return @mysql_query($q, $link);  
 }
 function obtenerFechasQuincena($date){
  $option_quincena=[];
  $dtz = new DateTimeZone("America/Bogota");
  $now = new DateTime();
  $now->setTimezone($dtz);
  $n=0;

   while($date<=$now)
   {
     
     $date->add(new DateInterval('P1D'));
     $option_quincena_temp=null;
     $option_quincena_temp["id"]=$n;
     $option_quincena_temp["fecha_desde_quincena"]=date_format($date, 'Y-m-d');
      $option_quincena_temp["intervalo_quincena"]=date_format($date, 'm-d');
     $option_quincena_temp["semana1"]=date_format($date, 'm-d');
     $option_quincena_temp["fecha_desde_semana1"]=date_format($date, 'Y-m-d');
     $date->add(new DateInterval('P6D'));
     $option_quincena_temp["semana1"]=$option_quincena_temp["semana1"]." / ".date_format($date, 'm-d');
     $option_quincena_temp["fecha_hasta_semana1"]=date_format($date, 'Y-m-d');
     $date->add(new DateInterval('P1D'));
      $option_quincena_temp["semana2"]=date_format($date, 'm-d');
     $option_quincena_temp["fecha_desde_semana2"]=date_format($date, 'Y-m-d');
     $date->add(new DateInterval('P6D'));
     $option_quincena_temp["semana2"]=$option_quincena_temp["semana2"]." / ".date_format($date, 'm-d');
     $option_quincena_temp["fecha_hasta_semana2"]=date_format($date, 'Y-m-d');
     $option_quincena_temp["fecha_hasta_quincena"]=date_format($date, 'Y-m-d');
      $option_quincena_temp["intervalo_quincena"]= $option_quincena_temp["intervalo_quincena"].' / '.date_format($date, 'm-d');

     
     array_push($option_quincena,$option_quincena_temp);
     $n++;
   }
   return $option_quincena;
 }

//funciones 1722295126




function cantidad_pedidos_by_semana_horno($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null) {
 global $link;
 
 $fecha_desde = $fecha_desde . " " . "00:00:00";
 $fecha_hasta = $fecha_hasta . " " . "23:59:59";
   
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . " AND ( p.id_modelo != 4 AND p.id_modelo != 6 ) "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_mod >= '".$fecha_desde."' AND p.fecha_mod <= '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . "";
  //  print $q . "<br>";

 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

function cantidad_pedidos_by_semana_baja($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null) {
 global $link;
 
 $fecha_desde = $fecha_desde . " " . "00:00:00";
 $fecha_hasta = $fecha_hasta . " " . "23:59:59";

   
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . "AND p.activo='0' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_mod >= '".$fecha_desde."' AND p.fecha_mod <= '".$fecha_hasta."' )  " : null)
    . "";
   // print $q;
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

function cantidad_pedidos_by_semana_fabrica($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null) {
 global $link;
 
 $fecha_desde = $fecha_desde . " " . "00:00:00";
 $fecha_hasta = $fecha_hasta . " " . "23:59:59";
   
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . "AND p.activo='1' "
    . "AND p.estado='7' "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_mod >= '".$fecha_desde."' AND p.fecha_mod <= '".$fecha_hasta."' )  " : null)
    . "";
   // print $q;
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}


// ESTA MAL LA SQL
function cantidad_pedidos_by_semana_noencimera($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null) {
 global $link;
 
 $fecha_desde = $fecha_desde . " " . "00:00:00";
 $fecha_hasta = $fecha_hasta . " " . "23:59:59";
   
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . " AND (( p.id_marca != 1  AND p.id_modelo != 4 ) or ( p.id_marca != 2  AND p.id_modelo != 6 ) ) "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_alta >= '".$fecha_desde."' AND p.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . "";
    //print $q;
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

function cantidad_pedidos_by_semana_noencimerav2($id_vendedor=null, $fecha_desde=null, $fecha_hasta=null, $activo=null, $estado=null) {
 global $link;
 
 $fecha_desde = $fecha_desde . " " . "00:00:00";
 $fecha_hasta = $fecha_hasta . " " . "23:59:59";
   
 $q = "SELECT * FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . " AND (( p.id_marca != 1  AND p.id_modelo != 4 ) or ( p.id_marca != 2  AND p.id_modelo != 6 ) ) "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_alta >= '".$fecha_desde."' AND p.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . ($activo ?  "AND p.activo='".$activo."' " : null)
    . "";
    //print $q;
 return @mysql_query($q, $link);
}

function cantidad_pedidos_by_vendedor_mes_baja($id_vendedor=null, $mes=null, $anio=null, $estado=null) {
 global $link;
 
 $fecha_desde = $anio."-".$mes."-01" . " " . "00:00:00";
 $fecha_hasta = $anio."-".$mes."-31" . " " . "23:59:59";
   
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . " AND p.activo=0 "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_mod >= '".$fecha_desde."' AND p.fecha_mod <= '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . "";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

function cantidad_pedidos_by_vendedor_mes_tags($id_vendedor=null, $mes=null, $anio=null, $estado=null, $tags_idx) {
 global $link;
 
 $fecha_desde = $anio."-".$mes."-01" . " " . "00:00:00";
 $fecha_hasta = $anio."-".$mes."-31" . " " . "23:59:59";
   
 $q = "SELECT COUNT(*) AS Cantidad  " 
    . "FROM pedidos AS p "
    . "INNER JOIN pedidos_incidencias pi ON pi.id_pedido=p.id "
    . "WHERE p.id_vendedor='".$id_vendedor."' "
    . "AND pi.id_incidencia IN ('".$tags_idx."') "
    . (($fecha_desde && $fecha_hasta) ?  "AND ( p.fecha_alta >= '".$fecha_desde."' AND p.fecha_alta <= '".$fecha_hasta."' )  " : null)
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . "";
 //print $q;   
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}



function cantidad_pedidos_papelera_by_vendedor($id_vendedor=null) {
 global $link;
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE p.id_vendedor='".$id_vendedor."' "
    . " AND p.activo='0' " 
    . "";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

function cantidad_pedidos( $estado=null ) {
 global $link;
 $q = "SELECT COUNT(*) AS Cantidad  FROM pedidos AS p "
    . " WHERE 1 "
    . ($estado ?  "AND p.estado='".$estado."' " : null)
    . "";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}

//////////////////////////////////////////////////////////
// OLLAS
//////////////////////////////////////////////////////////

 function obtener_pedido_ollas($id_pedido) {
    global $link;
    if($id_pedido > 0) { 
    $q = "SELECT po.* "
       . "FROM pedidos_ollas AS po "
       . "WHERE 1 "
       . "AND po.id_pedido='".$id_pedido."' "
       . "".$order_by.""
       . "";
    return @mysql_query($q, $link);  
    }
 }

//////////////////////////////////////////////////////////
// ESTADOS
//////////////////////////////////////////////////////////


 function cantidad_by_estado($id) {
  global $link;
  $q = "SELECT COUNT(*) AS Cantidad FROM pedidos WHERE estado='".$id."' ";
  $r = @mysql_query($q,$link);
  $a = @mysql_fetch_array($r);    
  return $a['Cantidad']; 
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
   $estado = 0; // NUEVO
      
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
    . ($limite  ? " limit ".$limite." " :null)    
    . "";
   // print $q;
  return @mysql_query($q, $link);  
 } 
 

function obtener_estado_pendientes() {
 global $link;
     
 $q = "SELECT pe.*"
    . "FROM pedidos AS pe "
    . "WHERE 1 "
    . "AND pe.estado='1' "
    . "AND pe.leido='0' "
    . " ORDER BY pe.id DESC "
    . "";
  return @mysql_query($q, $link);  
 } 
 
//////////////////////////////////////////////////////////
// EXCEL
//////////////////////////////////////////////////////////

function subir_excel($campos) {
  loadClasses('BackendUsuario');
  global $link;
  global $BackendUsuario;
  
   // id de los pedidos a tratar
   $pedidos_idx   = $campos['idx']; 
  
   // EXCEL
   $archivo = $_FILES["file"]["name"];
 
   // archivos
    if(strlen($archivo) > 0){ 
      $archivo =  escapeSQLFull($archivo);
      @move_uploaded_file($_FILES["file"]["tmp_name"], FILE_PATH_ROOT."/adj/despacho/".$archivo);
      @chmod(FILE_PATH_ROOT."/adj/despacho/".$archivo, 0755);
    }
   
   $q = "INSERT INTO pedidos_excel (id_admin,archivo,fecha_alta, activo) VALUES ('".$BackendUsuario->getUsuarioId()."', '".$archivo."', NOW(), '".$activo."') ";
   $r = @mysql_query($q,$link);  
   $last_id = @mysql_insert_id($link);
  
   require_once 'Excel/reader.php';
   // ExcelFile($filename, $encoding);
   $data = new Spreadsheet_Excel_Reader();
   
   // Set output Encoding.
   $data->setOutputEncoding('CP1251');
   $data->read(FILE_PATH_ROOT."/adj/despacho/".$archivo);
  
  
   for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
    for ($j = 1; $j <= 9; $j++) { // columnas tamanio fijo

    }
    
    $ID = $data->sheets[0]['cells'][$i][1]; // ID
    $TRANSPORTE = $data->sheets[0]['cells'][$i][33]; // TRANSPORTE
    $FACTIBILIDAD = $data->sheets[0]['cells'][$i][34]; // FACTIBILIDAD

    // cambio de estado y determina el tipo de despacho
    if($FACTIBILIDAD == "1") {
     $q = "UPDATE pedidos SET estado='3', despacho='".$TRANSPORTE."' WHERE id = '".$ID."' ";
     $r = @mysql_query($q,$link);  
    } else {
     $q = "UPDATE pedidos SET estado='2' WHERE id = '".$ID."' ";
     $r = @mysql_query($q,$link);  
    } 
    
   }


   return $last_id;
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

 function obtener_estado($id=null) {
  global $link;
  $q = "SELECT e.* "
     . "FROM estados AS e "
     . "WHERE 1 "
     . "AND e.id='".$id."' "
     . "LIMIT 1"
     . "";
   $r = @mysql_query($q,$link);
   return @mysql_fetch_array($r);     
 }
 
 
//////////////////////////////////////////////////////////
// INCIDENCIAS
//////////////////////////////////////////////////////////

 function obtener_incidencia_by_pedido($id_pedido=null) {
  global $link;
  $q = "SELECT i.* "
     . "FROM pedidos_incidencias AS i "
     . "WHERE 1 "
     . ($id_pedido ?  "AND i.id_pedido='".$id_pedido."' " : null)
     . "ORDER BY i.id ASC "
     . "";
   // print $q;
  return @mysql_query($q, $link);     
 }
 
 function obtener_incidencia_by_estado($id_pedido,$id_estado) {
   global $link;   
   $q  = "SELECT * FROM pedidos_incidencias  WHERE id_pedido='".$id_pedido."' AND id_estado='".$id_estado."' ORDER BY id DESC LIMIT 1 ";
   $r = @mysql_query($q,$link);
   return @mysql_fetch_array($r); 
 }

 function obtener_incidencia_by_estadov2($id_pedido,$id_estado) {
   global $link;   
   if($id_pedido > 0) {
   $q  = "SELECT i.*, t.nombre "
       . "FROM pedidos_incidencias i "
       . "LEFT JOIN pedidos_incidencias_tags t ON t.id=i.id_incidencia "
       . "WHERE id_pedido='".$id_pedido."' AND id_estado='".$id_estado."' "
       . "ORDER BY i.id DESC LIMIT 1 "
       . " ";
   $r = @mysql_query($q,$link);
   return @mysql_fetch_array($r); 
   }
 }

 function obtener_ultima_incidencia_by_pedido($id_pedido) {
   global $link;   
   if($id_pedido > 0) {
   $q  = "SELECT i.*, t.nombre "
       . "FROM pedidos_incidencias i "
       . "LEFT JOIN pedidos_incidencias_tags t ON t.id=i.id_incidencia "
       . "WHERE id_pedido='".$id_pedido."' "
       . "ORDER BY i.id DESC LIMIT 1 "
       . " ";
   $r = @mysql_query($q,$link);
   return @mysql_fetch_array($r); 
   }
 }
 function incidencia_multiple($id_pedido, $estado, $campos) {

   global $link;   
   // id pedido
   $id  = $id_pedido;
   $id_estado = $estado;
   $id_incidencia = escapeSQLFull($campos['rd_incidencia']);
   $contenido = escapeSQLFull($campos['contenido_incidencia']);

   // agrega un log a la instancia
   $q = "INSERT INTO pedidos_incidencias (id_incidencia, id_pedido, contenido, id_estado, fecha_alta) VALUES ('".$id_incidencia."', '".$id."', '".$contenido."', '".$id_estado."', NOW() ) ";
   $r = @mysql_query($q,$link);  
   
   //extras
   if($r) {
    
    // SI ES UNA INCIDENCIA GRAVE, MANDA EL PEDIDO A
    // LA PAPELERA
    
    if($id_incidencia == 4 or $id_incidencia == 9 or $id_incidencia == 16) {
     $q = "UPDATE pedidos SET activo = 0 WHERE id='".$id."' ";
     $r = @mysql_query($q,$link); 
    }
    
    // mails de incidencia
    // $this->enviar_incidencia_cliente($id);
    // $this->enviar_incidencia_vendedor($id);
   } 

   return $last_id;
 }  

 function incidencia($campos) {
   global $link;   
   // id pedido
   $id  = escapeSQLFull($campos['id']);
   $contenido = escapeSQLFull($campos['contenido_incidencia']);
   $id_estado = $campos['estado'];
   $id_incidencia = escapeSQLFull($campos['rd_incidencia']);

   // agrega un log a la instancia
   $q = "INSERT INTO pedidos_incidencias (id_incidencia, id_pedido, contenido, id_estado, fecha_alta) VALUES ('".$id_incidencia."', '".$id."', '".$contenido."', '".$id_estado."', NOW() ) ";
   $r = @mysql_query($q,$link);  
   
   // si el estado de agenda, igual lo pasa 
   if($id_estado == 1) {
    $q = "UPDATE pedidos SET incidencia='".$contenido."', fecha_mod=NOW(), estado='2' WHERE id='".$id."' ";
    $r = @mysql_query($q,$link);   
   } else if($id_estado == 2) {
    $q = "UPDATE pedidos SET incidencia='".$contenido."', fecha_mod=NOW(), estado='2' WHERE id='".$id."' ";
    $r = @mysql_query($q,$link);   
   } else if($id_estado == 3) {
    $q = "UPDATE pedidos SET incidencia='".$contenido."', fecha_mod=NOW(), estado='3' WHERE id='".$id."' ";
    $r = @mysql_query($q,$link);   
   } else {
    $q = "UPDATE pedidos SET incidencia='".$contenido."', fecha_mod=NOW() WHERE id='".$id."' ";
    $r = @mysql_query($q,$link);   
   }
   
   //extras
   if($r) {
     // mails de incidencia
     $this->enviar_incidencia_cliente($id);
     $this->enviar_incidencia_vendedor($id);
   } 

   return $last_id;
   
 }

//////////////////////////////////////////////////////////
// COMICIONAR
//////////////////////////////////////////////////////////

 function comicionar($id) {
   global $link;
   $q = "UPDATE pedidos SET comicionar='1' WHERE id='".$id."'";
   $r = @mysql_query($q,$link);
   return $r;
 }

 function descomicionar($id) {
   global $link;
   $q = "UPDATE pedidos SET comicionar='0' WHERE id='".$id."'";
   $r = @mysql_query($q,$link);
   return $r;
 }
 
//////////////////////////////////////////////////////////
// EMAILS
//////////////////////////////////////////////////////////
 
 function enviar_pedido_cliente($last_id) {
    global $link;
    require_once('clsMailer.php');
  
    // pedido
    $arrPedido = $this->obtener($last_id);
    
    if(strlen($arrPedido['cliente_email']) > 5) {
     
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
               Fecha de ingreso: " . $arrPedido['fecha_alta'] . "<br/>
               Vendedor: " . $arrPedido['vendedor_nombre']    . "
               <br><br>
               Nombre:   " . $arrPedido['cliente_nombre'] . "
               Apellido: " . $arrPedido['cliente_apellido'] . "
               Cédula:   " . $arrPedido['cliente_dni'] . "
               <br><br>
               Cocina:   " . $arrPedido['marca'] . " " . $arrPedido['modelo'] . " " . $arrPedido['color'] . "
               <br><br>
               Ha pasado al proceso de verificación de datos y asignar una fecha de entrega a call center
               ";

    
          $Mailer->Body = $HTML;

      if(!$Mailer->Send()) {
        // error en el envio
        // enviar alerta
      } else {
        return 1;
      }
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
    //print "ERROR ENVIO EMAIL ";
    //die; 
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
    //$Mailer->AddAddress('coordinacion@linkear.net'); 
    //$Mailer->AddAddress('mgassmann@gmail.com'); 
    $Mailer->AddAddress($arrVendedor['email']); 
    $Mailer->IsHTML(true); 
    $Mailer->Subject  =  "Venta  " . $arrPedido['cliente_nombre'] . " " . $arrPedido['cliente_apellido'];
  
    $HTML =  " Gracias " . $arrVendedor['nombre'] . " " . $arrVendedor['apellido'] . " tu registro del cliente " . $arrPedido['cliente_nombre'] . " " . $arrPedido['cliente_apellido'] . " fue ingresada la venta con éxito, estamos en la etapa de confirmación de datos.
               <br><br>
               Gracias por ser parte de la venta de cocinas de inducción, donde esperamos hacer un verdadero cambio en los ciudadanos no solo mejorando el ambiente sino un cambio tecnológico, te animamos a que continues en las ventas
               <br><br>
             ";

    
    $Mailer->Body = $HTML;

    if(!$Mailer->Send()) {
    print "ERROR ENVIO EMAIL ";
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

 // 3307
 function enviar_baja_vendedor($last_id) {
   global $link;
   require_once('clsMailer.php');
   loadClasses( 'Vendedor');
   global $Vendedor;
  
    // pedido
    $arrPedido = $this->obtener($last_id);

    // indicencia
    $arrIncidencia = $this->obtener_ultima_incidencia_by_pedido($last_id);
    $incidencia =  $arrIncidencia['nombre'] . " - "  . $arrIncidencia['contenido'];
    
    // vendedor
    $arrVendedor = $Vendedor->obtener($arrPedido['id_vendedor']);
    
    $Mailer = new phpmailer();
    $Mailer->Host     = MAIL_SMTP; // SMTP servers
    $Mailer->Mailer   = "mail";
    $Mailer->From     = "ventas@induccion.ec";
    $Mailer->FromName = "Induccion";
    //$Mailer->AddAddress('coordinacion@linkear.net'); 
    $Mailer->AddAddress($arrVendedor['email']); 
    //$Mailer->AddAddress('mgassmann@gmail.com'); 
    //$Mailer->AddAddress('jch@linkear.net');
    $Mailer->IsHTML(true); 
    $Mailer->Subject  =  "[PEDIDO BAJA]";
  
    $HTML =  "  El pedido numero (".$last_id.") del cliente (".$arrPedido['cliente_nombre']. " " . $arrPedido['cliente_apellido'] . ", " . $arrPedido['cliente_dni'] . ") con CUEN (".$arrPedido['cliente_cuen'].").
                <br><br>
                <font color='#AA0000'> <strong>" . $incidencia . " </font></strong>
                <br><br>
                Responsable de logística (<strong>Fernanda Estupiñán</strong>)
                <br><br>
                Por favor contactarse con el cliente los datos de contacto son:<br><br>

                Telefono: " . $arrPedido['cliente_telefono'] . " <br>
                Celular:  " . $arrPedido['cliente_celular'] . " <br>
                Provincia: " . $arrPedido['cliente_provincia'] . " <br>
                Canton: " . $arrPedido['cliente_canton'] . " <br>
                Parroquia " . $arrPedido['cliente_parroquia'] . " <br>
                Barrio " . $arrPedido['barrio'] . " <br>
                Calle principal " . $arrPedido['cliente_calle'] . " <br>
                Número " . $arrPedido['cliente_calle_numero'] . " <br>
                Referencia " . $arrPedido['cliente_referencia'] . " <br>
                
                Producto: [".$arrPedido['marca']."] [".$arrPedido['modelo']."]
             ";

    $Mailer->Body = $HTML;

    if(!$Mailer->Send()) {
    print "ERROR ENVIO EMAIL ";
    die; 
    } else {
    return 1;
    }
 }

 function enviar_alta_vendedor($last_id) {
   global $link;
   require_once('clsMailer.php');
   loadClasses( 'Vendedor', 'BackendUsuario');
   global $Vendedor, $BackendUsuario;
  
    // pedido
    $arrPedido = $this->obtener($last_id);

    // usuario de session
    $arrBackendUsuario = $BackendUsuario->obtener($BackendUsuario->getUsuarioId());
    
    // vendedor
    $arrVendedor = $Vendedor->obtener($arrPedido['id_vendedor']);
    
    $Mailer = new phpmailer();
    $Mailer->Host     = MAIL_SMTP; // SMTP servers
    $Mailer->Mailer   = "mail";
    $Mailer->From     = "ventas@induccion.ec";
    $Mailer->FromName = "Induccion";
    //$Mailer->AddAddress($arrVendedor['email']); 
    $Mailer->AddAddress('mgassmann@gmail.com'); 
    //$Mailer->AddAddress('jch@linkear.net');
    $Mailer->IsHTML(true); 
    $Mailer->Subject  =  "[PEDIDO RECUPERADO]";
  
    $HTML =  "  El pedido numero (".$last_id.") del cliente (".$arrPedido['cliente_nombre']. " " . $arrPedido['cliente_nombre'] . ", " . $arrPedido['cliente_dni'] . ") con CUEN (".$arrPedido['cliente_cuen'].").
                <br><br>
                Recuperado por (<strong>" . $arrBackendUsuario['nombre'] . " " . $arrBackendUsuario['apellido'] . "</strong>)
                <br><br>
             ";

    $Mailer->Body = $HTML;

    if(!$Mailer->Send()) {
    print "ERROR ENVIO EMAIL ";
    die; 
    } else {
    return 1;
    }
 }

} // end class
?>