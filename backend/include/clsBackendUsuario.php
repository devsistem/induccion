
<?php
/**
* @name Class BackendUsuario
* @package Interlogical.CMS
*/

class BackendUsuario 
{

var $_logeado = false;
var $_arrUsuario = array();

/**
* @access public
* Deslogea el usuario.
*/
function logOut() {
 $this->_logeado = 0;
 $this->_arrUsuario = array();
}

/**
* @access public
* @return bool
* Retorna el id del usuario
*/
function getUsuarioId() { 
 return $_SESSION['backenduser']['id'];
}
						
/**
* @access public
* @return bool
* Indica si el usuario esta logeado.
*/
function EstaLogeado() { 
return ($_SESSION['backenduser']['id'] && $_SESSION['backenduser']['logged_on'] == true) ? 1 : 0;
}



   
function EstaLogeadoBackend() {  
 if(!isset($_SESSION['backenduser']['id']) || empty($_SESSION['backenduser']['id'])) {
  @header("Location: ingreso.php");
  }
}

function tienePermisos() {  
 if(isset($_SESSION['backenduser']['id']) && $_SESSION['backenduser']['id'] > 0 && ($_SESSION['backenduser']['backenduser_tipo'] == 'admin' OR $_SESSION['backenduser']['backenduser_tipo'] == 'root')) {
  return 1;
 } else {
  return 0;
 }
}
   
function noEsRoot()
{  
 if(!isset($_SESSION['backenduser']['id']) || empty($_SESSION['backenduser']['id']) || ( $_SESSION['backenduser']['backenduser_tipo'] != 'root' AND $_SESSION['backenduser']['backenduser_tipo'] != 'admin') ){
   die;
  }
}

function esRoot()
{  
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == '1')){
    return 1;
  }
}


function esAdmin()
{  
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == '2' && $_SESSION['backenduser']['backenduser_roles'] == 'admin')){
    return 1;
  }
}


function esVendedor()
{  
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == '10')){
    return 1;
  }
}

function esSupervisor()
{  
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == '4')){
    return 1;
  }
}


function esCordinador()
{  
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == '3')){
    return 1;
  }
}

function esSupervisorCordinador()
{ 
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == 4 or $_SESSION['backenduser']['perfil'] == 5 )){
    return 1;
  }
}

function esGerenteGeneral()
{ 
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == 9)){
    return 1;
  }
}

function esGerenteVentas()
{ 
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == 3)){
    return 1;
  }
}

function esGerenteLogistica()
{ 
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == 11)){
    return 1;
  }
}

function esAsistente()
{ 
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == 12)){
    return 1;
  }
}

function esInventario()
{ 
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == 13)){
    return 1;
  }
}

function esEncargadoBodega()
{ 
 if(isset($_SESSION['backenduser']['id']) && ( $_SESSION['backenduser']['perfil'] == 14)){
    return 1;
  }
}

function obtenerNombreCompleto() {
 return $_SESSION['backenduser']['backenduser_nombre'] . " " .  $_SESSION['backenduser']['backenduser_apellido'];
}

function rol_nombre() {
 return $_SESSION['backenduser']['backenduser_rol_nombre'];
}

function obtenerImagen() {
 return $_SESSION['backenduser']['imagen'];
}

function obtenerDominio() {
 return $_SESSION['backenduser']['dominio'];
}

function obtenerCargo() {
 return $_SESSION['backenduser']['cargo'];
}

