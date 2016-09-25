<?php
 // ax_publicacion.json.php
 // 07/08/2015 6:29:26 PM
 @header('Content-type: application/json');
 require_once('../config/conn.php');
 declareRequest('accion','idx','idioma','ia','titulo','descripcion','contenido','id');
 loadClasses('Publicacion');
 
 $accion  = (!isset($_REQUEST['accion'])) ? null : $_REQUEST['accion'];
 $id 	    = (!isset($_REQUEST['id'])) ? 0 : $_REQUEST['id']; 
 
 $dbresult = $Publicacion->obtener($id);
 $arrJSON['id'][0] = $dbresult['id'];
 $arrJSON['titulo'][0] = $dbresult['titulo'];
 $arrJSON['descripcion'][0] = $dbresult['descripcion'];
 $arrJSON['contenido'][0] = $dbresult['contenido'];
 $arrJSON['activo'][0] = $dbresult['activo'];
 echo json_encode($arrJSON);
 exit;

?>