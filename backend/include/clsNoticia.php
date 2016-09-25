<?php
class Noticia {

 function obtener_all($porPagina=null, $paginacion=null, $limite=null, $palabra=null, $order_by=null, $filtro=null, $activo=null, $estado=null, $destacado=null, $filtro_id_categoria=null) {
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
 	   . ($id_subcategoria ?  "AND n.id_subcategoria='".$id_subcategoria."' " : null)
 	   . "".$order_by.""
     . ($limite	? " limit ".$limite." " :null)	  
 	   . "";
 	//print $q;
 	return @mysql_query($q, $link);  
 }

 function obtener($id) {
  global $link;
 	$q = "SELECT n.* FROM noticias AS n WHERE n.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 function grabar( $campos=null ) {
	 global $link;
 	 //include("api/facebook/src/Facebook/Facebook.php");
	 $titulo = escapeSQLFull($campos['noticia']['titulo']);
 	 $descripcion = escapeSQLFull($campos['noticia']['descripcion']);
	 $contenido = $campos['noticia']['contenido'];
	 $activo = $campos['noticia']['activo'];
	 $orden = $campos['noticia']['orden'];
	 
	 // publicaciones
	 $facebook_publica = $campos['ck_facebook_publica'];	 	 


   // imagen
	 $imagen = $campos['imagen'];
	 $imagen_th = "_th";
	 
	 // este valor tiene q estar en la configuracion
	 if(strlen($imagen) > 3) {
	  $imagen_th = crearImagenResampleada(150, 150, $imagen, FILE_PATH_FRONT_ADJ."/noticias/", $imagen_th);
	 } 
	 
   $q = "INSERT INTO noticias (titulo, descripcion, contenido, fecha_alta, activo, orden, imagen, imagen_th) VALUES ('".$titulo."','".$descripcion."','".$contenido."',NOW(), '".$activo."', '".$orden."', '".$imagen."', '".$imagen_th."')";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 if($last_id > 0) {
	  // se publica en fb
	  if($facebook_publica == 1) {

	  }
	 }
	 
	 return $last_id;
 }
  
 // Actualiza un registro
 function editar($id, $campos=null) {
   global $link;
   
	 $titulo = escapeSQLFull($campos['noticia']['titulo']);
 	 $descripcion = escapeSQLFull($campos['noticia']['descripcion']);
	 $contenido = $campos['noticia']['contenido'];
	 $orden = $campos['noticia']['orden'];

   //fk
   $id_categoria = escapeSQLFull($campos['id_categoria']); 
   $id_subcategoria = escapeSQLFull($campos['id_subcategoria']); 
   
   // estados
	 $activo = $campos['noticia']['activo'];
	 $orden = $campos['noticia']['orden'];
	 
	 // publicaciones
	 $facebook_publica = $campos['ck_facebook_publica'];	

   // imagen
	 $imagen = $campos['imagen'];
	 $imagen_th = "_th";
	 
	 // este valor tiene q estar en la configuracion
	 if(strlen($imagen) > 3) {
	  $imagen_th = crearImagenResampleada(150, 150, $imagen, FILE_PATH_FRONT_ADJ."/noticias/", $imagen_th);
	  $imagen_sql = "imagen='$imagen',imagen_th='$imagen_th', ";
	 }
	 
	 $q = "UPDATE noticias SET $imagen_sql titulo='".$titulo."', descripcion='".$descripcion."', contenido='".$contenido."', id_categoria='".$id_categoria."', id_subcategoria='".$id_subcategoria."', activo='".$activo."', orden='".$orden."', fecha_mod=NOW()  WHERE id='".$id."' ";
   @mysql_query($q,$link);
 }

 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE noticias SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function destacado($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE noticias SET destacado='".$campo."' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function eliminar($id) {
 	global $link;
  $q = "DELETE FROM noticias WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

 function aumentar_visita($id){
 	global $link;
  $q   = "UPDATE noticias SET visitas=(visitas + 1) WHERE id=".$id."";
  $r = @mysql_query($q,$link);	 
  return $id;
 }

 //////////////////////////////////////////////////////////////
 // EXTRAS
 //////////////////////////////////////////////////////////////  


} // end class
?>