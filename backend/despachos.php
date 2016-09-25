<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Despacho');
global $BackendUsuario, $Pedido, $Despacho;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;


// fecha y dia actual
$dia_actual  = date("d");
$mes_actual  = date("m");
$anio_actual = date("Y");

$dia_actual = ($_POST['dia_actual']) ? $_POST['dia_actual'] : $dia_actual;
$mes_actual = ($_POST['mes_actual']) ? $_POST['mes_actual'] : $mes_actual;
$anio_actual = ($_POST['anio_actual']) ? $_POST['anio_actual'] : $anio_actual;

$fecha_completa = $dia_actual."/".$mes_actual."/".$anio_actual;

switch ($accion) {
  
  case 'despacho':
  
  	$items = $Pedido->obtener($id);


			// crea la factura
			// CEDULA O RUC	CARGO	APELLIDOS O RAZON SOCIAL	NOMBRES	CENTRO DE COSTO	PROVINCIA	CANTON O CIUDAD	PARROQUIA	CALLE PRIMARIA	NUMERO	CALLE SECUNDARIA	REFERENCIA	CODIGO POSTAL	TELEFONO 1	TELEFONO 2	CORREO ELECTRONICO	PRODUCTO	PESO	LARGO	ALTO 	ANCHO	NUMERO BULTOS	NUMERO CAJAS	NUMERO SOBRES	ITEM	CANTIDAD DE ITEM	VALOR ASEGURADO	DESCRIPCION CONTENIDO	CODIGO DE ADJUNTO	OBSERVACIONES	NUMERO DE GUIA
			$shtml= "";
			$shtml=$shtml."<table cellspacing=0 cellpadding=0 border=1>";
			$shtml=$shtml."<tr>";
			$shtml=$shtml."<th width=50 align=left>CEDULA O RUC</th>";
			$shtml=$shtml."<th width=50 align=left>CARGO</th>";
			$shtml=$shtml."<th width=150 align=left>APELLIDOS O RAZON SOCIAL</th>";
			$shtml=$shtml."<th width=200 align=left>NOMBRES</th>";
			$shtml=$shtml."<th width=150 align=left>CENTRO DE COSTO</th>";
			$shtml=$shtml."<th width=50 align=left>PROVINCIA</th>";
			$shtml=$shtml."<th width=100 align=left>CANTON</th>";
			$shtml=$shtml."<th width=100 align=left>PARROQUIA</th>";
			$shtml=$shtml."<th width=100 align=left>CALLE PRIMARIA</th>";
			$shtml=$shtml."<th width=50 align=left>NUMERO</th>";
			$shtml=$shtml."<th width=50 align=left>CALLE SECUNDARIA</th>";
			$shtml=$shtml."<th width=50 align=left>REFERENCIA</th>";
			$shtml=$shtml."<th width=50 align=left>CODIGO POSTAL</th>";
			$shtml=$shtml."<th width=50 align=left>TELEFONO 1</th>";
			$shtml=$shtml."<th width=50 align=left>TELEFONO 1</th>";
			$shtml=$shtml."<th width=100 align=left>CORREO ELECTRONICO</th>";
			$shtml=$shtml."<th width=100 align=left>PRODUCTO</th>";
			$shtml=$shtml."<th width=50 align=left>PESO</th>";
			$shtml=$shtml."<th width=10 align=left>LARGO</th>";
			$shtml=$shtml."<th width=10 align=left>ALTO</th>";
			$shtml=$shtml."<th width=10 align=left>ANCHO</th>";
			$shtml=$shtml."<th width=50 align=left>NUMERO BULTOS</th>";
			$shtml=$shtml."<th width=50 align=left>NUMERO CAJAS</th>";
			$shtml=$shtml."<th width=50 align=left>NUMERO SOBRES</th>";
			$shtml=$shtml."<th width=50 align=left>ITEM</th>";
			$shtml=$shtml."<th width=50 align=left>CANTIDAD DE ITEM</th>";
			$shtml=$shtml."<th width=50 align=left>VALOR ASEGURADO</th>";
			$shtml=$shtml."<th width=50 align=left>DESCRIPCION CONTENIDO</th>";
			$shtml=$shtml."<th width=50 align=left>CODIGO DE ADJUNTO</th>";
			$shtml=$shtml."<th width=50 align=left>OBSERVACIONES</th>";
			$shtml=$shtml."<th width=50 align=left>NUMERO DE GUIA</th>";
			$shtml=$shtml."</tr>";	
		  
		  $shtml=$shtml."<tr>";
			$shtml=$shtml."<th width=50 align=left>".$items['cliente_dni']."</th>";
			$shtml=$shtml."<th width=50 align=left>".$cargo."</th>";
			$shtml=$shtml."<th width=150 align=left>".$items['cliente_apellido']."</th>";
			$shtml=$shtml."<th width=200 align=left>".$items['cliente_nombre']."</th>";
			$shtml=$shtml."<th width=150 align=left>".$centro_costo."</th>";
			$shtml=$shtml."<th width=50 align=left>".$items['nombre_provincia']."</th>";
			$shtml=$shtml."<th width=100 align=left>".$items['nombre_canton']."</th>";
			$shtml=$shtml."<th width=100 align=left>".$items['parroquia']."</th>";
			$shtml=$shtml."<th width=100 align=left>".$items['cliente_calle']."</th>";
			$shtml=$shtml."<th width=50 align=left>".$items['cliente_calle_numero']."</th>";
			$shtml=$shtml."<th width=50 align=left>".$items['cliente_calle_secundaria']."</th>";
			$shtml=$shtml."<th width=50 align=left>".$items['cliente_referencia']."</th>";
			$shtml=$shtml."<th width=50 align=left>".$items['cliente_cp']."</th>";
			$shtml=$shtml."<th width=50 align=left>".$items['cliente_telefono']."</th>";
			$shtml=$shtml."<th width=50 align=left>".$items['cliente_celular']."</th>";
			$shtml=$shtml."<th width=100 align=left>".$items['cliente_email']."</th>";
			$shtml=$shtml."<th width=100 align=left>".$producto."</th>";
			$shtml=$shtml."<th width=50 align=left>".$peso."</th>";
			$shtml=$shtml."<th width=10 align=left>".$largo."</th>";
			$shtml=$shtml."<th width=10 align=left>".$alto."</th>";
			$shtml=$shtml."<th width=10 align=left>".$ancho."</th>";
			$shtml=$shtml."<th width=50 align=left>".$numero_bultos."</th>";
			$shtml=$shtml."<th width=50 align=left>".$numero_cajas."</th>";
			$shtml=$shtml."<th width=50 align=left>".$numero_sobres."</th>";
			$shtml=$shtml."<th width=50 align=left>".$item."</th>";
			$shtml=$shtml."<th width=50 align=left>".$cantidad_item."</th>";
			$shtml=$shtml."<th width=50 align=left>".$valor_asegurado."</th>";
			$shtml=$shtml."<th width=50 align=left>".$descripcion_contenido."</th>";
			$shtml=$shtml."<th width=50 align=left>".$codigo_adjunto."</th>";
			$shtml=$shtml."<th width=50 align=left>".$observaciones."</th>";
			$shtml=$shtml."<th width=50 align=left>".$numero_guia."</th>";
      $shtml=$shtml." </tr>";
			$shtml=$shtml."<tr><td colspan=30 bgcolor=#666666 height=1></td></tr>";		  
 			$shtml=$shtml."</table>";
 			
			$fecha_hoy = date("d-m-Y");
			// escribe
			$scarpeta="../despacho"; 
			$Session = rand(10000,10000000);
			$sfile=$scarpeta."/pedido-despacho-externo-".$id."-".$fecha_hoy.".xls"; //ruta del archivo a generar 
			$location="../adj/pedido-despacho-externo-".$id."-".$fecha_hoy.".xls"; //ruta del archivo a generar 
			$fp=fopen($sfile,"w"); 
			fwrite($fp,$shtml); 
			fclose($fp);
  break;
  
}

