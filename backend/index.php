<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Incidencia','Pedido');
global $BackendUsuario, $Producto, $Incidencia,$Pedido;

$BackendUsuario->EstaLogeadoBackend();

// roles y permisos
//fecha origen cambiar  a 9-4 para produccion
	$date = new DateTime('2016-7-24');

$option_quincena=$Pedido->obtenerFechasQuincena($date);

$n=sizeof($option_quincena)-1;
//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>...;;ldkjaklsjdlkasdjlkdasjlasdkjdlsakjlsakjlsakjlsakjlsakjlsakjlsakjlsakjlsakjlsakj==>>".$n;
if($n<0)
	$n=1;
$quincena= (!isset($_POST['quincena'])) ? $n : $_POST['quincena'];


if(sizeof($option_quincena)>0)
{
	$quincena_seleccionada=$option_quincena[$quincena];
	
	$title_semana1='Semana '.$quincena_seleccionada["semana1"];
	$fecha_desde_semana_1 = $quincena_seleccionada["fecha_desde_semana1"];
	$fecha_hasta_semana_1 =$quincena_seleccionada["fecha_hasta_semana1"];


	$title_semana2='Semana '.$quincena_seleccionada["semana2"];
	$fecha_desde_semana_2 = $quincena_seleccionada["fecha_desde_semana2"];
	$fecha_hasta_semana_2 = $quincena_seleccionada["fecha_hasta_semana2"];
	$title_quincena='Quincena'. $quincena_seleccionada["fecha_desde_quincena"].' / '.$quincena_seleccionada["fecha_hasta_quincena"];
}

$idvendedor=$BackendUsuario->getUsuarioId();
$total_entregadas_valor1=$Pedido->pedidos_facturados_fechas($idvendedor, $fecha_desde_semana_1, $fecha_hasta_semana_1, 1, 7,'ENTREGADO');
$encimeras_valor1=$Pedido->pedidos_encimera_facturados_fechas($idvendedor, $fecha_desde_semana_1, $fecha_hasta_semana_1, 1, 7,'ENTREGADO');
$horno_valor1=$Pedido->pedidos_hornos_facturados_fechas($idvendedor, $fecha_desde_semana_1, $fecha_hasta_semana_1, 1, 7,'ENTREGADO');
$datos_grafica_semana1=datosGrafica($encimeras_valor1,$horno_valor1,$total_entregadas_valor1);

$total_entregadas_valor2=$Pedido->pedidos_facturados_fechas($idvendedor, $fecha_desde_semana_2, $fecha_hasta_semana_2, 1, 7,'ENTREGADO');
$encimeras_valor2=$Pedido->pedidos_encimera_facturados_fechas($idvendedor, $fecha_desde_semana_2, $fecha_hasta_semana_2, 1, 7,'ENTREGADO');
$horno_valor2=$Pedido->pedidos_hornos_facturados_fechas($idvendedor, $fecha_desde_semana_2, $fecha_hasta_semana_2, 1, 7,'ENTREGADO');
$datos_grafica_semana2=datosGrafica($encimeras_valor2,$horno_valor2,$total_entregadas_valor2);

function datosGrafica($i1,$i2,$t3){
 if($t3>5)
 	return [$i1,$i2];
 else
 	return [$i1,$i2,5-$t3];
}
?>
<?php include("meta.php");?>

<style>
.link-dash:link { 

	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: normal;
	color: #ffffff;
	text-decoration: underline;
}

