<?php
include_once("config/conn.php");
declareRequest('accion','usuario','clave');
loadClasses('BackendUsuario', 'Foto', 'Localizacion');
global $BackendUsuario, $Foto, $Localizacion;

$BackendUsuario->EstaLogeadoBackend();

$id_galeria = ($_REQUEST['id_galeria']) ? $_REQUEST['id_galeria'] : null;
$accion  = ($_POST['accion']) ? $_POST['accion'] : null;
$id = ($_POST['id']) ? $_POST['id'] : null;

if(empty($id_galeria)) {
 die;
}
switch($accion) {

  case 'grabar':
    $db_imagen = escapeSQLTags($_POST['imagen']);
  	
    $last_id = $Foto->grabar($_POST, $id_galeria, $ext);
    $id = $id_galeria;
    
    // obtiene para agregarle la marca a la g
    /*
    $arrFoto =  $Foto->obtener($last_id);
    $imagen_g = $arrFoto['imagen_g'];
    $imagen = $_REQUEST['imagen'];
	  $imagen = "../adj/galeria/".$db_imagen;
	  $marcadeagua = "../images/marcadeagua.png";
		$margen = 10;
	
	  //Averiguamos la extensión del archivo de imagen
	  $trozos_nombre_imagen = explode(".",$imagen);
	  $extension_imagen=$trozos_nombre_imagen[count($trozos_nombre_imagen)-1];
  
	  //Creamos la imagen según la extensión leída en el nombre del archivo
	  if(preg_match('/jpg|jpeg|JPG|JPEG/',$extension_imagen))
				$img=@ImageCreateFromJPEG($imagen); 
 	  if(preg_match('/png|PNG/',$extension_imagen)) 
        	$img=@ImageCreateFromPNG($imagen); 
 	  if(preg_match('/gif|GIF/',$extension_imagen)) 
        	$img=@ImageCreateFromGIF($imagen); 
	
	  //declaramos el fondo como transparente	
	  @ImageAlphaBlending($img, true);
		
		//Ahora creamos la imagen de la marca de agua
		$marcadeagua = @ImageCreateFromPNG($marcadeagua);
	
		//Hallamos las dimensiones de ambas imágenes para alinearlas
		$Xmarcadeagua = imagesx($marcadeagua);
		$Ymarcadeagua = imagesy($marcadeagua);
		$Ximagen = imagesx($img);
		$Yimagen = imagesy($img);
	
		//Copiamos la marca de agua encima de la imagen (alineada abajo a la derecha)
		@imagecopy($img, $marcadeagua, $Ximagen-$Xmarcadeagua-$margen, $Yimagen-$Ymarcadeagua-$margen, 0, 0, $Xmarcadeagua, $Ymarcadeagua);
	
		//Guardamos la imagen sustituyendo a la original, en este caso con calidad 100
		@ImageJPEG($img,$imagen,100);
	
		//Eliminamos de memoria las imágenes que habíamos creado
		@imagedestroy($img);
		@imagedestroy($marcadeagua);
   
    // g0
    $imagen_g = $arrFoto['imagen_g'];
    $imagen_g = "../adj/galeria/".$imagen_g;
	  $marcadeagua = "../images/marcadeagua.png";
		$margen = 10;
	
	  //Averiguamos la extensión del archivo de imagen
	  $trozos_nombre_imagen = explode(".",$imagen_g);
	  $extension_imagen=$trozos_nombre_imagen[count($trozos_nombre_imagen)-1];
  
	  //Creamos la imagen según la extensión leída en el nombre del archivo
	  if(preg_match('/jpg|jpeg|JPG|JPEG/',$extension_imagen))
				$img=@ImageCreateFromJPEG($imagen_g); 
 	  if(preg_match('/png|PNG/',$extension_imagen)) 
        	$img=@ImageCreateFromPNG($imagen_g); 
 	  if(preg_match('/gif|GIF/',$extension_imagen)) 
        	$img=@ImageCreateFromGIF($imagen_g); 
	
	  //declaramos el fondo como transparente	
	  @ImageAlphaBlending($img, true);
		
		//Ahora creamos la imagen de la marca de agua
		$marcadeagua = @ImageCreateFromPNG($marcadeagua);
	
		//Hallamos las dimensiones de ambas imágenes para alinearlas
		$Xmarcadeagua = imagesx($marcadeagua);
		$Ymarcadeagua = imagesy($marcadeagua);
		$Ximagen = imagesx($img);
		$Yimagen = imagesy($img);
	
		//Copiamos la marca de agua encima de la imagen (alineada abajo a la derecha)
		@imagecopy($img, $marcadeagua, $Ximagen-$Xmarcadeagua-$margen, $Yimagen-$Ymarcadeagua-$margen, 0, 0, $Xmarcadeagua, $Ymarcadeagua);
	
		//Guardamos la imagen sustituyendo a la original, en este caso con calidad 100
		@ImageJPEG($img,$imagen_g,100);
	
		//Eliminamos de memoria las imágenes que habíamos creado
		@imagedestroy($img);
		@imagedestroy($marcadeagua);
		*/
  break;

  case 'borrar-foto':
   $last_id = $Foto->eliminar($id);
  break;
  
  case 'ordenar-foto':
   $last_id = $Foto->ordenar($id_galeria, $_POST);
  break;
}

$result_fotos = $Foto->obtener_all(null, null, null, $id_galeria, null);
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
  
  function orden(id_galeria) {
    	var form = document.forms['frm_fotos'];
 			form['accion'].value = 'ordenar-foto';
 			form['id_galeria'].value = id_galeria;
 			form.submit();
  } 
  </script>
</head>
<body>	
<div>
<form name="frm_fotos" id="frm_fotos" method="POST" action="">
<input type="hidden" name="id_galeria" id="id_galeria" value="<?=$id_galeria?>">
<input type="hidden" name="accion" value="grabar">
<input type="hidden" name="id" value="<?=$id?>">
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
      <img src="<?=URL_PATH_FRONT_ADJ?>/galerias/<?=$items['imagen']?>" border="0" title="<?=$items['imagen']?>" hspace="4" width="120">
   		&nbsp;<a href="javascript:eliminar_foto(<?=$items['id']?>)">Eliminar</a>
			<div style="clear:both"></div><br/>
			Orden # <input type="text" name="orden<?=$items['id']?>" id="orden" style="width:50px" maxlength="2" value="<?=$items['orden']?>"/>
  </div>
    
 <?php } ?>
		<div style="clear:both"></div><br/>
		&nbsp;<a href="javascript:orden('<?=$id_galeria?>')"/><strong>Grabar Orden</strong></a>

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
							 		<iframe src="uploadGaleria.php?carpeta=galerias" class="iframeUpload" width="100%" height="50" scrolling="no" frameborder="0"></iframe></div><br/>
							 	  <img id="imagen_muestra" src="../adj/galerias/<?=($arrActual['imagen']) ? $arrActual['imagen'] : '_default.gif'?>" border="1" width="75" height="75">
									<b>Note:</b> <font color="#C80000">la imagen no se grabara hasta que no se  muestre en pantalla. Solo acepta imagenes JPG</font>
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
