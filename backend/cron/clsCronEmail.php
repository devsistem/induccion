<?php
include_once("../include/clsPedido.php"); 

$Pedido=new Pedido();
echo"xxxx";

$r=$Pedido->obtener_by_fecha_sipec_vendedor(null, null, null,null,null,'PENDIENTE',null,'IGUAL');
$filas_pedidos = @mysql_num_rows($r);
echo "filas:".$filas_pedidos;
for($k=0; $k < $filas_pedidos; $k++) 
{
    $items_pedido = @mysql_fetch_array($r); 
    echo "<br>pedido:".$items_pedido["id"]."<br>";
}



?>