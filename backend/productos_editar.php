<?php
// productos_editar.php
// 17/08/2015 6:21:11
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario','Producto','Categoria');
global $BackendUsuario,$Producto, $Categoria;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esRoot() && !$BackendUsuario->esInventario()) {
 die;
}

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$errores  = 0;
$str_errors = "";

// modelos cocina
$result_modelos_cocina = $Producto->obtener_modelos( ACTIVO, 'cocina');
$filas_modelos_cocina = @mysql_num_rows($result_modelos_cocina);

// modelos color
$result_modelos_color = $Producto->obtener_colores( ACTIVO, null);
$filas_modelos_color = @mysql_num_rows($result_modelos_color);

// modelos marca
$result_modelos_marca = $Producto->obtener_marcas( ACTIVO, 'cocina');
$filas_modelos_marca = @mysql_num_rows($result_modelos_marca);

$filas_espeficaciones = 0;
$filas_espeficaciones_nuevas = 0;
$result_espeficaciones = $Producto->obtener_espeficaciones(" ORDER BY e.orden ASC ",$id);
$filas_espeficaciones = @mysql_num_rows($result_espeficaciones);
$filas_espeficaciones_nuevas = 20 - $filas_espeficaciones;

$filas_caracteristicas = 0;
$filas_caracteristicas_nuevas = 0;
$result_caracteristicas = $Producto->obtener_caracteristicas(" ORDER BY e.orden ASC ",$id);
$filas_caracteristicas = @mysql_num_rows($result_caracteristicas);
$filas_caracteristicas_nuevas = 10 - $filas_caracteristicas;

// acciones de esta pagina

switch ($accion) {

 case 'grabar':
	// datos del post
	 $nombre = escapeSQLTags($_POST['producto']['nombre']);
  // validacion de errores  
  // validar codigo repetido
  // $existe_codigo =
  
  //- fin validaciones

  if ($errores == 0) {

     if ($id > 0) {
     	
       $Producto->editar($id, $_POST);
			 print  "<script>window.location.href='productos.php?id=".$id."';</script>";
       exit;
       //header("Location: productos.php?id=".$id."");
       //exit;
       $accion = "actualizado";
     } 
     else
     {
     	 $last_id = $Producto->grabar($_POST);
			 print  "<script>window.location.href='productos.php?id=".$last_id."';</script>";
       exit;
       //header("Location: productos.php?id=".$id."");
       //exit;
       $accion = "actualizado";
     }
   }
 break;
}

if ($id > 0) {
  $item = $Producto->obtener($id);
}

// categorias de productos
$result_categorias = $Categoria->obtener_all(null, $filtro, ACTIVO,  null,  null, null, null);
$filas_categorias   = @mysql_num_rows($result_categorias);
 
