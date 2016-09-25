<?php
class Usuario {

 // propiedades
 public $nombre;
 public $apellidos;
 public $sexo;

 // contructor
 function __construct() {
       
 }

 function logOut() {
		$this->_logeado = 0;
		$this->_arrUsuario = array();
 }

 function estaLogeado() {
	
		if(isset($_SESSION['frontenduser']['usuario_id'])) { 
		 return ($_SESSION['frontenduser']['usuario_id'] && $_SESSION['frontenduser']['logged_on'] == true) ? 1 : 0;
		}
		else
		{
		 return 0;	
		}
 }


 function getUsuarioNombre() { 
		return $_SESSION['frontenduser']['usuario_nombre'];
 }

 function getUsuarioApellido() { 
		return $_SESSION['frontenduser']['usuario_apellido'];
 }

	function getUsuario() { 
		return $_SESSION['frontenduser']['usuario_usuario'];
	}

	function getUsuarioId() { 
		return $_SESSION['frontenduser']['usuario_id'];
	}

	function getUsuarioTipo() { 
		return $_SESSION['frontenduser']['usuario_tipo'];
	} 

 function getUsuarioNombreCompleto() { 
		return $_SESSION['frontenduser']['usuario_nombre'];
 }

 function cargar_sesion_facebook($first_name, $last_name, $email) {
   
   $_SESSION['frontenduser']['usuario_id'] = "1";
   $_SESSION['frontenduser']['usuario_nombre'] = $first_name;
   $_SESSION['frontenduser']['usuario_apellido'] = $last_name;

 }
 
 function login_by_email($campos=null) {
	global $link;

	$email = trim($campos['login']['email']);
	$clave = trim($campos['login']['clave']);
  
	if( strlen($email) > 2 && strlen($clave) > 3 ) 
	{	
		$q   = "SELECT * FROM usuario "
			   . "WHERE 1 "
			   . "AND email = '".escapeSQL($email)."' "
				 . "AND clave = '".escapeSQL(md5($clave))."' "
			   . "AND activo = 1 "
			   . "LIMIT 1 ";
	
		$r	 = @mysql_query($q,$link);
	  $a   = @mysql_fetch_array($r);
	  
	  if(@mysql_num_rows($r) > 0) {
    
       $this->_logeado = 1;
       
       $_SESSION['frontenduser']['logged_on']      = true;
       $_SESSION['frontenduser']['ip']             = $_SERVER['REMOTE_ADDR'];
       $_SESSION['frontenduser']['logged_from']    = $_SERVER['HTTP_REFERER'];
       $_SESSION['frontenduser']['id']      			 = session_id();
       $_SESSION['frontenduser']['usuario_id']   		 = $a['id'];
       $_SESSION['frontenduser']['usuario_nombre'] 	 = $a['nombre'];
       $_SESSION['frontenduser']['usuario_apellido'] = $a['apellido'];
       $_SESSION['frontenduser']['usuario_email'] 	 = $a['email'];       
       $_SESSION['frontenduser']['usuario_tipo'] 	   = $a['tipo'];       
       
			$this->actualizar_logeo($a['id']);
		  return @mysql_num_rows($r);
		} else {
			return 0;
		}
	 }	
 } 
 
 function actualizar_logeo($id) {
	global $link;
	$q = "UPDATE usuario SET fecha_logeo=NOW() WHERE id='".$id."'";
	return @mysql_query($q,$link);
 }
 
 // select all
 function obtener_all($porPagina=null, $pagina=null, $palabra=null, $OrderBy=null, $filtro=null, $activo=null, $estado=null, $limite=null) {
  global $link;

   $q = "SELECT  c.* "
      . "FROM cliente AS c "
      . "WHERE 1 "
      . (($activo) ?  "AND c.activo='".$activo."' " : null)
      . (($estado != -1) ?  "AND c.estado='".$estado."' " : null)
      . " $OrderBy "
      . ($porPagina	? "limit ".$pagina*$porPagina.",".$porPagina :null)
      . "";
 	return @mysql_query($q,$link);
 }


 // select one
 function obtener($id) {
  global $link;
 	$q = "SELECT c.* FROM cliente AS c WHERE c.id='".$id."' LIMIT 1";
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
 	$q 		= "UPDATE usuario SET activo=".$campo." WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }

 function estado($id,$campo) {
 	global $link;

 	if($campo==0) {
 		$campo = 1;
 		$fecha_aprobado = ", fecha_aprobado=NOW()";
 	} else if($campo==1) {
 		$campo = 2;
 		$fecha_rechazado = ", fecha_rechazado=NOW()";
 	} else if($campo==2) {
 		$campo = 0;
 	}
 	
 	$q = "UPDATE usuario SET estado=".$campo." $fecha_aprobado $fecha_rechazado  WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM usuario WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }

function mail_existe($email) {
	global $link;
	$q  = "SELECT * FROM usuario  WHERE email='".$email."' ";
  $r	= @mysql_query($q,$link);
	return  @mysql_num_rows($r);
}

function usuario_existe($usuario) {
	global $link;
	$q  = "SELECT * FROM usuario  WHERE _usuario='".$usuario."' ";
  $r	= @mysql_query($q,$link);
	return @mysql_num_rows($r);
}  
 
//////////////////////////////////////////////////////////////
// EXTRAS
//////////////////////////////////////////////////////////////  

function contacto_item($campos) {
	global $link;
	
	$id = escapeSQLFull($campos['id']);
	$nombre = escapeSQLFull($campos['contacto']['nombre']);
	$email = escapeSQLFull($campos['contacto']['email']);
	$telefono = escapeSQLFull($campos['contacto']['telefono']);
	$contenido = escapeSQLFull($campos['contacto']['contenido']);
   	 
  $q = "INSERT INTO item_contacto (id_item, nombre, email, telefono, contenido, fecha_alta) VALUES ('".$id."', '".$nombre."', '".$email."', '".$telefono."', '".$contenido."', NOW())";
	$r = @mysql_query($q,$link);	 
	$last_id = @mysql_insert_id($link);
	 
	//extras
	if($last_id > 0) {
	  $this->enviar_mail_admin($last_id);
	  //$this->enviar_mail_inmobiliaria();
	}
	return $last_id;
}

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