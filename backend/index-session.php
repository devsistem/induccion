<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Incidencia');
global $BackendUsuario, $Producto, $Incidencia;

$BackendUsuario->EstaLogeadoBackend();


// roles y permisos
if(!$BackendUsuario->esRoot() && $_SESSION['session_root'] != 1) { 
 die;
}

$accion = ($_POST['accion']) ? $_POST['accion'] : null;

switch ($accion) {
 case 'session':
	   $BackendUsuario->logear_session($_POST['id_perfil']);
 break;
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
				
 				  <?php //- Para vendedores // ?>
 				  
          <form action="" method="POST" name="frmPrincipal" id="frmPrincipal">
          	<input type="hidden" name="accion" value="session"/>

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

				  <h1>Ingresar como</h1>
				  
				  <select name="id_perfil" id="id_perfil">
				     <option value="10">Vendedor </option>
				     <option value="3">Gerente Ventas </option>
				     <option value="4">Asesor Supervisor </option>
				     <option value="9">Gerente General </option>
				     <option value="11">Gerente Logística </option>
				     <option value="12">Asistentes </option>
				     <option value="13">Inventario </option>
				     <option value="14">Encargado Bodega </option>
				  </select>
				  
				  <input type="submit"  value="Ingresar" name="btingresar"/>
					</form>
 				  <?php //- Para supervisores // ?>
					
			    <!-- end col-3 -->
			</div>
			<!-- end row -->
	

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