function logear_session($id_perfil) {
   $_SESSION['backenduser']['perfil'] = $id_perfil; 
   $_SESSION['session_root'] = 1;     
   
   if($_SESSION['backenduser']['perfil'] == 2) {
	   $_SESSION['backenduser']['cargo'] = "Administrador";
	 } else if($_SESSION['backenduser']['perfil'] == 3) {
	   $_SESSION['backenduser']['cargo'] = "Gerente Ventas";
	 } else if($_SESSION['backenduser']['perfil'] == 4) {
	   $_SESSION['backenduser']['cargo'] = "Asesor Supervisor";
	 } else if($_SESSION['backenduser']['perfil'] == 9) {
	   $_SESSION['backenduser']['cargo'] = "Gerente General";
	 } else if($_SESSION['backenduser']['perfil'] == 10) {
	   $_SESSION['backenduser']['cargo'] = "Vendedor";
	 } else if($_SESSION['backenduser']['perfil'] == 11) {
	   $_SESSION['backenduser']['cargo'] = "Gerente Logística";
	 } else if($_SESSION['backenduser']['perfil'] == 12) {
	   $_SESSION['backenduser']['cargo'] = "Asistentes";
	 } else if($_SESSION['backenduser']['perfil'] == 13) {
	   $_SESSION['backenduser']['cargo'] = "Inventario";
	 } else if($_SESSION['backenduser']['perfil'] == 14) {
	   $_SESSION['backenduser']['cargo'] = "Encargado Bodega";
	 }
	 
}

function obtener($id) {
 global $link;
 $q = "SELECT BU.* FROM ".DBSYS_BACKENDUSER." AS BU WHERE  BU.id='".$id."' LIMIT 1";
 $r = @mysql_query($q,$link);
 return @mysql_fetch_array($r);		
}

function ObtenerUsuarioByUser($usuario=null) {
 global $link;
 $q = "SELECT BU.* FROM ".DBSYS_BACKENDUSER." AS BU WHERE  BU.usuario='".$usuario."' LIMIT 1";
 $r = @mysql_query($q,$link);
 return @mysql_fetch_array($r);		
}


function ObtenerUsuarioByMail($backenduser_email=null) {
 global $link;
 $q = "SELECT BU.* FROM ".DBSYS_BACKENDUSER." AS BU WHERE  BU.email='".$backenduser_email."' LIMIT 1";
 $r = @mysql_query($q,$link);
 return @mysql_fetch_array($r);		
}


function obtener_by_usuario_email($usuario=null) {
 global $link;
 $q = "SELECT bu.* FROM ".DBSYS_BACKENDUSER." AS bu WHERE ( bu._usuario='".$usuario."' OR bu.email='".$usuario."' ) LIMIT 1";
 $r = @mysql_query($q,$link);
 return @mysql_fetch_array($r);		
}

function editar_clave($id,$backenduser_clave=null) {
 global $link;
 $q = "UPDATE  ".DBSYS_BACKENDUSER." SET _clave='".$backenduser_clave."' WHERE id='".$id."'";
 return (@mysql_query($q,$link)) ? $id : 0;
}

function grabar($campos=null) {
 global $link;
 
 $id_perfil = escapeSQLFull($campos['id_perfil']);
 $id_supervisor = escapeSQLFull($campos['id_supervisor']);
 $email = escapeSQLFull($campos['email']);
 $nombre = escapeSQLFull($campos['nombre']);
 $apellido = escapeSQLFull($campos['apellido']);
 $contenido = escapeSQLFull($campos['contenido']);
 $usuario = escapeSQLFull($campos['usuario']);
 
 if($id_perfil == 10) {
	 $cargo = "Asesor Comercial";
 } elseif($id_perfil == 4) {
	 $cargo = "Asesor Supervisor";
 }
 
 // enc
 $claveEnc = md5("temporal1.2");

 $estado = 1;
 $activo = 1;

 $q = "INSERT INTO ".DBSYS_BACKENDUSER." "
     . "(contenido, cargo, id_supervisor, tipo, roles, perfil, _usuario, _clave, email, nombre, apellido, activo, estado, fecha_alta) "
     . "VALUES "
     . "('".$contenido."','".$cargo."', '".$id_supervisor."','1', '1','".$id_perfil."','".$usuario."', '".$claveEnc."', '".$email."', '".$nombre."', '".$apellido."', '".$activo."', '".$activo."', NOW()) ";

  $r = @mysql_query($q,$link);
  $last_id = @mysql_insert_id();
  
  // enviar mail? 
  return $last_id;
}

