<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Cliente');
global $BackendUsuario, $Cliente;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteGeneral() && !$BackendUsuario->esGerenteVentas() && !$BackendUsuario->esRoot()) {
 die;
}
	
// Modulo extra

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$errores  = 0;
$str_errors = "";

// configuracion del form
$conf_nombre = true;
$conf_apellidos = true;
$conf_email = true;

// roles y permisos

// acciones de esta pagina

switch ($accion) {

 case 'grabar':

	// datos del post
	$vendedor_nombre = escapeSQLTags($_POST['nombre']);
	$vendedor_apellidos = escapeSQLTags($_POST['apellido']);
	$vendedor_email = escapeSQLTags($_POST['email']);
	$vendedor_usuario = escapeSQLTags($_POST['usuario']);
		 
  // validacion de errores  
  /*
  if(strlen($cliente_nombre) < 1 ) {
	  	$str_errors  .= _LANG_CLIENTE_1;
		  $css_nombre = "error";
			$errores++;
  }		

  if(strlen($cliente_apellidos) < 1 ) {
	  	$str_errors  .= _LANG_CLIENTE_2;
		  $css_apellidos = "error";
			$errores++;
  }
  */

  if(strlen($vendedor_email) < 1 ) {
	  	$str_errors  .= _LANG_CLIENTE_3;
		  $css_email = "error";
			$errores++;
	}	
  
  // fin validaciones

  if ($errores == 0) {

    if ($id > 0) {
       $BackendUsuario->editar($id, $_POST);
			 print  "<script>window.location.href='usuarios.php?id=".$id."';</script>";
       exit;
       $accion = "actualizado";
     } 
     else
     {
       $last_id = $BackendUsuario->grabar($_POST);
			 print  "<script>window.location.href='usuarios.php?id=".$last_id."';</script>";
       exit;
       $accion = "insertado";
     }
   }
 break;
}

if ($id > 0) {
  $arrActual = $BackendUsuario->obtener($id);
}

// supervisores
$result_supervisores = $BackendUsuario->obtener_all(null, null, null, null, null, null, ACTIVO, 4, null);
$filas_supervisores = @mysql_num_rows($result_supervisores);

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
				<li><a href="javascript:;">Usuarios</a></li>
				<li class="active">Editar Usuarios</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Usuarios<small> ingresar o editar datos de un Usuarios</h1>
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
                            <h4 class="panel-title">Nuevo Usuarios</h4>
                        </div>
                        <div class="panel-body panel-form">


		   					  <form name="frm_editar" id="frm_editar" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">
							      <input type="hidden" name="id" value="<?=$id?>">
							      <input type="hidden" name="ia" value="<?=$ia?>">
							      <input type="hidden" name="accion" value="grabar">				
							      <input type="hidden" name="estado" value="1">		
							      <input type="hidden" name="activo" value="1">		

								<?php if($arrActual['id'] > 0) { ?>

							   <div class="form-group">
								  	<label class="control-label col-md-4 col-sm-4" for="admin_nombre">Id #: </label>
									  <div class="col-md-6 col-sm-6">
												<?=$arrActual['id']?>
										</div>
									</div>
								
								<?php } ?>	
									
								   <div class="form-group">
								  	<label class="control-label col-md-4 col-sm-4" for="admin_nombre">Tipo Usuario: *</label>
									  <div class="col-md-6 col-sm-6">
											<select name="id_perfil" id="id_perfil" style="width:300px; height:40px">
													<option value="10" <?=($arrActual['perfil']=='10') ? 'selected' : ''?>>Asesor Comercial</option>
													<option value="4"  <?=($arrActual['perfil']=='4') ? 'selected' : ''?>>Asesor Supervisor</option>
										 </select>

										</div>
									</div>
									
								   <div class="form-group">
								  	<label class="control-label col-md-4 col-sm-4" for="admin_nombre">Supervisor: *</label>
									  <div class="col-md-6 col-sm-6">

											<select name="id_supervisor" id="id_supervisor" style="width:300px; height:40px">
							 				<option value="0">Sin asignar</option>

									 		<?php
									  		 for ($i=1; $i <= $filas_supervisores; $i++) {
													 $items_supervisores = @mysql_fetch_array($result_supervisores); ?>
											 <?php if($items_supervisores['id'] == $arrActual['id_supervisor']) { ?>
													<option value="<?=$items_supervisores['id']?>" selected="selected"><?=$items_supervisores['nombre']?> <?=$items_supervisores['apellido']?></option>
											 <?php  } else { ?>	
													<option value="<?=$items_supervisores['id']?>"><?=$items_supervisores['nombre']?> <?=$items_supervisores['apellido']?></option>
 												 <?php } ?>	
            			    <?php } ?> 
										 
										 </select>

										</div>
									</div>
																
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Email: * </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_email" name="email" data-trigger="change" data-required="true" required="true" data-type="email" placeholder="Requerido" value="<?=$arrActual['email']?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Usuario: * (usado para login)</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_usuario" name="usuario" data-trigger="change" data-required="true" required="true" data-type="text" placeholder="Requerido" value="<?=$arrActual['_usuario']?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Clave: * (usado para login)</label>
									<div class="col-md-6 col-sm-6">
										<input type="checkbox" name="clave" value="1" CHECKED disabled /> Generada por el sistema
										
										<?=$arrActual['clave_registro']?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Nombre:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_nombre" name="nombre" placeholder="" value="<?=$arrActual['nombre']?>" maxlength="100">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Apellidos:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_apellidos" name="apellido" placeholder="" value="<?=$arrActual['apellido']?>" maxlength="200">
									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="fullname">Observaciones:</label>
									<div class="col-md-6 col-sm-6">
										<textarea class="form-control" id="vendedor_contenido" name="contenido" rows="4" data-trigger="keyup" data-rangelength="[20,200]" placeholder="Rango de 20 - 1000 palabras"><?=$arrActual['contenido']?></textarea>
									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="usuarios.php">cancelar</a>
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
