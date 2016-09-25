<?php
// 05/10/2015 20:31:55
// ifr_subir_excel.php

// TESTEAR
// 1 PEDIDOS SIN STOCK
// solo puede editar el usuario de nivel +4

header("X-Frame-Options: GOFORIT");
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Localizacion', 'Producto');
global $BackendUsuario, $Pedido, $Localizacion, $Producto;

$id = ($_REQUEST['id']) ? $_REQUEST['id'] : null; // id pedido
$estado = ($_REQUEST['estado']) ? $_REQUEST['estado'] : null;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$pedidos_idx = ($_REQUEST['idx']) ? $_REQUEST['idx'] : null;

$BackendUsuario->EstaLogeadoBackend();

// fecha y dia actual
$dia_actual = date("d");
$mes_actual = date("m");

$error_comprueba_agenda = null;

switch ($accion) {
	
 case 'grabar':
   	 
	// listad todos lo pedidos del estado 2
	//$result_todos = $Pedido->obtener_all($porPagina, $paginacion, $limite, $palabra, $order_by, $filtro, ACTIVO, 2, $destacado, $filtro_id_tipo, $filtro_id_categoria, $id, $BackendUsuario->getUsuarioId());
	//$filas_todos= @mysql_num_rows($result_todos_2);
  
  $last_id = $Pedido->subir_excel($_POST);
	
	if( $last_id > 0) {
	 $accion = "subido";
	} 

	 print "<script>window.parent.cargarEstadoAgenda('".$last_id."');</script>";
	 die;

 break;
}
?>

<?php include("meta.php");?>

<style>
	body {
	 padding:0px;
	}
 .contenedor_estado {
  background-color: #ffffff 
 }
 .mensaje-usuario {
  padding:20px;
  font-weight: bold;
 }
 .divhorariorecepcion {
   background-color: #FFFFE8;
    padding:10px;
    border: 1px solid #A4A4A4;
 }
 .titulo-form {
 	width:160px;
 }
 .imprimirfactura {
  background-color: #ffffff 
  border: 1px solid #000000; 
 }
</style>

<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<script>
	
</script>
</head>
<body>
<form name="frm_dashboard_mapa" id="frm_dashboard_mapa" enctype="multipart/form-data" method="POST" action="">
<input type="hidden" name="accion" value="grabar">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="idx" value="<?=$pedidos_idx?>">
<input type="hidden" name="campo">
<input type="hidden" name="estado">				


<div class="form-group" style="padding:20px">
    <label class="col-md-2 control-label"><h3>Seleccionar EXCEL para subir</h3></label>
    <div style="clear:both"></div>
     <div class="col-md-9">
     	
     	<?php if($accion == "subido") { ?>
     	  <div>Archivo Subido correctamente</div>
    	<?php } ?>
     
     	<input name="file" type="file" id="file">
     	<div style="padding-top:5px"></div>
     	<input type="submit" name="btsubir" value="Subir Excel" />
     </div>
</div>     	
</form>

</body>
</html>