<?php
class Texto {


function obtener_all($OrderBy=null,$filtro=null,$activo=null) {
global $link;
  if($filtro == 'titulo' && $OrderBy=='d')
     $ORDER = "ORDER BY C.titulo DESC ";
	elseif($filtro == 'titulo' && $OrderBy=='a')
  	 $ORDER = "ORDER BY C.titulo ASC ";
  elseif($filtro == 'id' && $OrderBy=='d')
	   $ORDER = "ORDER BY C.id DESC ";
  elseif($filtro == 'id' && $OrderBy=='a')
	    $ORDER = "ORDER BY C.id ASC ";
  elseif($filtro == 'estado' && $OrderBy=='d')
	    $ORDER = "ORDER BY C.estado DESC ";
  elseif($filtro == 'estado' && $OrderBy=='a')
	    $ORDER = "ORDER BY C.estado ASC ";
  else
	   $ORDER = " ORDER BY C.id ASC ";

    $q = "SELECT C.* FROM texto AS C "
	     . (!is_null($activo) ? "WHERE C.activo=".$activo."" :null)
			 .	" $ORDER ";
	  return @mysql_query($q,$link);
}

function obtenerByKey($Key) {
  global $link;
  $q  = "SELECT E.* FROM texto AS E WHERE E.objeto='".$Key."' LIMIT 1";
	$r  = @mysql_query($Q,$link);
	$a  = @mysql_fetch_array($R);		
return $r['contenido'];	 
}

function obtenerById($id) {
global $link;
 $q = "SELECT T.* FROM texto AS T WHERE T.id='".$id."' LIMIT 1";
 $r = @mysql_query($q,$link);
 $a = @mysql_fetch_array($r);		
return $a['contenido'];	 
}
  
function obtener($id) {
global $link;
 $q   = "SELECT T.* FROM texto AS T WHERE T.id='".$id."' LIMIT 1";
 $r   = @mysql_query($q,$link);
return @mysql_fetch_array($r);		
}

function obtenerTextosByKey($key) {
global $link;
 $q = "SELECT T.* FROM texto AS T WHERE T.objeto='".$key."' LIMIT 1";
 $r = @mysql_query($q,$link);
 $a = @mysql_fetch_array($r);		
return $a;	
}

// Agrega un registro
function agregar( $campos=null ) {
	 global $link;

	 $titulo= $campos['titulo'];
	 $titulo_cat= $campos['titulo_cat'];
	 $titulo_en= $campos['titulo_en'];
	 $titulo_fr= $campos['titulo_fr'];
	 $titulo_br= $campos['titulo_br'];
	 
	 $contenido = $campos['contenido'];
	 $contenido_cat = $campos['contenido_cat'];
	 $contenido_en = $campos['contenido_en'];
	 $contenido_fr = $campos['contenido_fr'];
	 $contenido_br = $campos['contenido_br'];

	 $contenido 		 = ParseFCKEditor($contenido);
	 $contenido_cat  = ParseFCKEditor($contenido_cat);
   $contenido_en   = ParseFCKEditor($contenido_en);
   $contenido_fr   = ParseFCKEditor($contenido_fr);
   $contenido_br   = ParseFCKEditor($contenido_br);
      
   $q = "INSERT INTO texto (titulo_cat,titulo_en,titulo_fr,titulo_br,contenido,contenido_cat,contenido_ent,contenido_fr,contenido_br) "
      . "VALUES "
      . "('".$titulo_cat."','".$titulo_en."','".$titulo_fr."','".$titulo_br."','".$contenido."','".$contenido_cat."','".$contenido_en."','".$contenido_fr."','".$contenido_br."')";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
}
  
// Actualiza un registro
function editar($id, $campos=null) {
    global $link;

	 $titulo= $campos['titulo'];
	 $titulo_cat= $campos['titulo_cat'];
	 $titulo_en= $campos['titulo_en'];
	 $titulo_fr= $campos['titulo_fr'];
	 $titulo_br= $campos['titulo_br'];
	 
	 $contenido = $campos['contenido'];
	 $contenido_cat = $campos['contenido_cat'];
	 $contenido_en = $campos['contenido_en'];
	 $contenido_fr = $campos['contenido_fr'];
	 $contenido_br = $campos['contenido_br'];

	 $contenido 		 = ParseFCKEditor($contenido);
	 $contenido_cat  = ParseFCKEditor($contenido_cat);
   $contenido_en   = ParseFCKEditor($contenido_en);
   $contenido_fr   = ParseFCKEditor($contenido_fr);
   $contenido_br   = ParseFCKEditor($contenido_br);
       
		$q   = "UPDATE texto SET titulo='".$titulo."', titulo_cat='".$titulo_cat."', titulo_en='".$titulo_en."', titulo_fr='".$titulo_fr."',  titulo_br='".$titulo_br."', "
	       . "contenido='".$contenido."', contenido_cat='".$contenido_cat."', contenido_en='".$contenido_en."', contenido_fr='".$contenido_fr."', contenido_br='".$contenido_br."', fecha_mod=NOW() 
	       WHERE id='".$id."'";
	  if(!@mysql_query($q,$link))
	   return null;
	  else
	   return $id;
  }

function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE texto SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);	 
  return $id;
 }
 
function eliminar($Idx) {
 global $link;
 $q = "DELETE FROM texto WHERE id='".$id."'";
 $r = @mysql_query($q,$link);	 
 }
  
} // end class
?>	