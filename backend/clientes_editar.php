<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Cliente');
global $BackendUsuario, $Cliente;

$BackendUsuario->EstaLogeadoBackend();

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
	$cliente_nombre = escapeSQLTags($_POST['cliente']['nombre']);
	$cliente_apellidos = escapeSQLTags($_POST['cliente']['apellidos']);
	$cliente_email = escapeSQLTags($_POST['cliente']['email']);
		 
  // validacion de errores  
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

  if(strlen($cliente_email) < 1 ) {
	  	$str_errors  .= _LANG_CLIENTE_3;
		  $css_email = "error";
			$errores++;
	  }	
  
  // fin validaciones

  if ($errores == 0) {

    if ($id > 0) {
       $Cliente->editar($id, $_POST);
       
       @header("Location: clientes.php?id=".$id); 
       exit;
       $accion = "actualizado";
     } 
     else
     {
       $last_id = $Cliente->grabar($_POST);
       @header("Location: clientes.php?id=".$last_id); 
       exit;
       $accion = "insertado";
     }
   }
 break;
}

if ($id > 0) {
  $arrActual = $Cliente->obtener($id);
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
				<li><a href="javascript:;">Clientes</a></li>
				<li class="active">Editar Cliente</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Clientes<small> ingresar o editar datos de un cliente</h1>
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


		   					  <form name="frm_dashboard_mapa" id="frm_dashboard_mapa" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">
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
							     
										<?php if($id > 0) { ?>
							
							      <input type="hidden" name="latitude"   value="<?=$arrActual['mapa_latitude']?>" maxlength="10" size="10">		
							      <input type="hidden" name="longitude"  value="<?=$arrActual['mapa_longitude']?>"  maxlength="10" size="10">
								
										<?php } else { ?>
							
							      <input type="hidden" name="latitude"   value="<?=$_SESSION['frontuser']['latitude_pais']?>" maxlength="10" size="10">		
							      <input type="hidden" name="longitude"  value="<?=$_SESSION['frontuser']['longitude_pais']?>"  maxlength="10" size="10">
							
									<?php } ?>

			 		<?php if($arrActual['id'] > 0 ) { ?>
				 				<div class="form-group m-r-10">
									<label class="control-label col-md-4 col-sm-4" for="cliente_nombre"> #CODIGO</label>
									<div class="col-md-6 col-sm-6">
										<?=$arrActual['codigo_subscripcion']?>
									</div>
								</div>
								
									<div class="form-group m-r-10">
									<label class="control-label col-md-4 col-sm-4" for="cliente_nombre"> Estado</label>
									<div class="col-md-6 col-sm-6">
									 <?php if($arrActual['estado'] == CLIENTE_NUEVO) { ?>
													<strong><font color="#7F7F7F">NUEVO</font> (<?=$arrActual['fecha_registro']?>)</strong>
									 <?php  } else if($arrActual['estado'] == CLIENTE_APROBADO)  { ?>
													<strong><font color="#008000">APROBADO</font> (<?=$arrActual['fecha_aprobado']?>)</strong>
									 <?php  } else if($arrActual['estado'] == CLIENTE_RECHAZADO)  { ?>
													<strong><font color="#FF0000">RECHAZADO</font> (<?=$arrActual['fecha_rechazado']?>)</strong>
									 <?php } ?>
									</div>
								</div>
	
							 <?php } ?>              	


								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for=""cliente_email">Email: * (usado para login)</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="cliente_email" name="cliente[email]" data-trigger="change" data-required="true" data-type="email" placeholder="Requerido" value="<?=$arrActual['email']?>">
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
									<label class="control-label col-md-4 col-sm-4" for="message">Teléfono:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="cliente_telefono" name="cliente[telefono]" data-type="phone" placeholder="(XXX) XXXX XXX" value="<?=$arrActual['telefono']?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="message">Celular:</label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="cliente_mobil" name="cliente[mobil]" data-type="phone" placeholder="(XXX) XXXX XXX" value="<?=$arrActual['mobil']?>">
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="cliente_direccion">Dirección: </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control parsley-validated" type="text" id="cliente_direccion" name="cliente[direccion]" data-required="false" placeholder="" maxlength="200" value="<?=$arrActual['direccion']?>">
										<input type="button" value="Localizar" id="search" />
									</div>
								</div>
							
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="cliente_nombre">Provincia: </label>
									<div class="col-md-6 col-sm-6">
									 <select id="item_id_provincia" name="item[id_provincia]" onChange="provincias()">
									 	<option value="">-Seleccionar-</option>
										<?php // PROVINCIAS
					  					for($i=0; $i < $filas_provincias; $i++) {
						 						$items_provincias = @mysql_fetch_array($result_provincias); ?> 									 	
										 		 <option value="<?=$items_provincias['id']?>"  <?=($arrActual['id_provincia']==$items_provincias['id']) ? 'selected' : ''?>><?=$items_provincias['nombre']?></option>
										<?php } ?>  
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="cliente_nombre">Localidad: </label>
									<div class="col-md-6 col-sm-6">
										<?php 
										 if($arrActual['id_localidad'] > 0) { 
											$result_localidades = $Localizacion->obtener_localidades(1, ACTIVO, null, null, null, null, null, null, $arrActual['id_provincia']);
											$filas_localidades = @mysql_num_rows($result_localidades);
										?>

									 <select id="item_id_localidad" name="item[id_localidad]" onChange="barrios()">
									 	<option value="">-Seleccionar-</option>
										<?php // LOCALIDADES
					  					for($i=0; $i < $filas_localidades; $i++) {
						 						$items_localidades = @mysql_fetch_array($result_localidades); ?> 									 	
										 		 <option value="<?=$items_localidades['id']?>"  <?=($arrActual['id_localidad']==$items_localidades['id']) ? 'selected' : ''?>><?=$items_localidades['nombre']?></option>
										<?php } ?>  
										</select>

									  <?php } else { ?>
									  <?php } ?>
									
										<div id="divlocalidades"></div>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="cliente_nombre">Barrio: </label>
									<div class="col-md-6 col-sm-6">
										<div id="divbarrios"></div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="cliente_direccion">Localizacion en el Mapa: *</label>
									<div class="col-md-6 col-sm-6">
										
										<?php // + Google Maps // ?>
										
											<input type="hidden" name="mapa_direccion" id="mapa_direccion" size="50" class="Field-Form" value="<?=$mapa_direccion?>">
										  <input type="hidden" id="mapa_latitude" name="mapa_latitude" value="<?=$_POST['mapa_latitude']?>" 	maxlength="10"  size="20">		
											<input type="hidden" id="mapa_longitude"  name="mapa_longitude"  value="<?=$_POST['mapa_longitude']?>"  maxlength="10" size="20">
		    
								    <div id="map" style="width:98%; height:300px; BORDER: #a7a6aa 1px solid; overflow:hidden; display:block"></div> </t
										<?php // - Google Maps // ?>

									</div>
								</div>
								

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="fullname">Observaciones:</label>
									<div class="col-md-6 col-sm-6">
										<textarea class="form-control" id="clinte_contenido" name="cliente[contenido]" rows="4" data-trigger="keyup" data-rangelength="[20,200]" placeholder="Rango de 20 - 1000 palabras"><?=$arrActual['contenido']?></textarea>
									</div>
								</div>

							<div class="form-group">
  						 <label class="control-label col-md-4 col-sm-4" for="admin_nombre">Seleccionar logo</label>
  						  <div class="col-md-6 col-sm-6">
  								<div id="images_container"></div>
									 	<div id="iframe_container" style="height:40px">
								 		<iframe src="<?=URL_PATH?>/admin/uploadForm.php?carpeta=clientes" class="iframeUpload" width="100%" height="50" scrolling="no" frameborder="0"></iframe>
								 		</div>
									  <img id="imagen_muestra" src="../adj/clientes/<?=($arrActual['foto']) ? $arrActual['foto'] : '_default.gif'?>" border="1" width="75" height="75">
										<b>Nota:</b> <font color="#C80000">la imagen no se grabara hasta que no se  muestre en pantalla. Solo acepta imagenes JPG</font>
										<input type="hidden" name="imagen" size="20" style="color: #000000;border: 1px solid #ffffff;background-color: #ffffff" value="<?=key_value('foto', $arrActual)?>" readonly>
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
