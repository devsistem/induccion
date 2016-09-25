<?php
ob_start();
include_once("../config/conn.php");
require("lib/mailchimp/src/mandril.php"); 

declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Incidencia', 'Localizacion');
global $BackendUsuario, $Pedido, $Incidencia, $Localizacion;

//$BackendUsuario->EstaLogeadoBackend();

$r=$Pedido->obtener_by_fecha_sipec_vendedor(null, null, null,null,null,'PENDIENTE',null,'IGUAL');
echo "......xxxxxxxxxx..".$r;
$filas_pedidos = @mysql_num_rows($r);
echo "filas:".$filas_pedidos;
for($k=0; $k < $filas_pedidos; $k++) 
{
    $items_pedido = @mysql_fetch_array($r); 
   // echo "<br>pedido:".$items_pedido["id"]."<br>";
}

try {
echo "inicio json";


 
?>

?>