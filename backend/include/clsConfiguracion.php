<?php
	/**
	* @name Class Configuracion
	* @package SQLServer
	* @subpackage Administración
	*/
class Configuracion  {
	
function editar($id, $campos=null) {
	global $link;
 	
	$twitter = escapeSQLFull($campos['configuracion']['twitter']);
  $facebook = escapeSQLFull($campos['configuracion']['facebook']);
  $googleplus = escapeSQLFull($campos['configuracion']['googleplus']);
  $instagram = escapeSQLFull($campos['configuracion']['instagram']);
  $youtube = escapeSQLFull($campos['configuracion']['youtube']);
  
  $google_pw = escapeSQLFull($campos['configuracion']['google_pw']);
	$twitter_pw = escapeSQLFull($campos['configuracion']['twitter_pw']);
  $facebook_pw = escapeSQLFull($campos['configuracion']['facebook_pw']);

	$serv_carpeta_temporal = escapeSQLFull($campos['configuracion']['serv_carpeta_temporal']);
	$serv_ssl = escapeSQLFull($campos['configuracion']['serv_ssl']);
	$serv_zona_horaria = escapeSQLFull($campos['configuracion']['serv_zona_horaria']);
	$bd_tipo = escapeSQLFull($campos['configuracion']['bd_tipo']);
	$bd_servidor = escapeSQLFull($campos['configuracion']['bd_servidor']);
	$bd_puerto = escapeSQLFull($campos['configuracion']['bd_puerto']);
	$bd_usuario = escapeSQLFull($campos['configuracion']['bd_usuario']);
	$bd_clave = escapeSQLFull($campos['configuracion']['bd_clave']);
	$ftp_habilitar = escapeSQLFull($campos['configuracion']['ftp_habilitar']);
	$ftp_servidor = escapeSQLFull($campos['configuracion']['ftp_servidor']);
	$ftp_puerto = escapeSQLFull($campos['configuracion']['ftp_puerto']);
	$ftp_usuario = escapeSQLFull($campos['configuracion']['ftp_usuario']);
	$ftp_clave = escapeSQLFull($campos['configuracion']['ftp_clave']);
	$ftp_carpeta = escapeSQLFull($campos['configuracion']['ftp_carpeta']);
	$mail_info = escapeSQLFull($campos['configuracion']['mail_info']);
	$mail_cuenta = escapeSQLFull($campos['configuracion']['mail_cuenta']);
	$mail_remitente = escapeSQLFull($campos['configuracion']['mail_remitente']);
	$mail_sendmail = escapeSQLFull($campos['configuracion']['mail_sendmail']);
	$mail_auth_smtp = escapeSQLFull($campos['configuracion']['mail_auth_smtp']);
	$mail_seguridad_smtp = escapeSQLFull($campos['configuracion']['mail_seguridad_smtp']);
	$mail_puerto_smtp = escapeSQLFull($campos['configuracion']['mail_puerto_smtp']);
	$mail_usuario_smtp = escapeSQLFull($campos['configuracion']['mail_usuario_smtp']);
	$mail_clave_smtp = escapeSQLFull($campos['configuracion']['mail_clave_smtp']);
	$mail_hospedaje_smtp = escapeSQLFull($campos['configuracion']['mail_hospedaje_smtp']);
 	
	$q = "UPDATE sys_conf SET  twitter='".$twitter."', facebook = '".$facebook."' , instagram='".$instagram."', youtube = '".$youtube."', googleplus = '".$googleplus."',  mail_info = '".$mail_info."', mail_admin = '".$mail_admin."', mail_contacto = '".$mail_contacto."', fecha_mod = NOW()  WHERE id='".$id."' "; 
  return @mysql_query($q,$link);
}

function obtener($id)	{
 global $link;
 $q = "SELECT c.* FROM sys_conf AS c WHERE id='".$id."' LIMIT 1";
 $r = @mysql_query($q,$link);
 $a = @mysql_fetch_array($r);
 return $a;
}

////////// Sitemap /////////////////////

function crear_sitemap() {
 loadClasses('Item','BaseGlobal', 'ItemCampos', 'Guia', 'Noticia', 'Pagina');  
 global $Item, $BaseGlobal, $ItemCampos, $Guia, $Noticia, $Pagina;
 global $link;
 $URL_PATH = "http://www.theviajeros.com";
 
 $xml = '';
 $xml  = "<?xml version='1.0' encoding='UTF-8'?>";
 $xml .= '<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
      http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';  
      
  // Guias
  $result_guias = $Guia->global_obtener_all(null,null,null,null,null,ACTIVO,null);
	$filas_guias = @mysql_num_rows($result_guias);

	for($i=0; $i < $filas_guias; $i++) {
		 $items = @mysql_fetch_array($result_guias);
		 
		 if($items['tipo'] == 1) {
		   $seccion = "guia-pais";
		 // guia de ciudad  
		 } else if($items['tipo'] == 2) {
		   $seccion = "guia-ciudad";
		 // perronalizada  
		 } else if($items['tipo'] == 3) {
		   $seccion = "guia-personalizada";
		 }
		 $titulo_permanent = permanent_link($items['nombre']);
		 
		 $xml .= '
			<url>
				<loc>'.$URL_PATH.'/'.$seccion.'/'.$items['id'].'/0/0/'.$items['id_destino'].'/es/'.$titulo_permanent.'</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';
		}
  
  // Paginas
  $ResultPaginas = $Pagina->obtener_all(null, null, 1, null, null, null, null, null, null, null, null);
  $FilasPaginas = @mysql_num_rows($ResultPaginas);

  for($i=0; $i < $FilasPaginas; $i++) {
		 $itemsPaginas = @mysql_fetch_array($ResultPaginas);
		 $permalink = permanent_link(utf8_encode($itemsPaginas['titulo']));
		 $seccion = $itemsPaginas['clave'];

		 $xml .= '
			<url>
				<loc>'.$URL_PATH.'/'.$seccion.'/'.$itemsPaginas['id'].'/0/0/0/es/'.$permalink.'</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';
	
	}
  
  // Noticias
  $ResultNoticias = $Noticia->obtener_all(null,null,null,null,null,1,null,null,null, null, null, null);
  $FilasNoticias = @mysql_num_rows($ResultNoticias);

  for($i=0; $i < $FilasNoticias; $i++) {
		 $itemsNoticias = @mysql_fetch_array($ResultNoticias);
		 $permalink = permanent_link(utf8_encode($itemsNoticias['titulo']));
		 $seccion = "nota";

		 $xml .= '
			<url>
				<loc>'.$URL_PATH.'/'.$seccion.'/'.$itemsNoticias['id'].'/0/0/0/es/'.$permalink.'</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';
	
	}
	  
  // Destinos    
  $ResultDestinos = $BaseGlobal->obtener_all(null, null, 1,  " ORDER BY DES.nombre ", null, null, null, null, null);
  $FilasDestinos = @mysql_num_rows($ResultDestinos);

  for($i=0; $i < $FilasDestinos; $i++) {
		 $itemsDestinos = @mysql_fetch_array($ResultDestinos);
		 $permalink = permanent_link(utf8_decode($itemsDestinos['nombre']));
		 $seccion = "item-destino";
		 
		 if($itemsDestinos['id_padre'] > 0) {
		 $xml .= '
			<url>
				<loc>'.$URL_PATH.'/'.$seccion.'/'.$itemsDestinos['id'].'/0/0/0/es/'.$permalink.'</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';
	   }
	}
 
  // Paginas Estaticas

  $xml .= '
			<url>
				<loc>'.$URL_PATH.'/blog</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';

  $xml .= '
			<url>
				<loc>'.$URL_PATH.'/guias</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';


  $xml .= '
			<url>
				<loc>'.$URL_PATH.'/plandeviaje.php</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';

  $xml .= '
			<url>
				<loc>'.$URL_PATH.'/como-hacer</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';
			
  $xml .= '
			<url>
				<loc>'.$URL_PATH.'/legal</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';
			
  $xml .= '
			<url>
				<loc>'.$URL_PATH.'/apps</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';			

  $xml .= '
			<url>
				<loc>'.$URL_PATH.'/theviajeros</loc>
				<changefreq>always</changefreq>
		 		<priority>0.5</priority>
			</url>';			
			
  $xml .= '</urlset>';

  unlink(FILE_PATH."/sitemap.xml");
  $archivo = FILE_PATH."/sitemap.xml";
  $fp = fopen($archivo, "a");
  $write = fputs($fp, $xml);
  fclose($fp);        
}

