<?php
// cocinas_editar.php
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

// roles y permisos

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
$result_categorias = $Categoria->obtener_all(null, $filtro, ACTIVO,  null,  null, $id_rubro, "productos");
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
                              Editar Cocina
	                          <?php } else { ?>
	                            Agegar Cocina
                            <?php } ?>
                              </h4>
                        </div>
                        <div class="panel-body panel-form">
                        	
              <form  name="frmEditar"  id="frmEditar" class="form-horizontal form-bordered" data-parsley-validate="true" method="POST">
              	<input type="hidden" name="accion" value="grabar" />
              	<input type="hidden" name="id" value="<?=$id?>"/>
              	<input type="hidden" name="borrar" id="borrar"/>
              	<input type="hidden" name="tipo" value="cocina" />
              	
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