.link-dash:hover{
	color: #9ACB00;	
}
</style>
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
				<li><a href="javascript:;">Dashboard</a></li>
				<li class="active">Dashboard </li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Dashboard  <small> <?=$BackendUsuario->obtenerCargo()?></small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			<div class="row">
				
				  <?php //+ Para vendedores // ?>

          <?php if($BackendUsuario->esVendedor()) { 
          	
          	$cantidad_pedidos_vendedor   = $Pedido->cantidad_pedidos_by_vendedor($BackendUsuario->getUsuarioId(), 1);
					  $cantidad_pedidos_despacho   = $Pedido->cantidad_pedidos_by_vendedor($BackendUsuario->getUsuarioId(), 2);
          	$cantidad_agendar_vendedor   = $Pedido->cantidad_pedidos_by_vendedor($BackendUsuario->getUsuarioId(), 3);
          	$cantidad_ingreso_vendedor   = $Pedido->cantidad_pedidos_by_vendedor($BackendUsuario->getUsuarioId(), 4);
          	$cantidad_despachos_vendedor = $Pedido->cantidad_pedidos_by_vendedor($BackendUsuario->getUsuarioId(), 5);
					?>
          
			    <!-- begin col-3 -->
			    <div class="col-md-3 col-sm-6">
			        <div class="widget widget-stats bg-green">
			            <div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
			            <div class="stats-title">PEDIDOS</div>
			            <div class="stats-number"><?=$cantidad_pedidos_vendedor?></div>
			            <div class="stats-progress progress">
                        </div>
                        <div class="stats-desc"><a href="pedidosv2.php?#step1" class="link-dash">Listado</a></div>
			        </div>
			    </div>

			    <div class="col-md-3 col-sm-6">
			        <div class="widget widget-stats bg-green">
			            <div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
			            <div class="stats-title">PRE-DESPACHO</div>
			            <div class="stats-number"><?=$cantidad_pedidos_despacho?></div>
			            <div class="stats-progress progress">
                        </div>
                        <div class="stats-desc"><a href="pedidosv2.php?#step2" class="link-dash">Listado</a></div>
			        </div>
			    </div>
			    			    
			    <!-- end col-3 -->
			    <!-- begin col-3 -->
			    <div class="col-md-3 col-sm-6">
			        <div class="widget widget-stats bg-blue">
			            <div class="stats-icon stats-icon-lg"><i class="fa fa-tags fa-fw"></i></div>
			            <div class="stats-title">AGENDAR</div>
			            <div class="stats-number"><?=$cantidad_agendar_vendedor?></div>
			            <div class="stats-progress progress">
                            <div class="progress-bar" style="width: 100%;"></div>
                        </div>
                        <div class="stats-desc"><a href="pedidosv2.php?#step3" class="link-dash">Listado</a></div>
			        </div>
			    </div>
			    <!-- end col-3 -->
			    <!-- begin col-3 -->
			    <div class="col-md-3 col-sm-6">
			        <div class="widget widget-stats bg-purple">
			            <div class="stats-icon stats-icon-lg"><i class="fa fa-shopping-cart fa-fw"></i></div>
			            <div class="stats-title">GENERACION DOCUMENTACION</div>
			            <div class="stats-number"><?=$cantidad_ingreso_vendedor?></div>
			            <div class="stats-progress progress">
                            <div class="progress-bar" style="width: 100%;"></div>
                        </div>
                        <div class="stats-desc"><a href="pedidosv2.php?#step4" class="link-dash">Listado</a></div>
			        </div>
			    </div>
			    <!-- end col-3 -->
			    <!-- begin col-3 -->
			    <!-- begin col-3 -->
			    <div class="col-md-3 col-sm-6">
			        <div class="widget widget-stats bg-black">
			            <div class="stats-icon stats-icon-lg"><i class="fa fa-comments fa-fw"></i></div>
			            <div class="stats-title">DESPACHOS</div>
			            <div class="stats-number"><?=$cantidad_despachos_vendedor?></div>
			            <div class="stats-progress progress">
                            <div class="progress-bar" style="width: 0%;"></div>
                        </div>
                        <div class="stats-desc"><a href="pedidosv2.php?#step5" class="link-dash">Listado</strong></u></h5></a></div>
			        </div>
			    </div>
 				  <?php //- Para vendedores // ?>

 				  <?php //+ Para supervisores // ?>
					<?php } else if($BackendUsuario->esSupervisor()) { ?>
					<div class="col-md-8">
					<div class="panel panel-inverse" data-sortable-id="index-1">
						<div class="panel-heading">
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
							</div>
							<h4 class="panel-title">Ventas Mensuales</h4>
						</div>
						<div class="panel-body">
							<form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
							<div style="align:center; text-align:center">

									<select name="quincena" id="quincena" data-live-search="true" class="selectpicker" data-size="7">
									    
									   			<?php
									   			
	   											$i=0;
	   											$nfila=sizeof($option_quincena);
	   											for($i=0;$i<$nfila;$i++)
	   											{
	   												$items=$option_quincena[$i];
				           						?>
					                         	        
				           						      <?php  if(($i+1)%2!=0){ 
				           						      	$fecha_i_mes=$items["fecha_desde_quincena"];
				           						      	
				           						      	if(($i+1)<$nfila){
				           						      		$itemnext=($option_quincena[($i+1)]);
				           						      		$fecha_f_mes=$itemnext["fecha_hasta_quincena"];

				           						      	}
				           						      	
				           						      	else
				           						      		$fecha_f_mes=$items["fecha_hasta_quincena"];
				           						      	$group_mes= "Induccion <br>".$fecha_i_mes." / ".$fecha_f_mes;


				           						      	?>
				           						      	<optgroup label="<?=$group_mes?>">
				           						      <?php } ?>
					                         	        <option value="<?=$items['id']?>" <?=($quincena==$items['id']) ? 'selected' : ''?>> <?=($items['id']+1).'. '.'Quincena'.' '.$items['intervalo_quincena']?></option>
				                         	   		  <?php  if($i%2!=0){ ?>
				           						      	</outgroup>
				           						      <?php } ?>

				                         	    <?php 
				                         	    
				                         	    } 
				                         	    ?>
															   
									</select>
									 <button type="submit" class="btn btn-info btn-xs" name="btMostrar">
															    <span class="glyphicon glyphicon-search"></span> Mostrar
									</button>


							</div>
							</form>
							<div class="ibox">
                            <div class="ibox-content">
                             
                                <h5>Alerts</h5>
                                <table class="table table-stripped small m-t-md">
                                	<thead>
									  <tr>
									  	 <th style="text-align:center ">Detalle</th>
									     <th style="text-align:center "><?=$title_semana1?></th>
									     <th style="text-align:center "><?=$title_semana2?></th>
									  </tr>
									 </thead> 
                                    <tbody>
                                    <tr style="color:#3366cc !important">
                                        <td class="no-borders">
                                            <i class="fa fa-circle text-navy" ></i>
                                            Encimeras
                                        </td>
                                        <td  class="no-borders" style="text-align:center ">
                                            <?=$encimeras_valor1?>
                                        </td>
                                         <td  class="no-borders" style="text-align:center ">
                                            <?=$encimeras_valor2?>
                                        </td>
                                    </tr>
                                    <tr style="color:#dc3912 !important">
                                        <td>
                                            <i class="fa fa-circle text-navy"></i>
                                            Hornos
                                        </td>
                                        <td style="text-align:center ">
                                           <?=$horno_valor1?>
                                        </td>
                                         <td  class="no-borders" style="text-align:center ">
                                           <?=$horno_valor2?>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr style="color:black !important">
                                        <td>
                                            <i class="fa fa-circle text-navy"></i>
                                            Total Cocinas
                                        </td>
                                        <td style="text-align:center ">
                                            <?=$total_entregadas_valor1?>
                                        </td>
                                         <td  class="no-borders" style="text-align:center ">
                                             <?=$total_entregadas_valor2?>
                                        </td>
                                    </tr>
                                    </tfoot>
                                    
                                </table>
                            </div>
                       		</div>
                       		<div style="align:center; text-align:center">
			 					<div class="col-lg-6">
			                        <div class="ibox">
			                            <div class="ibox-content">
			                                <h5><?=$title_semana1?></h5>
			                               
			                                <div class="text-center">
			                                    <div id="sparkline1"></div>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                    	<div class="col-lg-6">
			                        <div class="ibox">
			                            <div class="ibox-content">
			                                <h5><?=$title_semana2?></h5>
			                               
			                                <div class="text-center">
			                                    <div id="sparkline2"></div>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			                <div style="align:left; text-align:left">
			                	<a href="quincena.php">Detalle Quincena</a>
			                </div>
								                    
		                    
						</div>
					</div>
			  </div>


 				  <?php //+ Para Coordinadores // ?>
					<?php } else if($BackendUsuario->esCordinador()) { ?>
	
 				  <?php //+ Para GERENTE GENERAL // ?>
					<?php } else if($BackendUsuario->esGerenteGeneral()) { ?>

 				  <?php //+ Para esGerenteLogistica // ?>
					<?php } else if($BackendUsuario->esGerenteLogistica()) { ?>

					<div class="panel panel-inverse" data-sortable-id="index-6">
						<div class="panel-heading">
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
							</div>
							<h4 class="panel-title">Estadísticas de Incidencias</h4>
						</div>
						<div class="panel-body p-t-0">
							<table class="table table-valign-middle m-b-0">
								<thead>
									<tr>	
										<th>Incidencia</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
							<?php
								$result_incidencias = $Incidencia->obtener_all(null, null);
								$filas_incidencias = @mysql_num_rows($result_incidencias);		
									for($i=1; $i <= $filas_incidencias; $i++) {
										$items_incidencias = @mysql_fetch_array($result_incidencias);
										$cantidad = $Incidencia->cantidad_by_tag($items_incidencias['id']);
						 	?>
									<tr>
										<td><label class="label label-danger"><?=$items_incidencias['nombre']?></label></td>
										<td><?=$cantidad?></td>
									</tr>
								<?php } ?>		
								</tbody>
							</table>
						</div>
					</div>



					<div class="panel panel-inverse" data-sortable-id="index-6">
						<div class="panel-heading">
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
							</div>
							<h4 class="panel-title">Estadísticas de Pedidos</h4>
						</div>
						<div class="panel-body p-t-0">
							<table class="table table-valign-middle m-b-0">
								<thead>
									<tr>	
										<th>Estado</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
							<?php
								$result_estados = $Pedido->obtener_estados();
								$filas_estados = @mysql_num_rows($result_estados);		
									for($i=1; $i <= $filas_estados; $i++) {
										$items_estados = @mysql_fetch_array($result_estados);
										$cantidad = $Pedido->cantidad_by_estado($items_estados['id']);
						 	?>		
						 				<tr>
										<td><label class="label label-info"><?=$items_estados['nombre']?></label></td>
										<td><?=$cantidad?></td>
									</tr>
								<?php } ?>		
								</tbody>
							</table>
						</div>
					</div>

					<?php } ?>
					
					
 				  <?php //- Para supervisores // ?>
					
			    <!-- end col-3 -->
			</div>
			<!-- end row -->
			
			<?php /* ?>
			<!-- begin row -->
			<div class="row">
			    <div class="col-md-8">
			        <div class="widget-chart with-sidebar bg-black">
			            <div class="widget-chart-content">
			                <h4 class="chart-title">
			                    Tus Visitas
			                    <small>Where do our visitors come from</small>
			                </h4>
			                <div id="visitors-line-chart" class="morris-inverse" style="height: 260px;"></div>
			            </div>
			            <div class="widget-chart-sidebar bg-black-darker">
			                <div class="chart-number">
			                    0
			                    <small>visitantes</small>
			                </div>
			                <div id="visitors-donut-chart" style="height: 160px"></div>
			                <ul class="chart-legend">
			                    <li><i class="fa fa-circle-o fa-fw text-success m-r-5"></i> 34.0% <span>New Visitors</span></li>
			                    <li><i class="fa fa-circle-o fa-fw text-primary m-r-5"></i> 56.0% <span>Return Visitors</span></li>
			                </ul>
			            </div>
			        </div>
			    </div>
			    <div class="col-md-4">
			        <div class="panel panel-inverse" data-sortable-id="index-1">
			            <div class="panel-heading">
			                <h4 class="panel-title">
			                    Origen de los Despachos 
			                </h4>
			            </div>
			            <div id="visitors-map" class="bg-black" style="height: 181px;"></div>
			            <div class="list-group">
			            	
                            <a href="#" class="list-group-item list-group-item-inverse text-ellipsis">
                                <span class="badge badge-success">1</span>
                                1. Quito
                            </a> 
                            
                        </div>
			        </div>
			    </div>
			</div>
			<?php */ ?>

			<!-- end row -->
			<!-- begin row -->
		
			<!-- end row -->
		</div>
		<!-- end #content -->

		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
  <?php include("footer_meta.php")?>
	<script src="assets/plugins/sparkline/jquery.sparkline.min.js"></script>
	<script>
		$(document).ready(function() {
			App.init();
			DashboardV2.init();
			//dibujo
			var grafica_semana1 = new Array();
			var grafica_semana2 = new Array();
			<?php
			for ($i = 0, $total = count($datos_grafica_semana1); $i < $total; $i ++)
			  echo "\ngrafica_semana1[$i] = '$datos_grafica_semana1[$i]';"; 
			
			for ($i = 0, $total = count($datos_grafica_semana2); $i < $total; $i ++)
			  echo "\ngrafica_semana2[$i] = '$datos_grafica_semana2[$i]';"; 
			?>
			 var sparklineCharts = function(){


                 $("#sparkline1").sparkline(grafica_semana1, {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#3366cc','#dc3912','#d3d3d3']
                 });
                  $("#sparkline2").sparkline(grafica_semana2, {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#3366cc','#dc3912','#d3d3d3']
                 });

               
            };

            var sparkResize;

            $(window).resize(function(e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineCharts, 500);
            });

            sparklineCharts();

		});
	</script>
</body>
</html>
