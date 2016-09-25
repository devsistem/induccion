<?php
include("../config/conn.php");
$img = ($_GET['img']) ? $_GET['img'] : null;

$ext_jpg = ".jpg";
$ext_png = ".png";
$ext_gif = ".gif";

// carpeta general
//$carpeta_taller = substr(md5($id_taller), 0,10);

// si falta alguno de los campos, sale
if(empty($img)) {
 print "ERROR 1001";
 die;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Subir Imagenes </title>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>

<script type="text/javascript">
$(document).ready(function() { 
	var options = { 
			target: '#output',   // target element(s) to be updated with server response 
			beforeSubmit: beforeSubmit,  // pre-submit callback 
			success: afterSuccess,  // post-submit callback 
			resetForm: true        // reset the form after successful submit 
		}; 
		
	 $('#MyUploadForm').submit(function() { 
			$(this).ajaxSubmit(options);  			
			// always return false to prevent standard browser submit and page navigation 
			return false; 
		}); 
}); 

function afterSuccess() {
	$('#submit-btn').show(); //hide submit button
	$('#loading-img').hide(); //hide submit button
}

//function to check file size before uploading.
function beforeSubmit(){
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
	{
		
		if( !$('#imageInput').val()) //check empty input filed
		{
			$("#output").html("Debe seleccionar una imagen?");
			return false
		}
		
		var fsize = $('#imageInput')[0].files[0].size; //get file size
		var ftype = $('#imageInput')[0].files[0].type; // get file type
		
		//allow only valid image file types 
		switch(ftype) {
            case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
                break;
            default:
                $("#output").html("<b>"+ftype+"</b> El formato no es valido. Solo son aceptadas imagenes");
				return false
        }
		
		//Allowed file size is less than 1 MB (1048576)
		if(fsize>1048576) 
		{
			$("#output").html("<b>"+bytesToSize(fsize) +"</b> La imagen de demasiado grande <br />Por favor utilice un editor de imagenes para reducirla.");
			return false
		}
				
		$('#submit-btn').hide(); //hide submit button
		$('#loading-img').show(); //hide submit button
		$("#output").html("");  
		
		// campo
		var img_campo = $('#img_campo').val();
		var imagen_subida = $('#imageInput').val();
	  parent.document.getElementById(img_campo).value = $('#imageInput').val();

	}
	else
	{
		//Output error to older browsers that do not support HTML5 File API
		$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

</script>
<link href="style/upload_fotos_v2.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="upload-wrapper">
	
	<?php if($img == "imagen_factura") { ?>
		<span class="texto_upload">Foto Planilla de Luz</span>
  <?php } else if($img == "imagen_dni_frente") { ?>
    <span class="texto_upload">Foto Cédula Frente</span>
  <?php } else if($img == "imagen_dni_posterior") { ?>
    <span class="texto_upload">Foto Cédula Atras</span>
	<?php } else if($img == "imagen_dni_duenio_frente") { ?>
		<span class="texto_upload">Foto Cédula Frente Arrendador</span>
  <?php } else if($img == "imagen_dni_duenio_posterior") { ?>
    <span class="texto_upload">Foto Cédula Atras Arrendador</span>
  <?php } else if($img == "imagen_duenio_garante") { ?>
    <span class="texto_upload">Solicitud Garante</span>
  <?php } ?>
  

<div align="center">
<form action="processupload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
<input name="image_file" id="imageInput" type="file" />

<input type="hidden" name="id_pedido" value="<?=$id_pedido?>"/>
<input type="hidden" name="img" id="img_campo" value="<?=$img?>"/>

<input type="submit"  id="submit-btn" value="+ Subir" />
	<img src="images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Por favor espere..."/>
</form>
<div id="output"></div>

</div>
</div>

</body>
</html>