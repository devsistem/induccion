<?php
function insertarmarcadeagua($imagen,$marcadeagua,$margen)
{
  //$imagen = FILE_PATH."/adj/items/venta_casa_01.jpg";
  //$marcadeagua = FILE_PATH."/images/water.png";
	//$margen = 10;

	//Se supone que la marca de agua tiene menor tamaño que la imagen
	//$imagen es la ruta de la imagen. Ej.: "carpeta/imagen.jpg"
	//&marcadeagua es la ruta de la imagen marca de agua. Ej.: "marca.png"
	//$margen determina el margen que quedará entre la marca y los bordes de la imagen
	
	//Averiguamos la extensión del archivo de imagen
	$trozos_nombre_imagen = explode(".",$imagen);
	$extension_imagen=$trozos_nombre_imagen[count($trozos_nombre_imagen)-1];
  
	//Creamos la imagen según la extensión leída en el nombre del archivo
	if(preg_match('/jpg|jpeg|JPG|JPEG/',$extension_imagen))
				$img=ImageCreateFromJPEG($imagen); 
 	if(preg_match('/png|PNG/',$extension_imagen)) 
        	$img=ImageCreateFromPNG($imagen); 
 	if(preg_match('/gif|GIF/',$extension_imagen)) 
        	$img=ImageCreateFromGIF($imagen); 
	
	//declaramos el fondo como transparente	
	ImageAlphaBlending($img, true);
		
	//Ahora creamos la imagen de la marca de agua
	$marcadeagua = ImageCreateFromPNG($marcadeagua);
	
	//Hallamos las dimensiones de ambas imágenes para alinearlas
	$Xmarcadeagua = imagesx($marcadeagua);
	$Ymarcadeagua = imagesy($marcadeagua);
	$Ximagen = imagesx($img);
	$Yimagen = imagesy($img);
	
	//Copiamos la marca de agua encima de la imagen (alineada abajo a la derecha)
	imagecopy($img, $marcadeagua, $Ximagen-$Xmarcadeagua-$margen, $Yimagen-$Ymarcadeagua-$margen, 0, 0, $Xmarcadeagua, $Ymarcadeagua);
	
	//Guardamos la imagen sustituyendo a la original, en este caso con calidad 100
	ImageJPEG($img,$imagen,100);
	
	//Eliminamos de memoria las imágenes que habíamos creado
	imagedestroy($img);
	imagedestroy($marcadeagua);
}

function agregar_marca($water,$imagen,$image_name, $image_size, $image_temp, $image_type) {

	$max_size = 800; //max image size in Pixels
	$destination_folder = FILE_PATH."/adj/items/";
	$watermark_png_file = FILE_PATH."/images/water.png"; //watermark png file
	
	/*
	$image_name = $_FILES['image_file']['name']; //file name
	$image_size = $_FILES['image_file']['size']; //file size
	$image_temp = $_FILES['image_file']['tmp_name']; //file temp
	$image_type = $_FILES['image_file']['type']; //file type
	*/
	switch(strtolower($image_type)){ //determine uploaded image type 
			//Create new image from file
			case 'image/png': 
				$image_resource =  imagecreatefrompng($image_temp);
				break;
			case 'image/gif':
				$image_resource =  imagecreatefromgif($image_temp);
				break;          
			case 'image/jpeg': case 'image/pjpeg':
				$image_resource = imagecreatefromjpeg($image_temp);
				break;
			default:
				$image_resource = false;
		}
	
	if($image_resource){
		//Copy and resize part of an image with resampling
		list($img_width, $img_height) = getimagesize($image_temp);
		
	    //Construct a proportional size of new image
		$image_scale        = min($max_size / $img_width, $max_size / $img_height); 
		$new_image_width    = ceil($image_scale * $img_width);
		$new_image_height   = ceil($image_scale * $img_height);
		$new_canvas         = imagecreatetruecolor($new_image_width , $new_image_height);

		if(imagecopyresampled($new_canvas, $image_resource , 0, 0, 0, 0, $new_image_width, $new_image_height, $img_width, $img_height))
		{
			
			if(!is_dir($destination_folder)){ 
				mkdir($destination_folder);//create dir if it doesn't exist
			}
			
			//center watermark
			$watermark_left = ($new_image_width/2)-(300/2); //watermark left
			$watermark_bottom = ($new_image_height/2)-(100/2); //watermark bottom

			$watermark = imagecreatefrompng($watermark_png_file); //watermark image
			imagecopy($new_canvas, $watermark, $watermark_left, $watermark_bottom, 0, 0, 300, 100); //merge image
			
			//output image direcly on the browser.
			header('Content-Type: image/jpeg');
			imagejpeg($new_canvas, NULL , 90);
			
			//Or Save image to the folder
			//imagejpeg($new_canvas, $destination_folder.'/'.$image_name , 90);
			
			//free up memory
			imagedestroy($new_canvas); 
			imagedestroy($image_resource);
			die();
		}
	}

}
// Corta una imagen, en los tamaños pasados como parametro
 function clipImage($file, $dest, $width, $height) {
	$imSrc  = imagecreatefromjpeg($file);
	$w      = imagesx($imSrc);
	$h      = imagesy($imSrc);
	if($width/$height>$w/$h) {
		$nh = ($h/$w)*$width;
		$nw = $width;
	} else {
		$nw = ($w/$h)*$height;
		$nh = $height;
	}
	$dx = ($width/2)-($nw/2);
	$dy = ($height/2)-($nh/2);
	$imTrg  = imageCreateTrueColor($width, $height);
	imagecopyresized($imTrg, $imSrc, $dx, $dy, 0, 0, $nw, $nh, $w, $h);
	imagedestroy($imSrc);
	imagejpeg($imTrg, $dest, 100);
	imagedestroy($imTrg);
 }

 function crearImagenResampleada($ancho_max, $alto_max, $imagen, $path, $subfijo) {
  
  require_once('clsThumbnail.php');
  
  // random para el numero
  $key = genera_random(8);
	$foto_subida = $path.$imagen;
	$imagen_original = $key;
	$filename = $path.$subfijo.$imagen_original.".jpg";    
	$thumb=new Thumbnail($foto_subida);	
 	$thumb->size($ancho_max,$alto_max); 
	$thumb->process();
	$thumb->save($filename);
 
 return $subfijo.$imagen_original.".jpg";	
 }

 function crearImagenResampleadaV2($ancho_max, $alto_max, $imagen, $path,  $nueva_imagen) {
  require_once('clsThumbnail.php');

	// formato horizontal
	// completar
	
	$foto_subida = $path;
	$imagen_original = $key;
	$filename = $nueva_imagen;    
	$thumb=new Thumbnail($foto_subida);	
 	$thumb->size($ancho_max,$alto_max); 
	$thumb->process();
	$thumb->save($filename);
 
 return true;	
}

function genera_random($longitud){ 
 $exp_reg="[^A-Z0-9]"; 
 return substr(eregi_replace($exp_reg, "", md5(rand())) . 
  eregi_replace($exp_reg, "", md5(rand())) . 
  eregi_replace($exp_reg, "", md5(rand())), 
    0, $longitud); 
}
