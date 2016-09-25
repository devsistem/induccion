<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Cliente');
global $BackendUsuario, $Cliente;

$BackendUsuario->EstaLogeadoBackend();

// permisos

// modulo extra

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
	$admin_nombre = escapeSQLTags($_POST['admin']['nombre']);
	$admin_apellido = escapeSQLTags($_POST['admin']['apellido']);
	$admin_email = escapeSQLTags($_POST['admin']['email']);
	$admin_clave = escapeSQLTags($_POST['clave_registro']);
	$admin_clave2 = escapeSQLTags($_POST['clave_registro2']);
	$admin_clave_actual = escapeSQLTags($_POST['clave_actual']);

		 
  // validacion de errores  
  if(strlen($admin_nombre) < 1 ) {
	  	$str_errors  .= _LANG_CLIENTE_1;
		  $css_nombre = "error";
			$errores++;
  }		

  if(strlen($admin_apellido) < 1 ) {
	  	$str_errors  .= _LANG_CLIENTE_2;
		  $css_apellidos = "error";
			$errores++;
  }		

  if(strlen($admin_email) < 1 ) {
	  	$str_errors  .= _LANG_CLIENTE_3;
		  $css_email = "error";
			$errores++;
	}

// si esta cambiando la clave
	if(strlen($admin_clave_actual) > 0 && strlen($admin_clave) && strlen($admin_clave2) )
	{
  	if(strlen($admin_clave) < 4  || strlen($admin_clave2) < 4) {
	  	$str_errors  .= _LANG_ADMIN_EDIT_4;
		  $css_pass = "error";
			$errores++;
		}

		if($admin_clave != $admin_clave2) {
	  	$str_errors  .= _LANG_ADMIN_EDIT_5;
		  $css_pass = "error";
			$errores++;
		}  
  }  
  // fin validaciones

	if ($errores == 0) {
       $BackendUsuario->editar_mi_cuenta($_POST);
       $accion = "actualizado";
  } 
  break;
}

if ($BackendUsuario->getUsuarioId() > 0) {
  $arrActual = $BackendUsuario->obtener($BackendUsuario->getUsuarioId());
}

// supervisores
$result_supervisores = $BackendUsuario->obtener_all($paginacion, $porPagina, $usuario, $email, $nombre, null, null, 4);
$filas_supervisores = @mysql_num_rows($result_supervisores);