function editar_mi_cuenta($campos=null) {
 global $link;
 
 $arrActual = $this->obtener($this->getUsuarioId()); 

 $nombre = escapeSQLFull($campos['admin']['nombre']);
 $apellido = escapeSQLFull($campos['admin']['apellido']);
 $email = escapeSQLFull($campos['admin']['email']);
 $contenido = escapeSQLFull($campos['admin']['contenido']);
 $telefono = escapeSQLFull($campos['admin']['telefono']);
 $celular = escapeSQLFull($campos['admin']['celular']);


 // clave
 $claveEnc   = md5($campos['clave_registro']); // clave nueva que quiere insertar
 $claveVieja = md5($campos['clave_actual']);  // tiene q saber la clave anterior

 if($arrActual['_clave']==$claveVieja && strlen($campos['clave_actual']) > 3 && strlen($campos['clave_registro']) > 3) {
     $clave = $claveEnc;
 }
 else
 {
     $clave = $arrActual['_clave'];
 }
 
 $imagen = escapeSQLFull($campos['imagen']);
 
 // sin imagen
 if(strlen($imagen) == 0) {
	 $q = "UPDATE sys_backendusuario SET _clave='".escapeSQLFull($clave)."', email='".$email."', celular='".$celular."', telefono='".$telefono."', nombre='".$nombre."', apellido='".$apellido."', contenido='".$contenido."', fecha_mod=NOW() WHERE id='".$this->getUsuarioId()."' ";
	 $r = @mysql_query($q,$link);
 } else {
	 $q = "UPDATE sys_backendusuario SET imagen='".escapeSQLFull($imagen)."', _clave='".escapeSQLFull($clave)."', email='".$email."', celular='".$celular."', telefono='".$telefono."', nombre='".$nombre."', apellido='".$apellido."', contenido='".$contenido."', fecha_mod=NOW() WHERE id='".$this->getUsuarioId()."' ";
	 $r = @mysql_query($q,$link);
 }
}

function editar($id=null, $campos=null) {
 global $link;
 
 $id_perfil = escapeSQLFull($campos['id_perfil']);
 $id_supervisor = escapeSQLFull($campos['id_supervisor']);
 $email = escapeSQLFull($campos['email']);
 $nombre = escapeSQLFull($campos['nombre']);
 $apellido = escapeSQLFull($campos['apellido']);
 $contenido = escapeSQLFull($campos['contenido']);
 $usuario = escapeSQLFull($campos['usuario']);
 
 if($id_perfil == 10) {
	 $cargo = "Asesor Comercial";
 } elseif($id_perfil == 4) {
	 $cargo = "Asesor Supervisor";
 }
      
	$q = "UPDATE ".DBSYS_BACKENDUSER." SET cargo='".$cargo."', id_supervisor='".$id_supervisor."', perfil='".$id_perfil."', _usuario='".$usuario."',  email='".$email."', nombre='".$nombre."', apellido='".$apellido."', contenido='".$contenido."', fecha_mod=NOW() WHERE id='".$id."' ";
 	$r = @mysql_query($q,$link);
 return $r;
}



function renewUsuarioClave($idUsuario) {
 global $link;
 $clave = generatePassword();
 $this->updateUsuario($idUsuario, null, null, null, null, null, null, null, null, $clave);
 return $clave;
}

function obtener_all($pagina=null, $porPagina=null, $usuario=null, $email=null, $nombre=null, $estado=null, $activo=null, $perfil=null, $id_supervisor=null) {
 global $link;
 $offSet = $pagina * $porPagina;
 $q = "SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . "AND perfil != 1 "
    . ($estado    ? "AND estado='".$estado."' "   :null)
    . ($activo    ? "AND activo='".$activo."' "   :null)
    . ($perfil    ? "AND perfil='".$perfil."' "   :null)
    . ($id_supervisor   ? "AND id_supervisor='".$id_supervisor."' "   :null)
    . ($usuario   ? "AND usuario  LIKE '%$usuario%' "     :null)
    . ($email     ? "AND email LIKE '%$email%' "   :null)
    . ($nombre    ? "AND (nombre LIKE '%$nombre%' OR apellido LIKE '%$nombre%') " :null)
    . "";
    //print $q;
 return @mysql_query($q,$link);
}

