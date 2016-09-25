<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Cliente', 'Vendedor');
global $BackendUsuario, $Cliente, $Vendedor;

$BackendUsuario->EstaLogeadoBackend();

// Modulo extra

$id = ($_REQUEST['id']) ? $_REQUEST['id'] : 0;
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
	$vendedor_nombre = escapeSQLTags($_POST['vendedor']['nombre']);
	$vendedor_apellidos = escapeSQLTags($_POST['vendedor']['apellidos']);
	$vendedor_email = escapeSQLTags($_POST['vendedor']['email']);
	$vendedor_usuario = escapeSQLTags($_POST['vendedor']['usuario']);
		 
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
       $Vendedor->editar($id, $_POST);
       
       @header("Location: vendedores.php?id=".$id); 
       exit;
       $accion = "actualizado";
     } 
     else
     {
       $last_id = $Vendedor->grabar($_POST);
       @header("Location: vendedores.php?id=".$last_id); 
       exit;
       $accion = "insertado";
     }
   }
 break;
}

if ($id > 0) {
  $arrActual = $Vendedor->obtener($id);
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
				<li><a href="javascript:;">Vendedores</a></li>
				<li class="active">Editar Vendedor</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Vendedores<small> ingresar o editar datos de un Vendedor</h1>
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
                            <h4 class="panel-title">Nuevo Vendedor</h4>
                        </div>
                        <div class="panel-body panel-form">


		   					  <form name="frmEditar" id="frmEditar" method="POST"  enctype="multipart/form-data" action="" class="form-horizontal form-bordered" data-validate="parsley">
							      <input type="hidden" name="id" value="<?=$id?>">
							      <input type="hidden" name="ia" value="<?=$ia?>">
							
							      <?php // usuario registrado o anonimo // ?>
							      <input type="hidden" name="id_usuario">
							
							      <input type="hidden" name="accion" value="grabar">				
							      <input type="hidden" name="estado" value="1">		
							      <input type="hidden" name="activo" value="1">		


							<?php if($BackendUsuario->esGerenteVentas()) { ?>

							<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="fullname">Tipo Usuario:</label>
									<div class="col-md-6 col-sm-6">
										<select id="registro_perfil" name="vendedor[perfil]" class="select_cocina" style="height:40px;width:100px">
											<option value="10" <?=($arrActual['perfil']=='10') ? 'selected="selected"' : ''?>>Vendedor</option>
											<option value="4"  <?=($arrActual['perfil']=='4')  ? 'selected="selected"' : ''?>>Supervisor</option>
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
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Comision por Supervisado:  </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_comicion_supervisado" name="vendedor[comicion_supervisado]" value="<?=$arrActual['comicion_supervisado']?>" maxlength="2" style="width:50px"> %
									</div>
								</div>

									<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Meta de ventas:  </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_meta_ventas" name="vendedor[meta_ventas]" value="<?=$arrActual['meta_ventas']?>" maxlength="2" style="width:50px"> 
									</div>
								</div>
																							
							<?php } ?>
								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Email: * </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_email" name="vendedor[email]" data-trigger="change" data-required="true" required="true" data-type="email" placeholder="Requerido" value="<?=$arrActual['email']?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Usuario: * (usado para login)</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_usuario" name="vendedor[usuario]" data-trigger="change" data-required="true" required="true" data-type="text" placeholder="Requerido" value="<?=$arrActual['_usuario']?>">
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
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Nombre: *</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_nombre" name="vendedor[nombre]" placeholder="" value="<?=$arrActual['nombre']?>" maxlength="100">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Apellidos: *</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_apellidos" name="vendedor[apellidos]" placeholder="" value="<?=$arrActual['apellido']?>" maxlength="200">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Edad:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="number" id="vendedor_edad" name="vendedor[edad]" placeholder="" value="<?=$arrActual['edad']?>" maxlength="2" style="width:60px">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Telefono:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="number" id="vendedor_telefono" name="vendedor[telefono]" placeholder="" value="<?=$arrActual['telefono']?>" maxlength="20" style="width:150px">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Direccion:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="vendedor_direccion" name="vendedor[direccion]" placeholder="" value="<?=$arrActual['direccion']?>" maxlength="250">
									</div>
								</div>

								<div class="form-group">
  						 <label class="control-label col-md-4 col-sm-4" for="admin_nombre">Seleccionar Foto</label>
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
									<label class="control-label col-md-4 col-sm-4" for="fullname">Observaciones:</label>
									<div class="col-md-6 col-sm-6">
										<textarea class="form-control" id="vendedor_contenido" name="vendedor[contenido]" rows="4" data-trigger="keyup" data-rangelength="[20,200]" placeholder="Rango de 20 - 1000 palabras"><?=$arrActual['contenido']?></textarea>
									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
										o
										<a href="vendedores.php">cancelar</a>
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
