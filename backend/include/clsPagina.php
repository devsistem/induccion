<?php
class Pagina {

function obtener_all($order_by=null, $filtro=null, $activo=null, $destacado=null, $limite=null, $palabra=null, $id_seccion=null) {
  global $link;
  
  if($filtro == 'titulo' && $OrderBy=='d')
     $ORDER = "ORDER BY p.titulo DESC ";
	elseif($filtro == 'titulo' && $OrderBy=='a')
  	 $ORDER = "ORDER BY p.titulo ASC ";
  elseif($filtro == 'id' && $OrderBy=='d')
	   $ORDER = "ORDER BY p.id DESC ";
  elseif($filtro == 'id' && $OrderBy=='a')
	    $ORDER = "ORDER BY p.id ASC ";
  elseif($filtro == 'estado' && $OrderBy=='d')
	    $ORDER = "ORDER BY P.estado DESC ";
  elseif($filtro == 'estado' && $OrderBy=='a')
	    $ORDER = "ORDER BY p.estado ASC ";
  else
	   $ORDER = " ORDER BY p.id DESC ";

    $q = "SELECT  p.* FROM paginas AS p "
       . "WHERE 1 "
	  	 . ($activo 	  ? "AND p.activo='".$activo."' " :null)
	  // . ($id_seccion ? "AND P.id_seccion='".$id_seccion."' " :null)
	   	 .	" $order_by " 
	  // . ($limite			? "LIMIT ".$limite." " :null)	
		   . "";
	  return @mysql_query($q,$link);
}

function obtener($id) {
  global $link;
 	$q = "SELECT p.* FROM paginas AS p WHERE p.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
}

function grabar( $campos=null ) {
	 global $link;
	 $titulo = escapeSQLFull($campos['titulo']);
	 $contenido = $campos['contenido'];
 	 $descripcion = $campos['descripcion'];
	 $tags = escapeSQLFull($campos['tags']);
	 
	 $uri = escapeSQLFull($campos['uri']);
	  
	 // SEO
	 $meta_title = escapeSQLFull($campos['meta_title meta']);
	 $meta_keyboards = escapeSQLFull($campos['meta_keyboards']); 
	 $meta_description = ParseTextArea($campos['meta_description'],1);
   
   //fk
   $id_seccion = escapeSQLFull($campos['id_seccion']); 
   $id_categoria = escapeSQLFull($campos['id_categoria']); 
   $id_subcategoria = escapeSQLFull($campos['id_subcategoria']); 
   $id_continente = escapeSQLFull($campos['id_continente']);

   $id_guia = escapeSQLFull($campos['id_guia']);
   $id_guia = implode(",", $_POST['id_guia']);

   $q = "INSERT INTO paginas (titulo, descripcion, contenido, fecha_alta, meta_title, meta_keyboards, meta_description, uri, id_seccion, id_categoria, id_subcategoria, id_continente, tags, id_guia) VALUES ('".$titulo."','".$descripcion."','".$contenido."',NOW(), '".$meta_title."', '".$meta_keyboards."', '".$meta_description."','".$uri."', '".$id_seccion."', '".$id_categoria."', '".$id_subcategoria."', '".$id_continente."', '".$tags."', '".$id_guia."')";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
}
  
// Actualiza un registro
function editar($id, $campos=null) {
   global $link;
	 $titulo = escapeSQLFull($campos['titulo']);
	 $descripcion = $campos['descripcion'];
	 $contenido = $campos['contenido'];
	 $tags = escapeSQLFull($campos['tags']);
	 $uri = escapeSQLFull($campos['uri']);
	 	 
	 // SEO
	 $meta_title = escapeSQLFull($campos['meta_title']);
	 $meta_keyboards = escapeSQLFull($campos['meta_keyboards']); 
	 $meta_description = ParseTextArea($campos['meta_description'],1);

   //fk
   $id_seccion = escapeSQLFull($campos['id_seccion']); 
   $id_categoria = escapeSQLFull($campos['id_categoria']); 
   $id_subcategoria = escapeSQLFull($campos['id_subcategoria']); 
   $id_continente = escapeSQLFull($campos['id_continente']);
   
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