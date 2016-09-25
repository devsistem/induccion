<?php

class Foto {

 var $version = "1.0";

 function grabar($campos=null, $id_item=null, $ext=null) {
  global $link; 
  
  $titulo  = escapeSQLFull($campos['titulo']);
  $id_item  = escapeSQLFull($campos['id_item']); 
  $descripcion  = escapeSQLFull($campos['descripcion']);
  $pie  = escapeSQLFull($campos['pie']);

  //RS
  $numero  = mt_rand();

  // archivos
  // imagen
	$imagen = escapeSQLFull($campos['imagen']);
	$imagen_th = "";
		 
	if(strlen($imagen) > 3) {
	  $imagen_th = crearImagenResampleada(260, null, $imagen,  FILE_PATH_FRONT_ADJ."/adj/productos/",  $imagen_th);
	} 
  
  $q = "INSERT INTO productos_fotos (idx, imagen, imagen_g, imagen_th, titulo, descripcion, pie, activo, fecha_alta, orden) VALUES ('".$id_item."', '".$campos['imagen']."','".$imagen_g."', '".$imagen_th."', '".$titulo."', '".$descripcion."', '".$pie."', '1', NOW(), '".$order."')";
  @mysql_query($q,$link);
  return @mysql_insert_id($link);
 } 
 
 function editar($id=null, $campos=null) {
  global $link; 

  $titulo  = escapeSQLFull($campos['titulo']);
  $id_galeria  = escapeSQLFull($campos['id_galeria']); 
  $descripcion  = escapeSQLFull($campos['descripcion']);
  $pie  = escapeSQLFull($campos['pie']);
  
  //RS
  $numero  = mt_rand();

   // imagen
	$imagen = escapeSQLFull($campos['imagen']);
	$imagen_th = "";
		 
	if(strlen($imagen) > 3) {
	  $imagen_th = crearImagenResampleada(260, null, $imagen, FILE_PATH_FRONT_ADJ."/adj/productos/",  $imagen_th);
	} 
  
  if(strlen($archivo) > 0) {
	 $q = "UPDATE productos_fotos SET  orden='".$order."', titulo='".$titulo."',  descripcion='".$descripcion."', imagen='".$imagen."',  imagen_th	='".$imagen_th."', fecha_mod=NOW() WHERE id='".$id."'";
	 @mysql_query($q,$link);
  } else {
	 $q = "UPDATE productos_fotos SET  orden='".$order."', titulo='".$titulo."', descripcion='".$descripcion."',  fecha_mod=NOW() WHERE id='".$id."' ";
	 @mysql_query($q,$link);
  }
  
 return $id; 
}

function eliminar($id){
		global $link;

    $q = "SELECT * FROM productos_fotos WHERE id='".$id."' LIMIT 1";
	  $r = @mysql_query($q,$link);
    $a = @mysql_fetch_array($r);		
   
 		$q  = "DELETE FROM productos_fotos WHERE id='".$id."'";
    @mysql_query($q,$link);
    
		// borrado fisico
		if(@file_exists(FILE_PATH_FRONT_ADJ."/adj/productos/".$a['imagen_g'])) {
			 @unlink("../adj/productos/".$a['imagen_th']);
			 @unlink(substr(FILE_PATH_FRONT_ADJ."/productos/".$a['imagen_g'], 0, -4));
	  }
 }  
 
function obtener_all($paginacion=null, $porPagina=null, $limite=null, $id_item=null, $activo=null) {
 global $link;
 $q = "SELECT  g.* FROM productos_fotos AS g "
    . "WHERE 1 "
    . "AND g.idx='".$id_item."' "
    . (($activo) ?  "AND g.activo='".$activo."' " : null)
		. "ORDER BY g.orden ASC, g.id DESC "
    . ($porPagina	? "limit ".$paginacion*$porPagina.",".$porPagina :null)
    . ($limite		? "limit ".$limite." " :null)
		. ""; 
 return @mysql_query($q,$link);   
}

function obtener($id) {
 global $link;
 $q = "SELECT g.* FROM productos_fotos AS g WHERE g.id='".$id."' LIMIT 1 ";
 $r = @mysql_query($q,$link);
 return  @mysql_fetch_array($r);		
}

function publicar($id,$activo) {
 global $link;
 $activo = ($activo == 0) ? 1 : 0;
 $q	= "UPDATE productos_fotos SET activo='".$activo."' WHERE id='".$id."'";
 $r = @mysql_query($q,$link);	 
 return $Idx;
}  

function estado($id,$campo) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q	= "UPDATE productos_fotos SET estado='".$campo."' WHERE id='".$id."'";
 $r = @mysql_query($q,$link);	 
 return $Idx;
}  

function destacar($id,$campo) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q	= "UPDATE productos_fotos SET destacado='".$campo."' WHERE id='".$id."'";
 $r = @mysql_query($q,$link);	 
 return $Idx;
}  

function ordenar($id_item, $campos) {
 global $link;

 $result_fotos = $this->obtener_all(null, null, null, $id_item, 1);
 $filas_fotos = @mysql_num_rows($result_fotos);
 
 for($i=0; $i < $filas_fotos; $i++) {
	$items = @mysql_fetch_array($result_fotos);
	// campo
	$campo = "orden".$items['id'];
	$orden = $campos[$campo];
	$q	= "UPDATE productos_fotos SET orden='".$orden."' WHERE id='".$items['id']."'";
	$r = @mysql_query($q,$link);	 
 }
}

 function cantidad($id) {
  global $link;
  $q = "SELECT COUNT(*) AS Cantidad FROM productos_fotos AS g WHERE g.idx='".$id."' ";
  $r = @mysql_query($q,$link);
  $a = @mysql_fetch_array($r);		
  return $a['Cantidad'];
 }
}
?>	