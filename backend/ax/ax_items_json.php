<?php
// ax_localidades.json.php
// 22/04/2015 22:24:50
// trae listado de localidades dependiendo de la provincia
@header('Content-type: application/json');
require_once('../../config/conn.php');
declareRequest('accion','idx','idioma','ia','titulo','descripcion','contenido','id');
loadClasses('Localizacion','Item');

$accion  = (!isset($_REQUEST['accion'])) ? null : $_REQUEST['accion'];
$id_tipo_propiedad  = (!isset($_REQUEST['id_tipo_propiedad'])) ? 0 : $_REQUEST['id_tipo_propiedad'];
$id_tipo_operacion  = (!isset($_REQUEST['id_tipo_operacion'])) ? 0 : $_REQUEST['id_tipo_operacion']; // ID_PADRE 0 destinos RAIZ
$id_provincia  = (!isset($_REQUEST['id_provincia'])) ? 0 : $_REQUEST['id_provincia']; // ID_PADRE 0 destinos RAIZ
$id_localidad  = (!isset($_REQUEST['id_localidad'])) ? 0 : $_REQUEST['id_localidad']; // ID_PADRE 0 destinos RAIZ
$id_barrio  	 = (!isset($_REQUEST['id_barrio'])) ? 0 : $_REQUEST['id_barrio']; // ID_PADRE 0 destinos RAIZ

$result = $Item->obtener_all($porPagina, $paginacion, null, null, null, ACTIVO, ACTIVO, null, $id_tipo_propiedad, $id_tipo_operacion, $id_provincia, $id_localidad,$id_barrio);
$filas = @mysql_num_rows($result);
$arrJSON = array();

if($filas > 0) {
  for($i=0; $i < $filas; $i++) {
		$dbresult = @mysql_fetch_array($result);
 	  $arrJSON['id'][$i] = $dbresult['id'];
	  $arrJSON['titulo'][$i] = $dbresult['titulo'];
	  $arrJSON['cantidad'][$i] = $dbresult;
	  /*
	  $arrJSON['descripcion'][$i] = $items['descripcion'];
	  $arrJSON['codigo'][$i] = $items['codigo'];
	  $arrJSON['id_tipo'][$i] = $items['id_tipo'];
	  $arrJSON['id_tipo_operacion'][$i] = $items['id_tipo_operacion'];
	  $arrJSON['id_localidad'][$i] = $items['id_localidad'];
	  $arrJSON['id_barrio'][$i] = $items['id_barrio'];
	  $arrJSON['precio'][$i] = $items['precio'];
	  $arrJSON['precio_tipo'][$i] = $items['precio_tipo'];
	  $arrJSON['calle'][$i] = $items['calle'];
	  $arrJSON['calle_numero'][$i] = $items['calle_numero'];
	  $arrJSON['calle_piso'][$i] = $items['calle_piso'];
	  $arrJSON['calle_dto'][$i] = $items['calle_dto'];
	  $arrJSON['ambientes'][$i] = $items['ambientes'];
	  $arrJSON['condicion'][$i] = $items['condicion'];
	  $arrJSON['cantidad_de_banos'][$i] = $items['cantidad_de_banos'];
	  $arrJSON['cantidad_de_habitaciones'][$i] = $items['cantidad_de_habitaciones'];
	  $arrJSON['metros_interior'][$i] = $items['metros_interior'];
	  $arrJSON['latitude'][$i] = $items['latitude'];
	  $arrJSON['longitude'][$i] = $items['longitude'];
	  */

	}
		echo json_encode($arrJSON);
		exit;
}
?>