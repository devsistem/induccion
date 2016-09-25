<?php
// ax_cantones.json.php
// 15/09/2015 14:43:23
// trae listado de localidades dependiendo de la provincia
header("X-Frame-Options: GOFORIT");
require_once('config/conn.php');
declareRequest('accion','idx','idioma','ia','titulo','descripcion','contenido','id');
loadClasses('Pedido');

$serial  = (!isset($_REQUEST['serial'])) ? null :  $_REQUEST['serial'];

if(empty($serial)) {
 print "0";
 exit;
}

$cantidad = $Pedido->disponible_serial($serial);
print $cantidad;
exit;
?>