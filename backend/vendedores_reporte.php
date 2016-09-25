<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Incidencia', 'Localizacion', 'Vendedor');
global $BackendUsuario, $Pedido, $Incidencia, $Localizacion, $Vendedor;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteVentas() && !$BackendUsuario->esGerenteLogistica() && !$BackendUsuario->esRoot()) { 
 die;
}	

// id del pedido q trae la alerta
$id = ($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$id_vendedor = ($_REQUEST['id_vendedor']) ? $_REQUEST['id_vendedor'] : null;
$arrVendedor = $Vendedor->obtener($id_vendedor);
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Pedidos - Induccion</title>
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
   var id_vendedor = $("#id_vendedor").val();
   var form = document.forms['frmPrincipal'];
   form.submit();   
  }
  
 	function restablecer(idx) {
 	 if(confirm('Esta seguro de querer restablecer el pedido?')) {
     	  var form = document.forms['frmPrincipal'];
	  	  form['accion'].value = 'despapelera';
  		  form['id'].value = idx;
   		  form.submit();
 	  }
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
				<li><a href="javascript:;">Vendedores Estados</a></li>
				<li class="active">Listado de pedido en la papelera</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Pedidos <small> listado de pedidos por vendedor</small></h1>
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
                            <h4 class="panel-title">Listado de Pedidos de baja. <strong><?=$filas_todos?></strong> listados</h4>
                        </div>
                        <div class="panel-body">

											 <!-- begin row -->
                           <div class="row">
                           	 <div class="col-md-12"> 
                           	  <div class="table-responsive">

																<?php
                                 	// vendedores
																	$result_vendedores = $BackendUsuario->obtener_vendedores_y_supervisores(1); 
																	$filas_vendedores = @mysql_num_rows($result_vendedores);
                                   	?>
                                   	<select id="id_vendedor" name="id_vendedor" onChange="filtrar()" style="width:300px; height:40px" required="true">
                                   	 <option value=""> - Filtrar por vendedores -</option>
                                   	 <?php
                                   	  for($k=0; $k < $filas_vendedores; $k++) {
																			$items_vendedores = @mysql_fetch_array($result_vendedores); ?>
                                   	 		 <option value="<?=$items_vendedores['id']?>" <?=($id_vendedor==$items_vendedores['id']) ? 'selected' : ''?>><?=$items_vendedores['apellido']?>,  <?=$items_vendedores['nombre']?></option>
																		<?php	} ?>	
                                   	</select>
                                   	
                                   	<div style="clear:both"></div><br/>
                                   	
                                <?php if($id_vendedor > 0) { ?>    	
	
	                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                    												<th width="2%">Id</th>
                                            <th width="15%">Vendedor</th>
                                            <th width="15%">Estado</th>
                                            <th width="15%">Pedidos</th>
                                            <th width="15%"></th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>

                                       
                                        <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td><strong><?=$arrVendedor['nombre']?> <?=$arrVendedor['apellido']?></strong></td>
                                            <td colspan="3">
                                            </td>
                                        </tr>
                           
                                  
                                 	<?php
                                 	// estados
																	$result_estados = $Pedido->obtener_estados(ACTIVO); 
																	$filas_estados = @mysql_num_rows($result_estados);
                                                              
                                 	  for($k=0; $k < $filas_estados; $k++) {
																			$items_estados = @mysql_fetch_array($result_estados); 
																			$cantidad_pedidos = $Pedido->cantidad_pedidos_by_vendedor($arrVendedor['id'], $items_estados['id'], ACTIVO);
																			$cantidad_pedidos_papelera = $Pedido->cantidad_pedidos_papelera_by_vendedor($arrVendedor['id']);
																			?>
																			 
																			 <tr>
																				 <td></td>
																				 <td></td>
																				 <td><?=$items_estados['nombre']?></td>
																				 <td><a href="pedidos_por_vendedor.php?id=<?=$arrVendedor['id']?>&e=<?=$items_estados['id']?>"><?=$cantidad_pedidos?></a></td>
																				 <td></td>
																				</tr> 
																	
																	
																		<?php	} ?>	

																		 <tr>
																				 <td></td>
																				 <td></td>
																				 <td><STRONG>PAPELERA</STRONG></td>
																				 <td><a href="pedidos_por_vendedor.php?id=<?=$arrVendedor['id']?>&e=0"><?=$cantidad_pedidos_papelera?></a></td>
																				 <td></td>
																				</tr>                                     
                                         <tr>
                                          <td colspan="5"></td>

	                                        </td>
                                         </tr>
                                             <?php
                                 } // f pedidos
                                  ?>   
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
