<?php
/*
1. pdf deveria mostrarse de la siguiente forma

Foto de Dueño Frontal | Foto de Dueño Trasero

2. pdf deveria mostrarse de la siguiente forma

cada foto a lado de otra

Foto de Arrendador Frontal | Foto de Arrendador Trasero

(Si no solo hay arrendador no deveria aparecer este pdf)

3. Pdf con la Carta de Autorización
*/
include_once("config/conn.php");
define("dbi_DEBUG_MODE",0);

if( dbi_DEBUG_MODE )
{
	ini_set("display_errors",1);
	error_reporting(E_ALL);
}
else					  
{
	ini_set("display_errors",0);
	error_reporting(E_NONE);
}


// imagenes
$imagen1 = (!isset($_POST['imagen1'])) ? null : $_POST['imagen1'];	
$imagen2 = (!isset($_POST['imagen2'])) ? null : $_POST['imagen2'];	
//echo $imagen1;
	
require('fpdf17/fpdf.php');

//Stream handler to read from global variables
class VariableStream
{
	var $varname;
	var $position;

	function stream_open($path, $mode, $options, &$opened_path)
	{
		$url = parse_url($path);
		$this->varname = $url['host'];
		if(!isset($GLOBALS[$this->varname]))
		{
			trigger_error('Global variable '.$this->varname.' does not exist', E_USER_WARNING);
			return false;
		}
		$this->position = 0;
		return true;
	}

	function stream_read($count)
	{
		$ret = substr($GLOBALS[$this->varname], $this->position, $count);
		$this->position += strlen($ret);
		return $ret;
	}

	function stream_eof()
	{
		return $this->position >= strlen($GLOBALS[$this->varname]);
	}

	function stream_tell()
	{
		return $this->position;
	}

	function stream_seek($offset, $whence)
	{
		if($whence==SEEK_SET)
		{
			$this->position = $offset;
			return true;
		}
		return false;
	}
	
	function stream_stat()
	{
		return array();
	}
}

class PDF_MemImage extends FPDF
{
	function PDF_MemImage($orientation='P', $unit='mm', $format='A4')
	{
		$this->FPDF($orientation, $unit, $format);
		//Register var stream protocol
		stream_wrapper_register('var', 'VariableStream');
	}

	function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='')
	{
		//Display the image contained in $data
		$v = 'img'.md5($data);
		$GLOBALS[$v] = $data;
		$a = getimagesize('var://'.$v);
		if(!$a)
			$this->Error('Invalid image data');
		$type = substr(strstr($a['mime'],'/'),1);
		$this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
		unset($GLOBALS[$v]);
	}

	function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='')
	{
		//Display the GD image associated to $im
		ob_start();
		imagepng($im);
		$data = ob_get_clean();
		$this->MemImage($data, $x, $y, $w, $h, $link);
	}
}


$pdf = new PDF_MemImage();

$pdf->SetAuthor('Induccion.ec');
$pdf->SetTitle('Imagenes Pedido Dueño - Cliente Juan Perez');
$pdf->SetFont('Helvetica','B',20);
$pdf->SetTextColor(50,60,100);
$pdf->AddPage('P');

//Load an image into a variable
if(strlen($imagen1) > 4) {
	//echo FILE_PATH_FRONT_ADJ."/pedidos/".$imagen1;
	$cedula_frente = file_get_contents(FILE_PATH_FRONT_ADJ."/pedidos/".$imagen1);
	 //echo $cedula_frente;
	$pdf->MemImage($cedula_frente, 1, 1, 220, null);
	$pdf->AddPage('P');
}

if(strlen($imagen2) > 4) {
	//echo FILE_PATH_FRONT_ADJ."/pedidos/".$imagen2;
	$cedula_posterior = file_get_contents(FILE_PATH_FRONT_ADJ."/pedidos/".$imagen2);
	//echo $cedula_frente;
  $pdf->MemImage($cedula_posterior, 1, 1, 220, null);
}

$pdf->Output(); 

/*
$pdf = new FPDF();
$pdf->SetAuthor('Induccion.ec');
$pdf->SetTitle('Imagenes Pedido Dueño - Cliente Juan Perez');
$pdf->SetFont('Helvetica','B',20);
$pdf->SetTextColor(50,60,100);

//set up a page
$pdf->AddPage('P');
$pdf->SetDisplayMode(real,'default');

//print FILE_PATH_ROOT.'/adj/pedidos/1713553376_IMG_cedula_atras_duenio.JPG';

//insert an image and make it a link
$pdf->Image('../adj/pedidos/1713553376_IMG_2248_solicitud_garante.png',10,20,33,0,' ','');

//display the title with a border around it
$pdf->SetXY(50,20);
$pdf->SetDrawColor(50,60,100);
$pdf->Cell(100,10,'FPDF Tutorial',1,0,'C',0);

//Set x and y position for the main text, reduce font size and write content
$pdf->SetXY (10,50);
$pdf->SetFontSize(10);
$pdf->Write(5,'Congratulations! You have generated a PDF.');

//Output the document
$pdf->Output('../adj/pedidos/pedido_23232323.pdf','I'); 
*/
?>