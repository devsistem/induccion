
<?php
// _exportar.php
// 11/08/2015 6:59:47 PM
ob_start();


include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario', 'Foto', 'Producto', 'Pedido');
global $BackendUsuario, $Foto, $Producto, $Pedido;

$BackendUsuario->EstaLogeadoBackend();

$pedidos_idx = ($_POST['pedidos_idx']) ? $_POST['pedidos_idx'] : null;
$accion 		 = ($_POST['accion']) ? $_POST['accion'] : null;

// listad todos lo pedidos del estado 2
$result_todos = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, null, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, null, $pedidos_idx);
$filas_todos  = @mysql_num_rows($result_todos);

// ID	
// CEDULA O RUC	
// CARGO	
// APELLIDOS O RAZON SOCIAL	NOMBRES	CENTRO DE COSTO	
// PROVINCIA	
// CANTON O CIUDAD	
// PARROQUIA	
// CALLE PRIMARIA	NUMERO	
// CALLE SECUNDARIA	
// REFERENCIA	
// CODIGO POSTAL	
// TELEFONO 1	
// TELEFONO 2	
// CORREO ELECTRONICO	
// PRODUCTO	
// PESO	
// LARGO	
// ALTO 	
// ANCHO	
// NUMERO BULTOS	
// NUMERO CAJAS	
// NUMERO SOBRES	
// ITEM	
// CANTIDAD DE ITEM	
// VALOR ASEGURADO	
// DESCRIPCION CONTENIDO	
// CODIGO DE ADJUNTO	
// OBSERVACIONES	
// NUMERO DE GUIA	
// tranporte
// FACTIBILIDAD

			$shtml= "";
			$shtml=$shtml."<table cellspacing=0 cellpadding=0 border=1>";
			$shtml=$shtml."<tr>";
			$shtml=$shtml."<th width=50 align=left>Id</th>";
			$shtml=$shtml."<th width=100 align=left>CEDULA O RUC</th>";
			$shtml=$shtml."<th width=50 align=left>CARGO</th>";
			$shtml=$shtml."<th width=200 align=left>APELLIDOS O RAZON SOCIAL</th>";
			$shtml=$shtml."<th width=150 align=left>NOMBRES</th>";
			$shtml=$shtml."<th width=50 align=left>CENTRO COSTO</th>";
			$shtml=$shtml."<th width=100 align=left>PROVINCIA</th>";
			$shtml=$shtml."<th width=100 align=left>CANTON O CIUDAD</th>";
			$shtml=$shtml."<th width=100 align=left>PARROQUIA</th>";
			$shtml=$shtml."<th width=100 align=left>CALLE PRINCIPAL</th>";
			$shtml=$shtml."<th width=200 align=left>CALLE PRINCIPAL NUMERO</th>";
			$shtml=$shtml."<th width=100 align=left>CALLE SECUNDARIA</th>";
			$shtml=$shtml."<th width=100 align=left>REFERENCIA</th>";
			$shtml=$shtml."<th width=100 align=left>CODIGO POSTAL</th>";
			$shtml=$shtml."<th width=100 align=left>TELEFONO</th>";
			$shtml=$shtml."<th width=100 align=left>TELEFONO 2</th>";
			$shtml=$shtml."<th width=150 align=left>CORREO ELECTRONICO</th>";
			$shtml=$shtml."<th width=150 align=left>PRODUCTO</th>";
			$shtml=$shtml."<th width=50 align=left>PESO</th>";
			$shtml=$shtml."<th width=50 align=left>LARGO</th>";
			$shtml=$shtml."<th width=50 align=left>ALTO</th>";
			$shtml=$shtml."<th width=50 align=left>ANCHO</th>";
			$shtml=$shtml."<th width=150 align=left>NUMERO BULTOS</th>";
			$shtml=$shtml."<th width=50 align=left>NUMERO CAJAS</th>";
			$shtml=$shtml."<th width=50 align=left>NUMERO SOBRES</th>";
			$shtml=$shtml."<th width=50 align=left>ITEM</th>";
			$shtml=$shtml."<th width=250 align=left>CANTIDAD DE ITEM	</th>";
			$shtml=$shtml."<th width=150 align=left>VALOR ASEGURADO</th>";
			$shtml=$shtml."<th width=150 align=left>DESCRIPCION</th>";
			$shtml=$shtml."<th width=50 align=left> ADJUNTO</th>";
			$shtml=$shtml."<th width=200 align=left>OBSERVACIONES</th>";
			$shtml=$shtml."<th width=150 align=left>NUMERO GUIA</th>";
			$shtml=$shtml."<th width=150 align=left>TRANSPORTE</th>";
			$shtml=$shtml."<th width=150 align=left>FACTIBILIDAD</th>";
			
			$shtml=$shtml."</tr>";

		 for($i=0; $i < $filas_todos; $i++)  {
				$items = @mysql_fetch_array($result_todos);
			  $arrProducto = $items['id_producto'];
			  
			  $campo = "transporte_".$items['id'];
			  $transporte = $_POST[$campo];
			  
				if(strlen($items['id']) > 0) {		  
					$shtml=$shtml."<tr>";
					$shtml=$shtml."<td align=left>".$items['id']."</td>";
					$shtml=$shtml."<td align=center>".$items['cliente_dni']."</td>";
					$shtml=$shtml."<td align=left>0</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_apellido']."</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_nombre']."</td>";
					$shtml=$shtml."<td align=left>0</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_provincia']."</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_canton']."</td>";
					$shtml=$shtml."<td align=left>".$items['parroquia']."</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_calle']."</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_calle_numero']."</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_calle_secundaria']."</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_referencia']."</td>";
					$shtml=$shtml."<td align=left height=\"50\">0</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_telefono']."</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_celular']."</td>";
					$shtml=$shtml."<td align=left>".$items['cliente_email']."</td>";
					$shtml=$shtml."<td align=left>".$items['modelo'] . " - " . $items['marca']. " - " . $items['color']."</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left height=\"50\">1</td>";
					$shtml=$shtml."<td align=left>".$items['serie'] . " - " . $items['caracteristicas']."</td>";
					$shtml=$shtml."<td align=left height=\"50\"></td>";
					$shtml=$shtml."<td align=left>".$items['contenido']."</td>";
					$shtml=$shtml."<td align=left height=\"50\">0</td>";
					$shtml=$shtml."<td align=left height=\"50\">".$transporte."</td>";
					$shtml=$shtml."<td align=left height=\"50\"></td>";

					$shtml=$shtml." </tr>";
				  $shtml=$shtml."<tr><td colspan=22 bgcolor=#666666 height=1></td></tr>";
		  	}		
		 } 
		
				  $shtml=$shtml."</table>";
 
			//// EXPORTAR A EXCEL ////////////////////////////// 
			$fecha_hoy = date("d-m-Y");

			$scarpeta="../adj/pedidos"; 
			$Session = rand(10000,10000000);
			$sfile=$scarpeta."/pedidos-".$fecha_hoy.".xls"; //ruta del archivo a generar 
			$location="../adj/pedidos/pedidos-".$fecha_hoy.".xls"; //ruta del archivo a generar 
			$fp=fopen($sfile,"w"); 
			@fwrite($fp,$shtml); 
			@fclose($fp); 
			@header("Location: $location ");
			//borrar
			//unlink($sfile);
?>