 function agregarBackup() {
  require_once('clsMailer.php');
 	global $link; 
  		
        $backup_file = 'db_' . DB_NAME . '-' . date('YmdHis') . '.sql';
        
        $fp = fopen(DIR_FS_BACKUP . $backup_file, 'w');

        $schema = '#  INDUCCION ' . "\n" .
                  '# ' . "\n" .
                  '#' . "\n" .
                  '# Copyright (c) ' . date('Y') . ' ' . '' . "\n" .
                  '#' . "\n" .
                  '# Database: ' . DB_NAME . "\n" .
                  '# Database Server: ' . DB_HOST . "\n" .
                  '#' . "\n" .
                  '# Backup Date: ' . date(PHP_DATE_TIME_FORMAT) . "\n\n";
        fputs($fp, $schema);

        $tables_query = tep_db_query('SHOW TABLES  WHERE Tables_in_induccio_tienda  != "sys_backendusuario_perfiles"');
        while ($tables = tep_db_fetch_array($tables_query)) {
          list(,$table) = each($tables);


          $schema = 'drop table if exists ' . $table . ';' . "\n" .
                    'create table ' . $table . ' (' . "\n";

          $table_list = array();
          $fields_query = tep_db_query("show fields from " . $table);
          
          
          while ($fields = tep_db_fetch_array($fields_query)) {
            $table_list[] = $fields['Field'];

            $schema .= '  ' . $fields['Field'] . ' ' . $fields['Type'];

            if (strlen($fields['Default']) > 0) $schema .= ' default \'' . $fields['Default'] . '\'';

            if ($fields['Null'] != 'YES') $schema .= ' not null';

            if (isset($fields['Extra'])) $schema .= ' ' . $fields['Extra'];

            $schema .= ',' . "\n";
          }

          $schema = ereg_replace(",\n$", '', $schema);

					// add the keys
          $index = array();
          $keys_query = tep_db_query("show keys from " . $table);
          while ($keys = tep_db_fetch_array($keys_query)) {
            $kname = $keys['Key_name'];

            if (!isset($index[$kname])) {
              $index[$kname] = array('unique' => !$keys['Non_unique'],
                                     'columns' => array());
            }

            $index[$kname]['columns'][] = $keys['Column_name'];
          }

          while (list($kname, $info) = each($index)) {
            $schema .= ',' . "\n";

            $columns = implode($info['columns'], ', ');

            if ($kname == 'PRIMARY') {
              $schema .= '  PRIMARY KEY (' . $columns . ')';
            } elseif ($info['unique']) {
              $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
            } else {
              $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
            }
          }

          $schema .= "\n" . ');' . "\n\n";
          }
          fputs($fp, $schema);

					// dump the data
          $rows_query = tep_db_query("select " . implode(',', $table_list) . " from " . $table);
          while ($rows = tep_db_fetch_array($rows_query)) {
            $schema = 'insert into ' . $table . ' (' . implode(', ', $table_list) . ') values (';

            reset($table_list);
            while (list(,$i) = each($table_list)) {
              if (!isset($rows[$i])) {
                $schema .= 'NULL, ';
              } elseif ($rows[$i]!=null) {
                $row = addslashes($rows[$i]);
                $row = ereg_replace("\n#", "\n".'\#', $row);

                $schema .= '\'' . $row . '\', ';
              } else {
                $schema .= '\'\', ';
              }
            }

            $schema = ereg_replace(', $', '', $schema) . ');' . "\n";
            fputs($fp, $schema);

       
        }

        fclose($fp);

        if (isset($HTTP_POST_VARS['download']) && ($HTTP_POST_VARS['download'] == 'yes')) {
          switch ($HTTP_POST_VARS['compress']) {
            case 'gzip':
              exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
              $backup_file .= '.gz';
              break;
            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
              $backup_file .= '.zip';
          }
          header('Content-type: application/x-octet-stream');
          header('Content-disposition: attachment; filename=' . $backup_file);

          readfile(DIR_FS_BACKUP . $backup_file);
          unlink(DIR_FS_BACKUP . $backup_file);

          exit;
        } else {
          switch ($HTTP_POST_VARS['compress']) {
            case 'gzip':
              exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
              break;
            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
          }

          //$messageStack->add_session(SUCCESS_DATABASE_SAVED, 'success');
        }
        
        // enviar por email y elimiar los files
        $adjunto = DIR_FS_BACKUP . $backup_file;
       
        $Mailer = new phpmailer();
				$Mailer->Host     = MAIL_SMTP; // SMTP servers
				$Mailer->Mailer   = "mail";
			 	$Mailer->From     = 'info@induccion.ec';
				$Mailer->FromName = "Induccion - MySql Backup";
				$Mailer->AddAddress("desarrollo@interlogical.net"); 
				$Mailer->IsHTML(true); 
				$Mailer->Subject  =  " Backup de Induccion";
				$Mailer->AddAttachment($adjunto);
				 
				// se envio el email
				$resultado = $Mailer->Send();
				print "---" . $resultado;
				//unlink($adjunto); 
   } 
  
  
  function enviarErrorSoporte($error) {
	  require_once(FILE_PATH.'/include/clsMailer.php');  	
 		global $link; 
  
    $Mailer = new phpmailer();
		$Mailer->Host     = MAIL_SMTP; // SMTP servers
		$Mailer->Mailer   = "mail";
		$Mailer->From     = 'noresponder@telefonica.com';
		$Mailer->FromName = "Movistar ONE";
		$Mailer->AddAddress("desarrollo@interlogical.net"); 
		$Mailer->IsHTML(true); 
		$Mailer->Subject  =  $error;
		 
		// se envio el email
		$Mailer->Send();
  
  }
  
