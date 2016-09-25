<?php
global $BackendUsuario;

$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];
$params   = $_SERVER['QUERY_STRING'];
$urlTemp  = explode("/",$script);
$paginaActual = $urlTemp[count($urlTemp)-1];
?>


    <div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<!-- end sidebar user -->
				<!-- begin sidebar nav -->

	<ul class="nav">
	<li class="has-sub <?=($paginaActual=='cuenta_editar.php') ? 'active' : '' ?>" >
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Perfil</span> 
						</a>
						
						<ul class="sub-menu">
							<li class="<?=( $paginaActual=='cuenta_editar.php')  ? 'active' : '' ?>"><a href="cuenta_editar.php" title=""><strong>Editar Cuenta</strong></a></li>

						</ul>
					</li>
				</li>	
				</ul>
	
			<?php if($BackendUsuario->esSupervisor()) { ?>
					
				<ul class="nav">
					<li class="nav-header">Navegacion</li>

					<li class="has-sub active">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-laptop"></i>
						    <span>Mis Vendedores</span>
					    </a>
							<ul class="sub-menu">
								
							<?php //+ mis vendedores // ?>
							<?php	
						
							$result_vendedores = $BackendUsuario->obtener_all(null, null, null, null, null, null, 1, 10, $BackendUsuario->getUsuarioId());
							$filas_vendedores = @mysql_num_rows($result_vendedores);
					   		for($i=1; $i <= $filas_vendedores; $i++) {
						 		 $items_vendedores = @mysql_fetch_array($result_vendedores);
						 	?>										

								<li class="<?=($paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidosv2.php?id=<?=$items_vendedores['id']?>" title="Pedidos "><?=$items_vendedores['nombre']?> <?=$items_vendedores['apellido']?></a></li>

							<?php
								}
							?>
							</ul>
					</li>

				 
					<li class="has-sub <?=($paginaActual=='incidencias_editar.php' || $paginaActual=='incidencias.php' || $paginaActual=='usuario_editar.php' || $paginaActual=='usuarios.php' || $paginaActual=='pedidosv2.php' || $paginaActual=='pedidos_editar.php' || $paginaActual=='despachos.php' || $paginaActual=='asignados_pedidos.php' || $paginaActual=='modelos.php' || $paginaActual=='modelos_editar.php' || $paginaActual=='reportes.php' || $paginaActual=='comicionados.php') ? 'active' : '' ?>" >
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Menu</span> 
						</a>
						<ul class="sub-menu">
							<li class="<?=( $paginaActual=='pedidos_editar.php' || $paginaActual=='asignar_pedidos.php' || $paginaActual=='pedidosv2.php' || $paginaActual=='pedidos_productos')  ? 'active' : '' ?>"><a href="pedidos_editar.php" title="Nuevo Pedido"><strong>Nuevo Pedido</strong></a></li>
							<li class="<?=( $paginaActual=='pedidos_ollas_editar.php')  ? 'active' : '' ?>"><a href="pedidos_ollas_editar.php" title="Nuevo Pedido"><strong>Nuevo Pedido Ollas</strong></a></li>
							<li class="<?=($paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidosv2.php" title="Estado Clientes">Mis Pedidos</a></li>
							<li class="<?=( $paginaActual=='pedidos_productos.php' || $paginaActual=='pedidos_productos.php')  ? 'active' : '' ?>"><a href="pedidos_productos.php" title="Seguimiento Productos">Seguimiento Productos</a></li>
						  <li  class="<?=($paginaActual=='vendedores.php')  ? 'active' : '' ?>"><a href="vendedores.php">Vendedores</a></li>
						</ul>
					</li>
					
	
			
	        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>
				
			<?php } else if($BackendUsuario->esGerenteVentas()) { ?>
	
			<ul class="nav">
					<li class="nav-header">Navegacion</li>

				 
					<li class="has-sub <?=($paginaActual == 'vendedores_inactivos.php' || $paginaActual=='pedidos_consolidado.php' || $paginaActual=='consolidado.php' || $paginaActual=='incidencias_editar.php' || $paginaActual=='incidencias.php' || $paginaActual=='usuario_editar.php' || $paginaActual=='usuarios.php' || $paginaActual=='pedidosv2.php' || $paginaActual=='pedidos_editar.php' || $paginaActual=='despachos.php' || $paginaActual=='asignados_pedidos.php' || $paginaActual=='modelos.php' || $paginaActual=='modelos_editar.php' || $paginaActual=='reportes.php' || $paginaActual=='comicionados.php' || $paginaActual=='pedidos_baja') ? 'active' : '' ?>" >
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Menu</span> 
						</a>
						
						<ul class="sub-menu">
				        <li class="<?=($paginaActual=='vendedores.php')  ? 'active' : '' ?>"><a href="vendedores.php">Listado Vendedores</a></li>
				        <li class="<?=($paginaActual=='supervisores.php')  ? 'active' : '' ?>"><a href="supervisores.php">Listado Supervisores</a></li>
							  <li class="<?=($paginaActual=='vendedores_editar.php')  ? 'active' : '' ?>"><a href="vendedores_editar.php">Nuevo Vendedor/Supervisor</a></li>
								<li class="<?=($paginaActual=='pedidos_vendedores.php')  ? 'active' : '' ?>"><a href="pedidos_vendedores.php" title="Pedidos">Pedidos</a></li>
								<li class="<?=($paginaActual=='pedidos_baja.php')  ? 'active' : '' ?>"><a href="pedidos_baja.php" title="Pedidos">Pedidos en Papelera</a></li>
								<li class="<?=($paginaActual=='vendedores_reporte.php')  ? 'active' : '' ?>"><a href="vendedores_reporte.php" title="Pedidos">Pedidos por Vendedor</a></li>
								<li class="<?=($paginaActual=='reportes.php')  ? 'active' : '' ?>"><a href="reportes.php" title="Reportes">Reportes</a></li>
								<li class="<?=($paginaActual=='comicionados.php')  ? 'active' : '' ?>"><a href="comicionados.php" title="Comicionados">Comicionados</a></li>
				        <li class="<?=($paginaActual=='consolidado.php')  ? 'active' : '' ?>"><a href="consolidado.php">Consolidado</a></li>
 				        <li class="<?=($paginaActual=='vendedores_inactivos.php')  ? 'active' : '' ?>"><a href="vendedores_inactivos.php">Listado Vendedores Inactivos</a></li>
				        <li class="<?=($paginaActual=='semanal.php')  ? 'active' : '' ?>"><a href="semanal.php">Semanal</a></li>					
				       	<!--li class="<?=( $paginaActual=='quincena.php')  ? 'active' : '' ?>"><a href="quincena.php" title="Seguimiento Productos">Seguimiento Productos</a></li-->
										<li class="<?=( $paginaActual=='quincena.php')  ? 'active' : '' ?>"><a href="quincena.php" title="Pagos Quincenales">Pagos Quincenales</a></li>




						</ul>
					</li>
					
	        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>

			<?php } else if($BackendUsuario->esVendedor()) { ?>
			
				<ul class="nav">
					<li class="nav-header">Navegacion</li>

				 
					<li class="has-sub <?=($paginaActual=='mis_pedidos_baja.php' || $paginaActual=='pedidos_editar.php' || $paginaActual=='pedidos_ollas_editar.php' || $paginaActual=='pedidosv2.php' || $paginaActual=='pedidos_productos.php') ? 'active' : '' ?>" >
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Menu</span> 
						</a>
						
						<ul class="sub-menu">
							<li class="<?=( $paginaActual=='pedidos_editar.php' || $paginaActual=='asignar_pedidos.php' || $paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidos_editar.php" title="Nuevo Pedido"><strong>Nuevo Pedido</strong></a></li>
							<li class="<?=( $paginaActual=='pedidos_ollas_editar.php')  ? 'active' : '' ?>"><a href="pedidos_ollas_editar.php" title="Nuevo Pedido"><strong>Nuevo Pedido Ollas</strong></a></li>
							<li class="<?=($paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidosv2.php" title="Mis Pedidos">Mis Pedidos</a></li>
							<li class="<?=($paginaActual=='mis_pedidos_baja.php')  ? 'active' : '' ?>"><a href="mis_pedidos_baja.php" title="Mis Pedidos en Papelera">Mis Pedidos en Papelera</a></li>
							<li class="<?=( $paginaActual=='pedidos_productos.php')  ? 'active' : '' ?>"><a href="pedidos_productos.php" title="Seguimiento Productos">Seguimiento Productos</a></li>
		
						
						</ul>
					</li>
					
	        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>				
				
	  	<?php } else if($BackendUsuario->esGerenteLogistica()) { ?>
	  	
	  	
	  		<ul class="nav">
					<li class="nav-header">Navegacion</li>
					<li class="has-sub active">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-laptop"></i>
						    <span>Mis Asistentes</span>
					    </a>
							<ul class="sub-menu">
								
						<?php //+ asistentes // ?>
							<?php	
							// solo muestra los asistentes
							$result_asistentes = $BackendUsuario->obtener_asistentes(12,ACTIVO);
							$filas_asistentes = @mysql_num_rows($result_asistentes);
					   		for($i=1; $i <= $filas_asistentes; $i++) {
						 		 $items_asistentes = @mysql_fetch_array($result_asistentes);
						 	?>										

							<li class="<?=($paginaActual=='asignados_pedidos.php')  ? 'active' : '' ?>"><a href="asignados_pedidos.php?id_asistente=<?=$items_asistentes['id']?>" title="Pedidos Asignados"><?=$items_asistentes['nombre']?> <?=$items_asistentes['apellido']?></a></li>
																		<li class="<?=( $pag=='quincena.php')  ? 'active' : '' ?>"><a href="quincena.php" title="Pagos Quincenales">Pagos Quincenales</a></li>


							<?php
								}
							?>
							</ul>
					</li>

				 
					<li class="has-sub <?=($paginaActual=='incidencias_editar.php' || $paginaActual=='incidencias.php' || $paginaActual=='usuario_editar.php' || $paginaActual=='usuarios.php' || $paginaActual=='pedidosv2.php' || $paginaActual=='pedidos_editar.php' || $paginaActual=='despachos.php' || $paginaActual=='asignados_pedidos.php' || $paginaActual=='modelos.php' || $paginaActual=='modelos_editar.php' || $paginaActual=='reportes.php' || $paginaActual=='comicionados.php' || $paginaActual=='pedidos_baja.php' || $paginaActual=='vendedores_reporte.php') ? 'active' : '' ?>" >
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Menu</span> 
						</a>
						<ul class="sub-menu">
							<li class="<?=($paginaActual=='asignados_pedidos.php')  ? 'active' : '' ?>"><a href="asignados_pedidos.php" title="Pedidos Asignados">Pedidos Asignados</a></li>
							<li class="<?=($paginaActual=='incidencias_editar.php' || $paginaActual=='incidencias.php' || $paginaActual=='pedidos_editar.php' || $paginaActual=='asignar_pedidos.php' || $paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidos_editar.php" title="Nuevo Pedido">Nuevo Pedido</a></li>
							<li class="<?=($paginaActual=='pedidos_ollas_editar.php')  ? 'active' : '' ?>"><a href="pedidos_ollas_editar.php" title="Nuevo Pedido">Seguimiento Ollas</a></li>
							<li class="<?=($paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidosv2.php" title="Estado Clientes">Seguimiento </a></li>
							<li class="<?=( $paginaActual=='pedidos_productos.php')  ? 'active' : '' ?>"><a href="pedidos_productos.php" title="Seguimiento Productos"><strong>Seguimiento Productos</strong></a></li>
							<li class="<?=($paginaActual=='pedidos_baja.php')  ? 'active' : '' ?>"><a href="pedidos_baja.php" title="Pedidos">Pedidos en Papelera</a></li>
							<li class="<?=($paginaActual=='vendedores_reporte.php')  ? 'active' : '' ?>"><a href="vendedores_reporte.php" title="Pedidos">Pedidos por Vendedor</a></li>
							<li class="<?=($paginaActual=='incidencias.php')  ? 'active' : '' ?>"><a href="incidencias.php" title="Estado Clientes">Incidencias</a></li>
							<li class="<?=($paginaActual=='incidencias_editar.php')  ? 'active' : '' ?>"><a href="incidencias_editar.php" title="Estado Clientes">Nueva Incidencia</a></li>
						</ul>
					</li>
					
	
			
	        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>
				

			<?php } else if($BackendUsuario->esInventario()) {  ?>

					<ul class="nav">
					<li class="nav-header">Navegacion</li>

				 
					<li class="has-sub <?=($paginaActual=='pedidos_editar.php' || $paginaActual=='pedidos_ollas_editar.php' || $paginaActual=='pedidosv2.php' || $paginaActual=='pedidos_ollas.php') ? 'active' : '' ?>" >
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Menu</span> 
						</a>
						
						<ul class="sub-menu">
							
									<li class="<?=( $paginaActual=='inventario.php')  ? 'active' : '' ?>"><a href="inventario.php" title="Inventario">Inventario</a></li>
									<li class="<?=( $paginaActual=='cocinas_editar.php')  ? 'active' : '' ?>"><a href="cocinas_editar.php" title="Nueva Cocina">Nueva Cocina</a></li>
									<li class="<?=( $paginaActual=='productos_olla_editar.php')  ? 'active' : '' ?>"><a href="productos_olla_editar.php" title="Nueva Olla">Nueva Olla</a></li>
									<li class="<?=( $paginaActual=='modelos.php')  ? 'active' : '' ?>"><a href="modelos.php" title="Modulos">Modelos</a></li>
									<li class="<?=( $paginaActual=='modelos_editar.php')  ? 'active' : '' ?>"><a href="modelos_editar.php" title="Modulos">Nuevo Modelo</a></li>
									<li class="<?=( $paginaActual=='colores.php')  ? 'active' : '' ?>"><a href="colores.php" title="Colores">Colores</a></li>
									<li class="<?=( $paginaActual=='color_editar.php')  ? 'active' : '' ?>"><a href="color_editar.php" title="Colores">Nuevo Color</a></li>
									<li class="<?=( $paginaActual=='marcas.php')  ? 'active' : '' ?>"><a href="marcas.php" title="Marcas">Marcas</a></li>
									<li class="<?=( $paginaActual=='marca_editar.php')  ? 'active' : '' ?>"><a href="marca_editar.php" title="Marcas">Nueva Marca</a></li>
	
						</ul>
					</li>
					
	        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>				
					  					
			<?php } else if($BackendUsuario->esRoot()) {  ?>
			  
			  	<ul class="nav">
					<li class="nav-header">Navegacion</li>

				 
					<li class="has-sub <?=($paginaActual=='pedidos_productos.php' || $paginaActual=='pedidos_consolidado.php' || $paginaActual=='pedidos_baja.php' || $paginaActual=='consolidado.php' || $paginaActual=='pedidos_editar.php' || $paginaActual=='pedidos_ollas_editar.php' || $paginaActual=='pedidosv2.php' || $paginaActual=='pedidos_ollas.php') ? 'active' : '' ?>" >
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Menu</span> 
						</a>
						
						<ul class="sub-menu">
									<li class="<?=( $paginaActual=='pedidos_editar.php' || $paginaActual=='asignar_pedidos.php' || $paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidos_editar.php" title="Nuevo Pedido"><strong>Nuevo Pedido</strong></a></li>
									<li class="<?=( $paginaActual=='pedidos_ollas_editar.php')  ? 'active' : '' ?>"><a href="pedidos_ollas_editar.php" title="Nuevo Pedido"><strong>Nuevo Pedido Producto</strong></a></li>
									<li class="<?=($paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidosv2.php" title="Estado Clientes">Seguimiento</a></li>
									<li class="<?=( $paginaActual=='pedidos_productos.php' || $paginaActual=='pedidos_productos.php')  ? 'active' : '' ?>"><a href="pedidos_productos.php" title="Pedidos Productos"><strong>Seguimiento Productos</strong></a></li>
									<li class="<?=($paginaActual=='pedidos_baja.php')  ? 'active' : '' ?>"><a href="pedidos_baja.php" title="Pedidos">Pedidos en Papelera</a></li>
									<li class="<?=($paginaActual=='vendedores_reporte.php')  ? 'active' : '' ?>"><a href="vendedores_reporte.php" title="Pedidos">Pedidos por Vendedor</a></li>							
									<li class="<?=( $paginaActual=='inventario.php')  ? 'active' : '' ?>"><a href="inventario.php" title="Inventario">Inventario</a></li>
									<li class="<?=( $paginaActual=='cocinas_editar.php')  ? 'active' : '' ?>"><a href="cocinas_editar.php" title="Nueva Cocina">Nueva Cocina</a></li>
									<li class="<?=( $paginaActual=='cocinas_editar.php')  ? 'active' : '' ?>"><a href="productos_olla_editar.php" title="Nueva Olla">Nueva Olla</a></li>
									<li class="<?=( $paginaActual=='modelos.php')  ? 'active' : '' ?>"><a href="modelos.php" title="Modulos">Modelos</a></li>
									<li class="<?=( $paginaActual=='modelos_editar.php')  ? 'active' : '' ?>"><a href="modelos_editar.php" title="Modulos">Nuevo Modelo</a></li>
									<li class="<?=( $paginaActual=='colores.php')  ? 'active' : '' ?>"><a href="colores.php" title="Colores">Colores</a></li>
									<li class="<?=( $paginaActual=='color_editar.php')  ? 'active' : '' ?>"><a href="color_editar.php" title="Colores">Nuevo Color</a></li>
									<li class="<?=( $paginaActual=='marcas.php')  ? 'active' : '' ?>"><a href="marcas.php" title="Marcas">Marcas</a></li>
									<li class="<?=( $paginaActual=='marca_editar.php')  ? 'active' : '' ?>"><a href="marca_editar.php" title="Marcas">Nueva Marca</a></li>
				       	  <li class="<?=($paginaActual=='consolidado.php')  ? 'active' : '' ?>"><a href="consolidado.php">Consolidado</a></li>
				       	  <li class="<?=($paginaActual=='semanal.php')  ? 'active' : '' ?>"><a href="semanal.php">Consolidado Semanal</a></li>
	 				        <li class="<?=($paginaActual=='vendedores_inactivos.php')  ? 'active' : '' ?>"><a href="vendedores_inactivos.php">Listado Vendedores Inactivos</a></li>
												<li class="<?=( $paginaActual=='quincena.php')  ? 'active' : '' ?>"><a href="quincena.php" title="Pagos Quincenales">Pagos Quincenales</a></li>


						</ul>

					</li>


										
					
	        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>				

		<?php } else if($BackendUsuario->esAsistente()) {  ?>					  		

			<ul class="nav">
					<li class="nav-header">Navegacion</li>

				 
					<li class="has-sub <?=($paginaActual=='pedidos_productos.php' || $paginaActual=='pedidos_editar.php' || $paginaActual=='pedidos_ollas_editar.php' || $paginaActual=='pedidosv2.php' || $paginaActual=='pedidos_ollas.php') ? 'active' : '' ?>" >
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Menu</span> 
						</a>
					<ul class="sub-menu">						  		
					  	<li class="<?=( $paginaActual=='pedidos_editar.php' || $paginaActual=='asignar_pedidos.php' || $paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidos_editar.php" title="Nuevo Pedido"><strong>Nuevo Pedido</strong></a></li>
							<li class="<?=( $paginaActual=='pedidos_ollas_editar.php')  ? 'active' : '' ?>"><a href="pedidos_ollas_editar.php" title="Nuevo Pedido"><strong>Nuevo Pedido Ollas</strong></a></li>
							<li class="<?=($paginaActual=='pedidosv2.php')  ? 'active' : '' ?>"><a href="pedidosv2.php" title="Estado Clientes">Mis Pedidos</a></li>
							<li class="<?=( $paginaActual=='pedidos_productos.php')  ? 'active' : '' ?>"><a href="pedidos_productos.php" title="Seguimiento Productos"><strong>Seguimiento Productos</strong></a></li>
					</ul>
					</li>
					
	        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>		
			<?php } ?>


			
			
				<!-- end sidebar nav -->
				
				
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>