function obtener_all_sin_supervisor($pagina=null, $porPagina=null, $usuario=null, $email=null, $nombre=null, $estado=null, $activo=null, $perfil=null, $id_supervisor=null) {
 global $link;

 $q = "SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . "AND perfil = 10 "
    . "AND (id_supervisor = '0'  OR  id_supervisor = '') "
    . ($estado    ? "AND estado='".$estado."' "   :null)
    . ($activo    ? "AND activo='".$activo."' "   :null)
    . "";
    //print $q;
 return @mysql_query($q,$link);
}

function obtener_vendedores($activo=null) {
 global $link;
 $q = "SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . "AND  perfil=10  ) "
    . ($activo    ? "AND activo='".$activo."' "   :null)
    . "ORDER BY apellido ASC ";
 return @mysql_query($q,$link);
}
////////////////////////////////////////
//1722295126 obtener vendedores de un supervisor
//////////////////////////////////////
function obtener_vendedorer_id($id=null,$perfil=null, $i=null) {
  global $link;
  $q = "select concat(apellido,', ',nombre) as nombre,activo from sys_backendusuario where id=".$id;
  $r = @mysql_query($q, $link);
  $a = @mysql_fetch_array($r);
 $sp='';
 $valor_ret='';
  
  
  if($perfil=='4')
  { 
      // if ( $a['nombre']==null||  $a['nombre']==""){
         $valor_ret="1. Supervisor";
         $sp="<spam  style='color:blue'>";
      /*}
       else
            $valor_ret=$a['nombre'];*/
  }
  else if ($perfil=='10')
  {
     if ( $a['nombre']==null||  $a['nombre']==""){
         $valor_ret= "2. Vendedor sin Supervisor";
        $sp="<spam  style='color:blue'>";
      }
     else
          $valor_ret=$a['nombre'];
  }
  else{
     $valor_ret= "3. Otros Empleados";
      $sp="<spam  style='color:blue'>";
   }
   if($sp=="")
   {
     if($a["activo"]=='1')
      $sp="<spam  style='color:blue'>";
    else
      $sp="<spam  style='color:red'>";

   }
   

  return $sp.$valor_ret."</spam>";
 
}


function obtener_vendedores_y_supervisores($activo=null,$id_vendedor=null) {
 global $link;
 $q = "SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . "AND ( perfil=10 OR perfil=4 ) "
    . ($activo    ? "AND activo='".$activo."' "   :null)
    . ($id_vendedor    ? "AND id='".$id_vendedor."' "   :null)
    . "ORDER BY id_supervisor ASC,apellido ASC ";
 return @mysql_query($q,$link);
}
////////////////////////////////////////
//1722295126 obtener vendedores de un supervisor
//////////////////////////////////////
function obtener_empleados($activo=null,$id_vendedor=null,$tipo=null) {
 global $link;
 $q = "SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . ($tipo=='sv'    ? "AND ( perfil=10 OR perfil=4) "   :null)
    . ($tipo=='v'    ? "AND perfil=4 "   :null)
    . ($tipo=='s'    ? "AND perfil=10 "   :null)
    . ($activo=='1'    ? "AND activo='1' "   :null)
    . ($activo =='0'   ? "AND activo='0' "   :null)
    . ($id_vendedor    ? "AND id='".$id_vendedor."' "   :null)
    . "ORDER BY id_supervisor ASC,apellido ASC ";

 return @mysql_query($q,$link);
}
function obtener_supervisados_me($activo=null,$id_vendedor=null,$tipo=null) {
 global $link;
 $q = "(SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . ($tipo=='sv'    ? "AND ( perfil=10 OR perfil=4) "   :null)
    . ($tipo=='v'    ? "AND perfil=4 "   :null)
    . ($tipo=='s'    ? "AND perfil=10 "   :null)
    . ($activo=='1'    ? "AND activo='1' "   :null)
    . ($activo =='0'   ? "AND activo='0' "   :null)
    . ($id_vendedor    ? "AND id='".$id_vendedor."' "   :null)
    . "ORDER BY id_supervisor ASC,apellido ASC"
    . ")union all("
    . "SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . ($tipo=='sv'    ? "AND ( perfil=10 OR perfil=4) "   :null)
    . ($tipo=='v'    ? "AND perfil=4 "   :null)
    . ($tipo=='s'    ? "AND perfil=10 "   :null)
    . ($activo=='1'    ? "AND activo='1' "   :null)
    . ($activo =='0'   ? "AND activo='0' "   :null)
    . ($id_vendedor    ? "AND id_supervisor='".$id_vendedor."' "   :null)
    . "ORDER BY id_supervisor ASC,apellido ASC)";

 return @mysql_query($q,$link);
}



