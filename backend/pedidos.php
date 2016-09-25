<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido');
global $BackendUsuario, $Pedido;

$BackendUsuario->EstaLogeadoBackend();

// id del pedido q trae la alerta
$id = ($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

switch ($accion) {
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Pedido->eliminar($idx);
   }
 break;
 case 'publicar':
	   $Pedido->publicar($_POST['id'], $_POST['campo']);
 break;
 case 'estado':
	   $Pedido->estado($_POST['id'], $_POST['campo']);
 break;
}

// dependiendo del perfil del usuario se muestran los epdidos
// VENDEDORES
if($BackendUsuario->esVendedor()) {

	$result_todos = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, $estado, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, $BackendUsuario->getUsuarioId());
	$filas_todos = @mysql_num_rows($result_todos);

} else {

	// todos 
	$result_todos = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, $estado, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id,  null);
	$filas_todos = @mysql_num_rows($result_todos);

}
$result_estados_mostrar = $Pedido->obtener_estados(1);
$filas_estados_mostrar = @mysql_num_rows($result_estados_mostrar);

// si viene con un id, ya se cambia el estado
// el estado de nuevo se completa
if($id > 0) {
 $arrPedido = $Pedido->obtener($id);
 // leido
 $Pedido->leido($id);
}
?>
<?php include("meta.php");?>

