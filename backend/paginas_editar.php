<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Noticia');
global $BackendUsuario, $Noticia;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$errores  = 0;
$str_errors = "";

// roles y permisos

// acciones de esta pagina

// test
$Noticia->grabar($_POST);

switch ($accion) {

 case 'grabar':
	// datos del post
	 $titulo = escapeSQLTags($_POST['noticia']['titulo']);
  // validacion de errores  
  
  //- fin validaciones

  if ($errores == 0) {

     if ($id > 0) {
     	
       $Noticia->editar($id, $_POST);
			 print  "<script>window.location.href='noticias.php?id=".$id."';</script>";
       exit;
       $accion = "actualizado";
     } 
     else
     {
     	 $last_id = $Noticia->grabar($_POST);
			 print  "<script>window.location.href='noticias.php?id=".$last_id."';</script>";
       exit;
       $accion = "actualizado";
     }
   }
 break;
}

if ($id > 0) {
  $item = $Noticia->obtener($id);
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
				<li><a href="javascript:;">Noticias</a></li>
				<li class="active">Nueva Noticia</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Noticias<small> ingresar un nueva noticia</small></h1>
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
             <div class="panel-body panel-form">
              <form  name="frmEditar"  id="frmEditar" class="form-horizontal form-bordered" data-parsley-validate="true">
              	<input type="hidden" name="accion" />
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="fullname">Titulo * :</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="noticia_titulo" name="noticia[titulo]" placeholder="Requerido" maxlength="250" data-parsley-required="true" value="<?=$item['titulo']?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Descripcion Breve (0 chars min, 500 max) :</label>
									<div class="col-md-6 col-sm-6">
										<textarea class="form-control" id="noticia_descripcion" name="noticia[descripcion]" rows="4" data-parsley-range="[00,500]" placeholder="Entre 0 y 500 caracteres"><?=$item['descripcion']?></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Descripcion Breve (0 chars min, 500 max) :</label>
									<div class="col-md-6 col-sm-6">
										<textarea class="ckeditor" id="editor1" name="noticia[contenido]" rows="20"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Publicar Inmediatamente:</label>
									<div class="col-md-6 col-sm-6">
									 <input type="checkbox" data-render="switchery" data-theme="default"  checked />
                  <span class="text-muted m-l-5">si</span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Programar Publicación:</label>
									<div class="col-md-6 col-sm-6">
									</div>
								</div>								
							  <div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Publicar en Facebook:</label>
									<div class="col-md-6 col-sm-6">
									 <input type="checkbox" id="facebook_publica" value="1" name="ck_facebook_publica" data-render="switchery" data-theme="default" />
                  <span class="text-muted m-l-5">no</span>
									</div>
								</div>



								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="noticias.php">cancelar</a>
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
