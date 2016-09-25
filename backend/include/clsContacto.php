<?php
class Contacto {

 // propiedades
 public $nombre;


 // contructor
 function __construct() {
       
 }
 
 // ABM
 function obtener($id) {
  global $link;
 	$q = "SELECT c.* "
 		 . "FROM contacto AS c "
 		 . "WHERE c.id='".$id."' "
 		 . "LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 function obtener_all($porPagina=null, $pagina=null, $palabra=null, $OrderBy=null, $filtro=null, $activo=null, $estado=null, $limite=null, $id_item=null, $tipo=null) {
  global $link;

   $q = "SELECT  c.* "
      . "FROM contacto AS c "
      . "WHERE 1 "
      . (($activo) ?  "AND c.activo='".$activo."' " : null)
      . (($estado) ?  "AND c.estado='".$aestadoctivo."' " : null)
      . (($tipo)   ?  "AND c.tipo='".$tipo."' " : null)
      . (($id_item) ?  "AND c.id_item='".$id_item."' " : null)
      . " ORDER BY c.id DESC "
      . ($porPagina	? "limit ".$pagina*$porPagina.",".$porPagina :null)
      . ($limite	? "limit ".$limite :null)
      . "";
 	return @mysql_query($q,$link);
}
 
// insert
function pedido($id, $nombre,$email,$telefono, $contenido ) {
	global $link;

	$id_item = escapeSQLFull($id);
	$nombre = escapeSQLFull($nombre);
	$email = escapeSQLFull($email);
	$telefono = escapeSQLFull($telefono);
	$contenido = escapeSQLFull($contenido);

	$tipo = "pedido";
	$activo = 1;
		 	 		 	 
  $q = "INSERT INTO contacto (id_item, nombre, email, telefono, contenido, tipo, activo, fecha_alta ) VALUES ('".$id_item."', '".$nombre."', '".$email."', '".$telefono."', '".$contenido."',  '".$tipo."', '".$activo."', NOW() )";
	$r = @mysql_query($q,$link);	 
  $last_id = @mysql_insert_id($link);
  
  // enviar mail al admin
  if($last_id > 0) { 
  	$this->enviar_pedido_admin($last_id, $id, $nombre, $email, $telefono, $contenido);
	}
  
  return $last_id;
}
 
// insert
function contacto( $nombre,$email,$mensaje ) {
	global $link;

	$nombre = escapeSQLFull($nombre);
	$email = escapeSQLFull($email);
	$contenido = escapeSQLFull($mensaje);
	$tipo = "contacto";
	$estado = "1";
	$activo = "1";
			 	 
  $q = "INSERT INTO contacto ( nombre, email, telefono, contenido,  activo, estado, fecha_alta ) VALUES ( '".$nombre."', '".$email."', '".$telefono."', '".$contenido."', '".$activo."', '".$estado."', NOW() )";
	$r = @mysql_query($q,$link);	 
  $last_id = @mysql_insert_id($link);
  
  // enviar mail al admin
  if($last_id > 0) { 
  	$this->enviar_contacto_admin($last_id, $nombre, $email, $telefono, $mensaje);
	}  
  return $last_id;
}


function responder_contacto($campos) {
 	global $link;
  
  $id_contacto = $campos['id_contacto'];
  $contenido = $campos['contenido'];
  
  // ENVIO DE MAIL?
  
}
  

function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE contacto SET activo='".$campo."' WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
}

function estado($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q = "UPDATE contacto SET estado='".$campo."'  WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
}
 
// a la papelera
function eliminar($id) {
  global $link;
  $q = "DELETE FROM contacto WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }
 
///////////////////////////////////////////////////
// EMAILS
///////////////////////////////////////////////////

function enviar_contacto_admin($last_id, $nombre, $email, $telefono, $contenido) {
 require_once(FILE_PATH.'/include/clsMailer.php');
 global $link;

 // conf
 $q    = "SELECT C.* FROM  sys_conf AS C WHERE C.id='1' LIMIT 1";
 $a    = @mysql_query($q,$link);
 $conf = @mysql_fetch_array($a);
  
 //email 
 $asunto = " Nuevo contacto en la pagina #".$last_id;
 	  
 $HTML = '
 					 Nombre: 		'.$nombre.'<br/>
 					 Email: 		'.$email.'<br/>
 					 Mensaje:   '.$contenido.' <br/>
 				 ';

 $Mailer = new phpmailer();
 $Mailer->Host     = MAIL_SMTP; // SMTP servers
 $Mailer->Mailer   = "mail";
 $Mailer->From     = $conf['mail_info'];
 $Mailer->FromName = "Mingrone Propiedades";
 $Mailer->AddAddress($conf['mail_info']); 
 $Mailer->IsHTML(true); 					

 $Mailer->Subject  = $asunto;
 $Mailer->Body     = $HTML;
 $enviado = $Mailer->Send();
 return $last_id;
}

function enviar_pedido_admin($last_id, $id_propiedad, $nombre, $email, $telefono, $contenido) {
 require_once(FILE_PATH.'/include/clsMailer.php');
 global $link;

 // conf
 $q    = "SELECT C.* FROM  sys_conf AS C WHERE C.id='1' LIMIT 1";
 $a    = @mysql_query($q,$link);
 $conf = @mysql_fetch_array($a);
 
 // propiedad texto
 $q2    = "SELECT i.* FROM  item AS i WHERE i.id='".$id_propiedad."' LIMIT 1";
 $r2    = @mysql_query($q2,$link);
 $a2 = @mysql_fetch_array($r2);
  
 //email 
 $asunto = " Nuevo pedido en la pagina #".$last_id . "  -  " . $a2['titulo'];
 	  
 $HTML = '
 					 Propiedad:	 '.$id_propiedad . ' - ' . $a2['titulo'] . '<br/>
 					 Nombre: 		 '.$nombre.'<br/>
 					 Email: 		 '.$email.'<br/>
 					 Telefono:   '.$telefono.' <br/>
 					 Mensaje:    '.$contenido.' <br/>
 				 ';
 
 $Mailer = new phpmailer();
 $Mailer->Host     = MAIL_SMTP; // SMTP servers
 $Mailer->Mailer   = "mail";
 $Mailer->From     = $conf['mail_info'];
 $Mailer->FromName = "Mingrone Propiedades";
 $Mailer->AddAddress($conf['mail_info']); 
 $Mailer->IsHTML(true); 					

 $Mailer->Subject  = $asunto;
 $Mailer->Body     = $HTML;
 $enviado = $Mailer->Send();
 return $last_id;
}   
 
} // end class
?>