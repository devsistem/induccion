<?php
// ax_modelos.json.php
// 15/09/2015 14:43:23
// trae listado de localidades dependiendo de la provincia
header("X-Frame-Options: GOFORIT");
require_once('config/conn.php');
declareRequest('accion','idx','idioma','ia','titulo','descripcion','contenido','id');
loadClasses('Producto');

$accion   = (!isset($_REQUEST['accion'])) ? null : $_REQUEST['accion'];
$id_marca = (!isset($_REQUEST['id_marca'])) ? 0 : $_REQUEST['id_marca']; // id_marca
$tipo = (!isset($_REQUEST['tipo'])) ? 'cocina' : $_REQUEST['tipo']; // id_marca


$destino_raiz = 0;

if($id_marca > 0) {
	
$result = $Producto->obtener_modelos(ACTIVO, $tipo, $id_marca);
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
}
?>