<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Tag');
global $BackendUsuario, $Tag;

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
	 $nombre = escapeSQLTags($_POST['tag']['nombre']);
  // validacion de errores  
  
  //- fin validaciones

  if ($errores == 0) {

     if ($id > 0) {
     	
       $Tag->editar($id, $_POST);
			 print  "<script>window.location.href='tags.php?id=".$id."';</script>";
       exit;
       $accion = "actualizado";
     } 
     else
     {
     	 $last_id = $Tag->grabar($_POST);
			 print  "<script>window.location.href='tags.php?id=".$last_id."';</script>";
       exit;
       $accion = "actualizado";
     }
   }
 break;
}

if ($id > 0) {
  $item = $Tag->obtener($id);
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
				<li><a href="javascript:;">Tag</a></li>
				<li class="active">Nuevo Tag</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Tag<small> ingresar un nuevo Tag</small></h1>
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
                            <h4 class="panel-title">Nueva Tag</h4>
                        </div>
             <div class="panel-body panel-form">
              <form  name="frmEditar"  id="frmEditar" class="form-horizontal form-bordered" data-parsley-validate="true" method="POST" action="">
              	<input type="hidden" name="accion" value="grabar" />
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="fullname">Nombre * :</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="tag_nombre" name="tag[nombre]" style="width:300px" placeholder="Requerido" maxlength="100" data-parsley-required="true" value="<?=$item['nombre']?>"/>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Publicar Inmediatamente:</label>
									<div class="col-md-6 col-sm-6">
										
										<?php if($id > 0) { ?>
										
										<select name="activo" id="activo">
											 <option value="1" <?=($item['activo']==1) ? 'selected="selected"' : ''?>>SI</option>
											 <option value="0" <?=($item['activo']==0) ? 'selected' : ''?>>NO</option>
										</select>
										
										<?php } else { ?>

										<select name="activo" id="activo">
											 <option value="1" selected="selected">SI</option>
											 <option value="0">NO</option>
										</select>

										<?php } ?>
	
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Orden:</label>
									<div class="col-md-6 col-sm-6">
                   <input type="number" id="tag_orden" name="tag[orden]" maxlength="2" style="width:100px" class="form-control" value="<?=$item['orden']?>"/>
									</div>
								</div>

									
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="tags.php">cancelar</a>
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