function obtener_asistentes($perfil,$activo=null) {
 global $link;
 $q = "SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . ($activo    ? "AND activo='".$activo."' "   :null)
    . ($perfil    ? "AND perfil='".$perfil."' "   :null)
    . "ORDER BY apellido ASC ";
 return @mysql_query($q,$link);
}

function obtener_vendedores_inactivos($activo=null) {
 global $link;
 $q = "SELECT * "
    . "FROM ".DBSYS_BACKENDUSER." "
    . "WHERE 1 "
    . "AND perfil=10 "
    . "AND activo='0' " 
    . "ORDER BY apellido ASC ";
 return @mysql_query($q,$link);
}

function login_by_usuario($usuario=null,$clave=null) {
global $link;
$q   = "SELECT * "
  			 . "FROM  ".DBSYS_BACKENDUSER." "
			   . "WHERE (( _usuario = '".$usuario."' AND 	_clave   = '".md5($clave)."' ) OR (_usuario = '".$usuario."' AND _clave_salt = '".md5($clave)."' )) "
			   . "AND   activo  = 1 "
		     . "LIMIT 1";

$r = @mysql_query($q,$link)  or die('error: ' . mysql_error()); 
$a = @mysql_fetch_array($r);

	  if(@mysql_num_rows($r) > 0) {
    
       $this->_logeado = 1;
       $_SESSION['backenduser']['logged_on']      = true;
       $_SESSION['backenduser']['ip']             = $_SERVER['REMOTE_ADDR'];
       $_SESSION['backenduser']['logged_from']    = $_SERVER['HTTP_REFERER'];
       $_SESSION['backenduser']['session_id']      			  = session_id();
       $_SESSION['backenduser']['id'] = $a['id'];
       $_SESSION['backenduser']['backenduser_usuario']  = $a['_usuario'];
       $_SESSION['backenduser']['backenduser_nombre'] 	= $a['nombre'];
       $_SESSION['backenduser']['backenduser_apellido'] = $a['apellido'];
       $_SESSION['backenduser']['backenduser_email'] 	  = $a['email'];       
       $_SESSION['backenduser']['perfil'] 	  = $a['perfil'];       
       $_SESSION['backenduser']['roles'] = $a['roles'];
       $_SESSION['backenduser']['backenduser_imagen'] = $a['imagen'];
       $_SESSION['backenduser']['backenduser_dominio'] = $a['dominio'];
       $_SESSION['backenduser']['cargo'] = $a['cargo'];
       // si es vendedor
       if($this->esVendedor()) {
        $_SESSION['vendedoruser']['imagen'] = $a['imagen'];
			  $_SESSION['vendedoruser']['id'] = $a['id'];
				$_SESSION['vendedoruser']['nombre'] = $a['nombre'] . " " . $a['apellido'];
       }
              
			 $this->actualizar_logeo($a['id']);
			
		  return $a;
		} else {
		return 0;
		}
  }


/**
* @access public
* @return int
* @param string $usuario
* Actualiza la fecha del último logeo.
*/

