<?php
include_once("config/conn.php");
declareRequest('accion','usuario','clave');
loadClasses('BackendUsuario', 'Producto', 'Foto');
global $BackendUsuario, $Producto, $Foto;

//$BackendUsuario->EstaLogeadoBackend();

$id_item = ($_REQUEST['id_item']) ? $_REQUEST['id_item'] : null;
$accion  = ($_POST['accion']) ? $_POST['accion'] : null;
$id = ($_POST['id']) ? $_POST['id'] : null;

if(empty($id_item)) {
 die;
}
switch($accion) {

  case 'grabar':
    $db_imagen = escapeSQLTags($_POST['imagen']);
  	
    $last_id = $Foto->grabar($_POST, $id_item, $ext);
    $id = $id_galeria;
  break;

  case 'borrar-foto':
   $last_id = $Foto->eliminar($id);
  break;
  
  case 'ordenar-foto':
   $last_id = $Foto->ordenar($id_item, $_POST);
  break;
}

$result_fotos = $Foto->obtener_all(null, null, null, $id_item, null);
$filas_fotos = @mysql_num_rows($result_fotos);
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title> Editar/Agregar Fotos</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="assets/plugins/jquery-ui-1.10.4/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-3.1.1/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<style>
	body { background-color: #ffffff}
	margin: 0px
	.foto-default {border:1px dotted #000; width:100px;height:60px}
	.itemfoto { border:1px solid #000000; width:120px; float:left;margin-left:5px}
	</style>
  
  <script>
   function eliminar_foto(idx) {
    if(confirm('Esta seguro de querer borrar?')) {
 			var form = document.forms['frm_fotos'];
 			form['accion'].value = 'borrar-foto';
 			form['id'].value = idx;
 			form.submit();
	  }
  }
  
  function orden(id_item) {
    	var form = document.forms['frm_fotos'];
 			form['accion'].value = 'ordenar-foto';
 			form['id_item'].value = id_item;
 			form.submit();
  } 
  </script>
</head>
<body>	
<div>
<form name="frm_fotos" id="frm_fotos" method="POST" action="">
<input type="hidden" name="accion" value="grabar">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="id_item" value="<?=$id_item?>">


<input type="hidden" name="borrar" id="borrar">

<div style="padding-left:20px">
 <h2>Galeria de fotos</h2>
</div>

<?php if($filas_fotos == 0) { ?>

<div align="center"> <strong>todavia no hay fotos en el item</strong></div>

<?php } else { ?>

<div style="width:800px; padding:20px">
	
<?php
		for($i=1; $i <= $filas_fotos; $i++) {
			$items = @mysql_fetch_array($result_fotos); ?> 

    <div class="itemfoto" style="width:140px;padding:10px">
      <img src="<?=URL_PATH_FRONT_ADJ?>/productos/<?=$items['imagen']?>" border="0" title="<?=$items['imagen']?>" hspace="4" width="120">
   		&nbsp;<a href="javascript:eliminar_foto(<?=$items['id']?>)">Eliminar</a>
			<div style="clear:both"></div><br/>
			Orden # <input type="text" name="orden<?=$items['id']?>" id="orden" style="width:50px" maxlength="2" value="<?=$items['orden']?>"/>
  </div>
    
 <?php } ?>
		<div style="clear:both"></div><br/>
		&nbsp;<a href="javascript:orden('<?=$id_item?>')"/><strong>Grabar Orden</strong></a>

</div>
  
<?php } ?>

<div style="clear:both"></div><br/><br/>

<hr size="1">

<div class="form-group" style="padding:20px">
    <label class="col-md-2 control-label"><h3>Seleccionar foto para subir</h3></label>
    <div style="clear:both"></div>
     <div class="col-md-9">
					   <p>
								<div id="images_container"></div>
							 	<div id="iframe_container" style="height:40px">
							 		<iframe src="uploadGaleria.php?carpeta=productos" class="iframeUpload" width="100%" height="50" scrolling="no" frameborder="0"></iframe></div><br/>
							 	  <img id="imagen_muestra" src="../adj/productos/<?=($arrActual['imagen']) ? $arrActual['imagen'] : '_default.gif'?>" border="1" width="75" height="75">

									<div style="clear:both"></div><br/>									
									<strong>Nota:</strong> <br/>
									<font color="#C80000">
										La imagen no se grabara hasta que no se  muestre en pantalla. <br/>
										Solo acepta imagenes JPG y PNG
									</font>
									<input type="hidden" name="imagen" id="imagen" size="20" style="color: #000000;border: 1px solid #ffffff;background-color: #ffffff" value="<?=key_value('imagen', $arrActual)?>" readonly>
						 </p>
		
						<div style="clear:both"></div><br/>
						<input type="submit" name="btgrabar" value="Grabar Foto" />
		 </div>
  <div style="clear:both"></div><br/>
</div>

</form>
</div>
</body>
</html>
