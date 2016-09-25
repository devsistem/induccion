<?php
// 24/11/2015 12:06:26
// ifr_imagen.php

header("X-Frame-Options: GOFORIT");
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Localizacion', 'Producto', 'Incidencia');
global $BackendUsuario, $Pedido, $Localizacion, $Producto, $Incidencia;

$id_pedido = ($_REQUEST['id_pedido']) ? $_REQUEST['id_pedido'] : null; // id pedido
$imagen    = ($_REQUEST['imagen']) ? $_REQUEST['imagen'] : null;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$BackendUsuario->EstaLogeadoBackend();

// fecha y dia actual
$dia_actual = date("d");
$mes_actual = date("m");
$fecha_hora_actual = date("F j, Y, g:i a"); 
$errores = 0;

switch($accion) {

 case 'editar-imagen':

 		//  dueño
		$imagen_factura = escapeSQLTags($_POST['imagen_factura']);	
		$imagen_dni_frente = escapeSQLTags($_POST['imagen_dni_frente']);	
		$imagen_dni_posterior = escapeSQLTags($_POST['imagen_dni_posterior']);

		//  alquila
		$imagen_dni_duenio_frente = escapeSQLTags($_POST['imagen_dni_duenio_frente']);	
		$imagen_dni_duenio_posterior = escapeSQLTags($_POST['imagen_dni_duenio_posterior']);
		$imagen_dni_duenio_garante = escapeSQLTags($_POST['imagen_dni_duenio_garante']);

	   // imagenes
     if($imagen == "imagen_factura") {
	    if(strlen($imagen_factura) ==  0 ) {
			 $str_errors  .= "Debe adjuntar una imagen de la factura<br/>";
			 $css_imagen_factura = "error_div";
			 $errores++;
		  }
		 }	

     if($imagen == "imagen_dni_frente") {
      if(strlen($imagen_dni_frente) ==  0 ) {
			 $str_errors  .= "Debe adjuntar una imagen de la cedula<br/>";
			 $css_imagen_dni_frente = "error_div";
			 $errores++;
		  }
		 }	
     
     if($imagen == "imagen_dni_posterior") {
      if(strlen($imagen_dni_posterior) ==  0 ) {
			 $str_errors  .= "Debe adjuntar una imagen posterior<br/>";
			 $css_imagen_dni_posterior = "error_div";
			 $errores++;
		  }
		 }
		 
 		 if($errores == 0) {
			 // todas las validaciones ok, graba
			 $last_id = $Pedido->grabar_imagen($id_pedido, $imagen, $_POST);
	 	   print "<script>window.opener.location.reload();</script>";
		   print "<script>window.close();</script>";
		 }
		 
  break;
 
}

// cargo el pedido
$arrPedido = $Pedido->obtener($id_pedido);
?>

<?php include("meta.php");?>

<style>
	body {
	 padding:0px;
	}
</style>

<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
<form name="frm_dashboard_mapa" id="frm_dashboard_mapa" enctype="multipart/form-data" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">

<input type="hidden" name="accion" value="editar-imagen">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="campo">
<input type="hidden" name="imagen" value="<?=$imagen?>">		
<input type="hidden" name="id_pedido" value="<?=$id_pedido?>">	
<input type="hidden" name="id_producto">	

<input type="hidden" id="imagen_factura" name="imagen_factura">		
<input type="hidden" id="imagen_dni_frente" name="imagen_dni_frente">		
<input type="hidden" id="imagen_dni_posterior" name="imagen_dni_posterior">		

<input type="hidden" id="imagen_duenio_garante" name="imagen_duenio_garante">		
<input type="hidden" id="imagen_dni_duenio_frente" name="imagen_dni_duenio_frente">		
<input type="hidden" id="imagen_dni_duenio_posterior" name="imagen_dni_duenio_posterior">	
			
<div class="contenedor_estado"  style="padding:15px">  

<?php if($accion == "foto-agregada") { ?>
  
  <div class="mensaje-usuario">Se agregó correctamente la incidencia.<br/><br/>
  <button type="button" class="btn btn-sm btn-default" onClick="cerrar()">Cerrar</button>
  </div>

<?php }  ?>

  <?php //+ IMAGEN /////////////////////////////// ?>


	<div style="padding-top:5px"></div><br/>
	<div class="caja_estado" style="background-color:#D9D8BA"></div> 
	<strong>&nbsp;&nbsp;EDITAR IMAGEN</strong>
	<div style="padding-top:5px"></div><br/>
	Vendedor: <strong><?=$arrPedido['vendedor_nombre']?></strong>
	<div style="padding-top:5px"></div><br/>
	
	<div class="contenedor_estado_3">  
  
	<div><h3>Actualizar Imagen</h3></div>


 						<div id="divfotosduenio" style="padding:10px">
 							
			      	<?php // Fotos 1/6  // ?>
							<?php if($imagen == "imagen_factura") { ?>
							<div class="control-group size1 <?=$css_imagen_factura?>" style="padding:20px">
								<br/>
								<iframe id="imagen_factura" src="uploadlib/index.php?img=imagen_factura" frameBorder="0" style="padding:10px;width:100%;height:300px" border="0"></iframe>

			 					<input class="submit btn btn-large btn-success boton_color" id="bt_grabar" name="bt_grabar" type="submit" value="Grabar Imagen">
							</div>

							<?php // Fotos 2/6 // ?>
							<?php } else if($imagen == "imagen_dni_frente") { ?>							
							<div class="control-group size1  <?=$css_imagen_dni_frente?>" style="padding:20px">
								<br/>
								<iframe id="imagen_dni_frente" src="uploadlib/index.php?img=imagen_dni_frente" frameBorder="0" style="padding:10px;width:100%;height:300px" border="0"></iframe>

			 					<input class="submit btn btn-large btn-success boton_color" id="bt_grabar" name="bt_grabar" type="submit" value="Grabar Imagen">
							</div>

							<?php // Fotos 3/6 // ?>
							<?php } else if($imagen == "imagen_dni_posterior") { ?>							

							<div class="control-group size1 <?=$css_imagen_dni_posterior?>" style="padding:20px">
								<br/>
								<iframe id="imagen_dni_posterior" src="uploadlib/index.php?img=imagen_dni_posterior" frameBorder="0" style="padding:10px;width:100%;height:300px" border="0"></iframe>
			 					<input class="submit btn btn-large btn-success boton_color" id="bt_grabar" name="bt_grabar" type="submit" value="Grabar Imagen">
							</div>
							
							<?php } ?>
							
				    </div>
	  </div>

</div>
</form>


</body>
</html>