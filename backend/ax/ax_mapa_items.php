<?php
// devuelve los destinos de nivel 0 desde global
header('Content-type: application/json');
include_once("../../config/conn.php");
loadClasses('BackendUsuario', 'Estacion');
global $BackendUsuario, $Estacion;

$accion  = (!isset($_REQUEST['accion'])) ? null : $_REQUEST['accion'];

$result = $Estacion->obtener_all(null, null, $palabra, $OrderBy, $filtro, 1, null, null);
$filas = @mysql_num_rows($result);

$valores = null;	
$i=0;

$arrJSON = array();
$objJSON = null;

if( $filas > 0)
{
   for($i=0; $i < $filas; $i++) {
		$dbresult = @mysql_fetch_array($result);

      if(strlen($dbresult['latitude']) > 5 && strlen($dbresult['longitude']) > 5) {

	      
      	  $arrJSON['id'][$i] = $dbresult['id'];
	  		  $arrJSON["nombre"][$i] = $dbresult['nombre'];
	  		  $arrJSON["latitude"][$i] = $dbresult['latitude'];
	  		  $arrJSON["longitude"][$i] = $dbresult['longitude'];
	  		  $arrJSON["direccion"][$i] = $dbresult['direccion'];

	  		  // cantidad
				  $arrJSON["cantidad"][$i] = $filas;
           
	  		  // extras

			}
 	}
	echo json_encode($arrJSON);
	exit;
}
?>

