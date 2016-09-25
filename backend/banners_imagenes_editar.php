<?php
// banners_imagenes_editar.php
// 17/08/2015 6:21:11
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario','Producto','Banner');
global $BackendUsuario,$Producto, $Banner;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esRoot() && !$BackendUsuario->esInventario()) {
 die;
}

$id = ($_POST['id']) ? $_POST['id'] : 0; // id banner imagen
$id_banner = ($_POST['id_banner']) ? $_POST['id_banner'] : 0; // id banner
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$errores  = 0;
$str_errors = "";

// roles y permisos

// acciones de esta pagina

switch ($accion) {

 case 'grabar':
	// datos del post
  
  //- fin validaciones

  if ($errores == 0) {

     if ($id > 0) {
     	
       $Banner->editar_fotos($id, $_POST);
			 print  "<script>window.location.href='banners_imagenes.php?id=".$id."';</script>";
       exit;
       $accion = "actualizado";
     } 
     else
     {
     	 $last_id = $Banner->grabar_modelo($_POST);
			 print  "<script>window.location.href='modelos.php?id=".$last_id."';</script>";
       exit;
       $accion = "actualizado";
     }
   }
 break;
}

if($id > 0) {
  $item = $Banner->obtener_foto($id);
  $itemBanner = $Banner->obtener($item['id_banner']);

}

include("meta.php");
?>

	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="assets/plugins/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->

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
				<li><a href="javascript:;">Banner</a></li>
				<li class="active">Nuevo <?=$item['nombre']?></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Banners<small> ingresar un nuevo <strong><?=$item['nombre']?></strong></small></h1>
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
                              Editar Banner 
	                          <?php } else { ?>
	                            Agegar Banner
                            <?php } ?>
                              </h4>
                        </div>
                        <div class="panel-body panel-form">
                        	
              <form  name="frmEditar"  id="frmEditar" class="form-horizontal form-bordered" data-parsley-validate="true" method="POST">
              	<input type="hidden" name="accion" value="grabar" />
              	<input type="hidden" name="id" value="<?=$id?>"/>
              	<input type="hidden" name="id_banner" value="<?=$itemBanner['id_banner']?>"/>
              	<input type="hidden" name="borrar" id="borrar"/>
              	
              	<?php // evitar repost // ?>
              
              	<?php if($id > 0 ) { ?>

  							<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname"><strong>Id#</strong> </label>
									<div class="col-md-6 col-sm-6">
										<h5><?=$item['id']?></h5>
									</div>
								</div>
  
  						
  	
	              <?php } ?>	


								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Texto 1:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_texto1" name="texto1"  maxlength="200"  value="<?=$item['texto1']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Texto 2:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_texto2" name="texto2"  maxlength="200"  value="<?=$item['texto2']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Texto Boton:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_boton" name="boton"  maxlength="100"  value="<?=$item['boton']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Url :</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_url" name="url"  maxlength="250"  value="<?=$item['url']?>"/>
									</div>
								</div>
							
							<?php if($id > 0 ) { ?>

							<div class="form-group">
  						 <label class="control-label col-md-2 col-sm-2" for="admin_nombre">Seleccionar Banner</label>
  						  <div class="col-md-6 col-sm-6">
  								<div id="images_container"></div>
									 	<div id="iframe_container" style="height:40px">
								 		<iframe src="uploadForm.php?carpeta=banners" class="iframeUpload" width="100%" height="50" scrolling="no" frameborder="0"></iframe>
								 		</div>
									  <img id="imagen_muestra" src="../adj/banners/<?=($item['imagen']) ? $item['imagen'] : '_default.gif'?>" border="1">
										<br><br>
										<b>Nota:</b> <br/>
										<font color="#C80000">
											La imagen no se grabara hasta que no se  muestre en pantalla. 
											<br>
											Solo acepta imagenes JPG
										</font>
										<input type="hidden" name="imagen" size="20" value="<?=key_value('imagen', $item)?>">
  						  </div>											
							</div>
	
							<?php } else { ?>

							<div class="form-group">
  						 <label class="control-label col-md-2 col-sm-2" for="admin_nombre">Seleccionar Banner</label>
  						  <div class="col-md-6 col-sm-6">
  								<div id="images_container"></div>
									 	<div id="iframe_container" style="height:40px">
									 		<iframe src="uploadForm.php?carpeta=banners" class="iframeUpload" width="100%" height="50" scrolling="no" frameborder="0"></iframe>
								 		</div>
									  <img id="imagen_muestra" border="1">
									  <br><br>
										<b>Nota:</b> <br/>
										<font color="#C80000">
											La imagen no se grabara hasta que no se  muestre en pantalla. 
											<br>
											Solo acepta imagenes JPG
										</font>
										<input type="hidden" name="imagen" size="20">
  						  </div>											
							</div>

							<?php } ?>

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
