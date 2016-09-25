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

// roles y permisos

// acciones de esta pagina

switch ($accion) {

 case 'grabar':
	// datos del post
	 $nombre = escapeSQLTags($_POST['nombre']);
  
  //- fin validaciones

  if ($errores == 0) {

     if ($id > 0) {
     	
       $Producto->editar_marca($id, $_POST);
			 print  "<script>window.location.href='marcas.php?id=".$id."';</script>";
       exit;
       $accion = "actualizado";
     } 
     else
     {
     	 $last_id = $Producto->grabar_marca($_POST);
			 print  "<script>window.location.href='marcas.php?id=".$last_id."';</script>";
       exit;
       $accion = "actualizado";
     }
   }
 break;
}

if ($id > 0) {
  $item = $Producto->obtener_marca($id);
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
				<li><a href="javascript:;">Marcas</a></li>
				<li class="active">Nueva marca</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Marcas<small> ingresar una nueva marca</small></h1>
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
                              Editar marca
	                          <?php } else { ?>
	                            Agegar marca
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
  
  						
  	
	              <?php } ?>	

              	<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Tipo:</label>
									<div class="col-md-6 col-sm-6">
										<select name="tipo" id="tipo">
										  <option value="cocina">Cocina</option>
										  <option value="olla">Olla</option>

										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2" for="fullname">Marcas:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="producto_nombre" name="nombre"  maxlength="50"  value="<?=$item['nombre']?>"/>
									</div>
								</div>
								

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="marcas.php">cancelar</a>
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
