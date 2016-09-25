<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Incidencia', 'Reporte');
global $BackendUsuario, $Producto, $Incidencia, $Reporte;

$BackendUsuario->EstaLogeadoBackend();

// roles y permisos
if(!$BackendUsuario->esGerenteVentas() && !$BackendUsuario->esRoot()) { 
 die;
}	

// solo asesores comerciales
// Top vendedores

$result_top = $Reporte->obtener_top_vendedores(10, 5);
$filas_top = @mysql_num_rows($result_top);

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
				
				  <?php //+ Para vendedores // 
          	$cantidad_pedidos_vendedor   = $Pedido->cantidad_pedidos(1);
					  $cantidad_pedidos_despacho   = $Pedido->cantidad_pedidos(2);
          	$cantidad_agendar_vendedor   = $Pedido->cantidad_pedidos(3);
          	$cantidad_ingreso_vendedor   = $Pedido->cantidad_pedidos(4);
          	$cantidad_despachos_vendedor = $Pedido->cantidad_pedidos(5);
					  $cantidad_recepcion_vendedor = $Pedido->cantidad_pedidos(6);
					  $cantidad_entrega_vendedor   = $Pedido->cantidad_pedidos(7);
					  $cantidad_completados_vendedor = 0;
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
			    
			     <!-- begin col-3 -->
			    <div class="col-md-3 col-sm-6">
			        <div class="widget widget-stats bg-black">
			            <div class="stats-icon stats-icon-lg"><i class="fa fa-comments fa-fw"></i></div>
			            <div class="stats-title">RECEPCION DOCUMENTACION</div>
			            <div class="stats-number"><?=$cantidad_recepcion_vendedor?></div>
			            <div class="stats-progress progress">
                            <div class="progress-bar" style="width: 0%;"></div>
                        </div>
                        <div class="stats-desc"><a href="pedidosv2.php?#step5" class="link-dash">Listado</strong></u></h5></a></div>
			        </div>
			    </div>
			    
			     <!-- begin col-3 -->
			    <div class="col-md-3 col-sm-6">
			        <div class="widget widget-stats bg-black">
			            <div class="stats-icon stats-icon-lg"><i class="fa fa-comments fa-fw"></i></div>
			            <div class="stats-title">ENTREGA A FABRICA</div>
			            <div class="stats-number"><?=$cantidad_entrega_vendedor?></div>
			            <div class="stats-progress progress">
                            <div class="progress-bar" style="width: 0%;"></div>
                        </div>
                        <div class="stats-desc"><a href="pedidosv2.php?#step5" class="link-dash">Listado</strong></u></h5></a></div>
			        </div>
			    </div>
			    
			    			    <div class="col-md-3 col-sm-6">
			        <div class="widget widget-stats bg-black">
			            <div class="stats-icon stats-icon-lg"><i class="fa fa-comments fa-fw"></i></div>
			            <div class="stats-title">PEDIDOS COMPLETADOS</div>
			            <div class="stats-number"><?=$cantidad_completados_vendedor?></div>
			            <div class="stats-progress progress">
                            <div class="progress-bar" style="width: 0%;"></div>
                        </div>
                        <div class="stats-desc"><a href="pedidosv2.php?#step5" class="link-dash">Listado</strong></u></h5></a></div>
			        </div>
			    </div>
 				  <?php //- Para Gerente Ventas // ?>
					
					<div style="clear:both"></div><br/>

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

					<?php //- Para supervisores // ?>
					
			    <!-- end col-3 -->
			</div>
			<!-- end row -->
			
			<div class="row">
				<!-- begin col-8 -->
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
							<div id="interactive-chart" class="height-sm"></div>
						</div>
					</div>
			  </div>


				<div class="col-md-4">
					<div class="panel panel-inverse" data-sortable-id="index-6">
						<div class="panel-heading">
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
							</div>
							<h4 class="panel-title">Top 10 Vendedores</h4>
						</div>
						<div class="panel-body p-t-0">
							<table class="table table-valign-middle m-b-0">
								<thead>
									<tr>	
										<th width="60%">Vendedor<th>
										<th width="20%"></th>
										<th  width="20%"></th>
									</tr>
								</thead>
								<tbody>
								<?php
					   			for($t=1; $t <= $filas_top; $t++) {
						 			 $items_top = @mysql_fetch_array($result_top);
						 			 $cantidad = $items_top['post_count'];	
						 			?>								
									<tr>
										<td width="60%"><label class="label label-info">  <?=$items_top['apellido']?></label></td>
										<td width="20%"></td>
										<td width="20%"><label class="label"> <font color="#000000"><?=$cantidad?> ventas</font></label></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
			</div>		
				
	
			</div>		
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
	
	<script>
		$(document).ready(function() {
			App.init();
			DashboardV2.init();
		});
	</script>
</body>
</html>
