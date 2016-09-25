<?php
// productos.php
// 05/08/2015 15:33:34
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Foto', 'Categoria');
global $BackendUsuario, $Producto, $Foto, $Categoria;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

switch ($accion) {
 case 'eliminar':
  if(is_array($_POST['arrSeleccion'])) foreach($_POST['arrSeleccion'] as $idx) {
 	  $Producto->eliminar($idx);
   }
 break;
 
 case 'publicar':
	   $Producto->publicar($_POST['id'], $_POST['campo']);
 break;

 case 'destacado':
	   $Producto->destacado($_POST['id'], $_POST['campo']);
 break;

 case 'home':
	   $Producto->home($_POST['id'], $_POST['campo']);
 break;

 case 'oferta':
	   $Producto->oferta($_POST['id'], $_POST['campo']);
 break;

 case 'estado':
	   $Producto->estado($_POST['id'], $_POST['campo']);
 break;
}

// todos 
$result_todos = $Producto->obtener_all(null, null, null, $palabra, $order_by, $filtro, null, $estado, $destacado, 'producto');
$filas_todos = @mysql_num_rows($result_todos);

// categorias de productos
$result_categorias = $Categoria->obtener_all(null, $filtro, ACTIVO,  null,  null, null, null);
$filas_categorias   = @mysql_num_rows($result_categorias);
?>
<?php include("meta.php");?>

