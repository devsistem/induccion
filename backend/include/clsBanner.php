<?php
class Banner {

 // propiedades
 public $nombre;


 // contructor
 function __construct() {
       
 }
 
 // ABM
 function obtener($id) {
  global $link;
 	$q = "SELECT b.* FROM banner AS b WHERE b.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 function obtener_all($order_by=null, $activo=null) {
  global $link;

   $q = "SELECT  b.* "
      . "FROM banner AS b "
      . "WHERE 1 "
      . (($activo) ?  "AND b.activo='".$activo."' " : null)
      . " $order_by "
      . "";
 	return @mysql_query($q,$link);
 }
 
 // insert
 function grabar( $campos=null ) {
	 global $link;
	 
	 $titulo = escapeSQLFull($campos['slider']['titulo']);
	 $subtitulo = escapeSQLFull($campos['slider']['subtitulo']);
   $estado = escapeSQLFull($campos['slider']['estado']);
	 $activo = escapeSQLFull($campos['ck_activo']);
 	 $url = escapeSQLFull($campos['slider']['url']);
 	 $orden = escapeSQLFull($campos['slider']['orden']);

   // imagen
	 $imagen = escapeSQLFull($campos['imagen']);
	 	 	 
   $q = "INSERT INTO web_slider (titulo, subtitulo, url, imagen, estado, activo, fecha_alta, orden ) VALUES ('".$titulo."', '".$subtitulo."', '".$url."', '".$imagen."', '".$estado."', '".$activo."', NOW(), '".$orden."' )";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
 }
  
 // update
 function editar($id, $campos=null) {
   global $link;

	 $titulo = escapeSQLFull($campos['slider']['titulo']);
	 $subtitulo = escapeSQLFull($campos['slider']['subtitulo']);
   $estado = escapeSQLFull($campos['slider']['estado']);
	 $activo = escapeSQLFull($campos['slider']['activo']);
 	 $url = escapeSQLFull($campos['slider']['url']);
 	 $orden = escapeSQLFull($campos['slider']['orden']);

   // imagen
	 $imagen = escapeSQLFull($campos['imagen']);
   
   if(strlen($imagen) == 0) {
	   $q = "UPDATE web_slider SET orden='".$orden."', url='".$url."',  titulo='".$titulo."', subtitulo='".$subtitulo."'  WHERE id='".$id."' ";
		 $r = @mysql_query($q,$link);	 
	 } else {
	   $q = "UPDATE web_slider SET orden='".$orden."', url='".$url."',  imagen='".$imagen."', subtitulo='".$subtitulo."', titulo='".$titulo."' WHERE id='".$id."' ";
		 $r = @mysql_query($q,$link);	 
	 }

	 return $r;
 }

 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE banner SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 // a la papelera
 function eliminar($id) {
 }
 
 
 ///////////////////////////////////////////////////////
 // FOTOS
 ///////////////////////////////////////////////////////
 function obtener_by_id($id_banner,$order_by=null, $activo=null) {
  global $link;

   $q = "SELECT  bi.* "
      . "FROM banner_imagen AS bi "
      . "WHERE 1 "
      . (($id_banner) ?  "AND bi.id_banner='".$id_banner."' " : null)
      . (($activo) ?  "AND bi.activo='".$activo."' " : null)
      . " $order_by "
      . "";
 	return @mysql_query($q,$link);
 }
 	
 function obtener_foto($id) {
	 global $link;
	 $q = "SELECT bi.* FROM banner_imagen AS bi WHERE bi.id='".$id."' LIMIT 1 ";
	 $r = @mysql_query($q,$link);
	 return  @mysql_fetch_array($r);		
 } 

 function obtener_front($id) {
	 global $link;
	 $q = "SELECT bi.* FROM banner_imagen AS bi WHERE bi.id='".$id."' LIMIT 1 ";
	 $r = @mysql_query($q,$link);
	 return  @mysql_fetch_array($r);		
 } 
 
 function eliminar_foto($id) {
  global $link;
  $q = "DELETE FROM web_slider_galeria WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }
 
 function ordenar_fotos($id_item, $campos) {
  global $link;
 
  $result_fotos = $this->obtener_fotos_all( $id_item, null);
  $filas_fotos = @mysql_num_rows($result_fotos);
 
  for($i=0; $i < $filas_fotos; $i++) {
			$items = @mysql_fetch_array($result_fotos);
			// campo
			$campo = "orden".$items['id'];
			$orden = $campos[$campo];
			$q	= "UPDATE web_slider_galeria SETorden='".$orden."' WHERE id='".$items['id']."'";
			$r = @mysql_query($q,$link);	 
  }
 }
 
 function grabar_fotos($id, $campos=null) {
  global $link; 


  
  // inserta
	$q = "INSERT INTO web_slider_galeria (id_slider, texto1,texto2,texto3,texto4, link, imagen,  orden, activo, fecha_alta)  VALUES ('".$id."', '".$campo_texto1_val."','".$campo_texto2_val."','".$campo_texto3_val."','".$campo_texto4_val."', '".$campo_link_val."', '".$campo_image_val."', '".$campo_order_val."', 1, NOW()) ";
	@mysql_query($q,$link);
  return $id; 
 }

 function editar_fotos($id, $campos=null) {
  global $link; 
  $imagen = $campos['imagen'];
	$url = $campos['url'];
	$texto1 = $campos['texto1'];
	$texto2 = $campos['texto2'];
	$boton = $campos['boton'];
	// actualiza
	$q = "UPDATE banner_imagen SET texto1='".$texto1."', texto2='".$texto2."', boton='".$boton."', imagen='".$imagen."', url='".$url."', fecha_mod=NOW() WHERE id='".$id."' ";
	@mysql_query($q,$link);
		

 return $id; 
 }
} // end class
?>