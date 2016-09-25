<?php
header("X-Frame-Options: GOFORIT");
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Localizacion', 'Producto');
global $BackendUsuario, $Pedido, $Localizacion, $Producto;

global $link;

include("meta.php");
?>
</head>
<body>
<?php
$q = "SELECT * FROM pedidos ORDER BY id ";
$r = @mysql_query($q, $link);  
$f = @mysql_num_rows($r);
	
 for($i=0; $i < $f; $i++) {
	$items = @mysql_fetch_array($r); 	
    
     
    if($items['id'] > 138) { 
 	  	//$q2 = "UPDATE pedidos SET imagen_factura_g='".$items['imagen_dni_posterior_g']."', imagen_dni_frente_g='".$items['imagen_factura_g']."', imagen_dni_posterior_g='".$items['imagen_dni_frente_g']."' WHERE id='".$items['id']."' ";
 		 	//print $q2;
  		//@mysql_query($q2, $link);  
    }
 ?>
 

 <table border="1" width="1000" align="center" bgcolor="#ffffff">
 	<TR>
  <td width="20">Id</td>
  <td width="400">Nombre</td>
  <td width="100">DNI FRENTE:</td>
  <td width="100">DEN POSTERIOR</td>
  <td width="100">FACTURA</td>
 </TR> 
 	<TR>
 		<td><?=$items['id']?></td>
  <td><?=$items['cliente_nombre']?> <?=$items['cliente_apellido']?></td>
  <td><img src="../adj/pedidos/<?=$items['imagen_dni_frente_g']?>" width="100" height="100"></td>
  <td><img src="../adj/pedidos/<?=$items['imagen_dni_posterior_g']?>"  width="100" height="100"></td>
  <td><img src="../adj/pedidos/<?=$items['imagen_factura_g']?>"  width="100" height="100"></td>
 </TR> 
 </table> 

 <br><br>
<?php } ?>				
</body>	