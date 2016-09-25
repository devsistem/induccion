<?php
/**
* @name Class Vendedor
* @package Interlogical.CMS
*/

class Vendedor {

var $_logeado = false;
/**
* @access public
* Deslogea el usuario.
*/
function logOut() {
 $this->_logeado = 0;
 $this->_arrUsuario = array();
}

function obtener_vendedor_id() { 
 return $_SESSION['vendedoruser']['id'];
}

function esta_logeado_vendedor() { 
 return ($_SESSION['vendedoruser']['id'] && $_SESSION['vendedoruser']['logged_on'] == true) ? 1 : 0;
}
   
function obtener_nombre() {
 return $_SESSION['vendedoruser']['nombre'] . " " .  $_SESSION['vendedoruser']['apellido'];
}

function obtener_imagen() {
 return $_SESSION['vendedoruser']['imagen'] . " " .  $_SESSION['vendedoruser']['imagen'];
}


function obtener($id) {
 global $link;
 $q = "SELECT v.* FROM sys_backendusuario AS v WHERE  v.id='".$id."' LIMIT 1";
 $r = @mysql_query($q,$link);
 return @mysql_fetch_array($r);		
}

function editar_clave($id,$backenduser_clave=null) {
 global $link;
 $q = "UPDATE vendedor SET clave='".$backenduser_clave."' WHERE id='".$id."'";
 return (@mysql_query($q,$link)) ? $id : 0;
}

// PENDIENTE
function grabar($campos=null) {
 global $link;
 
 $nombre = escapeSQLFull($campos['vendedor']['nombre']);
 $apellidos = escapeSQLFull($campos['vendedor']['apellidos']);
 $usuario = escapeSQLFull($campos['vendedor']['usuario']);
 $email = escapeSQLFull($campos['vendedor']['email']);
 //$clave = escapeSQLFull($campos['vendedor']['clave']);
 $contenido = escapeSQLFull($campos['vendedor']['contenido']);
 $perfil = escapeSQLFull($campos['vendedor']['perfil']);
 $comicion_supervisado = escapeSQLFull($campos['vendedor']['comicion_supervisado']); 

 // 28/02/2016 15:02:55
 $meta_ventas = escapeSQLFull($campos['vendedor']['meta_ventas']);

 // 31/08/2016 2:09:32 PM
 $edad = escapeSQLFull($campos['vendedor']['edad']);
 $telefono = escapeSQLFull($campos['vendedor']['telefono']);
 $direccion = escapeSQLFull($campos['vendedor']['direccion']);
 $id_supervisor  = escapeSQLFull($campos['id_supervisor']);
 $contenido = escapeSQLFull($campos['vendedor']['contenido']);


 //clave generada por el sistema
 $clave = "temporal1.2"; 
 // enc
 $claveEnc = md5($clave);
 
 $estado = 1;
 $activo = 1;

 $q = "INSERT INTO sys_backendusuario "
     . "(id_supervisor, edad, telefono, direccion, contenido, meta_ventas, comicion_supervisado, perfil, _usuario, _clave,  email, nombre, apellido, activo, estado, fecha_alta) "
     . "VALUES "
     . "('".$id_supervisor."', '".$edad."', '".$telefono."', '".$direccion."', '".$contenido."', '".$meta_ventas."', '".$comicion_supervisado."', '".$perfil."', '".$usuario."', '".$claveEnc."', '".$email."', '".$nombre."', '".$apellidos."', '".$activo."', '".$activo."', NOW()) ";
  $r = @mysql_query($q,$link);
  $last_id = @mysql_insert_id();
    
  return $last_id;
}

function editar($id=null, $campos=null) {
 global $link;
 
 $nombre = escapeSQLFull($campos['vendedor']['nombre']);
 $apellidos = escapeSQLFull($campos['vendedor']['apellidos']);
 $usuario = escapeSQLFull($campos['vendedor']['usuario']);
 
 $email = escapeSQLFull($campos['vendedor']['email']);
 $clave = escapeSQLFull($campos['vendedor']['clave']);
 $edad = escapeSQLFull($campos['vendedor']['edad']);							
 $telefono = escapeSQLFull($campos['vendedor']['telefono']);
 $direccion = escapeSQLFull($campos['vendedor']['direccion']);
 $imagen =  $campos['imagen'];
 $perfil = escapeSQLFull($campos['vendedor']['perfil']);
 $comicion_supervisado = escapeSQLFull($campos['vendedor']['comicion_supervisado']); 
 $id_supervisor = escapeSQLFull($campos['id_supervisor']);

 // 28/02/2016 15:02:55
 $meta_ventas = escapeSQLFull($campos['vendedor']['meta_ventas']);

 // 31/08/2016 2:09:32 PM
 $contenido = escapeSQLFull($campos['vendedor']['contenido']);

  
 if(strlen($imagen) > 3) {
  $imagen_sql = ", imagen='$imagen'";
 }

 // si cambia clave c/ MD5
 //$clave = escapeSQLFull($campos['admin']['clave']);
 //$clave = ($cambiarclave) ? md5($backenduser_pass) : null;
 //$clave = ($cambiarclave) ? $backenduser_pass : null;
      
 $q = "UPDATE sys_backendusuario "
    . "SET fecha_mod=NOW() "
    . (!is_null($usuario) ? ", _usuario='".$usuario."' " :null)
    . (!is_null($nombre) ? ", nombre='".$nombre."' " :null)
    . (!is_null($apellidos) ? ", apellido='".$apellidos."' " :null)
    . (!is_null($edad) ? ", edad='".$edad."' " :null)
    . (!is_null($telefono) ? ", telefono='".$telefono."' " :null)
    . (!is_null($direccion) ? ", direccion='".$direccion."' " :null)
    . (!is_null($email)  ? ", email='".$email."' "   :null)
    . (!is_null($perfil)  ? ", perfil='".$perfil."' "   :null)
    . (!is_null($contenido)  ? ", contenido='".$contenido."' "   :null)
    . (!is_null($id_supervisor)  ? ", id_supervisor='".$id_supervisor."' "   :null)
    . (!is_null($meta_ventas)  ? ", meta_ventas='".$meta_ventas."' "   :null)
    . (!is_null($comicion_supervisado)  ? ", comicion_supervisado='".$comicion_supervisado."' "   :null)
    .  $imagen_sql
    . "WHERE id='".$id."' ";
 $r = @mysql_query($q,$link);
 
 // cambio de clave
 return $id;
}