// todos los pedidos con estado despacho
$result_todos = $Despacho->obtener_all(" ORDER BY  d.recepcion_confirmada_mes ASC, d.recepcion_confirmada_dia ASC ", $dia_actual, $mes_actual, $anio_actual, ACTIVO, 6, $filtro_id_vendedor);
$filas_todos = @mysql_num_rows($result_todos);

?>
<?php include("meta.php");?>

<?php //+  acciones js // ?>
 <script>
</script>
<?php //-  acciones js // ?>


<link   href="assets/plugins/colorbox/example1/colorbox.css" rel="stylesheet" />

</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade in page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<!-- begin #header -->
		<?php include("header.php")?>
		<!-- end #header -->
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<!-- begin #sidebar -->
		<?php include("sidebar.php")?>
		<!-- end #sidebar -->
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="index.php">Portada</a></li>
				<li><a href="noticias.php">Despachos</a></li>
				<li class="active">Listado</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Despachos <small>listado de despachos</small></h1>
			<!-- end page-header -->

		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">

			<!-- begin row -->
			<div class="row">
			    
			    <!-- end col-2 -->
			    <!-- begin col-10 -->
			    <div class="col-md-12">
			        <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Pedidos</h4>
                        </div>
                        
                        
                        <?php //+ mensajes // ?>
                        <?php if($accion == "eliminado") { ?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            El Despacho ha sido eliminado correctamente.
                        </div>
	                      <?php } ?>

                        <div class="panel-body">

                            <div class="table-responsive">


                                <table id="data-table" class="table table-striped table-bordered">

                                    <thead>
                                    	<tr>
                                    	 <td colspan="5">
	                                    	 	Despachos para la fecha: <strong><?=$fecha_completa?></strong>
                                    	 	</td>
                                    	 	<td colspan="3">
                                    	 		Cambia Fecha:
                                    	 	<select name="mes_actual" id="filtro_dia_actual">
																												 <option value="01" <?=($dia_actual=='01') ? 'selected' : ''?>>01</option>
																												 <option value="02" <?=($dia_actual=='02') ? 'selected' : ''?>>02</option>
																												 <option value="03" <?=($dia_actual=='03') ? 'selected' : ''?>>03</option>
																												 <option value="04" <?=($dia_actual=='04') ? 'selected' : ''?>>04</option>
																												 <option value="05" <?=($dia_actual=='05') ? 'selected' : ''?>>05</option>
																												 <option value="06" <?=($dia_actual=='06') ? 'selected' : ''?>>06</option>
																												 <option value="07" <?=($dia_actual=='07') ? 'selected' : ''?>>07</option>
																												 <option value="08" <?=($dia_actual=='08') ? 'selected' : ''?>>08</option>
																												 <option value="09" <?=($dia_actual=='09') ? 'selected' : ''?>>09</option>
																												 <option value="10" <?=($dia_actual=='11') ? 'selected' : ''?>>10</option>
																												 <option value="11" <?=($dia_actual=='11') ? 'selected' : ''?>>11</option>
																												 <option value="12" <?=($dia_actual=='12') ? 'selected' : ''?>>12</option>
																												 <option value="13" <?=($dia_actual=='13') ? 'selected' : ''?>>13</option>
																												 <option value="14" <?=($dia_actual=='14') ? 'selected' : ''?>>14</option>
																												 <option value="15" <?=($dia_actual=='15') ? 'selected' : ''?>>15</option>
																												 <option value="16" <?=($dia_actual=='16') ? 'selected' : ''?>>16</option>
																												 <option value="17" <?=($dia_actual=='17') ? 'selected' : ''?>>17</option>
																												 <option value="18" <?=($dia_actual=='18') ? 'selected' : ''?>>18</option>
																												 <option value="19" <?=($dia_actual=='19') ? 'selected' : ''?>>19</option>
																												 <option value="20" <?=($dia_actual=='20') ? 'selected' : ''?>>20</option>
																												 <option value="21" <?=($dia_actual=='21') ? 'selected' : ''?>>21</option>
																												 <option value="22" <?=($dia_actual=='22') ? 'selected' : ''?>>22</option>
																												 <option value="23" <?=($dia_actual=='23') ? 'selected' : ''?>>23</option>
																												 <option value="24" <?=($dia_actual=='24') ? 'selected' : ''?>>24</option>
																												 <option value="25" <?=($dia_actual=='25') ? 'selected' : ''?>>25</option>
																												 <option value="26" <?=($dia_actual=='26') ? 'selected' : ''?>>26</option>
																												 <option value="27" <?=($dia_actual=='27') ? 'selected' : ''?>>27</option>
																												 <option value="28" <?=($dia_actual=='28') ? 'selected' : ''?>>28</option>
																												 <option value="29" <?=($dia_actual=='29') ? 'selected' : ''?>>29</option>
																												 <option value="30" <?=($dia_actual=='30') ? 'selected' : ''?>>30</option>
																												 <option value="31" <?=($dia_actual=='31') ? 'selected' : ''?>>31</option>
																				</select>
																				/
                                    	 	<select name="mes_actual" id="filtro_fecha_mes_actual">
                                    	 	 <option value="01" <?=($mes_actual == '01') ? 'selected' : ''?>>Enero</option>
                                    	 	 <option value="02" <?=($mes_actual == '02') ? 'selected' : ''?>>Febrero</option>
                                    	 	 <option value="03" <?=($mes_actual == '03') ? 'selected' : ''?>>Marzo</option>
                                    	 	 <option value="04" <?=($mes_actual == '04') ? 'selected' : ''?>>Abril</option>
                                    	 	 <option value="05" <?=($mes_actual == '05') ? 'selected' : ''?>>Mayo</option>
                                    	 	 <option value="06" <?=($mes_actual == '06') ? 'selected' : ''?>>Junio</option>
                                    	 	 <option value="07" <?=($mes_actual == '07') ? 'selected' : ''?>>Julio</option>
                                    	 	 <option value="08" <?=($mes_actual == '08') ? 'selected' : ''?>>Agosto</option>
                                    	 	 <option value="09" <?=($mes_actual == '09') ? 'selected' : ''?>>Septiembre</option>
                                    	 	 <option value="10" <?=($mes_actual == '10') ? 'selected' : ''?>>Octubre</option>
                                    	 	 <option value="11" <?=($mes_actual == '11') ? 'selected' : ''?>>Noviembre</option>
                                    	 	 <option value="12" <?=($mes_actual == '12') ? 'selected' : ''?>>Diciembre</option>
                                    	 	</select>
                                    	 	/
                                    	 	<select name="anio_actual" id="filtro_fecha_anio">
                                    	 	 <option value="2015" <?=($anio_actual == '2015') ? 'selected' : ''?>>2015</option>
                                    	 	 <option value="2016" <?=($anio_actual == '2016') ? 'selected' : ''?>>2016</option>
                                    	 	</select>
                                    	 	
                                    	 	<input type="submit" value="Filtrar" name="btfiltrar"/>
                                    	 	</td>
                                    	</tr>
                                        <tr>
                                            <td width="5%"></td>
                                            <th width="15%">Cliente Nombre</th>
                                            <th width="10%">Cédula</th>
                                            <th width="20%">Producto</th>
                                            <th width="15%">Fecha Alta</th>
                                            <th width="15%">Fecha Depacho</th>
                                            <th width="10%" align="center">Interno</th>
                                            <th width="10%" align="center">Externo</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($i=1; $i <= $filas_todos; $i++) {
						 												$items = @mysql_fetch_array($result_todos);
																	?>                                     	
                                        <tr class="odd gradeX">
                                    	  		<td align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items['id']?>"></td>
                                            <td>
                                            	<?=$items['cliente_nombre']?> <?=$items['cliente_apellidos']?> 
                                              <?php if($_REQUEST['id'] == $items['id']) { ?>
                                               <strong>(actualizado)</strong>
	                                            <?php } ?>
                                            </td>
                                            <td><?=$items['cliente_dni']?></td>
                                            <td><?=$items['modelo']?> <?=$items['marca']?> <?=$items['color']?></td>
                                            <td>
                                            	<?=GetFechaTexto($items['fecha_alta'])?>
                                            	</td>
                                            <td>
                                            	<strong><?=$items['recepcion_confirmada_dia']?>/<?=$items['recepcion_confirmada_mes']?>/<?=$items['recepcion_confirmada_anio']?></strong>
                                            	desde <?=$items['recepcion_confirmada_desde']?> hasta <?=$items['recepcion_confirmada_hasta']?> hs
                                           	</td>
                                           	<td align="center"><input type="radio" name="rd_tipo_despacho_<?=$items['id']?>" value="interno" /></td>
                                           	<td align="center"><input type="radio" name="rd_tipo_despacho_<?=$items['id']?>" value="externo" /></td>

                                        </tr>
                                  <?php
                                  } 
                                  ?>     
                                    </tbody>
                                </table>

                            </div>

	                            <input type="button" class="btn blue" value="Generar Despacho" onClick="Generar"/>
                            
                        </div>

                    </div>
                    <!-- end panel -->
                </div>
                <!-- end col-10 -->
            </div>
            <!-- end row -->
		  </div>
		</form>
		<!-- end #content -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
 <?php include("footer_meta.php")?>
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="assets/plugins/DataTables/js/dataTables.colReorder.js"></script>
	<script src="assets/js/table-manage-colreorder.demo.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script src="assets/plugins/colorbox/jquery.colorbox.js"></script>
	
	<script>
		$(document).ready(function() {
			App.init();
			TableManageColReorder.init();
			
			$(".estado").colorbox({iframe:true, width:"600px", height:"800px", left: "50%"});
 	
 			  $(".sipec").click(function(){	
	 				window.open("http://www.cocinasdeinduccion.gob.ec/web/guest/registro-en-el-programa", "sipec", "height=900,width=700, scroll=yes");
			  });
		});
	</script>
</body>
</html>
