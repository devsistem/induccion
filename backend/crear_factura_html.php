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
	
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Producto');
global $BackendUsuario, $Pedido, $Producto;

$id_pedido = ($_POST['id_pedido']) ? $_POST['id_pedido'] : null; // id pedido
$id_producto = ($_POST['id_producto']) ? $_POST['id_producto'] : null; // id pedido

$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$arrPedido   = $Pedido->obtener($id_pedido);
$arrProducto = $Producto->obtener($id_producto);

$precio_con_iva = $arrProducto['precio_contado'];
$iva = "";
?>

<style type="text/css">
.cliente {
	
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 70mm;
	top: 40mm;
	left: 25mm;
	position: absolute;
}
.ci {
	
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 70mm;
	top: 48mm;
	left: 25mm;
	position: absolute;
}
.telefono {
	
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 32mm;
	top: 50mm;
	left: 110mm;
	position: absolute;
}
.fecha {
	
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 32mm;
	top: 40mm;
	left: 110mm;
	position: absolute;
}
.direccion{
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 70mm;
	top: 54mm;
	left: 25mm;
	position: absolute;
}

.cantidad1{
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 5mm;
	top: 80mm;
	left: 10mm;
	position: absolute;
	text-align:center;
}

.detalle1{
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 68mm;
	top: 80mm;
	left: 23mm;
	position: absolute;
	text-align:left;
}

.valorunitario{
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 21mm;
	top: 80mm;
	left: 91mm;
	position: absolute;
	text-align:center;
}

.valortotal{
	
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 21mm;
	top: 80mm;
	left: 112mm;
	position: absolute;
	text-align:center;
}
.subtotal{
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 21mm;
	top: 175mm;
	left: 112mm;
	position: absolute;
	text-align:center;
}

.iva12{
	color: #000;
	height: 7mm;
	width: 21mm;
	top: 185mm;
	left: 112mm;
	position: absolute;
	text-align:center;
}
.total{
	
	font-size: 4mm;
	color: #000;
	height: 7mm;
	width: 21mm;
	top: 190mm;
	left: 112mm;
	position: absolute;
	text-align:center;
}
</style>

<div class="cliente"><?=$arrPedido['cliente_nombre']?> <?=$arrPedido['cliente_apellido']?></div>
<div class="ci"><?=$arrPedido['cliente_dni']?></div>
<div class="telefono"><?=$arrPedido['cliente_telefono']?></div>
<div class="fecha"><?=$arrPedido['recepcion_confirmada_dia']?>/<?=$arrPedido['recepcion_confirmada_mes']?>/<?=$arrPedido['recepcion_confirmada_anio']?></div>
<div class="direccion"><?=$arrPedido['cliente_calle']?> <?=$arrPedido['cliente_numero']?></div>

<!-- aqui hay que programar para que las distancias no sean mayores y que la información siga para abajo por ejemplo si tenemos mas de un item -->
<div class="cantidad1">1</div>
<div class="detalle1"><?=$arrPedido['marca']?> <?=$arrPedido['modelo']?> <?=$arrPedido['color']?> SN: <?=$arrProducto['serie']?></div>
<div class="valorunitario"><?=$arrProducto['precio_contado']?>,00</div>
<div class="valortotal"><?=$arrProducto['precio_contado']?>,00</div>


<div class="subtotal"> <?=$arrProducto['precio_contado']?>,00</div>
<div class="iva12"><?=$iva?></div>
<div class="total"><?=$precio_con_iva?>,00</div>

<?php /* ?>
<table border="0" width="700" cellpadding="4">
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="60">&nbsp;</td>
		<td width="210"><?=$arrPedido['cliente_nombre']?> <?=$arrPedido['cliente_apellido']?></td>
		<td width="50">&nbsp;</td>
		<td width="50">&nbsp;</td>
		<td width="50">&nbsp;</td>
		<td width="90" align="left"><?=$arrPedido['recepcion_confirmada_dia']?>/<?=$arrPedido['recepcion_confirmada_mes']?>/<?=$arrPedido['recepcion_confirmada_anio']?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td></td>
		<td><?=$arrPedido['cliente_canton']?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left"><?=$arrPedido['cliente_dni']?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="left"><?=$arrPedido['cliente_telefono']?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left"><?=$arrPedido['cliente_calle']?> <?=$arrPedido['cliente_numero']?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="left"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left"></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>1</td>
		<td align="left"><?=$arrPedido['marca']?> <?=$arrPedido['modelo']?> <?=$arrPedido['color']?> SN: <?=$arrProducto['serie']?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?=$arrProducto['precio_contado']?>,00</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?=$arrProducto['precio_contado']?>,00</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?=$arrProducto['precio_contado']?>,00</td>
	</tr>
</table>
<?php */ ?>