   function enviarMensajeSoporte($mensaje) {
	  require_once(FILE_PATH.'/include/clsMailer.php');  	
 		global $link; 
  
    $Mailer = new phpmailer();
		$Mailer->Host     = MAIL_SMTP; // SMTP servers
		$Mailer->Mailer   = "mail";
		$Mailer->From     = 'noresponder@telefonica.com';
		$Mailer->FromName = "Movistar ONE";
		$Mailer->AddAddress("desarrollo@interlogical.net;smartinez@cuoma.com"); 
		$Mailer->IsHTML(true); 
		$Mailer->Subject  =  $mensaje;
		 
		// se envio el email
		$Mailer->Send();
  }

function obtenerBackups() {
 global $link; 
 $q = "SELECT B.* FROM ".DBSYS_BACKUP."  AS B "
			   . "ORDER BY B.backup_id ASC";
 $r = @mysql_query($q,$link);
 return $r;	
}

function eliminarBackup($Idx) {
 global $link;
 $q  = "DELETE FROM  ".DBSYS_BACKUP."  WHERE backup_id=".$Idx."";
 @mysql_query($q,$link);	 
	
 // eliminar fisicamente el archivo .sql
 $q = "SELECT B.* FROM ".DBSYS_BACKUP."  AS B WHERE B.backup_id=".$Idx." LIMIT 1";
 $r = @mysql_query($q,$link);
 $a    = @mysql_fetch_array($r);		
 @unlink(FILE_PATH . "/adjuntos/" . $a[2]);
}
function restaurar($Idx) {
 	require_once('database.php');
  global $link; 
		
	$q = "SELECT B.* FROM ".DBSYS_BACKUP."  AS B WHERE B.backup_id=".$Idx." LIMIT 1";
	$r = @mysql_query($q,$link);
  $arr    = @mysql_fetch_array($r);		
  
  $FileSQL = FILE_PATH . "/adjuntos/" . $arr[2];
  $read_from = $arr[2];
}
} // end class
?>