<?php //+  acciones js // ?>
 <script>
 function eliminar() {
  if(confirm('Esta seguro de querer eliminar los items seleccionados?')) {
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
 function destacado(idx,campo) {
  var form = document.forms['frmPrincipal'];
  form['campo'].value = campo;
  form['id'].value = idx;
  form['accion'].value = 'destacado';
  form.submit();
 }
 function home(idx,campo) {
  var form = document.forms['frmPrincipal'];
  form['campo'].value = campo;
  form['id'].value = idx;
  form['accion'].value = 'home';
  form.submit();
 }
 function oferta(idx,campo) {
  var form = document.forms['frmPrincipal'];
  form['campo'].value = campo;
  form['id'].value = idx;
  form['accion'].value = 'oferta';
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
  form.action = 'productos_editar.php';
  form.submit();
 }
 function buscar() {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'bucar';
  form.submit();
 }
 function importar() {
  if(confirm('Esta seguro de querer importar los productos a excel?')) {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'importar';
  form.submit();
  }
 } 
 function exportar() {
  if(confirm('Esta seguro de querer exportar los productos a excel?')) {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = 'exportar';
  form.submit();
  }
 } 
 function galeria(idx) {
  var form = document.forms['frmPrincipal'];
  form['id_item'].value = idx;
  form.action = 'productos_galeria.php';
  form.submit();
 }
</script>
<?php //-  acciones js // ?>
<link   href="assets/plugins/colorbox/example1/colorbox.css" rel="stylesheet" />
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
				<li><a href="noticias.php">Productos</a></li>
				<li class="active">Listado</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Productos <small>listado de productos</small></h1>
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
                            <h4 class="panel-title">Productos</h4>
                        </div>

                        <?php //+ mensajes // ?>
                        <?php if($accion == "eliminado") { ?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            El Producto ha sido eliminado correctamente.
                        </div>
	                      <?php } ?>
                        <div class="panel-body">
                            <div class="table-responsive">
																	
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                    	  <tr>
                                    	   <td colspan="8"> 
                                    	   	Categorias: 
												<select name="id_categoria" id="id_categoria" style="font-size:14px;width:300px; height:40px">
							 				<option value="0">-- Todas --</option>
												<?php
					   							for($i=1; $i <= $filas_categorias; $i++) {
						 								$items = @mysql_fetch_array($result_categorias); ?>

						 								<option value="<?=$items['id']?>" <?=($items['id']==$item['id_categoria']) ? 'selected' : ''?>><?=$items['nombre']?></option>
						 									
						 									<?php
						 										// subcategorias
																	 $result_subcategorias = $Categoria->obtener_subcategorias($OrderBy , $filtro , 1, $items['id'] );
																	 $filas_subcategorias = @mysql_num_rows($result_subcategorias);
																	
					   													for($k=0; $k < $filas_subcategorias; $k++) {
										 											$items_subcategorias = @mysql_fetch_array($result_subcategorias);	?> 

												 								<option value="<?=$items_subcategorias['id']?>" <?=($items_subcategorias['id']==$item['id_categoria']) ? 'selected' : ''?>>--<?=$items_subcategorias['nombre']?></option>
                                
                                
                                         <?php //+ subcateogrias nivel 2 // ?>
 									                       <?php 
																						$result_subcategorias2 = $Categoria->obtener_subcategorias(null , $filtro , 1, $items_subcategorias['id'] );
																						$filas_subcategorias2 = @mysql_num_rows($result_subcategorias2);
					   																	for($j=0; $j < $filas_subcategorias2; $j++) {
										 													 $items_subcategorias2 = @mysql_fetch_array($result_subcategorias2);	?> 
				
																 								<option value="<?=$items_subcategorias2['id']?>" <?=($items_subcategorias2['id']==$item['id_categoria']) ? 'selected' : ''?>>----<?=$items_subcategorias2['nombre']?></option>
        
				
				                                <?php }	?>

                                <?php }	?>
                                        		
						 				<?php }	?> 
										 
										 </select>
                                    	   	<input type="button" value="Filtrar" onClick="filtrar()"/>
                                    	   </td>
                                    	  </tr>
                                   
                                    	  <tr>
                                    	   <td colspan="8"> 
                                    	   	<input type="button" value="Nuevo Producto" class="btn btn-inverse" onClick="editar('0');"/>
                                    	   </td>
                                    	  </tr>
                                        <tr>
                                            <th width="5%" class="sorting_desc_disabled"><button type="button" class="btn btn-primary btn-xs m-r-5" onClick="eliminar('<?=$items['id']?>')" onClick="eliminar()" title="Eliminar seleccionados">Eliminar Seleccion</button></th>
                                            <th width="1%">Id</th>
                                            <th width="5%"></th>
                                            <th width="20%">Nombre</th>
                                            <th width="10%">Categoria</th>
                                            <th width="10%">Precio Tienda (U$D)</th>
                                            <th width="5%">Activo</th>
                                            <th width="5%">Destacado</th>
                                            <th width="5%">Home</th>
                                            <th width="5%">Oferta</th>
                                            <th width="10%">Fecha Alta</th>
                                            <th width="20%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

																	<?php
					   											for($i=1; $i <= $filas_todos; $i++) {
						 												$items = @mysql_fetch_array($result_todos);
						 												$cantidad = $Foto->cantidad($items['id']);
																	?>                                     	
                                        <tr class="odd gradeX">
                                    	  		<td align="center"><input type="checkbox" id="arrSeleccion[]" name="arrSeleccion[]" value="<?=$items['id']?>"></td>
                                            <td align="center"><?=$items['id']?></td>
                                            <td align="center"><img src="<?=URL_PATH_ROOT?>/adj/productos/<?=$items['imagen_th']?>" width="50"></td>
                                            <td>
                                            	<?=$items['nombre']?>
                                            	<?php if($_GET['id'] == $items['id']) { ?>
                                            	  <strong>(actualizado)</strong>
	                                            <?php } ?>
                                            	</td>
                                            <td><?=$items['categoria_nombre']?></td>
                                            <td><?=$items['precio_tienda']?></td>
                                            <td align="center">
	                                            <a href="javascript:publicar('<?=$items['id']?>','<?=$items['activo']?>');" title="Activar o desactivar un usuario en el backend"><b><?=($items['activo'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            </td>
                                            <td align="center">
	                                            <a href="javascript:destacado('<?=$items['id']?>','<?=$items['destacado']?>');" title="Activar o desactivar destacado"><b><?=($items['destacado'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            </td>
                                            <td align="center">
	                                            <a href="javascript:home('<?=$items['id']?>','<?=$items['home']?>');" title="Activar o desactivar home"><b><?=($items['home'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            </td>
                                            <td align="center">
	                                            <a href="javascript:oferta('<?=$items['id']?>','<?=$items['oferta']?>');" title="Activar o desactivar oferta"><b><?=($items['oferta'] == 1) ? '<font color=008000>SI</font>' : '<font color=FF0000>NO</font>'?></b></a>
                                            </td>
                               
                                            <td><?=$items['fecha_alta']?></td>
                                            <td>
	                                            <button type="button" class="btn btn-inverse" onClick="editar('<?=$items['id']?>')" ><i class="fa fa-cog"></i> Editar</button>
	                                            <button type="button" class="btn btn-inverse" onClick="galeria('<?=$items['id']?>')" ><?=$cantidad?> Fotos</button>

                                            </td>
                                        </tr>
                                  <?php
                                  } 
                                  ?>     
                                  
                                    </tbody>
                                </table>
                            </div>

                        <div>
                        	<input href="_importar.php" type="button" class="btn btn-inverse importar" value="Importar a Excel"/>
													<input type="button" class="btn btn-inverse" value="Exportar a Excel" onClick="exportar()" disabled/>
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
	<script src="assets/plugins/colorbox/jquery.colorbox.js"></script>

	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
			TableManageColReorder.init();
			
			$(".importar").colorbox({iframe:true, width:"40%", height:"50%"});
				
		});
	</script>
</body>
</html>
