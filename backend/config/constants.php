<?php
	define("dbi_DEBUG_MODE",0);	
	if( dbi_DEBUG_MODE )
	{
		ini_set("display_errors",1);
		error_reporting(E_ALL);
	}
	else					  
	{
		ini_set("display_errors",0);
		error_reporting(E_NONE);
	}

	define("TIENE_LOGEO",false);
	define("TIENE_PUBLICACION",true);
	define("TIENE_ROLES",false);
	define("TIENE_PERMISOS_USUARIO",false);
	define("TIENE_TIPOS_USUARIO",false); // administrador / publicador / lector

	define("TIPOS_USUARIO_ADMINISTRADOR",'administrador');
	define("TIPOS_USUARIO_PUBLICADOR",'publicador');
	define("TIPOS_USUARIO_LECTOR",'lector');
	define("TIPOS_USUARIO_ROOT",'root');
				
	define('SERVER', $_SERVER['SERVER_NAME']);
	//define('DOMINIO','induccion.ec');
  define('DOMINIO','127.0.0.1');
	define('FRONTEND','cocinas');
		
	// Estructura de directorios
	//define('FILE_PATH_ROOT', 'C:/xampp/htdocs/induccion/backend');

  define('FILE_PATH_ROOT', 'C:/xampp/htdocs/induccion');
	define('URL_PATH_ROOT',  'http://localhost/induccion/backend');



	
 	define('WS_BASE', '/backend');
	define('FILE_PATH', FILE_PATH_ROOT.'/backend');
	define('URL_PATH', URL_PATH_ROOT.'/backend');
	define('URL_PATH_FRONT', URL_PATH_ROOT.'/frontend/'.FRONTEND);
	define('FILE_PATH_FRONT', FILE_PATH_ROOT.'/frontend');
	define('FILE_PATH_FRONT_ADJ',  FILE_PATH_ROOT.'/backend/adj');
	define('URL_PATH_FRONT_ADJ',  URL_PATH_ROOT.'/adj');
	
  define('DIR_FS_BACKUP', FILE_PATH_ROOT.'/backend/adj/');
  define('TEMPLATE', '2015');
  define('TEMPLATE_ADMIN', 'default');
	define('URL_TEMPLATE', URL_PATH.'/templates/'.TEMPLATE);

  define('CONF', 1);  
  define('CONF_ADMIN', 'admin');
    
  // Base de Datos 
	define('DB_TYPE', 'mysql');
	define('DB_PREFIX', '_');
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	define('DB_NAME', 'induccion');
	define('DB_PCONNECT', '0');

	// Global Servidor
  // Mails
	define('MAIL_SYSTEM'	 , 'mail.induccion.ec');
	define('MAIL_FROM'		 , 'Induccion.ec');
	define('MAIL_TO'			 , 'info@localhost');
	define('MAIL_FROM_NAME', 'induccion.ec');
	define('MAIL_SMTP'		 , 'mail.induccion.ec');
	define('MAIL_SUPPORT'	 , 'desarrollo@interlogical.net');

	// Parametros de session
	define('SESSION_COOKIE_LIFETIME', 3600);
	define('SESSION_COOKIE_PATH', WS_BASE);
	define('SESSION_COOKIE_DOMAIN', SERVER);
	define('SESSION_TIMEOUT_ALERT', SESSION_COOKIE_LIFETIME - 10);
	
  // Tablas
  define('DBSYS_BACKENDUSER', 			 'sys_backendusuario');
  define('DBSYS_BACKENDUSER_GROUPS', 'sys_backenduser_gropus');
  define('DBSYS_COUNTRIES', 				 'sys_countries'); 
  define('DBSYS_USERS', 						 'sys_users');
  define('DBSYS_CONF', 						 	 'sys_conf');
  define('DBSYS_BACKUP', 						 'sys_backup');
  define('DBSYS_NEWS', 						 	 'news');
  
  // Facebook Connect
  define('CONF_FB_API', "");
  define('CONF_FB_SECRET', "");
  
  // Facebook Publish
  define('CONF_FB_FUNPAGE','247922008633091');
  define('CONF_FB_APP_API', "226687190786410"); 
  define('CONF_FB_APP_SECRET', "fc0614b58db4f2218dcbe33f3749a464");
  define('FACEBOOK_SDK_V4_SRC_DIR',FILE_PATH.'/api/facebook4/src');

  // Estado de los contenidos
  define('PENDIENTE', 0);
  define('PUBLICADO', 1);
  define('EN_PAPELERA', 1);
  define('FUERA_PAPELERA', 0);
  define('ACTIVO', 1);

  // Configuracion Actual
  define('ADMIN_CONF',1);
  
  // Configuracion de objetos
  define('CONF_TIENE_404',false);
  define('CONF_TIENE_REGISTRO',true);
  define('CONF_TIENE_LOGIN',true);
  define('CONF_TIENE_COMENTARIOS',true);
  define('CONF_TIENE_JQUERY',true);
  define('CONF_TIENE_MOOTOOLS',false);
  define('CONF_TIENE_FORO',false);
  define('CONF_TIENE_FLICKR',false);
  define('CONF_TIENE_GOOGLE_PLUS',false);
  
  define('CONF_TIENE_FB_LIKE',false);
  define('CONF_TIENE_FB_CONNECT',false);
  define('CONF_TIENE_FB_SHARE',false);
  define('CONF_TIENE_TW_CONNECT',false);
  define('CONF_TIENE_GMAPS',true);
  define('CONF_TIENE_ADSENSE',true);
  define('CONF_TIENE_FAVORITOS',false);
  define('CONF_REGISTRO_TIPO','DIV'); // pagina - div - popup
  define('CONF_LOGIN_TIPO','DIV');
  define('CONF_REGISTRO_MAILACTIVACION',false);

  // Mensajes, pasar a languajes
  define('MSG_UPDATE',1);
  define('MSG_ERROR'	 ,2);
  define('MSG_ERROR_LOGIN',3);

  // Idiomas
  define('ESPANOL', 'es');

  // Facebook Connect
  define('CONF_FB_API', "");
  define('CONF_FB_SECRET', "");
  
  // MailChimp 
  define('CONF_MAILCH_API', "");
  define('CONF_MAILCH_KEY', "");

  // MailGun 
  define('CONF_MAILGUN_API', "");
  define('CONF_MAILGUN_KEY', "");
  define('CONF_MAILGUN_SANDBOX', "");
  define('CONF_MAILGUN_NAME', "");
  define('CONF_MAILGUN_MAIL_FROM', "");
    
  // NO EDITABLE  ////////////////////////////////////////////////////////////
  define('FS_ADMIN_PATH',    FILE_PATH.'/'.CONF_ADMIN);
  define('FS_TEMPLATES',     FILE_PATH.'/templates');
  define('FS_PAGINAS',       FILE_PATH.'/paginas/');
  define('FSA_MODULOS',      FILE_PATH.'/'.CONF_ADMIN.'/modulos/');
  /////////////////////////////////////////////////////////////////////////////
?>