<?php
class Publicacion {

 function obtener_all($porPagina, $paginacion, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_categoria=null) {
 	 global $link;
 	 
   if(strlen($order_by) == 0) {
    if($filtro && $order=='d')
 	    $order_by = "ORDER BY n.$filtro DESC";
    elseif($filtro && $order=='a')
 	    $order_by = "ORDER BY n.$filtro ASC";
 	 else
  	    $order_by = "ORDER BY n.orden ASC, n.id DESC";
   }	 
   	 
  $q = "SELECT n.* "
     . "FROM noticias AS n "
   //  . "LEFT JOIN ciudades ciu ON ciu.id=i.id_ciudad "
     . "WHERE 1 "  
 	  . ($activo ?  "AND n.activo='".$activo."' " : null)
 	  . ($estado ?  "AND n.estado='".$estado."' " : null)
 	  . ($destacado ?  "AND n.destacado='".$destacado."' " : null)
 	  . ($id_categoria ?  "AND n.id_categoria='".$id_categoria."' " : null)
 	  . "".$order_by.""
     . ($limite	? " limit ".$limite." " :null)	  
 	  . "";
 	//print $q;
 	return @mysql_query($q, $link);  
 }

 function obtener($id) {
  global $link;
 	$q = "SELECT p.* FROM paginas AS p WHERE p.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 function grabar( $campos=null ) {
	 global $link;

	 $titulo = escapeSQLFull($campos['noticia']['titulo']);
 	 $descripcion = escapeSQLFull($campos['noticia']['descripcion']);
	 $contenido = $campos['noticia']['contenido'];
	 $activo = $campos['noticia']['activo'];
	 $orden = $campos['noticia']['orden'];
	 
	 // publicaciones
	 $facebook_publica = $campos['ck_facebook_publica'];	 	 

	 /*
	 $tags = escapeSQLFull($campos['tags']);
	 $uri = escapeSQLFull($campos['uri']);
	  
	 // SEO
	 $meta_title = escapeSQLFull($campos['meta_title meta']);
	 $meta_keyboards = escapeSQLFull($campos['meta_keyboards']); 
	 $meta_description = ParseTextArea($campos['meta_description'],1);
   */
   //fk

   $q = "INSERT INTO noticias (titulo, descripcion, contenido, fecha_alta, activo, orden) VALUES ('".$titulo."','".$descripcion."','".$contenido."',NOW(), '".$activo."', '".$orden."')";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 if($last_id > 0) {
	 
	  // se publica en fb
	  if($facebook_publica == 1) {
	  	
	
	  
	  }
	 }
 }
  
// Actualiza un registro
function editar($id, $campos=null) {
   global $link;

	 $titulo = escapeSQLFull($campos['pagina']['titulo']);
	 $descripcion = $campos['descripcion'];
	 $contenido = $campos['contenido'];
	 $activo = escapeSQLFull($campos['activo']);

   //fk multiple
	 $q = "UPDATE paginas SET titulo='".$titulo."', descripcion='".$descripcion."', contenido='".$contenido."', meta_title='".$meta_title."', meta_keyboards='".$meta_keyboards."', meta_description='".$meta_description."', uri='".$uri."', id_seccion='".$id_seccion."', id_categoria='".$id_categoria."', id_subcategoria='".$id_subcategoria."', id_continente='".$id_continente."', fecha_mod=NOW()  WHERE id='".$id."' ";
   @mysql_query($q,$link);
}

function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE paginas SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
}
function eliminar($id) {
 global $link;
 $q = "DELETE FROM paginas WHERE id='".$id."'";
 $r = @mysql_query($q,$link);
}

function aumentar_visita($id){
 	global $link;
  $q   = "UPDATE paginas SET visitas=(visitas + 1) WHERE id=".$id."";
  $r = @mysql_query($q,$link);	 
  return $id;
}

//////////////////////////////////////////////////////////////
// EXTRAS
//////////////////////////////////////////////////////////////  

function obtener_extras_all($id=null, $OrderBy=null) {
  global $link;
  $q = "SELECT CE.*, PCE.titulo AS tipo_campo, PCE.tipo "
  	 . "FROM paginas_extras AS CE "
	   . "LEFT JOIN paginas_campo_extra PCE ON PCE.id=CE.id_campo_extra "
	   . "WHERE 1 "
	   . "AND CE.id_pagina='".$id."' "
	   . " $OrderBy "
		 . "";
	return @mysql_query($q,$link);
}

function obtener_extra($id) {
  global $link;
 	$q = "SELECT pe.* FROM paginas_extras AS pe WHERE pe.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);
}

function publicar_extra($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE paginas_extras SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);	 
  return $id;
}
 
function eliminar_extra($id) {
 global $link;
 $q = "DELETE FROM paginas_extras WHERE id='".$id."'";
 $r = @mysql_query($q,$link);
}

function editar_extra($id, $campos=null) {
   global $link;
   
	 $id_pagina = escapeSQLFull($campos['id']);
	 $id_campo_extra = escapeSQLFull($campos['id_campo_extra']);
	 $nombre = escapeSQLFull($campos['nombre']);
	 $url = escapeSQLFull($campos['url']);
	 $contenido = $campos['contenido'];
 	 $activo = (empty($campos['activo'])) ? 1 : 0;
}

//    contenido activo
function grabar_extra($campos=null) {
   global $link;
   
	 $id_pagina = escapeSQLFull($campos['id']);
	 $id_campo_extra = escapeSQLFull($campos['id_campo_extra']);
	 $nombre = escapeSQLFull($campos['nombre']);
	 $url = escapeSQLFull($campos['url']);
	 $contenido = $campos['contenido'];
 	 $activo = (empty($campos['activo'])) ? 1 : 0;
   $q = "INSERT INTO paginas_extras (id_pagina, id_campo_extra, nombre,  contenido, url, tipo,  fecha_alta) VALUES ('".$id_pagina."', '".$id_campo_extra."', '".$nombre."',  '".$contenido."', '".$url."', '".$tipo."', NOW())";
	 $r = @mysql_query($q,$link);
	 return @mysql_insert_id($link);
 }
} // end class
?>