include("meta.php");
?>

	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
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
				<li><a href="javascript:;">Mi Cuenta</a></li>
				<li class="active">Editar</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Mi Cuenta<small> editar datos de mi cuenta</h1>
			<!-- end page-header -->
			
      <?php if($accion == "actualizado") { ?>
         <div class="alert alert-success fade in m-b-15">
								<strong>Datos actualizados</strong>
								Se actualizaron los datos de su cuenta.
								<span class="close" data-dismiss="alert">&times;</span>
							</div>
    	<?php } ?>
     
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
                            <h4 class="panel-title">Editar</h4>
                        </div>
                        <div class="panel-body panel-form">


		   					  <form name="frmEditar" id="frmEditar" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">
							      <input type="hidden" name="id" value="<?=$id?>">
							      <input type="hidden" name="ia" value="<?=$ia?>">
							      <input type="hidden" name="idx" value="<?=$idx?>">
							
							      <?php // usuario registrado o anonimo // ?>
							      <input type="hidden" name="id_usuario">
							
							      <input type="hidden" name="accion" value="grabar">				
							      <input type="hidden" name="estado" value="1">		
							      <input type="hidden" name="activo" value="1">		
							      <input type="hidden" name="zoom_inicial" value="6">		
							      <input type="hidden" name="dragable" id="dragable" value="true">		
							      <input type="hidden" name="modo" value="enviar">	
							     
							     <input type="hidden" id="ciudad_mapa" name="ciudad_mapa" value="Quito">	
							     <input type="hidden" id="pais_mapa" name="pais_mapa" value="Ecuador">	
							     
							      <input type="hidden" name="latitude"   value="<?=$arrActual['mapa_latitude']?>" maxlength="10" size="10">		
							      <input type="hidden" name="longitude"  value="<?=$arrActual['mapa_longitude']?>"  maxlength="10" size="10">

			 				<?php if($arrActual['id'] > 0 ) { ?>
				 				<div class="form-group m-r-10">
									<label class="control-label col-md-4 col-sm-4" for="cliente_nombre"> #ID</label>
									<div class="col-md-6 col-sm-6">
										<?=$arrActual['id']?>
									</div>
								</div>
	
							 <?php } ?>              	

							<?php // Si es coordinador Coordinador  3
							      // puede asignar un supervisor al vendedor
							 ?>
								
								<?php if($BackendUsuario->esCordinador()) {?>	
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_nombre">Supervisor: *</label>
									<div class="col-md-6 col-sm-6">

									<select name="id_supervisor" id="id_supervisor" style="width:300px; height:40px">
							 				<option value="0">Sin asignar</option>

									 		<?php
									  		 for ($i=1; $i <= $filas_supervisores; $i++) {
													 $items_supervisores = @mysql_fetch_array($result_supervisores); ?>
											 <?php if($items_supervisores['id'] == $item['id_supervisor']) { ?>
													<option value="<?=$items_supervisores['id']?>" selected><?=$items_supervisores['nombre']?></option>
											 <?php  } else { ?>	
													<option value="<?=$items_supervisores['id']?>"><?=$items_supervisores['nombre']?></option>
 												 <?php } ?>	
            			    <?php } ?> 
										 
										 </select>

									</div>
								</div>

							 <?php } ?>              	

								<?php if($BackendUsuario->esVendedor()) {?>	
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_nombre">Supervisor: *</label>
									<div class="col-md-6 col-sm-6">

									<select name="id_supervisor" id="id_supervisor" style="width:300px; height:40px">
							 				<option value="0">Sin asignar</option>

									 		<?php
									  		 for ($i=1; $i <= $filas_supervisores; $i++) {
													 $items_supervisores = @mysql_fetch_array($result_supervisores); ?>
											 <?php if($items_supervisores['id'] == $arrActual['id_supervisor']) { ?>
													<option value="<?=$items_supervisores['id']?>" selected><?=$items_supervisores['nombre']?></option>
											 <?php  } else { ?>	
												 <?php } ?>	
            			    <?php } ?> 
										 
										 </select>

									</div>
								</div>

							 <?php } ?>              	
								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_nombre">Nombre: *</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="admin_nombre" name="admin[nombre]" required="true" placeholder="Requerido" maxlength="60" value="<?=$arrActual['nombre']?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_nombre">Apellido:*</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="admin_apellido" name="admin[apellido]" required="true" placeholder="Requerido" maxlength="60" value="<?=$arrActual['apellido']?>">
									</div>
								</div>

								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Email: * </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="admin_email" name="admin[email]"  required="true"  data-trigger="change" data-required="true" data-type="email" placeholder="Requerido" value="<?=$arrActual['email']?>">
									</div>
								</div>

							<?php if($BackendUsuario->getUsuarioId() > 0) { ?>
								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_clave">Clave Actual  :</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="password" id="admin_clave_actual" name="clave_actual"  maxlength="12">
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_clave">Nueva Clave  :</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="password" id="admin_clave" name="clave_registro"  maxlength="12">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_clave2">Repetir Nueva Clave  :</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="password" id="admin_clave2" name="clave_registro2" maxlength="12">
									</div>
								</div>
								
								<?php } else { ?>

									<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_clave"><strong>Clave:</strong></label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="password" id="admin_clave" name="admin[clave]"  maxlength="12">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_clave2"><strong>Repetir Nueva Clave:</strong></label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="password" id="admin_clave2" name="admin[clave2]" maxlength="12">
									</div>
								</div>
								
								<?php } ?>

				
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Teléfono:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="admin_telefono" name="admin[telefono]" data-type="phone" placeholder="(XXX) XXXX XXX" value="<?=$arrActual['telefono']?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Celular:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="admin_celular" name="admin[celular]" data-type="phone" placeholder="(XXX) XXXX XXX" value="<?=$arrActual['celular']?>">
									</div>
								</div>
								


								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="fullname">Observaciones:</label>
									<div class="col-md-6 col-sm-6">
										<textarea class="form-control" id="admin_contenido" name="admin[contenido]" rows="4" data-trigger="keyup" data-rangelength="[20,200]" placeholder="Rango de 20 - 1000 palabras"><?=$arrActual['contenido']?></textarea>
									</div>
								</div>


							<div class="form-group">
  						 <label class="control-label col-md-4 col-sm-4" for="admin_nombre">Seleccionar Imagen</label>
  						  <div class="col-md-6 col-sm-6">
  								<div id="images_container"></div>
									 	<div id="iframe_container" style="height:40px">
								 		<iframe src="uploadForm.php?carpeta=vendedores" class="iframeUpload" width="100%" height="50" scrolling="no" frameborder="0"></iframe>
								 		</div>
									  <img id="imagen_muestra" src="../adj/vendedores/<?=($arrActual['imagen']) ? $arrActual['imagen'] : '_default.gif'?>" border="1" width="75" height="75">
										<b>Nota:</b> <font color="#C80000">la imagen no se grabara hasta que no se  muestre en pantalla. Solo acepta imagenes JPG</font>
										<input type="hidden" name="imagen" size="20" style="color: #000000;border: 1px solid #ffffff;background-color: #ffffff" value="<?=key_value('imagen', $arrActual)?>" readonly>
  						  </div>											
							</div>



								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="index.php">cancelar</a>
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