<?php //+  acciones js // ?>
 <script>
 function eliminar() {
  if(confirm('Esta seguro de querer borrar')) {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'eliminar';
  form.submit();
  }
 }
 function publicar(idx,campo) {
 var form = document.forms['frmPrincipal'];
 form['campo'].value = campo;
 form['id'].value = idx;
 form['accion'].value = 'publicar';
 form.submit();
 }
 function destacado(idx,campo) {
 var form = document.forms['frmPrincipal'];
 form['campo'].value = campo;
 form['id'].value = idx;
 form['accion'].value = 'destacado';
 form.submit();
 }
 function estado(idx,campo) {
 var form = document.forms['frmPrincipal'];
 form['campo'].value = campo;
 form['id'].value = idx;
 form['accion'].value = 'estado';
 form.submit();
 }
 function cargarEstado(idx) {
  location.href = "pedidos.php?id="+idx;
 }

 function generar_pdf(imagen1,imagen2) {
	 var form = document.forms['frmPrincipal'];
	 form['imagen1'].value = imagen1;
	 form['imagen2'].value = imagen2;
	 form.action = "crear_pdf.php";
	 form['accion'].value = 'generar';
 	 form.submit();
 }
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
		<?php include("header.php")?>
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
				<li><a href="noticias.php">Pedidos</a></li>
				<li class="active">Listado</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">
				Pedidos <small>listado de pedidos</small>
				<?php if( $BackendUsuario->esVendedor() ) { ?>
				        <strong><?=$BackendUsuario->obtenerNombreCompleto()?></strong>
				<?php } ?>
				</h1>
			<!-- end page-header -->

		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">

			<input type="hidden" name="imagen1">
			<input type="hidden" name="imagen2">			
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
                            El Pedido ha sido eliminado correctamente.
                        </div>
	                      <?php } ?>
                        <div class="panel-body">
                            <div class="table-responsive">

                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="2%">Id</th>
                                            <th width="20%">Cliente Nombre</th>
                                            <th width="10%">Cedula Identidad</th>
                                            <th width="20%">Producto</th>
                                            <th width="15%">Estado</th>
                                            <th width="15%">Fecha Alta</th>
                                            <th width="20%">Vendedor</th>
                                            <th width="10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($k=1; $k <= $filas_todos; $k++) {
						 												$items = @mysql_fetch_array($result_todos);
						 												//$cantidad_fotos = $Foto->cantidad($items['id']);
																	?>                                     	
                                        <tr class="odd gradeX">
                                            <td><?=$items['id']?></td>
                                            <td>
                                            	<?=$items['cliente_nombre']?> <?=$items['cliente_apellido']?> 
                                              <?php if($_REQUEST['id'] == $items['id']) { ?>
                                               <strong>(actualizado)</strong>
	                                            <?php } ?>
                                            </td>
                                            <td><?=$items['cliente_dni']?></td>
                                            <td><?=$items['modelo']?> <?=$items['marca']?> <?=$items['color']?></td>
                                            <td align="center">
	                                            <!--
	                                            <a href="javascript:estado('<?=$items['id']?>','<?=$items['estado']?>');" title="estado"><b><?=($items['estado'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            	-->
                                            	<?php // estados ?>
																							<?php
																							$result_estados = $Pedido->obtener_estados(1);
																						  $filas_estados = @mysql_num_rows($result_estados);
					  							 											for($z=1; $z <= $filas_estados; $z++) {
						 																			$items_estados = @mysql_fetch_array($result_estados);
						 																			
						 																			// busca la inicidencia del estado
						 																			
						 																			$arrIncidencia = $Pedido->obtener_incidencia_by_estado($items['id'],$items_estados['id']);
						 																			$incidencia = null;
						 																				if(strlen($arrIncidencia['contenido']) > 0) {
						 																					$incidencia = "Incidencia: " . $arrIncidencia['contenido'];
						 																				}
						 																			
						 																			$tooltip = "(" . $items_estados['nombre']. ")" . "\n\n".$incidencia;
						 																			
						 																	?>
																						
																							<?php if($items_estados['id'] > 1) { ?>
						 																	 <a href="ifr_estado.php?id=<?=$items['id']?>&estado=<?=$items_estados['id']?>" class="estado">
						 																	<?php } ?>	
						 																	 	<div  data-toggle="tooltip" data-placement="top" data-original-title="Default tooltip" class="caja_estado <?=($items['estado']==$items_estados['id']) ? '':''?>" style="" title="<?=$tooltip?>">
						 																	 	 <?php // validar los estados con el log // ?>
						 																	 	 
						 																	 	 <?php 
					 																	 		    if($items_estados['id'] == 1) { 
					 																	 		    	  if($items['leido'] == 0) {	?>
									 																	 	   <img src="iconos/<?=$items_estados['icono']?>" title="<?=$items_estados['nombre']?>" border="0">
									 																<?php } else {?>	 	 
									 																	 	   <img src="iconos/<?=$items_estados['icono2']?>" title="<?=$items_estados['nombre']?>" border="0">
									 																<?php } ?>
	
			 																	 	    <?php } else if($items_estados['id'] == 2) { ?>
							 																	 	 	   
							 																	 	 	   <?php if($items['estado'] == 2) {	?>
							 																	 	 			 <?php // si tiene incidencia muestra el icono rojo ?>
							 																	 	 			 <?php if(strlen($arrIncidencia['contenido']) > 0) { ?>
							 																	 	 			 <img src="iconos/<?=$items_estados['icono4']?>" title="<?=$items_estados['nombre']?>" border="0">
								 																	 	 			<?php } else { ?>
							 																	 	 			 <img src="iconos/<?=$items_estados['icono2']?>" title="<?=$items_estados['nombre']?>" border="0">
								 																	 	 			<?php }  ?>
							 																	 	 	     <?php  } else if($items['estado'] == 3) {	?>
									 																	 	   <img src="iconos/<?=$items_estados['icono2']?>" title="<?=$items_estados['nombre']?>" border="0">
											 																<?php } else {?>	 	 
									 																	 	   <img src="iconos/<?=$items_estados['icono']?>" title="<?=$items_estados['nombre']?>" border="0">
											 																<?php } ?>

		 																	 	    <?php } else if($items_estados['id'] == 3) { ?>
					 																	 	 	   <?php if($items['estado'] == 3) {	?>
							 																	 	 			 <?php // si tiene incidencia muestra el icono rojo ?>
							 																	 	 			 <?php if(strlen($arrIncidencia['contenido']) > 0) { ?>
							 																	 	 			 <img src="iconos/<?=$items_estados['icono4']?>" title="<?=$items_estados['nombre']?>" border="0">
								 																	 	 			<?php } else { ?>
							 																	 	 			 <img src="iconos/<?=$items_estados['icono2']?>" title="<?=$items_estados['nombre']?>" border="0">
								 																	 	 			<?php }  ?>
							 																	 	 	     <?php  } else if($items['estado'] == 4) {	?>
									 																	 	   <img src="iconos/<?=$items_estados['icono2']?>" title="<?=$items_estados['nombre']?>" border="0">
											 																<?php } else {?>	 	 
									 																	 	   <img src="iconos/<?=$items_estados['icono']?>" title="<?=$items_estados['nombre']?>" border="0">
											 																<?php } ?>
		 																	 	    <?php } else if($items_estados['id'] == 4) { ?>
							 																	 	 	   <?php if($items['estado'] == 4) {	?>
							 																	 	 			 <img src="iconos/<?=$items_estados['icono2']?>" title="<?=$items_estados['nombre']?>" border="0">
									 																<?php } else {?>	 	 
									 																	 	   <img src="iconos/<?=$items_estados['icono']?>" title="<?=$items_estados['nombre']?>" border="0">
									 																<?php } ?>
									 																

		 																	 	    <?php } else { ?>

									 																	 	   <img src="iconos/<?=$items_estados['icono']?>" title="<?=$items_estados['nombre']?>" border="0">

									 												  <?php } ?>
	 																							</div>
						 																	  
																							<?php if($items_estados['id'] > 1) { ?>
						 																	     </a> 
						 																	<?php } ?>	
						 																	<?php 
						 																		} // f estados
						 																		?>

							 																
						 																		
						 																		<?php 
						 																		/*
						 																		if(strlen($items['incidencia']) > 0) { ?>
						 																		<div align="left" style="padding-top:5px"><br/>
						 																			<strong>Incidencia:</strong><br/>
						 																			<?=$items['incidencia']?>
						 																		</div>	
						 																	<?php } */ ?>
						 																		
																						</td>
                                            <td>
                                            	<?=GetFechaTexto($items['fecha_alta'])?>
                                            	/
                                            	<?php if(strlen($items['fecha_mod']) > 11) { ?>
                                            		<?=GetFechaTexto($items['fecha_mod'])?>
	                                            <?php } ?>
                                            	</td>
                                            	<td><?=$items['vendedor_nombre']?></td>
                                            <td>
                                            	<?php if(strlen($items['imagen_dni_frente_g']) > 11 && strlen($items['imagen_dni_posterior_g']) > 11) { ?>
	                                            	<a href="javascript:generar_pdf('<?=$items['imagen_dni_frente_g']?>','<?=$items['imagen_dni_posterior_g']?>')"><img src="icon_pdf.png" width="30" title="Generar PDF Dueno"></a>
                                              <?php } ?>
  
                                            	<?php if($items['dueno'] == 0) { ?>
	                                            	<?php if(strlen($items['imagen_dni_duenio_frente_g']) > 11 && strlen($items['imagen_dni_duenio_posterior_g']) > 11) { ?>
  	                                          		<a href="javascript:generar_pdf('<?=$items['imagen_dni_duenio_frente_g']?>','<?=$items['imagen_dni_duenio_posterior_g']?>')"><img src="icon_pdf.png" width="30" title="Generar PDF Arrendador"></a>
    	                                          <?php } ?>
	                                            	<?php if(strlen($items['imagen_duenio_garante_g']) > 11) { ?>
	                                            		<a href="javascript:generar_pdf('<?=$items['imagen_duenio_garante_g']?>','')"><img src="icon_pdf.png" width="30" title="Carta de Autorizacion"></a>
	  	                                          <?php } ?>
	
	                                            <?php } ?>
                                            </td>
                                        </tr>
                                  <?php
                                  } // f pedidos
                                  ?>     
                                    </tbody>
                                </table>

                            </div>
                        </div>
												<div style="padding-left:15px;padding-bottom:20px">
													      
																<table width="1000">	
                                	 <tr>
                                	  <td colspan="6"><h3>Estados</h3></td>
                                	 </tr>
                                	 <tr>
                            					<?php
			 							 											for($i=1; $i <= $filas_estados_mostrar; $i++) {
			 																			$items_estados_muestra = @mysql_fetch_array($result_estados_mostrar);
																			?>
			 																	<td width="200" valign="top">
			 																	 <div>
			 																	 	<img src="iconos/<?=$items_estados_muestra['icono']?>">
			 																	 	<img src="iconos/<?=$items_estados_muestra['icono2']?>">
			 																	 	<img src="iconos/<?=$items_estados_muestra['icono3']?>">
			 																	 	<img src="iconos/<?=$items_estados_muestra['icono4']?>">
			 																	 </div>
			 																	 <div style="clear:both"></div><br/>
			 																	 <div><strong><?=$items_estados_muestra['nombre']?></strong></div>
																			  </td>
						 													<?php } ?>
			                            	 </tr>
                                	 </table>
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
			
			$('[data-toggle="tooltip"]').tooltip(); 
			
			$(".estado").colorbox({iframe:true, width:"800px", height:"800px", left: "30%"});
 				/*
 			  $(".sipec").click(function(){	
	 				window.open("http://www.cocinasdeinduccion.gob.ec/web/guest/registro-en-el-programa", "sipec", "height=900,width=700, scroll=yes");
			  });
			  */
		});
	</script>
</body>
</html>
