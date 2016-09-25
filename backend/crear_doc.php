<?php
header('Content-type: application/vnd.ms-word');
header("Content-Disposition: attachment; filename=factura_000001.doc");
header("Pragma: no-cache");
header("Expires: 0");
header("X-Frame-Options: GOFORIT");

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
	
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Producto');
global $BackendUsuario, $Pedido, $Producto;

$id_pedido = ($_POST['id_pedido']) ? $_POST['id_pedido'] : null; // id pedido
$id_producto = ($_POST['id_producto']) ? $_POST['id_producto'] : null; // id pedido

$accion = ($_POST['accion']) ? $_POST['accion'] : null;


$arrPedido   = $Pedido->obtener($id_pedido);
$arrProducto = $Producto->obtener($id_producto);

?>

<table>
 <tr>
  <td>
  	<?=$arrPedido['cliente_nombre']?> <?=$arrPedido['cliente_apellido']?>
 	</td>
 </tr>	
 <tr>
  <td>
  	<?=$arrPedido['cliente_dni']?>
 	</td>
 </tr>	
 <tr>
  <td>
  	<?=$arrPedido['cliente_telefono']?>
 	</td>
 </tr>
</table>

<?php
/*
 <div id="divfactura" class="imprimirfactura">
	<div class="cliente"><?=$arrPedido['cliente_nombre']?> <?=$arrPedido['cliente_apellido']?></div>
	<div class="ci"><?=$arrPedido['cliente_dni']?></div>
	<div class="telefono"><?=$arrPedido['cliente_telefono']?></div>
	<div class="fecha"><?=$arrPedido['recepcion_confirmada_dia']?>/<?=$arrPedido['recepcion_confirmada_mes']?>/<?=$arrPedido['recepcion_confirmada_anio']?></div>
	<div class="direccion"><?=$arrPedido['cliente_direccion']?></div>
	<div class="cantidad1">1</div>
	<div class="detalle1"><?=$arrPedido['marca']?> <?=$arrPedido['modelo']?> <?=$arrPedido['color']?> SN: <?=$arrProducto['serie']?></div>
	<div class="valorunitario"><?=$arrProducto['precio_contado']?></div>
	<div class="valortotal"><?=$arrProducto['precio_contado']?></div>
	
	
	<div class="subtotal"> <?=$arrProducto['precio_contado']?></div>
	<div class="iva12">0</div>
	<div class="total"><?=$arrProducto['precio_contado']?></div>
</div>
<?php */ ?>