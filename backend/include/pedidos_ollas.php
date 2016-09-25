<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Incidencia', 'Vendedor', 'Pedido', 'Producto');
global $BackendUsuario, $Pedido, $Incidencia, $Vendedor, $Pedido, $Producto;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteVentas()) {
 //die;
}

// id vendedor
$id 		= ($_REQUEST['id_vendedor']) ? $_REQUEST['id_vendedor'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$fecha_desde  = (!isset($_POST['fecha_desde'])) ? null : $_POST['fecha_desde'];
$fecha_hasta  = (!isset($_POST['fecha_hasta'])) ? null : $_POST['fecha_hasta'];

$mes  = (!isset($_POST['mes'])) ? null : $_POST['mes'];
$anio = (!isset($_POST['anio'])) ? null : $_POST['anio'];

$fecha_rango_desde = date("d/m/Y");  
$fecha_rango_hasta = date("d/m/Y");
$fecha_rango_desde = null;  
$fecha_rango_hasta = null;

switch ($accion) {
 
}

// todos los vendedores y supervisores
$result = $BackendUsuario->obtener_vendedores_y_supervisores( null);
$filas = @mysql_num_rows($result);
																		
// todos los pedidos
$result_pedidos = $Pedido->obtener_ollas_all(null, null, $limite, $palabra, $order_by, $filtro, ACTIVO, null, null, null, null, null, $id);
$filas_pedidos  = @mysql_num_rows($result_pedidos );
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Pedidos - Ollas -  Induccion</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="assets/plugins/bootstrap-wizard/css/bwizard.min.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->

	<link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
  <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
  <link href="assets/plugins/colorbox/example1/colorbox.css" rel="stylesheet" />
  
  <script>
 	 function filtrar() {
 	  var id_vendedor = $("id_vendedor").val();
	  var form = document.forms['frmPrincipal'];
	  form['accion'].value = 'filtrar';
 	  form.submit(); 	  
 	 }

 function filtrar_general() {
  var form = document.forms['frmPrincipal'];
	form.submit();
 }

 function filtrar_estado() {
  var id_vendedor = $("#id_vendedor").val();
  var form = document.forms['frmPrincipal'];
 }

 function filtrar_todos() {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = "todos";
	form.submit();
 } 	 
  </script>
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
   		<?php include("header.php")?>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
   		<?php include("sidebar.php")?>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Home</a></li>
				<li><a href="javascript:;">Pedidos</a></li>
				<li class="active">Listado de pedidos ollas</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Pedidos ollas <small> listado</small></h1>
			<!-- end page-header -->
			
		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_item">	
			<input type="hidden" name="pedidos_idx"  id="pedidos_idx">	
			<input type="hidden" name="imagen1">
			<input type="hidden" name="imagen2">		
						
			<!-- begin row -->
			<div class="row">
                <!-- begin col-12 -->
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
                            <h4 class="panel-title">PEDIDOS</h4>
                        </div>
                        <div class="panel-body">
												<div class="row">
                         <div class="col-md-12"> 
                         	
                         	Filtrar por vendedores
                         	
                         	<select name="id_vendedor" id="id_vendedor" style="width:300px; height:30px;padding-left:20px" onChange="filtrar()">
                         	 <option value="">-Todos-</option>
                         			<?php
					   											for($i=1; $i <= $filas; $i++) {
						 												$items = @mysql_fetch_array($result);
															?>
	                         	        <option value="<?=$items['id']?>" <?=($id==$items['id']) ? 'selected' : ''?>> <?=$items['apellido']?> <?=$items['nombre']?></option>
                         	    <?php
                         	   	}
                         	   	?>
                         	</select>
                         		<br/>
                         		Filtrar por mes
                         
                         		<select id="mes" name="mes" style="width:180px; height:40px">
                                   	 <option value=""> - Mes -</option>
                                   	 		 <option value="01" <?=($mes=='01') ? 'selected' : ''?>>Enero</option>
                                   	 		 <option value="02" <?=($mes=='02') ? 'selected' : ''?>>Febrero</option>
                                   	 		 <option value="03" <?=($mes=='03') ? 'selected' : ''?>>Marzo</option>
                                   	 		 <option value="04" <?=($mes=='04') ? 'selected' : ''?>>Abril</option>
                                   	 		 <option value="05" <?=($mes=='05') ? 'selected' : ''?>>Mayo</option>
                                   	 		 <option value="06" <?=($mes=='06') ? 'selected' : ''?>>Junio</option>
                                   	 		 <option value="07" <?=($mes=='07') ? 'selected' : ''?>>Julio</option>
                                   	 		 <option value="08" <?=($mes=='08') ? 'selected' : ''?>>Agosto</option>
                                   	 		 <option value="09" <?=($mes=='09') ? 'selected' : ''?>>Septiembre</option>
                                   	 		 <option value="10" <?=($mes=='10') ? 'selected' : ''?>>Octubre</option>
                                   	 		 <option value="11" <?=($mes=='11') ? 'selected' : ''?>>Noviembre</option>
                                   	 		 <option value="12" <?=($mes=='12') ? 'selected' : ''?>>Diciembre</option>
                                   	</select>
                                   	
																	<select id="anio" name="anio" style="width:80px; height:40px">
                                   	 <option value=""> - A&ntilde;o -</option>
                                   	 		 <option value="2015" <?=($anio=='2015') ? 'selected' : ''?>>2015</option>
                                   	 		 <option value="2016" <?=($anio=='2016') ? 'selected' : ''?>>2016</option>
                                   	</select>
                                   	<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" onClick="filtrar_general()">
																					Filtrar
																				</button>
                         	<br>
                         	<h3>Se listaron <?=$filas_pedidos?> items </h3>
                         	</div>
                         </div>
 													<div style="clear:both"></div><br/>
 													<!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                    												<th width="2%">Id</th>
                                            <th width="10%">Cedula Identidad</th>
                                            <th width="10%">Nombre</th>
                                            <th width="10%">Apellido</th>
                                            <th width="20%">Ollas</th>
                                            <th width="10%">Fecha Alta</th>
                                            <th width="15%">Vendedor</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($k=1; $k <= $filas_pedidos; $k++) {
						 												  $items = @mysql_fetch_array($result_pedidos);
																		  
																		  $arrVendedor = $BackendUsuario->obtener($items['id_vendedor']);
																		  
						 												  // ollas
						 												  $result_ollas = $Pedido->obtener_pedido_ollas($items['id']);
																		  $filas_ollas = @mysql_num_rows($result_ollas);
																	?>                                     	
                                     <?php if($items['id'] > 0) { ?>
                                       
                                        <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td><?=$items['dni']?></td>
                                            <td><?=$items['nombre']?></td>
                                            <td><?=$items['apellido']?></td>
                                            <td>
                                            		
                                            		<?php if($filas_ollas > 0) { ?>

	                                                <div style="clear:both"></div>
	                                                Ollas:<br/>
	                                                <strong>
																									<?php
																										for($o=1; $o <= $filas_ollas; $o++) {
																											$items_ollas = @mysql_fetch_array($result_ollas); 
																											 
																											 $arrMarca  = $Producto->obtener_marca($items_ollas['olla_marca']);
																											 $arrModelo = $Producto->obtener_modelo($items_ollas['olla_modelo']);
																											?>
    																								
																											 <?=$arrMarca['nombre']?> / <?=$arrModelo['nombre']?> / <?=$items_ollas['cantidad']?><br/>
																											
																										<?php } ?>
																										</strong>	
	                                            	<?php } ?>
  
                                            	</td>
                                            <td>
	                                            	<?=GetFechaTexto($items['fecha_alta'])?>
                                            	</td>
                                            	<td><?=$arrVendedor['nombre']?> <?=$arrVendedor['apellido']?></td>
                                            
                                        </tr>
                                  <?php
                                     }
                                  } // f pedidos
                                  ?>     
                                         <tr>
                                          <td colspan="6"></td>
                                          <td colspan="2" align="right">
                                          </td>
                                         </tr>
                                    </tbody>
                                </table>
                              </div>  
                            </div>
                           </div>
	                      	 <!-- end row -->
															
									
																</div>
														</form>
                        </div>
                    </div>
                    <!-- end panel -->
                </div>
                <!-- end col-12 -->
            </div>
            <!-- end row -->
		</div>
		<!-- end #content -->
		
		
        <!-- begin theme-panel -->
      
        <!-- end theme-panel -->
				</form>

		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
	<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
	<script src="assets/plugins/masked-input/masked-input.min.js"></script>
	<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="assets/plugins/password-indicator/js/password-indicator.js"></script>
	<script src="assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
	<script src="assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
	<script src="assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/moment.js"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
    <script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<script src="assets/js/form-plugins.demo.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/bootstrap-wizard/js/bwizard.js"></script>
	<script src="assets/js/form-wizards.demo.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
		<script src="assets/plugins/colorbox/jquery.colorbox.js"></script>

	<script>
		$(document).ready(function() {
			App.init();
			FormWizard.init();
			FormPlugins.init();
		});

			function subir_excel() {
					//$('.myCheckbox')[0].checked = true;
					/*	
					var chk_arr =  document.getElementsByName("arrSeleccion2[]");
					var chklength = chk_arr.length;   
					var chk_arr_seleccionados = [];          

					for(k=0; k < chklength;k++) {
				    if(chk_arr[k].checked == true) {
				    	chk_arr_seleccionados.push(chk_arr[k].value);
				    }
					} 
          
          if(chk_arr_seleccionados == 0) {
            alert("Debe seleccionar al menos un pedido");
          } else {
           var idx =  chk_arr_seleccionados.join(",")
					 window.open("ifr_subir_excel.php?idx="+idx, "Subir Excel", "height=900,width=700, scroll=yes");
					//$(".estado").colorbox({iframe:true, width:"800px", height:"800px", left: "30%"});
					}
					*/
					 window.open("ifr_subir_excel.php", "Subir Excel", "height=900,width=700,scrollbars=1");
			}

			function agendar(idx) {
					 window.open("ifr_estado.php?id="+idx+"&estado=3", "Agendar", "height=900,width=700,scrollbars=1");
			}

			function detalle(idx) {
					 window.open("ifr_detalle.php?id="+idx, "Detalle", "height=900,width=700,scrollbars=1");
			}


			function generar_documentacion(idx) {
					 window.open("ifr_estado.php?id="+idx+"&estado=4", "Generar Documentacion", "height=900,width=700,scrollbars=1");
			}
			
			function recepcion_documentacion(idx) {
					 window.open("ifr_estado.php?id="+idx+"&estado=6", "Recepcion Documentacion", "height=900,width=700,scrollbars=1");
			}
			

	</script>

</body>
</html>
