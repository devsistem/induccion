<?php
class Referido {

 // propiedades
 public $nombre;
 public $apellidos;
 public $sexo;

 // contructor
 function __construct() {
       
 }

function getUsuarioId() { 
 return $_SESSION['referidouser']['id'];
}
						
/**
* @access public
* @return bool
* Indica si el usuario esta logeado.
*/
function EstaLogeado() { 
return ($_SESSION['referidouser']['id'] && $_SESSION['referidouser']['logged_on'] == true) ? 1 : 0;
}
   

function login_by_email($campos=null) {
	global $link;

	$email = trim($campos['login']['email']);
	$clave = trim($campos['login']['clave']);
  
	if( strlen($email) > 2 && strlen($clave) > 3 ) 
	{	
		$q   = "SELECT * FROM referidos "
			   . "WHERE 1 "
			   . "AND email = '".escapeSQL($email)."' "
				 . "AND _clave = '".escapeSQL(md5($clave))."' "
			   . "AND activo = 1 "
			   . "LIMIT 1 ";
			   
		$r	 = @mysql_query($q,$link);
	  $a   = @mysql_fetch_array($r);
	  
	  if(@mysql_num_rows($r) > 0) {
    
       $this->_logeado = 1;
       
       $_SESSION['referidouser']['logged_on']  = true;
       $_SESSION['referidouser']['ip']  = $_SERVER['REMOTE_ADDR'];
       $_SESSION['referidouser']['logged_from']  = $_SERVER['HTTP_REFERER'];
       $_SESSION['referidouser']['session_id'] = session_id();
       $_SESSION['referidouser']['id'] = $a['id'];
       $_SESSION['referidouser']['nombre'] 	 = $a['nombre'];
       $_SESSION['referidouser']['apellido'] 	 = $a['apellido'];
       $_SESSION['referidouser']['email'] 	= $a['email'];       
       $_SESSION['referidouser']['foto'] = $a['foto'];   
       
			$this->actualizar_logeo($a['id']);
		  return @mysql_num_rows($r);
		} else {
			return 0;
		}
	 }	
 } 


 function actualizar_logeo($id=null){
 	global $link;
 	$q = "UPDATE referidos SET fecha_logeo = NOW() WHERE id='".$id."' ";
 	return @mysql_query($q,$link);
 }
 
 // ABM
 
 // select all
 function obtener_all($porPagina=null, $pagina=null, $palabra=null, $OrderBy=null, $filtro=null, $activo=null, $estado=null, $limite=null) {
  global $link;

   $q = "SELECT  r.* "
      . "FROM referidos AS r "
      . "WHERE 1 "
      . (($activo) ?  "AND r.activo='".$activo."' " : null)
      . (($estado != -1) ?  "AND r.estado='".$estado."' " : null)
      . " $OrderBy "
      . ($porPagina	? "limit ".$pagina*$porPagina.",".$porPagina :null)
      . "";
 	return @mysql_query($q,$link);
 }

 function obtener($id) {
  global $link;
 	$q = "SELECT r.* FROM referidos AS r WHERE r.id='".$id."' LIMIT 1";
 	$r = @mysql_query($q,$link);
	return @mysql_fetch_array($r);		
 }

 // insert
 function grabar( $campos=null ) {
	 global $link;
	 
	 $razon_social = escapeSQLFull($campos['referidos']['razon_social']);
	 $nombre = escapeSQLFull($campos['referidos']['nombre']);
	 $apellido = escapeSQLFull($campos['referidos']['apellido']);

	 $latitude = $campos['latitude'];
	 $longitude = $campos['longitude'];
	 $email = escapeSQLFull($campos['referidos']['email']);
	 $telefono = escapeSQLFull($campos['referidos']['telefono']);
	 $mobil = escapeSQLFull($campos['referidos']['mobil']);
	 $direccion	= escapeSQLFull($campos['referidos']['direccion']);
	 $id_ciudad	= escapeSQLFull($campos['referidos']['id_ciudad']);

   // imagen
   $foto = $campos['foto'];

	 // clave

   // crea un codigo_subscripcion unico de 5 digitos
   mt_srand(time());
	 $codigo_subscripcion = mt_rand(0,9999);  
	 
	 // clave
	 $clave_registro = GeneratePassword();
	 $clave_enc = md5($clave_registro); 
 
   $q = "INSERT INTO referidos (foto ,razon_social,nombre,apellidos,email,_clave,clave_registro,fecha_registro, telefono, mobil, direccion, contenido, latitude, longitude, fecha_alta, activo ) VALUES ('".$foto."', '".$razon_social."', '".$nombre."', '".$apellidos."', '".$email."', '".$clave_enc."', '".$clave_registro."', NOW(), '".$telefono."', '".$mobil."', '".$direccion."', '".$contenido."', '".$latitude."', '".$logitude."', NOW(), 1)";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
 }
  
 // update
 function editar($id, $campos=null) {
   global $link;
    
	 
	 $razon_social = escapeSQLFull($campos['referidos']['razon_social']);
	 $nombre = escapeSQLFull($campos['referidos']['nombre']);
	 $apellido = escapeSQLFull($campos['referidos']['apellido']);

	 $latitude = $campos['latitude'];
	 $longitude = $campos['longitude'];
	 $email = escapeSQLFull($campos['referidos']['email']);
	 $telefono = escapeSQLFull($campos['referidos']['telefono']);
	 $mobil = escapeSQLFull($campos['referidos']['mobil']);
	 $direccion	= escapeSQLFull($campos['referidos']['direccion']);
	 $id_ciudad	= escapeSQLFull($campos['referidos']['id_ciudad']);

   // imagen
   $imagen = $campos['imagen'];

	 // clave
	 $claveEnc   = md5($campos['clave_registro']); // clave nueva
   $claveVieja = md5($campos['clave_actual']); 
    
    if($arrActual['clave']==$claveVieja && strlen($campos['clave_actual']) > 3 && strlen($clave_actual['clave_registro']) > 3) {
      $clave = $claveEnc;
      //$this->CambiarClaveMail($arrActual['usuario_registro'],$arrCampos['clave_registro']);
    }
    else
    {
      $clave = $arrActual['clave'];
    }
    
	   // crea un codigo_subscripcion unico de 5 digitos
	   mt_srand(time());
		 $codigo_subscripcion = mt_rand(0,9999);  
	
		 // sin imagen
		 $q = "UPDATE referidos SET clave='".escapeSQLFull($clave)."', razon_social='".$razon_social."', email='".$email."',  telefono='".$telefono."', mobil='".$mobil."', direccion='".$direccion."', contenido='".$contenido."', latitude='".$latitude."', logitude='".$logitude."', fecha_mod=NOW()  WHERE id='".$id."' ";
  	 @mysql_query($q,$link);
 }

 function publicar($id,$campo) {
 	global $link;
 	$campo = ($campo == 0) ? 1 : 0;
 	$q 		= "UPDATE referidos SET activo=".$campo." WHERE id='".$id."'";
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
 	
 	$q = "UPDATE referidos SET estado=".$campo." $fecha_aprobado $fecha_rechazado  WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
  return $id;
 }
 
 function eliminar($id) {
  global $link;
  $q = "DELETE FROM referidos WHERE id='".$id."'";
  $r = @mysql_query($q,$link);
 }
 
 //////////////////////////////////////////////////////////////
 // EXTRAS
 //////////////////////////////////////////////////////////////  

} // end class
?>