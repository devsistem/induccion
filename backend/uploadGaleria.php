<?php
include_once("config/conn.php");
include_once("../include/funcImage.php");

if (isset($_FILES['image'])) {

	$carpeta = $_REQUEST['carpeta'];
	$ftmp  = $_FILES['image']['tmp_name'];
	$oname = $_FILES['image']['name'];
	
	// cambio de nombre?
	
	$fname = FILE_PATH_FRONT_ADJ."/".$carpeta."/".$_FILES['image']['name'];
  $path  = URL_PATH_FRONT_ADJ."/".$carpeta."/";
  $foto_muestra = $path.$oname;

  
  @chmod($fname, 0777);
    
	if(move_uploaded_file($ftmp, $fname)){
		?>
		<html><head><script>
		var par = window.parent.document;
		var images2 = par.getElementById('images_container');
		var imagen_muestra = par.getElementById('imagen_muestra');
		var imgdiv = images2.getElementsByTagName('div')[<?=(int)$_POST['imgnum']?>];
		var image = imgdiv.getElementsByTagName('img')[0];
		imgdiv.removeChild(image);
		var image_new = par.createElement('img');
		
		par.frm_fotos.imagen.value = '<?=$oname?>';
		imagen_muestra.src = '<?=$path.$oname?>';
    imagen_muestra.style.width = '90px';
    imagen_muestra.style.Height = '90px';
    par.frm_fotos.borrar.disabled=false;
		</script></head>
		</html>
		<?php
		exit();
	}
}
?>
<html><head>
<script language="javascript">
function upload(){
	var par = window.parent.document;

	// hide old iframe
	var iframes = par.getElementsByTagName('iframe');
	var iframe = iframes[iframes.length - 1];
	iframe.className = 'hidden';

	// create new iframe
	var new_iframe = par.createElement('iframe');
	new_iframe.src = 'uploadGaleria.php';
	new_iframe.frameBorder = '0';
	par.getElementById('iframe_container').appendChild(new_iframe);

	// add image progress
	var images = par.getElementById('images_container');
	var new_div = par.createElement('div');
	var new_img = par.createElement('img');
	new_img.src = 'assets/img/indicator.gif';
	new_img.className = 'load';
	new_div.appendChild(new_img);
	images.appendChild(new_div);
	
	// send
	var imgnum = images.getElementsByTagName('div').length - 1;
	document.iform.imgnum.value = imgnum;
	setTimeout(document.iform.submit(),5000);
}
</script>
<style>
#file {	width: 200px;}
input {font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;font-size:  13px;font-weight:bold;MARGIN: 0px 0px 0px 0px;VERTICAL-ALIGN: middle;CURSOR: pointer;COLOR: #ffffff;BACKGROUND-COLOR: #F7F7F7; border: 1px solid #cccccc;}
</style>
<head><body topmargin="3" leftmargin="0">
<form name="iform" action="" method="post" enctype="multipart/form-data">
<input id="file" type="file" name="image" onchange="upload()" />
<input type="hidden" name="imgnum"  />
</form>
</html>