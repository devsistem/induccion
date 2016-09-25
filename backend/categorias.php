<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Categoria');
global $BackendUsuario, $Categoria;

$BackendUsuario->EstaLogeadoBackend();

$strError = '';
$errores  = 0;
$id = ($_POST['id']) ? $_POST['id'] : null;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$filtro_estado = (isset($_POST['filtro_estado'])) ? $_POST['filtro_estado'] : -1;

switch ($accion) {
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Categoria->eliminar($idx);
   }
 break;
 
 case 'publicar':
	   $Categoria->publicar($_POST['id'], $_POST['campo']);
 break;

 case 'estado':
	   $Categoria->estado($_POST['id'], $_POST['campo']);
 break;
}

$result = $Categoria->obtener_all( 'ORDER BY  c.orden ASC, c.nombre ASC', $filtro, 1, null, 1);
$filas = @mysql_num_rows($result);
?>
<?php include("meta.php");?>

<?php //+  acciones js // ?>
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
 
 function editar(idx) {
  var form = document.forms['frmPrincipal'];
  form['id'].value = idx;
  form.action = 'categorias_editar.php';
  form.submit();
 }
 </script>
<?php //-  acciones js // ?>

</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade in page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<!-- begin #header -->
		<?php include("header.php")?>
		<!-- end #header -->
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<!-- begin #sidebar -->
		<?php include("sidebar.php")?>
		<!-- end #sidebar -->
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="index.php">Portada</a></li>
				<li><a href="noticias.php">Categorias</a></li>
				<li class="active">Listado</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Categorias <small>listado de categorias</small></h1>
			<!-- end page-header -->

		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_item">			
			<!-- begin row -->
			<div class="row">
			    
			    <!-- end col-2 -->
			    <!-- begin col-10 -->
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
                            <h4 class="panel-title">Categorias</h4>
                        </div>
                        
                        <?php //+ mensajes // ?>
                        <?php if($accion == "eliminado") { ?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            La Categoria ha sido eliminada correctamente.
                        </div>
	                      <?php } ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                            	

                                  <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="sorting_desc_disabled"><button type="button" class="btn btn-primary btn-xs m-r-5" onClick="eliminar('<?=$items['id']?>')" onClick="eliminar()" title="Eliminar seleccionados">Eliminar Seleccion</button></th>
                                            <th width="2%">#Id</th>
                                            <th width="30%">Nombre</th>
                                            <th width="5%" align="center">Activo</th>
                                            <th width="10%">Alta</th>
                                            <th width="10%">Orden</th>
                                            <th width="20%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
																		<?php
																						// categorias
																						$result_categorias = $Categoria->obtener_all( null, $filtro ,  null,  null,  null, null );
																						$filas_categorias = @mysql_num_rows($result_categorias);
																						for($j=0; $j < $filas_categorias; $j++) {
										 													$items_categorias = @mysql_fetch_array($result_categorias);	?> 

	                                        <tr class="odd gradeX">
                                    	  		<td width="2%" align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items_categorias['id']?>"></td>
                                            <td width="2%" align="center"><?=$items_categorias['id']?></td>
                                            <td width="50%">
                                            	
                                            	<strong><?=$items_categorias['nombre']?></strong>
                                            	
                                            	<?php if($_GET['id'] == $items_categorias['id']) { ?>	
                                            			<strong>(actualizado)</strong>
	                                            <?php } ?>
                                            </td>
                                            <td  width="10%" align="center"><a href="javascript:publicar('<?=$items_categorias['id']?>','<?=$items_categorias['activo']?>');" title="Activar o desactivar un usuario en el backend"><b><?=($items_categorias['activo'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a></td>
                                            <td  width="10%"><?=$items_categorias['fecha_alta']?></td>
                                            <td  width="10%"><?=$items_categorias['orden']?></td>
                                            <td  width="20%"><button type="button" class="btn btn-primary btn-xs m-r-5" onClick="editar('<?=$items_categorias['id']?>')" title="Editar un item">Editar</button></td>
	                                        </tr>
                                        	<?php
                                        		
																						// subcategorias 1
																						$result_subcategorias = $Categoria->obtener_subcategorias($OrderBy , $filtro , 1, $items_categorias['id'] );
																						$filas_subcategorias = @mysql_num_rows($result_subcategorias);
																	
					   																for($k=0; $k < $filas_subcategorias; $k++) {
										 													$items_subcategorias = @mysql_fetch_array($result_subcategorias);	?> 
                                        		
                                  			  		<tr class="odd gradeX">
                                    	  				<td width="2%" align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items_subcategorias['id']?>"></td>
                                            		<td width="2%" align="center"><?=$items_subcategorias['id']?></td>
                                            		<td width="50%">
                                            	
                                            	 		-- <?=$items_subcategorias['nombre']?>
                                            
                                             			<?php if($_GET['id'] == $items_subcategorias['id']) { ?>	
                                            					<strong>(actualizado)</strong>
	                                            		<?php } ?>
	                                            
                                            		</td>
                                            		<td  width="10%" align="center"><a href="javascript:publicar('<?=$items_subcategorias['id']?>','<?=$items_subcategorias['activo']?>');" title="Activar o desactivar un usuario en el backend"><b><?=($items_subcategorias['activo'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a></td>
                                            		<td  width="10%"><?=$items_subcategorias['fecha_alta']?></td>
                                            		<td  width="10%"><?=$items_subcategorias['orden']?></td>
                                            		<td  width="20%"><button type="button" class="btn btn-primary btn-xs m-r-5" onClick="editar('<?=$items_subcategorias['id']?>')" title="Editar un item">Editar</button></td>
	                                        		</tr>

																								<?php
																								// subcategorias 2
																								$result_subcategorias2 = $Categoria->obtener_subcategorias($OrderBy , $filtro , 1, $items_subcategorias['id'] );
																								$filas_subcategorias2 = @mysql_num_rows($result_subcategorias2);
																	
						   																	for($l=0; $l < $filas_subcategorias2; $l++) {
											 														$items_subcategorias2 = @mysql_fetch_array($result_subcategorias2);	?> 
											 														  
											 													<tr class="odd gradeX">
                                    	  					<td width="2%" align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items_subcategorias2['id']?>"></td>
                                            			<td width="2%" align="center"><?=$items_subcategorias2['id']?></td>
                                            			<td width="50%">
                                            	
                                            	 			---- <?=$items_subcategorias2['nombre']?>
                                            
                                             			<?php if($_GET['id'] == $items_subcategorias2['id']) { ?>	
                                            					<strong>(actualizado)</strong>
	                                            		<?php } ?>
	                                            
                                            			</td>
                                            			<td  width="10%" align="center"><a href="javascript:publicar('<?=$items_subcategorias2['id']?>','<?=$items_subcategorias2['activo']?>');" title="Activar o desactivar un usuario en el backend"><b><?=($items_subcategorias2['activo'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a></td>
                                            			<td  width="10%"><?=$items_subcategorias2['fecha_alta']?></td>
                                            			<td  width="10%"><?=$items_subcategorias2['orden']?></td>
                                            			<td  width="20%"><button type="button" class="btn btn-primary btn-xs m-r-5" onClick="editar('<?=$items_subcategorias2['id']?>')" title="Editar un item">Editar</button></td>
	                                        			</tr>

											 													<?php
											 													} // subcategorias 2
                                		        } // subcategorias 1
                                					} // categorias
                               			 ?>       
                                    </tbody>
                                </table>
                                
                               <input type="button" style="margin-left:20px" class="btn" value="Nueva Categoria" onClick="editar(0)" />

                            </div>
                        </div>
                    </div>
                    <!-- end panel -->
                </div>
                <!-- end col-10 -->
            </div>
            <!-- end row -->
		  </div>
		</form>
		<!-- end #content -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
 <?php include("footer_meta.php")?>
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="assets/plugins/DataTables/js/dataTables.colReorder.js"></script>
	<script src="assets/js/table-manage-colreorder.demo.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
			TableManageColReorder.init();
		});
	</script>
</body>
</html>
