<?php
// categorias_editar.php
// 03/09/2015 13:26:50
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Categoria');
global $BackendUsuario, $Producto, $Categoria;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$errores  = 0;
$str_errors = "";

// roles y permisos

// acciones de esta pagina

switch ($accion) {

 case 'grabar':

	// datos del post
	$nombre = escapeSQLTags($_POST['nombre']);
	$id_categoria = escapeSQLTags($_POST['id_categoria']);
		 
  // validacion de errores  
  if(strlen($nombre) < 1 ) {
	  	$str_errors  .= "";
		  $css_nombre = "error";
			$errores++;
  }		

  //- fin validaciones

  if ($errores == 0) {

    if ($id > 0) {
       $Categoria->editar($id, $_POST);
       echo("<script>location.href = 'categorias.php?id=$id';</script>");
       exit;
       $accion = "actualizado";
     } 
     else
     {
       $last_id = $Categoria->grabar($_POST);
       echo("<script>location.href = 'categorias.php?id=$last_id';</script>");
       exit;
       $accion = "insertado";
     }
   }
 break;
}

// categorias raiz
$result_categorias = $Categoria->obtener_all($OrderBy , $filtro , 1, null );
$filas_categorias = @mysql_num_rows($result_categorias);

if($id > 0) {
  $item = $Categoria->obtener($id);
}
include("meta.php");
?>

	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="assets/plugins/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->

	<!-- ================== BEGIN PAGE CSS STYLE ================== -->	
	<!-- ================== END PAGE CSS STYLE ================== -->
		
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
				<li><a href="javascript:;">Categorias</a></li>
				<li class="active">Nueva Categoria</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Categorias<small> ingresaro editar una categoria</small></h1>
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
                              Editar categoria
	                          <?php } else { ?>
	                            Agegar categoria
                            <?php } ?>
                              </h4>
                        </div>
                        <div class="panel-body panel-form">
                        	
            <form  name="frmEditar"  id="frmEditar" class="form-horizontal form-bordered" data-parsley-validate="true" method="POST">
              	<input type="hidden" name="accion" value="grabar" />
              	<input type="hidden" name="id" value="<?=$id?>"/>
              	<input type="hidden" name="borrar" id="borrar"/>
              	
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
										<?php if(strlen($item['fecha_mod']) > 5) { ?>
											<h5><?=GetFechaTexto($item['fecha_mod'])?></h5>
										<?php } else { ?>
										no fue modificada
										<?php }  ?>
									</div>
								</div>

            	
	              <?php } ?>	
              	
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="admin_nombre"><strong>Categoria:</strong> *</label>
									<div class="col-md-6 col-sm-6">
										
										<select id="id_padre" name="id_padre" class="select" style="height:30px;width:300px">
											<option value="0">Raiz</option>
												<?php
					   							for($i=1; $i <= $filas_categorias; $i++) {
						 								$items = @mysql_fetch_array($result_categorias); ?>

						 								<option value="<?=$items['id']?>" <?=($items['id']==$item['id_padre']) ? 'selected' : ''?>><?=$items['nombre']?></option>
						 									
						 									<?php
						 										// subcategorias
																	 $result_subcategorias = $Categoria->obtener_subcategorias($OrderBy , $filtro , 1, $items['id'] );
																	 $filas_subcategorias = @mysql_num_rows($result_subcategorias);
																	
					   													for($k=0; $k < $filas_subcategorias; $k++) {
										 											$items_subcategorias = @mysql_fetch_array($result_subcategorias);	?> 

												 								<option value="<?=$items_subcategorias['id']?>" <?=($items_subcategorias['id']==$item['id_padre']) ? 'selected' : ''?>>--<?=$items_subcategorias['nombre']?></option>
                                <?php }	?>
                                        		
						 				<?php }	?> 
										</select>
										
									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="admin_nombre"><strong>Nombre:</strong> *</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="categoria_nombre" name="nombre" required="true" placeholder="Requerido" maxlength="60" value="<?=$item['nombre']?>">
									</div>
								</div>
								
									<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="admin_usuario"><strong>Activo: </strong> * </label>
									<div class="col-md-6 col-sm-6">
										
										<?php if($item['id'] > 0) { ?>

										<select class="form-control parsley-validated" id="categoria_activo" name="activo" data-required="true" style="width:100px">
											<option value="1" <?=($item['activo']==1) ? 'selected' : ''?>>SI</option>
											<option value="0" <?=($item['activo']==0) ? 'selected' : ''?>>NO</option>
										</select>

										<?php } else { ?>		

										<select class="form-control parsley-validated" id="categoria_activo" name="activo" data-required="true" style="width:100px">
											<option value="1">SI</option>
											<option value="0">NO</option>
										</select>

										<?php } ?>
										
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="admin_nombre"><strong>Orden:</strong> </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="number" maxlength="3" id="categoria_orden" name="orden" style="width:80px" value="<?=$item['orden']?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="categorias.php">cancelar</a>
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
			FormWysihtml5.init();
			FormSliderSwitcher.init();
		});
	</script>
</body>
</html>
