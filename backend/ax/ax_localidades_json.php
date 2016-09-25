<?php
// ax_localidades.json.php
// 22/04/2015 22:24:50
// trae listado de localidades dependiendo de la provincia
require_once('../../config/conn.php');
declareRequest('accion','idx','idioma','ia','titulo','descripcion','contenido','id');
loadClasses('Localizacion');

$accion  = (!isset($_REQUEST['accion'])) ? null : $_REQUEST['accion'];
$id_provincia  	   = (!isset($_REQUEST['id'])) ? 0 : $_REQUEST['id']; // ID_PADRE 0 destinos RAIZ
$destino_raiz = 0;

$result = $Localizacion->obtener_localidades(null,null, null, $OrderBy, null, 1, null, $id_partido, $id_provincia);
$filas = @mysql_num_rows($result);

$valores = null;	
$arrJSON = array();
$objJSON = null;

  for($i=0; $i < $filas; $i++) {
		$items = @mysql_fetch_array($result);
 	  $arrJSON['id'][$i] = $items['id'];
	  $arrJSON['nombre'][$i] = $items['nombre'];
	}
 	
 $arrJSON['cantidad'][0] = $filas;
 $objJSON = json_encode($arrJSON);
 echo $objJSON;
 exit;
?>