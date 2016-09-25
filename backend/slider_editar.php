<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Slider');
global $BackendUsuario, $Slider;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$errores  = 0;
$str_errors = "";

print "-" . $accion;
// roles y permisos

// acciones de esta pagina

switch ($accion) {

 case 'grabar':
	// datos del post
	$imagen = escapeSQLTags($_POST['imagen']);
		 
  // validacion de errores  
  if(strlen($imagen) < 1 ) {
	  	$str_errors  .= "La imagen es requerida";
		  $css_imagen = "error";
			$errores++;
  }	  
  //- fin validaciones

  if ($errores == 0) {

     if ($id > 0) {
     	
       $Slider->editar($id, $_POST);
			 print  "<script>window.location.href='slider.php?id=".$id."';</script>";
       exit;
       $accion = "actualizado";
     } 
     else
     {
     	 $last_id = $Slider->grabar($_POST);
			 print  "<script>window.location.href='slider.php?id=".$last_id."';</script>";
       exit;
       $accion = "actualizado";
     }
   }
 break;
}

if ($id > 0) {
  $item = $Slider->obtener($id);
}
include("meta.php");
?>


	<!-- ================== BEGIN PAGE CSS STYLE ================== -->	
	<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
	<link href="assets/plugins/powerange/powerange.min.css" rel="stylesheet" />
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
				<li><a href="javascript:;">Slider</a></li>
				<li class="active">Nueva Foto</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Slider<small> ingresar un nueva noticia</small></h1>
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
                            <h4 class="panel-title">Nueva noticia</h4>
                        </div>

               				<?php //+ mensajes // ?>
                        <?php if($errores > 0) { ?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=$str_errors?>
                        </div>
	                      <?php } ?>
	                      
                        <div class="panel-body panel-form">

              <form  name="frmEditar"  id="frmEditar" class="form-horizontal form-bordered" data-parsley-validate="true" method="POST">
              	<input type="hidden" name="accion" value="grabar"/>
              	<input type="hidden" name="id" />
              	
              	
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="fullname">Titulo * :</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="slider_titulo" name="slider[titulo]" placeholder="Requerido" required="true" maxlength="250" data-parsley-required="true" value="<?=$item['titulo']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="fullname">Link:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="slider_link" name="slider[link]" placeholder="" maxlength="100"  value="<?=$item['url']?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Publicar Inmediatamente:</label>
									<div class="col-md-6 col-sm-6">
									 <input type="checkbox" data-render="switchery" name="ck_activo" data-theme="default" value="1" checked />
                  <span class="text-muted m-l-5">si</span>
									</div>
								</div>

							  <div class="form-group">
										<label class="control-label col-md-4 col-sm-4" for="admin_nombre">Orden: </label>
										<div class="col-md-6 col-sm-6">
											<input class="form-control" type="number" id="orden" name="orden" maxlength="2" style="width:100px" value="<?=$item['orden']?>">
										</div>
								</div>
																	
								<?php //+ IMAGEN /////////////////// ?>
									<div class="form-group">
  								 <label class="control-label col-md-4 col-sm-4" for="admin_nombre">Seleccionar foto para subir</label>
  								  <div class="col-md-6 col-sm-6">
											<div id="images_container"></div>
										 	<div id="iframe_container" style="height:40px">
											<iframe src="uploadForm.php?carpeta=slider" class="iframeUpload" width="100%" height="50" scrolling="no" frameborder="0"></iframe>
											</div>

										  <?php if(strlen($item['imagen_th']) > 4) { ?>
											  <img id="imagen_muestra" src="<?=URL_PATH_FRONT_ADJ?>/slider/<?=($item['imagen_th']) ? $item['imagen_th'] : '_default.gif'?>" border="1" width="75" height="75">
											<?php } elseif(strlen($item['imagen']) > 4) { ?>
											  <img id="imagen_muestra" src="<?=URL_PATH_FRONT_ADJ?>/slider/<?=($item['imagen']) ? $item['imagen'] : '_default.gif'?>" border="1" width="75" height="75">
											<?php } ?>
		
											<b>Note:</b> <font color="#C80000">la imagen no se grabara hasta que no se  muestre en pantalla. Solo acepta imagenes JPG</font>
											<input type="hidden" name="imagen" size="20" style="color: #000000;border: 1px solid #ffffff;background-color: #ffffff" value="<?=key_value('imagen', $item)?>" readonly>
											<input type="hidden" name="borrar" id="borrar">
  						  </div>
							</div>
								<?php //- IMAGEN /////////////////// ?>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="slider.php">cancelar</a>
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