 function obtener_all($pagina=null, $porPagina=null, $usuario=null, $email=null, $nombre=null, $estado=null, $activo=null) {
  global $link;
  $q = "SELECT * "
     . "FROM vendedor "
     . "WHERE 1 "
     . ($estado    ? "AND estado='".$estado."' "   :null)
     . ($activo    ? "AND activo='".$activo."' "   :null)
     . ($usuario   ? "AND _usuario  LIKE '%$usuario%' "     :null)
     . ($email     ? "AND  email LIKE '%$email%' "   :null)
     . ($nombre    ? "AND (nombre LIKE '%$nombre%' OR apellidos LIKE '%$nombre%') " :null)
     . "";
  return @mysql_query($q,$link);
 }

function login_by_usuario($usuario=null,$clave=null) {
	global $link;
	$q   = "SELECT * "
  		 . "FROM  vendedor "
			 . "WHERE _usuario = '".$usuario."'    "
			 . "AND 	_clave   = '".md5($clave)."' "
			 . "AND   activo  = 1 "
			 . "LIMIT 1";
	$r = @mysql_query($q,$link);
	$a = @mysql_fetch_array($r);

	  if(@mysql_num_rows($r) > 0) {
    
       $this->_logeado = 1;
       $_SESSION['vendedoruser']['logged_on']      = true;
       $_SESSION['vendedoruser']['ip']             = $_SERVER['REMOTE_ADDR'];
       $_SESSION['vendedoruser']['logged_from']    = $_SERVER['HTTP_REFERER'];
       $_SESSION['vendedoruser']['session_id']      			  = session_id();
       $_SESSION['vendedoruser']['id'] = $a['id'];
       $_SESSION['vendedoruser']['usuario']  = $a['_usuario'];
       $_SESSION['vendedoruser']['nombre'] 	 = $a['nombre'];
       $_SESSION['vendedoruser']['apellido'] = $a['apellidos'];
       $_SESSION['vendedoruser']['email'] 	 = $a['email'];       
       $_SESSION['vendedoruser']['tipo'] 	   = $a['tipo'];
       $_SESSION['vendedoruser']['imagen']   = $a['imagen'];
       
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
 $q = "UPDATE vendedor SET fecha_logeo = NOW() WHERE id='".$id."' ";
 return @mysql_query($q,$link);
}

// Cambia el estado 
function publicar($id,$campo=null) {
 global $link;
 $campo = ($campo == 0) ? 1 : 0;
 $q = "UPDATE vendedor SET activo='".$campo."' WHERE id='".$id."'";
 $r = @mysql_query($q,$link);	 
}

function eliminar($id) {
 global $link;
 $q  = "DELETE FROM vendedor  WHERE id='".$id."'";
 $r = @mysql_query($q,$link);	 
}
    
// Obtiene el total de usuarios registrados ACTIVOS e INACTIVOS
function ultimo_acceso($id) {
 global $link;
 $q = "SELECT fecha_logeo AS ultimoLogeo FROM vendedor WHERE id=".$id."";
 $r = @mysql_query($q,$link);
 $a = @mysql_fetch_array($r);	
 return $a['ultimoLogeo'];
}

// Existe mail
function mail_existe($email) {
 global $link;
 $q  = "SELECT * FROM vendedor WHERE email='".$email."' ";
 $r	= @mysql_query($q,$link);
 return (@mysql_num_rows($r) > 0) ?  1 : 0;
}

// Exister user
function UsuarioExiste($usuario) {
 global $link;
 $q  = "SELECT * FROM vendedor  WHERE _usuario='".$usuario."' ";
 $r	= @mysql_query($q,$link);
 return (@mysql_num_rows($r) > 0) ?  1 : 0;
}


//////////////////////////////////////////////////////////////////////
// ENVIO DE MAILS
//////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//no more
} // end class
?>