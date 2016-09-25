<?php
class Newsletter {

 // propiedades
 public $nombre;
 public $apellidos;
 public $email;
 public $observaciones;

 // contructor
 function __construct() {
       
 }
 
 // select all
 function obtener_usuarios_all($porPagina=null, $pagina=null, $OrderBy=null, $activo=null, $palabra) {
  global $link;

   $q = "SELECT  nu.* "
      . "FROM newsletter_usuarios AS nu "
      . "WHERE 1 "
      . (($activo) ?  "AND nu.activo='".$activo."' " : null)
      . " $OrderBy "
      . ($porPagina	? "limit ".$pagina*$porPagina.",".$porPagina :null)
      . "";
 	return @mysql_query($q,$link);
 }


 // select one
 function obtener_usuario($id) {
  global $link;
 	$q = "SELECT nu.* FROM newsletter_usuarios AS nu WHERE nu.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 // insert
 function grabar( $campos=null ) {
	 global $link;
	
 }
 
 // update
 function editar($id, $campos=null) {
   global $link;
 }

 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE newsletter_usuarios SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM newsletter_usuarios WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

 function mail_existe($email) {
	global $link;
	$q  = "SELECT * FROM newsletter_usuarios  WHERE email='".$email."' ";
  $r	= @mysql_query($q,$link);
	return  @mysql_num_rows($r);
 }


//////////////////////////////////////////////////////////////
// TEMPLATES
//////////////////////////////////////////////////////////////  

 function obtener_templates_all($porPagina=null, $pagina=null, $OrderBy=null, $activo=null, $palabra=null, $tipo=null) {
   global $link;
   $q = "SELECT  nt.* "
      . "FROM newsletter_templates AS nt "
      . "WHERE 1 "
      . (($activo) ?  "AND nt.activo='".$activo."' " : null)
      . (($tipo) ?  "AND nt.tipo='".$tipo."' " : null)
      . " $OrderBy "
      . ($porPagina	? "limit ".$pagina*$porPagina.",".$porPagina :null)
      . "";
 	 return @mysql_query($q,$link);
 }
 
 function obtener_template($id) {
   global $link;
   $q = "SELECT  nt.* "
      . "FROM newsletter_templates AS nt "
      . "WHERE 1 "
      . "AND nt.id='".$id."' "
      . "LIMIT 1 "
      . "";
 	 $r = @mysql_query($q,$link);
	 return @mysql_fetch_array($r);
 }


 function grabar_template( $campos=null ) {
	 global $link;
	 
	 $nombre = escapeSQLFull($campos['template']['nombre']);
 	 $url = $campos['template']['url'];
	 $activo = $campos['activo'];
	 $html = $campos['html'];

   $q = "INSERT INTO newsletter_templates (nombre,  html, url, fecha_alta, activo) VALUES ('".$nombre."','".$html."','".$url."',NOW(), '".$activo."')";
	 $r = @mysql_query($q,$link);	 
	 $last_id = @mysql_insert_id($link);
	 
	 if($last_id > 0) {
	 
	 }
 }

 function editar_template( $id, $campos=null ) {
	 global $link;
	 
	 $nombre = escapeSQLFull($campos['template']['nombre']);
 	 $url = $campos['template']['url'];
	 $activo = $campos['activo'];
	 $html = $campos['html'];

   $q = "UPDATE newsletter_templates SET nombre='".$nombre."', html='".$html."', url='".$url."', fecha_mod=NOW() WHERE id='".$id."' ";
	 $r = @mysql_query($q,$link);	 
	 
	 if($r) {
	 
	 }
 }
   

//////////////////////////////////////////////////////////////
// EXTRAS
//////////////////////////////////////////////////////////////  


////////////////////////////////////////////////////
// EMAILS
////////////////////////////////////////////////////

function enviar_mail_admin($last_id) {
 loadClasses('Item');
 require_once(FILE_PATH.'/include/clsMailer.php');
 global $link;
 global $Item;

 // contacto
 $arrContacto = $this->obtener($last_id);
 // item
 $arrItem = $Item->obtener($arrContacto['id_item']);

 /*	
 // crearle una cuenta?
 $newclave_mail = (generatePassword());
 $newclave      = md5($newclave_mail);
 */

 $q = "SELECT * FROM  sys_conf AS C WHERE C.id='1' LIMIT 1";
 $arrConf  = @mysql_query($q,$link);
 $ResConf  = @mysql_fetch_array($arrConf);	

 if($arrActual) {
    $Mailer = new phpmailer();
 	  $Mailer->Host     = MAIL_SMTP; // SMTP servers
 	  $Mailer->Mailer   = "mail";
   	$Mailer->From     = $ResConf['mail_admin'];
   	$Mailer->FromName = "G2 Inmmo  ";
   	$Mailer->AddAddress($ResConf['mail_admin']); 
   	$Mailer->IsHTML(true); 
   	$Mailer->Subject  =  " Contacto por propiedad " . DOMINIO;
	
    $HTML =  "<br>El Usuario " . $arrContacto['nombre'] . " esta interesado en la siguiente propiedad: <br>
    <br/><br/>             
    <br/> Propiedad:  " . $arrItem['nombre'] . "
    <br/> id:  #". $arrItem['id'] . "
    <br/> Codigo: ". $arrItem['codigo'] . "

    Datos del usuario<br/>  
    <br/> Nombre: " . $arrContacto['nombre'] . "
    <br/> Email:  " . $arrContacto['email'] . "
    <br/> Telefono: " . $arrContacto['telefono'] . "
    <br/> Observaciones: " . $arrContacto['contenido'] . "
    <br/> <br/><br/>";
    
    $Mailer->Body = $HTML;

 	  if(!$Mailer->Send()) {
    print "ERROR. ";
    die; 
   	} else {
   	return 1;
  	}
  	
  	// Enviar a la inmo?
 }
}

} // end class
?>