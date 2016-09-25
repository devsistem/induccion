<?php
include_once("../config/conn.php");
declareRequest('accion','usuario','clave');
loadClasses('BackendUsuario', 'Configuracion');
global $BackendUsuario, $Configuracion;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$errores  = 0;
$str_errors = "";

// configuracion del form
$conf_nombre = true;


switch ($accion) {

 case 'grabar':

	// datos del post
	$twitter = escapeSQLTags($_POST['configuracion']['twitter']);
	$google = escapeSQLTags($_POST['configuracion']['google']);
	$facebook = escapeSQLTags($_POST['configuracion']['facebook']);

		 
  // validacion de errores  
  /*
  if(strlen($twitter) < 1 ) {
	  	$str_errors  .= _LANG_MATERIAL_EDIT_1;
		  $css_twitter = "error";
			$errores++;
  }
  */

  //- fin validaciones

  if ($errores == 0) {
       $Configuracion->editar(1, $_POST);
       $accion = "actualizado";
   }
 break;
}

$arrActual = $Configuracion->obtener(1);
?>
<?php include("meta.php")?>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin #header -->
		
		<?php include("header.php")?>
		
		<!-- end #header -->
		
		<!-- begin #sidebar -->

		<?php include("sidebar.php");?>

		<!-- end #sidebar -->
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Home</a></li>
				<li><a href="javascript:;">Configuracion</a></li>
				<li class="active">Editar</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
				<h1 class="page-header">Configuracion<small> Agregar/Editar Configuracion</small></h1>
			<!-- end page-header -->
			
			<!-- begin row -->
			<div class="row">
                <!-- begin col-6 -->
			    <div class="col-md-6" style="width:100%">
			        <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Complete el formulario para editar la configuracion</h4>
                        </div>
                        
                        
                        <?php //+ TABS // ?>
                        
                        <!-- begin row -->
      <div class="panel-body panel-form">

      <form  name="frmPrincipal" id="frmPrincipal" method="POST" class="form-horizontal form-bordered" data-validate="parsley">
			<input type="hidden" name="id" value="1">
			<input type="hidden" name="accion" value="grabar">

			<div class="row">
			    <!-- begin col-6 -->
			    <div class="col-md-6">
					<ul class="nav nav-tabs">
						
						
								<?php if($accion == "actualizado") { ?>

								<div class="alert alert-success fade in m-b-15">
										<strong>Correcto</strong>
										Los datos fueron actualizados.
										<span class="close" data-dismiss="alert">&times;</span>
								</div>

								<?php } ?>
								
								<?php // error? ?>
						<div style="clear:both"></div><br/>
						<li class=""><a href="#default-tab-5" data-toggle="tab">Email</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade" id="">
						</div>

						<div class="tab-pane "  id="default-tab-4">
							<p>
								 
																												
							
							</p>
						</div>

						<div class="tab-pane fade active in" id="default-tab-5">
							<p>
								 
								 <div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="estacion_nombre"><strong>Gestor de Correo:</strong> </label>
									<div class="col-md-6 col-sm-6">
										
										<select id="mail_gestor" name="configuracion[mail_gestor]">
											<option value="Sendmail">Sendmail</option>
										</select>
										
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4" for="admin_direccion"><strong>Correo electrónico del sitio: </strong>  </label>
									<div class="col-md-6 col-sm-6">
										<input class="form-control" type="text" id="mail_cuenta" name="configuracion[mail_info]"   placeholder=""   maxlength="160"   value="<?=$arrActual['mail_info']?>">
									</div>
								</div>
								
</div>

							<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="submit" class="btn btn-primary">Grabar</button>
									</div>
								</div>
								
					</div>
				</div>
       <?php //- TABS // ?>
				 		
               </form>
             </div>
             </div>
              <!-- end panel -->
            </div>
                <!-- end col-6 -->
                <!-- begin col-6 -->
			
                <!-- end col-6 -->
            </div>
            <!-- end row -->
		</div>
		<!-- end #content -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery-1.8.2/jquery-1.8.2.min.js"></script>
	<script src="assets/plugins/jquery-ui-1.10.4/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap-3.1.1/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/parsley/parsley.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
	<script src="assets/js/map-google.demo.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
			MapGoogle.init();
		});
	</script>
</body>
</html>
