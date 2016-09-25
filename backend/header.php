<?php
loadClasses('Pedido', 'Vendedor');
global $Pedido, $Vendedor;

$result_alertas = $Pedido->obtener_alertas_all(null, " ORDER BY a.id DESC ", ACTIVO, 1, null,  $id_operador);
$filas_alertas  = @mysql_num_rows($result_alertas);

$result_pedidos_estado = $Pedido->obtener_estado_pendientes();
$filas_pedidos_estado  = @mysql_num_rows($result_pedidos_estado);
?>

			<div id="header" class="header navbar navbar-default navbar-fixed-top">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				<div class="navbar-header">
					<a href="index.php" class="navbar-brand"><img src="assets/img/logo_header.png"></a>
					<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- end mobile sidebar expand / collapse button -->
				
				<!-- begin header navigation right -->
				<ul class="nav navbar-nav navbar-right">
					<li>
						<form class="navbar-form full-width">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Enter keyword" />
								<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
							</div>
						</form>
					</li>
					<li class="dropdown">
						<a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
							<i class="fa fa-bell-o"></i>
							<span class="label"><?=$filas_pedidos_estado?></span>
						</a>
						<ul class="dropdown-menu media-list pull-right animated fadeInDown">
                            <li class="dropdown-header">Alertas (<?=$filas_pedidos_estado?>)</li>
                            <li class="media">
                                <a href="javascript:;">
                                    <div class="media-left"><i class="fa fa-bug media-object bg-red"></i></div>
                                    
                                    <div class="media-body" style="padding:5px">
                                    	<?php //+ alertas // ?>
                                    	<?php
                                    	/*
                                    	for($i=1; $i <= $filas_alertas; $i++) {
																				 $items_alertas = @mysql_fetch_array($result_alertas);
																				 $arrPedido = $Pedido->obtener($items_alertas['id_pedido']);
																			?>	 

                                        <a href="pedidos.php?id=<?=$items_alertas['id_pedido']?>"><h6 class="media-heading">(<?=$items_alertas['id_pedido']?>) - <?=$arrPedido['cliente_nombre']?> <?=$arrPedido['cliente_apellido']?></h6></a>
                                        <div class="text-muted f-s-11"><?=GetFechaTexto($items_alertas['fecha_alta'])?></div>
                                    	<?php } ?>
                                    	<?php  */?>

                                    	<?php //+ alertas // ?>


																			<?php //+ pedidos con estado 1 // ?>
                                    	<?php
                                    	for($i=1; $i <= $filas_pedidos_estado; $i++) {
																				 $items_pedidos_estado = @mysql_fetch_array($result_pedidos_estado);
																			?>	 

                                        <a href="pedidos.php?id=<?=$items_pedidos_estado['id']?>"><h6 class="media-heading">(<?=$items_pedidos_estado['id']?>) - <?=$items_pedidos_estado['cliente_nombre']?> <?=$items_pedidos_estado['cliente_apellido']?></h6></a>
                                        <div class="text-muted f-s-11"><?=GetFechaTexto($items_pedidos_estado['fecha_alta'])?></div>
                                    	<?php } ?>
                                    	<?php //+ alertas // ?>
                                    	                                    	
                                    </div>
                                    
                                </a>
                            </li>
                            <!--
                            <li class="dropdown-footer text-center">
                                <a href="javascript:;">View more</a>
                            </li>
                            -->
						</ul>
					</li>
					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							
							<?php if($BackendUsuario->esVendedor()) { ?>
								
								<img src="../adj/vendedores/<?=$_SESSION['vendedoruser']['imagen']?>">
							
							<?php } else { ?>

								<img src="../adj/vendedores/<?=$_SESSION['backenduser']['backenduser_imagen']?>">

							<?php } ?>
								 
							 
							<span class="hidden-xs"><?=$BackendUsuario->obtenerNombreCompleto()?> </span> 
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<li><a href="cuenta_editar.php">Editar Cuenta</a></li>
							<li class="divider"></li>
							<li><a href="salir.php">Salir</a></li>
						</ul>
					</li>
				</ul>
				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>