function actualizar_logeo($id=null){
 global $link;
 $q = "UPDATE ".DBSYS_BACKENDUSER." SET logeo = NOW() WHERE id='".$id."' ";
 return @mysql_query($q,$link);
}

// Cambia el estado 
function publicar($id,$campo=null) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q = "UPDATE ".DBSYS_BACKENDUSER." SET activo='".$campo."' WHERE id='".$id."'";
 $r = @mysql_query($q,$link);	 
}

function eliminar($id) {
 global $link;
 $q  = "DELETE FROM ".DBSYS_BACKENDUSER."  WHERE id='".$id."'";
 $r = @mysql_query($q,$link);	 
}
    
// Obtiene el total de usuarios registrados ACTIVOS e INACTIVOS
function ultimo_acceso($id) {
 global $link;
 $q = "SELECT ultimo_logeo AS ultimoLogeo FROM ".DBSYS_BACKENDUSER." WHERE id=".$id."";
 $r = @mysql_query($q,$link);
 $a = @mysql_fetch_array($r);	
 return $a['ultimoLogeo'];
}

// Existe mail
function MailExiste($backenduser_email) {
 global $link;
 $q  = "SELECT * FROM '".DBSYS_BACKENDUSER."' WHERE backenduser_email='".$backenduser_email."' ";
 $r	= @mysql_query($q,$link);
 return (@mysql_num_rows($r) > 0) ?  1 : 0;
}

// Exister user
function UsuarioExiste($backenduser_usuario) {
 global $link;
 $q  = "SELECT * FROM '".DBSYS_BACKENDUSER."'  WHERE backenduser_usuario='".$backenduser_usuario."' ";
 $r	= @mysql_query($q,$link);
 return (@mysql_num_rows($r) > 0) ?  1 : 0;
}

/////////////////////////////////////////////////////////////////////
// TIPOS DE USUARIO
////////////////////////////////////////////////////////////////////


Function EsTipoUsuario($id, $tipo=null) {
 global $link;
 $q  = "SELECT COUNT(*) FROM '".DBSYS_BACKENDUSER."'  WHERE id='".$id."' AND backenduser_tipo='".$tipo."' ";
 $r	= @mysql_query($q,$link);
 return (@mysql_num_rows($r) > 0) ?  1 : 0;
}

//////////////////////////////////////////////////////////////////////
// ENVIO DE MAILS
//////////////////////////////////////////////////////////////////////

function recuperar_clave($usuario) {
 global $link;
 require_once(FILE_PATH.'/include/clsMailer.php');

 $arrActual     = $this->obtener_by_usuario_email($usuario);
 $newclave_mail = (generatePassword());
 $newclave      = md5($newclave_mail);

 if( $newclave && $arrActual['id'] > 0 ) {
  $val = $this->editar_clave($arrActual['id'],$newclave);

    $Mailer = new phpmailer();
 	  $Mailer->Host     = MAIL_SMTP; // SMTP servers
 	  $Mailer->Mailer   = "mail";
   	$Mailer->From     = "info@livenature.com.ar";
   	$Mailer->FromName = "Livenature";
   	$Mailer->AddAddress($arrActual['email']); 
   	$Mailer->IsHTML(true); 
   	$Mailer->Subject  =  " Recuperar Clave " . DOMINIO;
	
    $HTML =  "<br>Estimado Usuario " . $arrActual['nombre'] . "<br>
    Este mensaje ha sido enviado desde livenature.com.ar   
    Has recibido este mensaje porque ha solicitado recuperar sus datos de registro. 
    <br/><br/>             
    Información de su Cuenta<br/>  
    <br/> Usuario:        " . $arrActual['_usuario'] . "
    <br/> Nueva Clave:    " . $newclave_mail . "<br/><br/>Gracias por confiar en livenature.com.ar  <br/><br/>";
    
  $Mailer->Body = $HTML;

 	if(!$Mailer->Send()) {
    return 0;
   } else {
    return 1;
  }
 }
} // f
///////////////////////////////////////////////////////////////////////
//no more
} // end class
?>