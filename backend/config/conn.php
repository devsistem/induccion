<?php
	@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	// siempre modificado
	@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	@header("Cache-Control: no-store, no-cache, must-revalidate");
	@header("Cache-Control: post-check=0, pre-check=0", false);
	// HTTP/1.0
	@header("Pragma: no-cache");
	@header('content-type: text/html; charset: utf-8');
  @session_start();
	require_once realpath(dirname(__FILE__)) . '/constants.php';
	
	
	// Funciones de Seguridad
	require_once FILE_PATH.'/include/funcSeguridad.php';  


	if(is_array($_GET) ){
	 foreach ($_GET as $_GET_nombre=>$_GET_contenido) {
 		$_GET[$_GET_nombre]= seguridad_x($_GET_contenido);
	 }
	}
	if(is_array($_POST) ) {
	foreach ($_POST as $_POST_nombre=>$_POST_contenido)	{
	 	$_GET[$_POST_nombre]= seguridad_x($_POST_contenido);
	 }
	}
	
   $_REQUEST['lang'] = null;
   $_REQUEST['lang'] = $_REQUEST['lang'];

	// Configuracion de funciones con datos de localización.
	setlocale(LC_TIME, 'es_ES');
	setlocale(LC_CTYPE, 'es_ES');

  // Idiomas
	if($_REQUEST['lang']) {
     switch($_REQUEST['lang']) 
     {
        case 'en':
        $Leng = 'lang/en.php';
        $_SESSION['lang'] = $Leng ;
        break;

        case 'pr':
        $Leng = 'lang/pt.php';
        $_SESSION['lang'] = $Leng ;
        break;
     
        case 'es':
        $Leng = 'lang/es.php';
        $_SESSION['lang'] = $Leng;
        break;
        
        default:
        $Leng = 'lang/en.php';
        $_SESSION['lang'] = $Leng;
        break;
     }
	} 
	else 
	{     if(isset($_SESSION['lang'])) {
        $Leng = $_SESSION['lang'];
        $_SESSION['lang'] = $_SESSION['lang'];
      	} else {
        $_SESSION['lang'] = 'lang/es.php';
        $Leng = $_SESSION['lang'];
      	}
   }

  // Carga idioma deo frontend

  $Leng = 'lang/es.php';
  include(FILE_PATH_FRONT.'/'.FRONTEND."/".$Leng);

  // Idioma del Admin
  include(FILE_PATH."/".CONF_ADMIN."/lenguaje/spanish.php"); 
  
	//Funciones Fechas
	require_once FILE_PATH.'/include/funcFecha.php';
	
	//Funciones CORE
	require_once FILE_PATH.'/include/funcCore.php';

	// Utilidades Generales
	require_once FILE_PATH.'/include/funcUtil.php';


	// Utilidades Parseo
	require_once FILE_PATH.'/include/funcParser.php';
  
	// Funciones de Imagen
	require_once FILE_PATH.'/include/funcImage.php';
  
  require_once FILE_PATH.'/include/clsJS.php';
  
  // Utilidades Form
	require_once FILE_PATH.'/include/funcForm.php';

	// Utilidades Parseo
	require_once FILE_PATH.'/include/funcFacebook.php';

	
	/**
	* Instanciar clases
	* Llamo a la funcion loadClasses y le paso los valores del array de classes como parametros.
	*/
	if(isset($arrClass)) call_user_func_array('loadClasses', $arrClass);
  
  // Conexion
  $dbuser		= DB_USER;
	$dbpass		= DB_PASS;
	$dbhost		= DB_HOST;
	$db			  = DB_NAME;

// Conectando, seleccionando la base de datos
  $link =mysql_connect($dbhost,$dbuser,$dbpass) or die('No se pudo conectar: ' . mysql_error());  
  mysql_select_db($db) or die('No se pudo seleccionar la base de datos'. mysql_error());

 
  mysql_query("SET NAMES 'utf8'");
  
  // Paginas
	$arrModulos = array(
     "admin_usuarios"  => "admin_usuarios.php",
     "admin_usuarios_backend"  => "admin_usuarios_backend.php",
     "admin_conf"  => "admin_conf.php",
     "admin_profile"  => "admin_profile.php",
     "admin_secciones"  => "admin_secciones.php",
     "admin_noticias"  => "admin_noticias.php",
 		 "admin_backup"  => "admin_backup.php",
 		 "admin_banners"  => "admin_banners.php",
 		 "admin_modulos"  => "admin_modulos.php",
 		 "admin_textos"  => "admin_textos.php",
 		 "admin_fotos"  => "admin_fotos.php",
 		 "admin_videos"  => "admin_videos.php",
 		 "admin_ip"  => "admin_ip.php",
 		 "admin_portada"  => "admin_portada.php",
 		 "admin_eventos"  => "admin_eventos.php",
 		 "admin_multimedia"  => "admin_multimedia.php",
 		 "admin_publicacion"  => "admin_publicacion.php",
 		 "admin_categorias"  => "admin_categorias.php",
     "portada"  => "portada.php",
     "index.php"  => "index.php",
     "login"  => "login.php",
     "ingreso"  => "login.php",
     "item"  => "item.php",
     "registro"  => "registro.php",
     "activaction"  => "activaction.php",
     "busqueda"  => "listados.php",
     "agregartour"  => "account_addtour.php",
     "cuenta_email"  => "account_email.php",
     "sitemap"  => "sitemap.php",
     "listados"  => "listados.php",
     "cuenta_mensajes"  => "account_messages.php"
   );
?>