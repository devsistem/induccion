<?php
// _exportar.php
// 11/08/2015 6:59:47 PM
ob_start();
include_once("../config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario', 'Foto', 'Producto');
global $BackendUsuario, $Foto, $Producto;

$BackendUsuario->EstaLogeadoBackend();

$arrRubro[0] = "1";
$result_historial = $Producto->obtener_all($porPagina, $pagina, $palabra, $OrderBy, $filtro, null, null, null, null, $arrRubro);
$filas_historial = @mysql_num_rows($result_historial);

			$shtml= "";
			$shtml=$shtml."<table cellspacing=0 cellpadding=0 border=1>";
			$shtml=$shtml."<tr>";
			$shtml=$shtml."<th width=50 align=left>Id</th>";
			$shtml=$shtml."<th width=50 align=left></th>";
			$shtml=$shtml."<th width=50 align=left>Cliente</th>";
			$shtml=$shtml."<th width=200 align=left>Nombre</th>";
			$shtml=$shtml."<th width=150 align=left>Numero de Molde</th>";
			$shtml=$shtml."<th width=50 align=left>Foto Molde</th>";
			$shtml=$shtml."<th width=100 align=left>Material</th>";
			$shtml=$shtml."<th width=100 align=left>Volumen</th>";
			$shtml=$shtml."<th width=100 align=left>Volumen a Rebalse</th>";
			$shtml=$shtml."<th width=100 align=left>Peso</th>";
			$shtml=$shtml."<th width=100 align=left>Cantidad Hora</th>";
			$shtml=$shtml."<th width=100 align=left>Ancho mm</th>";
			$shtml=$shtml."<th width=100 align=left>Altura mm</th>";
			$shtml=$shtml."<th width=100 align=left>Espesor mm</th>";
			$shtml=$shtml."<th width=100 align=left>Diametro mm</th>";
			$shtml=$shtml."<th width=100 align=left>Ancho mm</th>";
			$shtml=$shtml."<th width=100 align=left>Altura mm</th>";
			$shtml=$shtml."<th width=100 align=left>Espesor mm</th>";
			$shtml=$shtml."<th width=100 align=left>Cantidad de Bocas</th>";
			$shtml=$shtml."<th width=100 align=left>Stock</th>";
			$shtml=$shtml."<th width=100 align=left>ACTUALES</th>";
			$shtml=$shtml."<th width=100 align=left>MP</th>";
			$shtml=$shtml."<th width=100 align=left>MO</th>";
			$shtml=$shtml."<th width=100 align=left>TOTAL</th>";
			$shtml=$shtml."</tr>";

		 for($i=0; $i < $filas_historial; $i++)  {
				$items = @mysql_fetch_array($result_historial);

				if(strlen($items['titulo']) > 0) {		  
					$shtml=$shtml."<tr>";
					$shtml=$shtml."<td align=left height=\"50\">".$items['id']."</td>";
					$shtml=$shtml."<td align=center height=\"50\"><img src=".URL_PATH."/adj/productos/".$items['imagen_th']."\" width=\"50\" height=\"50\"></td>";
					$shtml=$shtml."<td align=left height=\"50\">".$items['cliente']."</td>";
					$shtml=$shtml."<td align=left>".$items['titulo']."</td>";
					$shtml=$shtml."<td align=left>".$items['numero_molde']."</td>";
					if(strlen($items['imagen_molde']) > 0) {
					$shtml=$shtml."<td align=center height=\"50\"><img src=".URL_PATH."/adj/moldes/".$items['imagen_molde']."\" width=\"50\" height=\"50\"></td>";
					} else {
					$shtml=$shtml."<td align=center height=\"50\"></td>";
					}
					$shtml=$shtml."<td align=left>".$items['material']."</td>";
					$shtml=$shtml."<td align=left>".$items['volumen']."</td>";
					$shtml=$shtml."<td align=left>".$items['volumen_rebalse']."</td>";
					$shtml=$shtml."<td align=left>".$items['peso']."</td>";
					$shtml=$shtml."<td align=left>".$items['cantidad_hora']."</td>";
					$shtml=$shtml."<td align=left>".$items['envase_ancho']."</td>";
					$shtml=$shtml."<td align=left>".$items['envase_alto']."</td>";
					$shtml=$shtml."<td align=left>".$items['envase_espesor']."</td>";
					$shtml=$shtml."<td align=left>".$items['envase_diametro']."</td>";
					$shtml=$shtml."<td align=left>".$items['molde_ancho']."</td>";
					$shtml=$shtml."<td align=left>".$items['molde_alto']."</td>";
					$shtml=$shtml."<td align=left>".$items['molde_espesor']."</td>";
					$shtml=$shtml."<td align=left>".$items['molde_bocas']."</td>";
					$shtml=$shtml."<td align=left>".$items['stock']."</td>";
					$shtml=$shtml."<td align=left>".$items['precio_actual']."</td>";
					$shtml=$shtml."<td align=left>".$items['precio_mp']."</td>";
					$shtml=$shtml."<td align=left>".$items['precio_mo']."</td>";
					$shtml=$shtml."<td align=left>".$items['precio_final']."</td>";

					$shtml=$shtml." </tr>";
				  $shtml=$shtml."<tr><td colspan=22 bgcolor=#666666 height=1></td></tr>";
		  	}		
		 } 
		
				  $shtml=$shtml."</table>";
 
			//// EXPORTAR A EXCEL ////////////////////////////// 
			$fecha_hoy = date("d-m-Y");

			$scarpeta="../adj/xls"; 
			$Session = rand(10000,10000000);
			$sfile=$scarpeta."/productos-envases-".$fecha_hoy.".xls"; //ruta del archivo a generar 
			$location="../adj/xls/productos-envases-".$fecha_hoy.".xls"; //ruta del archivo a generar 
			$fp=fopen($sfile,"w"); 
			fwrite($fp,$shtml); 
			fclose($fp); 
			header("Location: $location ");
			//borrar
			//unlink($sfile);
?>