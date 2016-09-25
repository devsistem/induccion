<?php
// productos.php
// 05/08/2015 15:33:34
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Foto');
global $BackendUsuario, $Producto, $Foto;

$BackendUsuario->EstaLogeadoBackend();

$id_item = ($_POST['id_item']) ? $_POST['id_item'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
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
				<li class="active">Listado de fotos del producto</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Fotos <small>listado de fotos del producto</small></h1>
			<!-- end page-header -->
     
      <iframe src="ifr_productos_fotos.php?id_item=<?=$id_item?>" width="100%" height="600" scrolling="auto"></iframe>
			
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
