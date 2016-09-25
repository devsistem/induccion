<?php
class Slider {

 // propiedades
 public $nombre;


 // contructor
 function __construct() {
       
 }
 
 // ABM
 function obtener($id) {
  global $link;
 	$q = "SELECT s.* FROM web_slider AS s WHERE s.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 function obtener_all($porPagina=null, $pagina=null, $palabra=null, $OrderBy=null, $filtro=null, $activo=null, $estado=null, $limite=null) {
  global $link;

   $q = "SELECT  s.* "
      . "FROM web_slider AS s "
      . "WHERE 1 "
      . (($activo) ?  "AND s.activo='".$activo."' " : null)
      . (($estado) ?  "AND s.estado='".$estado."' " : null)
      . " ORDER BY s.orden ASC "
      . ($porPagina	? "limit ".$pagina*$porPagina.",".$porPagina :null)
      . ($limite	? "limit ".$limite :null)
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
 	$q 		= "UPDATE web_slider SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function estado($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q = "UPDATE web_slider SET estado=".$campo."  WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 // a la papelera
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM web_slider WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }
 
 
 ///////////////////////////////////////////////////////
 // FOTOS
 ///////////////////////////////////////////////////////
 
   function eliminar_fotos($id){
		global $link;

    $q = "SELECT * FROM web_slider_galeria WHERE id='".$id."' LIMIT 1";
	  $r = @mysql_query($q,$link);
    $a = @mysql_fetch_array($r);		
   
 		$q  = "DELETE FROM web_slider_galeria WHERE id='".$id."'";
    @mysql_query($q,$link);
    
		// borrado fisico
		if(@file_exists(FILE_PATH_FRONT_ADJ."/adj/slider/".$a['imagen_g'])) {
			 @unlink("../adj/slider/".$a['imagen_m']);
			 @unlink("../adj/slider/".$a['imagen_g']);
			 @unlink(substr(FILE_PATH_FRONT_ADJ."/slider/".$a['imagen_g'], 0, -4));
	  }
  }  
 
	function obtener_fotos_all($id_slider=null, $activo=null, $id_dominio=null, $id_dominio_global=null, $language_id=null) {
	 global $link;
	 $q = "SELECT  g.* FROM web_slider_galeria AS g "
	    . "WHERE 1 "
	    . "AND g.id_slider='".$id_slider."' "
			. "ORDER BY g.orden ASC, g.id DESC "
			. ""; 
			//print $q;
	 return @mysql_query($q,$link);   
	}
	
	function obtener_foto($id) {
	 global $link;
	 $q = "SELECT g.* FROM web_slider_galeria AS g WHERE g.id='".$id."' LIMIT 1 ";
	 $r = @mysql_query($q,$link);
	 return  @mysql_fetch_array($r);		
	} 
 
 function cantidad_fotos($id) {
  global $link;
   $q = "SELECT COUNT(*) AS Cantidad FROM web_slider_galeria AS g WHERE g.id_slider='".$id."' ";
   $r = @mysql_query($q,$link);
   $a = @mysql_fetch_array($r);		
  return $a['Cantidad'];
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

  $items['id'] = 0;
  $campo_texto1 = "banner_texto1_".$items['id'];
  $campo_texto2 = "banner_texto2_".$items['id'];
  $campo_texto3 = "banner_texto3_".$items['id'];
  $campo_texto4 = "banner_texto4_".$items['id'];
  $campo_link   = "banner_link_".$items['id'];
  $campo_orden  = "banner_orden_".$items['id'];
	// img desde la biblioteca
  $campo_image = "banner_image_".$items['id'];
  $campo_image_path = "banner_image_path_".$items['id'];
  
  $campo_texto1_val = $campos[$campo_texto1];
  $campo_texto2_val = $campos[$campo_texto2];
  $campo_texto3_val = $campos[$campo_texto3];
  $campo_texto4_val = $campos[$campo_texto4];

  $campo_link_val = $campos[$campo_link];
  $campo_order_val = $campos[$campo_orden];

  $campo_image_val = $campos[$campo_image];
  $campo_image_path_val = $campos[$campo_image_path];
  $campo_image_val = $campo_image_path_val.$campo_image_val;
  
  // inserta
	$q = "INSERT INTO web_slider_galeria (id_slider, texto1,texto2,texto3,texto4, link, imagen,  orden, activo, fecha_alta)  VALUES ('".$id."', '".$campo_texto1_val."','".$campo_texto2_val."','".$campo_texto3_val."','".$campo_texto4_val."', '".$campo_link_val."', '".$campo_image_val."', '".$campo_order_val."', 1, NOW()) ";
	@mysql_query($q,$link);
  return $id; 
 }

 function editar_fotos($id, $campos=null) {
  global $link; 

			$result_globales = $this->obtener_fotos_all( $id, null, null, 1, 1);
			$filas_globales = @mysql_num_rows($result_globales);  
  
       // global en en
			 for($i=0; $i < $filas_globales; $i++) {
				$items = @mysql_fetch_array($result_globales);
      
			  $campo_texto1 = "banner_texto1_".$items['id'];
			  $campo_texto2 = "banner_texto2_".$items['id'];
			  $campo_texto3 = "banner_texto3_".$items['id'];
			  $campo_texto4 = "banner_texto4_".$items['id'];
			  $campo_link   = "banner_link_".$items['id'];
			  $campo_orden  = "banner_orden_".$items['id'];
				// img desde la biblioteca
			  $campo_image = "banner_image_".$items['id'];
			  $campo_image_path = "banner_image_path_".$items['id'];
			  
			  $campo_texto1_val = $campos[$campo_texto1];
			  $campo_texto2_val = $campos[$campo_texto2];
			  $campo_texto3_val = $campos[$campo_texto3];
			  $campo_texto4_val = $campos[$campo_texto4];
			
			  $campo_link_val = $campos[$campo_link];
			  $campo_order_val = $campos[$campo_orden];
			
			  $campo_image_val = $campos[$campo_image];
			  $campo_image_path_val = $campos[$campo_image_path];
			  $campo_image_val = $campo_image_path_val.$campo_image_val;
        
        // actualiza
				$q = "UPDATE web_slider_galeria SET  texto1='".$campo_texto1_val."', texto2='".$campo_texto2_val."', texto3='".$campo_texto3_val."',texto4='".$campo_texto4_val."', link='".$campo_link_val."', imagen='".$campo_image_val."',  orden='".$campo_order_val."' WHERE id='".$items['id']."' ";
				@mysql_query($q,$link);
			 }


 return $id; 
 }
} // end class
?>