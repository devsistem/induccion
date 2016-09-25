<?php
ob_start();
include_once("../config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario', 'Contacto', 'Item');
global $BackendUsuario, $Contacto, $Item;

$BackendUsuario->EstaLogeadoBackend();

$strError = '';
$errores  = 0;
$id = ($_POST['id']) ? $_POST['id'] : null;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$filtro_estado = (isset($_POST['filtro_estado'])) ? $_POST['filtro_estado'] : -1;

switch ($accion) {
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Contacto->eliminar($idx);
   }
 break;
 
 case 'publicar':
	   $Contacto->publicar($_POST['id'], $_POST['campo']);
 break;

 case 'estado':
	   $Contacto->estado($_POST['id'], $_POST['campo']);
 break;
 
 case 'responder':
	   $Contacto->responder_contacto($_POST['campo']);
 break;
 
}

$result = $Contacto->obtener_all(null, null, $palabra, $OrderByull, $filtro, null, null, null, null, 'contacto');
$filas = @mysql_num_rows($result);
// todos 
?>

<?php include("meta.php"); ?>

<script>
 function eliminar() {
  if(confirm('Esta seguro de querer borrar')) {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'eliminar';
  form.submit();
  }
 }
 function publicar(idx,campo) {
 var form = document.forms['frmPrincipal'];
 form['campo'].value = campo;
 form['id'].value = idx;
 form['accion'].value = 'publicar';
 form.submit();
 }
 
 function estado(idx,campo) {
 var form = document.forms['frmPrincipal'];
 form['campo'].value = campo;
 form['id'].value = idx;
 form['accion'].value = 'estado';
 form.submit();
 }
 
 function grabar(idx){
 var form = document.forms['frmPrincipal'];
 form['accion'].value = 'responder';
 form.submit();
 }


 </script>
<style>
.link-blanco:link { color:#ffffff}
</style> 
</head>
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
				<li><a href="javascript:;">Portada</a></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Contacto<small> Listado de contactos enviados desde la pagina</small></h1>
			<!-- end page-header -->
			
			<!-- begin row -->
			<div class="row">
										
			    <!-- begin col-12 -->
			    <div class="col-md-12">
			        <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Listado</h4>

	
                        </div>
			      
										<form name="frmPrincipalFiltros" id="frmPrincipalFiltros" method="POST" action="">
					  							<input type="hidden" name="accion">
  												<input type="hidden" name="id">
 						  						<input type="hidden" name="campo">
 						  						<input type="hidden" name="filtrar_estado" id="filtrar_estado">
 						  						<input type="hidden" name="id_contacto" id="id_contacto">
 						  						<input type="hidden" name="contenido" id="contenido">
					  				</form>		

											<form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
					  							<input type="hidden" name="accion">
  												<input type="hidden" name="id">
 						  						<input type="hidden" name="campo">
                          <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="10" class="sorting_desc_disabled"><button type="button" class="btn btn-primary btn-xs m-r-5" onClick="eliminar('<?=$items['id']?>')" onClick="eliminar()" title="Eliminar seleccionados">Eliminar Seleccion</button></th>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Mensaje</th>
                                            <th>Alta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
																<?php
					   											for($i=1; $i <= $filas; $i++) {
						 												$items = @mysql_fetch_array($result);
						 												
						 												$arrItem = $Item->obtener($items['id_item']);
																	?> 
															
                                        <tr class="odd gradeX">
                                    	  		<td align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items['id']?>"></td>
                                            <td><?=$items['id']?></td>
                                            <td><?=$items['nombre']?></td>
                                            <td><?=$items['email']?></td>
                                            <td><?=$items['contenido']?></td>
                                            <td><?=$items['fecha_alta']?></td>
                                        </tr>
                                <?php
                                	}
                                ?>       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                      				</form>		

                    <!-- end panel -->
                </div>
                <!-- end col-12 -->
            </div>
            <!-- end row -->
		</div>
		<!-- end #content -->

	<?php // + Ventana Respuesta // ?>
																			<form name="frmPrincipalRespuesta" id="frmPrincipalRespuesta" method="POST" action="">
										  								<input type="hidden" name="accion" value="responder">
										  								<input type="hidden" name="id_contacto" id="id_contacto">

																			<div class="modal fade" id="modal-dialog">
																				<div class="modal-dialog">
																					<div class="modal-content">
																						<div class="modal-header">
																							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																							<h4 class="modal-title"><strong>Responder Contacto</strong></h4>
																						</div>
																						<div class="modal-body">
																							<strong>Pregunta</strong><br/><br/>
																							<div class="alert alert-info fade in m-b-15">
																								<div id="mensaje" class="alert"  data-dismiss="alert"></div><br/><br/>
																							</div>
																							
																							<strong>Respuesta</strong> <br/><br/>
												
												                    <div class="box">
												                        <textarea placeholder="Ingrese su mensaje..." class="form-control" style="height:300px" name="contenido id="contenido<?=$items['id']?>"></textarea>
												                     </div>											
																							
																							
																						</div>
																						<div class="modal-footer">
																							<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cancelar</a>
																							<a href="javascript:grabar('<?=$items['id']?>')" class="btn btn-sm btn-success">Enviar Respuesta</a>
																						</div>
																					</div>
																				</div>
																			</div>
																			</form>
																			<?php // - Ventana Respuesta // ?>

									
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	
	<?php include("footer.php"); ?>


</body>
</html>
