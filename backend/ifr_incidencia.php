<?php
// 18/09/2015 18:23:53
// ifr_estado.php

// TESTEAR
// 1 PEDIDOS SIN STOCK
// solo puede editar el usuario de nivel +4

header("X-Frame-Options: GOFORIT");
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Localizacion', 'Producto', 'Incidencia');
global $BackendUsuario, $Pedido, $Localizacion, $Producto, $Incidencia;

$idx_pedidos = ($_REQUEST['id_pedidos']) ? $_REQUEST['id_pedidos'] : null; // idx pedido
$estado = ($_REQUEST['estado']) ? $_REQUEST['estado'] : null;

$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$BackendUsuario->EstaLogeadoBackend();

// fecha y dia actual
$dia_actual = date("d");
$mes_actual = date("m");

$fecha_hora_actual = date("F j, Y, g:i a"); 

$error_comprueba_agenda = null;

switch ($accion) {
 
 case 'incidencia':
     
     $temp_pedidos = explode(",",$idx_pedidos);
     
     for($i=0; $i < count($temp_pedidos); $i++) {
		   $Pedido->incidencia_multiple($temp_pedidos[$i], $estado, $_POST);
		 }
		   $accion = "incidencia-agregada";
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
 
 function	agregar_incidencia() {
  if(confirm('Confirma el envio de una incidencia en el pedido?')) {
	 var form = document.forms['frm_dashboard_mapa'];
	 form['accion'].value = 'incidencia';
	 form.submit();
  }
 }
 
 function cerrar() {
   window.opener.location.reload();
	 window.close();
 }

</script>

</head>

<body>
<form name="frm_dashboard_mapa" id="frm_dashboard_mapa" enctype="multipart/form-data" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">
<input type="hidden" name="zoom_inicial" value="8">		
<input type="hidden" name="zoom" id="zoom" value="<?=$arrPedido['zoom']?>">		
<input type="hidden" name="dragable" id="dragable" value="true">		
<input type="hidden" name="modo" value="enviar">	
<input type="hidden" id="ciudad_mapa" name="ciudad_mapa" value="Quito">	
<input type="hidden" id="pais_mapa" name="pais_mapa" value="Ecuador">	

<input type="hidden" name="accion">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="campo">
<input type="hidden" name="estado"  value="<?=$estado?>">		
<input type="hidden" name="idx_pedido"  value="<?=$idx_pedidos?>">	

<div class="contenedor_estado"  style="padding:15px">  

<?php if($accion == "incidencia-agregada") { ?>
  
  <div class="mensaje-usuario">Se agregó correctamente la incidencia.<br/><br/>
  <button type="button" class="btn btn-sm btn-default" onClick="cerrar()">Cerrar</button>
  </div>

<?php } ?>

	<?php //+ form de incidencia // ?>	
	<div id="divincidencia">
	 <div class="panel-body">
          <fieldset>
              <legend>Agregar una incidencia</legend>

              <div class="form-group">
              	
              	<?php // + listado ?>
								<?php
								// todos 
								$result_todos = $Incidencia->obtener_all(null, null);
								$filas_todos = @mysql_num_rows($result_todos);
									for($i=1; $i <= $filas_todos; $i++) {
											$items = @mysql_fetch_array($result_todos);
								?>
								
										<div class="label label-danger" style="padding:5px">
											<input type="radio" name="rd_incidencia" value="<?=$items['id']?>" />
											<?=$items['nombre']?>
										</div>
										<div style="clear:both;padding-top:2px"></div>
								
								<?php
									} 
								?>
								
								<label>Observaciones</label>
								<textarea name="contenido_incidencia" id="contenido_incidencia" style="width:600px;height:100px"></textarea>

              </div>
              
               <div style="clear:both"></div><br/>	
               
              <button type="button" class="btn btn-sm btn-primary m-r-5" onClick="agregar_incidencia()">Grabar</button>
              <button type="button" class="btn btn-sm btn-default" onClick="cancelar_incidencia()">Cancelar</button>
          
          </fieldset>
   </div>
	</div>
	<?php //- form de incidencia // ?>	
</div>
</form>

</body>
</html>