include("meta.php");
?>

	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="assets/plugins/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->

	<!-- ================== BEGIN PAGE CSS STYLE ================== -->	
	<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
	<link href="assets/plugins/powerange/powerange.min.css" rel="stylesheet" />
	<!-- ================== END PAGE CSS STYLE ================== -->
	<script>
		function modelos() {
		  $("#divmodelos").text("");
		  var id_marca = $("#registro_marca").val();
		  var _url =  'ax_modelos_json.php?id_marca='+id_marca;
      
      $("#divmodelostxt").text("Cargando...");	
			$.post(_url,function(result){

			  dataItem = $.parseJSON(result);
			 	var html = "";
			 	html += '<select class="select_cocina"  name="registro[modelo]" style="width:160px" id="registro_id_modelo">';
			 	for(i=0; i < dataItem['cantidad'][0]; i++) {
	  			 html += ' <option value="'+dataItem['id'][i]+'">'+dataItem['nombre'][i]+'</option>';
			  }
			 	html += '</select>';
			 	
	      $("#registro_modelo_lectura").hide();
	      $("#divmodelostxt").text("");	
		  	$("#divmodelos").append(html);	 	
			});
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
				<li><a href="javascript:;">Portada</a></li>
				<li><a href="javascript:;">Productos</a></li>
				<li class="active">Nueva Cocina</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Productos<small> ingresar una nueva cocina</small></h1>
			<!-- end page-header -->
			
       
       <!-- begin row -->
			<div class="row">
                <!-- begin col-6 -->
			    <div class="col-md-12">
			        <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">
                            <?php if ($id > 0) { ?>
                              Editar Producto
	                          <?php } else { ?>
	                            Agegar Producto
                            <?php } ?>
                              </h4>
                        </div>
                        <div class="panel-body panel-form">
                        	
              <form  name="frmEditar"  id="frmEditar" class="form-horizontal form-bordered" data-parsley-validate="true" method="POST">
              	<input type="hidden" name="accion" value="grabar" />
              	<input type="hidden" name="id" value="<?=$id?>"/>
              	<input type="hidden" name="borrar" id="borrar"/>
              	<input type="hidden" name="tipo" value="producto" />
              	
              	<?php // evitar repost // ?>
              
              	<?php if($id > 0 ) { ?>

  							<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname"><strong>Id#</strong> </label>
									<div class="col-md-6 col-sm-6">
										<h5><?=$item['id']?></h5>
									</div>
								</div>
  
  							<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname"><strong>Fecha Alta:</strong></label>
									<div class="col-md-6 col-sm-6">
										<h5><?=GetFechaTexto($item['fecha_alta'])?></h5>
									</div>
								</div>

  							<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname"><strong>Modificación:</strong></label>
									<div class="col-md-6 col-sm-6">
										<h5><?=GetFechaTexto($item['fecha_mod'])?></h5>
									</div>
								</div>
  	
	              <?php } ?>	
								
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Nombre: *</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_nombre" name="producto[nombre]" placeholder="Requerido" required="true" maxlength="250" data-parsley-required="true" value="<?=$item['nombre']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">CODIGO: *</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_codigo" name="producto[codigo]" placeholder="Requerido" required="true" maxlength="20" data-parsley-required="true" value="<?=$item['codigo']?>"/>
									</div>
								</div>
																
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Categoria: *</label>
									<div class="col-md-6 col-sm-6">
										
										<select name="id_categoria" id="id_categoria" style="font-size:14px;width:300px; height:40px">
							 				<option value="0">-- Sin Asignar --</option>
												<?php
					   							for($i=1; $i <= $filas_categorias; $i++) {
						 								$items = @mysql_fetch_array($result_categorias); ?>

						 								<option value="<?=$items['id']?>" <?=($items['id']==$item['id_categoria']) ? 'selected' : ''?>><?=$items['nombre']?></option>
						 									
						 									<?php
						 										// subcategorias
																	 $result_subcategorias = $Categoria->obtener_subcategorias($OrderBy , $filtro , 1, $items['id'] );
																	 $filas_subcategorias = @mysql_num_rows($result_subcategorias);
																	
					   													for($k=0; $k < $filas_subcategorias; $k++) {
										 											$items_subcategorias = @mysql_fetch_array($result_subcategorias);	?> 

												 								<option value="<?=$items_subcategorias['id']?>" <?=($items_subcategorias['id']==$item['id_categoria']) ? 'selected' : ''?>>--<?=$items_subcategorias['nombre']?></option>
                                
                                
                                         <?php //+ subcateogrias nivel 2 // ?>
 									                       <?php 
																						$result_subcategorias2 = $Categoria->obtener_subcategorias(null , $filtro , 1, $items_subcategorias['id'] );
																						$filas_subcategorias2 = @mysql_num_rows($result_subcategorias2);
					   																	for($j=0; $j < $filas_subcategorias2; $j++) {
										 													 $items_subcategorias2 = @mysql_fetch_array($result_subcategorias2);	?> 
				
																 								<option value="<?=$items_subcategorias2['id']?>" <?=($items_subcategorias2['id']==$item['id_categoria']) ? 'selected' : ''?>>----<?=$items_subcategorias2['nombre']?></option>
        
				
				                                <?php }	?>

                                <?php }	?>
                                        		
						 				<?php }	?> 
										 
										 </select>
								
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Marca:</label>
									<div class="col-md-6 col-sm-6">
										<select id="registro_marca" name="registro[marca]" class="select_cocina" onChange="modelos()">
			  	 						<option value="">MARCA</option>	
											 <?php	
													for($i=1; $i <= $filas_modelos_marca; $i++) {
															$items_modelos_marca = @mysql_fetch_array($result_modelos_marca); ?>
													 		 <option value="<?=$items_modelos_marca['id']?>"   <?=($_POST['registro']['marca']==$items_modelos_marca['nombre']) ? 'selected' : ''?>><?=$items_modelos_marca['nombre']?></option>
					 							<?php } ?>	 
										</select>	
									</div>
								</div>
								              	
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Modelo:</label>
									<div class="col-md-6 col-sm-6">
										
										<select id="registro_modelo_lectura" name="registro[modelo_lectura]"  DISABLED class="select_cocina">
			  	    				<option>MODELO</option>
			  	    			</select>
										<div id="divmodelos"></div>
										<div id="divmodelostxt" class="cargando"></div>
										<input type="hidden" id="registro_id_modelo" />
										</div>
									</div>



								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Color:</label>
									<div class="col-md-6 col-sm-6">
										<select id="registro_color" name="registro[color]"  class="select_cocina">
	  		 							<option value="">COLOR</option>
											 <?php	
												for($i=1; $i <= $filas_modelos_color; $i++) {
													$items_modelos_color = @mysql_fetch_array($result_modelos_color); ?>
											 		 <option value="<?=$items_modelos_color['nombre']?>"   <?=($_POST['registro']['color']==$items_modelos_color['nombre']) ? 'selected' : ''?>><?=$items_modelos_color['nombre']?></option>
											 	<?php } ?>	 
										</select>		
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Descripcion Breve:</label>
									<div class="col-md-10 col-sm-10">
										<textarea class="form-control" id="producto_descripcion" name="producto[descripcion]"/><?=$item['descripcion']?></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Detalles:</label>
									<div class="col-md-10 col-sm-10">
										<textarea class="ckeditor" id="editor1" name="producto[contenido]" rows="20"><?=$item['contenido']?></textarea>
									</div>
								</div>


							<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Precio Anterior: </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_precio_anterior" name="producto[precio_anterior]" maxlength="10" style="width:100px" value="<?=$item['precio_anterior']?>"/>
										<select name="precio_moneda" style="height:30px">
											<option value="1">U$D</option>
										</select>
									</div>
								</div>								


							 <div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Precio Oferta: </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_precio_tienda" name="producto[precio_tienda]" maxlength="10" style="width:100px" value="<?=$item['precio_tienda']?>"/>
										<select name="precio_moneda" style="height:30px">
											<option value="1">U$D</option>
										</select>
									</div>
								</div>	

							 <div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Precio Entrada: </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_precio_entrada" name="producto[precio_entrada]" maxlength="10" style="width:100px" value="<?=$item['precio_entrada']?>"/>
									</div>
								</div>

							 <div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Costo Envio: </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_costo_envio" name="producto[costo_envio]" maxlength="10" style="width:100px" value="<?=$item['costo_envio']?>"/>
									</div>
								</div>	
																
				 		<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname"><h2>Características</h2></label>
						  </div>
						  
		
							<div class="form-group">
						<?php 
							  // Características
								for($i=0; $i < $filas_caracteristicas; $i++) {
									$items_caracteristicas = @mysql_fetch_array($result_caracteristicas); ?>
							   	<div class="row">
										<div class="col-md-4">
											Nombre: <input class="form-control" type="text" id="producto_caracteristica_nombre" name="caracteristica_nombre[]" maxlength="100" value="<?=$items_caracteristicas['nombre']?>"/>
	  	               </div>
										<div class="col-md-4">
											Caracteristica: <textarea class="form-control" type="text" id="producto_caracteristica_nombre" name="caracteristica_valor[]"  maxlength="1000" style="height:80px"><?=$items_caracteristicas['valor']?></textarea>
                   </div>
								 </div>
							<?php } ?>
							</div>

						<div class="form-group">
							<?php 
							  // Características
								for($i=$filas_caracteristicas; $i < $filas_caracteristicas_nuevas; $i++) {
									$items_caracteristicas = @mysql_fetch_array($result_caracteristicas); ?>
									<div class="row">
										<div class="col-md-4">
											Nombre: <input class="form-control" type="text" id="producto_caracteristica_nombre" name="caracteristica_nombre[]" maxlength="100" value=""/>
	  	               </div>
										<div class="col-md-4">
											Caracteristica: <textarea class="form-control" type="text" id="producto_caracteristica_nombre" name="caracteristica_valor[]"  maxlength="1000" style="height:80px"/></textarea>
                   </div>
							  </div>
							
							<?php } ?>
							</div>
							
					 		<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname"><h2>Especificaciones</h2></label>
						  </div>

							<div class="form-group">
							<?php 
							  // espeficaciones
								for($i=0; $i < $filas_espeficaciones; $i++) {
									$items_espeficaciones = @mysql_fetch_array($result_espeficaciones); ?>
									
									<div class="row">
 										<div class="col-md-4">
											Nombre: <input class="form-control" type="text" id="producto_especificacion_nombre_<?=$i?>" name="especificacion_nombre[]" maxlength="100" value="<?=$items_espeficaciones['nombre']?>"/>
										</div>
										<div class="col-md-4">
											Caracteristica: <input class="form-control" type="text" id="producto_especificacion_detalle_<?=$i?>"" name="especificacion_detalle[]"  maxlength="200" value="<?=$items_espeficaciones['detalle']?>"/>
										</div>
										<div class="col-md-4">
											Descripción: <input class="form-control" type="text" id="producto_especificacion_nombre_<?=$i?>"" name="especificacion_valor[]"  maxlength="200" value="<?=$items_espeficaciones['descripcion']?>"/>
										</div>
									</div>
							<?php } ?>	
							</div>
							
							<div style="clear:both"></div>			

							<div class="form-group">
							<?php 
							  // espeficaciones
								for($i=$filas_espeficaciones; $i < $filas_espeficaciones_nuevas; $i++) {
									$items_espeficaciones = @mysql_fetch_array($result_espeficaciones); ?>
								<div class="row">
 										<div class="col-md-4">
											Nombre: <input class="form-control" type="text" id="producto_especificacion_nombre_<?=$i?>" name="especificacion_nombre[]" maxlength="100" value=""/>
									  </div>
									  <div class="col-md-4">
  										Caracteristica: <input class="form-control" type="text" id="producto_especificacion_detalle_<?=$i?>"" name="especificacion_detalle[]"  maxlength="200" value=""/>
									  </div>
									  <div class="col-md-4">
  										Descripción: <input class="form-control" type="text" id="producto_especificacion_nombre_<?=$i?>"" name="especificacion_valor[]"  maxlength="200" value=""/>
									  </div>
							  </div>
							
							<?php } ?>
							</div>
							
							
							<div class="form-group">
									<div class="col-md-12 col-sm-12">
										 <strong>COCINAS EXTRAS </strong>
									</div>
							</div>
								
								<?php // para cocinas // ?>
																
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Caracteristicas :</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_caracteristicas" name="caracteristicas"  maxlength="250"  value="<?=$item['caracteristicas']?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Fabrica:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_fabrica" name="fabrica"  maxlength="50"  value="<?=$item['fabrica']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Serie:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_serie" name="serie"  maxlength="50" value="<?=$item['serie']?>"/>
									</div>
								</div>
			
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">RUC Fabricante:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_ruc_fabricante" name="ruc_fabricante"  maxlength="50"  value="<?=$item['ruc_fabricante']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Precio Fabrica:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_precio_fabrica" name="precio_fabrica"  maxlength="50" value="<?=$item['precio_fabrica']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Precio Contado:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_contado" name="precio_contado"  maxlength="20" value="<?=$item['precio_contado']?>"/>
									</div>
								</div>
			
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Precio Tarjeta:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_precio_tarjeta" name="precio_tarjeta"  maxlength="20" value="<?=$item['precio_tarjeta']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Factura Ingreso:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_factura_ingreso" name="factura_ingreso"  maxlength="10" value="<?=$item['factura_ingreso']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="productos.php">cancelar</a>
									</div>
								</div>
               </form>
             </div>
          </div>
                    <!-- end panel -->
         </div>
        </div>
		</div>
		<!-- end #content -->
	
  <?php include("footer_meta.php")?>


	<script src="assets/plugins/ckeditor/ckeditor.js"></script>
	<script src="assets/plugins/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.js"></script>
	<script src="assets/plugins/bootstrap-wysihtml5/src/bootstrap-wysihtml5.js"></script>
	<script src="assets/js/form-wysiwyg.demo.min.js"></script>
	<script src="assets/plugins/switchery/switchery.min.js"></script>
	<script src="assets/plugins/powerange/powerange.min.js"></script>
	<script src="assets/js/form-slider-switcher.demo.min.js"></script>
	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
</body>
</html>
