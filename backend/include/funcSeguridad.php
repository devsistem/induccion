<?php
/**
* seguridad.php
* Funciones de seguridad general y sql injection
* interlogical.net
* 15/04/2011 21:25:53
*/

function postBlock($postID) {
  if(isset($_SESSION['postID'])) {
   if ($postID == $_SESSION['postID']) {
    return false;
    } else {
    $_SESSION['postID'] = $postID;
    return true;
    }
    } else {
    $_SESSION['postID'] = $postID;
    return true;
  }
} 

function pasar_utf8($text){
  $text = utf8_encode($text);
  return $text;
}

function limpiar_string($text){
  $text = trim($text);
  $text = escapeSQLFull($text);
  return $text;
}

function escapeSQL($text){
	return mysql_real_escape_string($text);
}

// escapear tags
function escapeSQLFull($text){
	$text = mysql_real_escape_string($text);
	$text = htmlspecialchars($text);
	return $text;
}

function escapeSQLTags($text){
	$text = htmlspecialchars($text);
	$text = eliminar_palabras_rss($text);
	return $text;
}

// sin espacios - minusculas
function parsear_string_basico($text) {
  $text = trim($text);
  $text = strtolower($text);
  return $text;
}

function desescapear($text=null) {
 return str_replace("\\","",$text);
}

function eliminar_palabras_rss($text) {
  $text = str_replace("\\","",$text);
  $text = str_replace("<script>","",$text);
  $text = str_replace("script","",$text);
  $text = str_replace("<?php","",$text);
  $text = str_replace("<?","",$text);
	return $text;
	}
// sin espacios al principio del texto
// - por espacios
// sin acentos ni ñ
// minusculas
function limpiar_normal($s=null) {
  //sacar espacios del final y principio
 	$s = trim($s);
 	//remplazar espacios por  -
 	$s = str_replace(" ","-",$s);
  // minusculas
 	$s = strtolower($s);
 	// caracteresespeciales off
 	$s = str_replace("á","a",$s);
 	$s = str_replace("é","e",$s);
 	$s = str_replace("í","i",$s);
 	$s = str_replace("ó","o",$s);
 	$s = str_replace("ú","u",$s);
 	$s = str_replace("ñ","n",$s);
 return $s;
}

function parse_para_fckeditor($s=null) {}
function limpiar_full() {}
//	<script> o <iframe>.

function validar_xss($s) {
	}
	
function preparar_busqueda($s) {
 $s = strtolower($s);
 $s = mysql_real_escape_string($s);
 $s = htmlspecialchars($s);	  
 $s = preg_replace('/ +,/', ',', $s); // Avoid errors like " ,
 return $s;
}

function seguridad_x($t){
	$t	= @stripslashes($t);
	$t	= @addslashes($t);
	$t  = @ereg_replace(";","",$t);
	$t  = @ereg_replace("<","",$t);
	$t  = @ereg_replace(">","",$t);
	$t  = @ereg_replace("/","",$t);
	$t  = @ereg_replace(':',"",$t);
return $t;
}
?>