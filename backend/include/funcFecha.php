<?php
// functiones de fecha
// 19/07/2012 10:34:55

function to_mysql($fecha) {
	$temp = explode("/",$fecha);
  $fecha_mysql = $temp[2]."-".$temp[1]."-".$temp[0];
  return $fecha_mysql;
} 
//mm/dd/aaaa
function to_mysql_reporte($fecha) {
	$temp = explode("/",$fecha);
  $fecha_mysql = $temp[2]."-".$temp[0]."-".$temp[1];
  return $fecha_mysql;
} 

function to_mysql_detalles($fecha) {
	$temp = explode("-",$fecha);
  $fecha_mysql = $temp[0]."/".$temp[1]."/".$temp[2];
  return $fecha_mysql;
}

function to_mysql_semanal($fecha) {
	$temp = explode("-",$fecha);
  $fecha_mysql = $temp[2]."/".$temp[1]."/".$temp[0];
  return $fecha_mysql;
}
//Pasa del formato yyyy-mm-dd hh:ii:ss a Enero 09, 2007 18:22:28 
function GetFechaTexto($datetime) 	{
  
  $arrHoraFecha = explode(" ",$datetime);
  $Fecha  = $arrHoraFecha[0];
  $Hora   = $arrHoraFecha[1];

  $arrFechaAlta = explode("-",$Fecha);
  $Dia  = $arrFechaAlta[2];
  $Mes  = GetMes($arrFechaAlta[1]);
  $Anio = $arrFechaAlta[0];
  
  return $Mes . " " . $Dia . ", " . $Anio . " " . substr ($Hora, 0, 5);
}

function solo_fecha_texto($datetime) 	{
  
  $arrHoraFecha = explode(" ",$datetime);
  $Fecha  = $arrHoraFecha[0];
  $Hora   = $arrHoraFecha[1];

  $arrFechaAlta = explode("-",$Fecha);
  $Dia  = $arrFechaAlta[2];
  $Mes  = GetMes($arrFechaAlta[1]);
  $Anio = $arrFechaAlta[0];
  
  return $Mes." ".$Dia.", ".$Anio;
}

function CutDate($datetime) {
	return substr ($datetime, 0, 16); 
}
	
function HourOk($hora) 	{
	return (preg_match('/^00[\:\.]00/', $hora) ? false : $hora);
}

/**
* @return string
* @param string $date
* Convierte una fecha en formato yyyy-mm-dd (ISO) a dd-mm-yyyy y viceversa
*/
function ConverterDate($date) {
	return join('-', array_reverse(split('-', $date)));
}

	
	/**
	* @return int
	* @param int $year
	* Devuelve la cantidad de semanas del año
	*/
	function Weeks($year) {
	   return date("W",mktime(0,0,0,12,28,$year));
	}


	/**
	* @return string
	* @param int $mes
	* Devuelve un string con el nombre del mes
	*/
	function GetMes($mes) {
	  $mes = (int)$mes;
	  $arrMeses = array( 1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril" , 5 => "Mayo" , 6 => "Junio" , 7 => "Julio" , 8 => "Agosto" , 9 => "Septiembre" , 10 => "Octubre" , 11 => "Noviembre", 12 => "Diciembre" );
    return ( isset($arrMeses[$mes]) ? $arrMeses[$mes] : "" );
	}


	/**
	* @return string
	* @param int $dia
	* Devuelve un string con el nombre del dia
	*/
	function GetDia($dia) {
	  $dia = (int)$dia;
	  $arrDias = array( 0 => "Domingo", 1 => "Lunes", 2 => "Martes", 3 => "Miercoles", 4 => "Jueves" , 5 => "Viernes" , 6 => "Sabado" , 7 => "Domingo");
    return ( isset($arrDias[$dia]) ? $arrDias[$dia] : "" );
	}


	function DateToSpanish() {
	/* nombramos en una matriz los nombres de los meses y días*/
	$meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
	$dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");

	$dia  = date("j"); // devuelve el día del mes
	$dia2 = date("w"); // devuelve el número de día de la semana
	$mes  = date("n")-1; // devuelve el número del mes
	$ano  = date("Y"); // devuelve el año
	$fecha = $dias[$dia2].", ".$dia." de ".$meses[$mes]." de ".$ano;
	echo $fecha;
	}

	/**
	* @return string
	* @param string $datetime
	* Acorta el formato yyyy-mm-dd hh:ii:ss 
	*/
  function AcortarFecha($datetime) {
		return @substr($datetime, 0, 16); 
	}

	function AcortarFechaFull($datetime) {
		return @substr($datetime, 0, 10); 
	}

	/**
	* @return int
	* @param int[optional] $mes
	* @param int[optional] $dia
	* @param int[optional] $anio
	* Devuelve un timestamp con los datos indicados.
	*/
	function MakeDate($mes=0, $dia=0, $anio=0) {
		$mes  = $mes  ? $mes  : date('m');
		$dia  = $dia  ? $dia  : date('d');
		$anio = $anio ? $anio : date('Y');
		return mktime(0, 0, 0, $mes, $dia, $anio);
	}

	/**
	* @param string $format
	* @param int $timestamp
	* Imprime a pantalla la fecha indicada en $timestamp con el format $format.
	*/
	function ShowTime($format, $timestamp=null) {
		$timestamp = is_null($timestamp) ? time() : $timestamp;
		echo str_replace('De', 'de', ucwords(strftime($format, $timestamp)));
	}

  function MostrarFechaHoyByTexto() {
	// Obtenemos y traducimos el nombre del día
	$dia=date("l");
	if ($dia=="Monday") $dia="Lunes";
	if ($dia=="Tuesday") $dia="Martes";
	if ($dia=="Wednesday") $dia="Miércoles";
	if ($dia=="Thursday") $dia="Jueves";
	if ($dia=="Friday") $dia="Viernes";
	if ($dia=="Saturday") $dia="Sabado";
	if ($dia=="Sunday") $dia="Domingo";

	// Obtenemos el número del día
	$dia2=date("d");

	// Obtenemos y traducimos el nombre del mes
	$mes=date("F");
	if ($mes=="January") $mes="Enero";
	if ($mes=="February") $mes="Febrero";
	if ($mes=="March") $mes="Marzo";
	if ($mes=="April") $mes="Abril";
	if ($mes=="May") $mes="Mayo";
	if ($mes=="June") $mes="Junio";
	if ($mes=="July") $mes="Julio";
	if ($mes=="August") $mes="Agosto";
	if ($mes=="September") $mes="Setiembre";
	if ($mes=="October") $mes="Octubre";
	if ($mes=="November") $mes="Noviembre";
	if ($mes=="December") $mes="Diciembre";

	// Obtenemos el año
	$ano=date("Y");

	// Imprimimos la fecha completa
	echo "$dia $dia2 de $mes de $ano";
 }

function obtener_numero_mes($mes) {
 switch($mes) {	
  case 'Enero':
  $mes = '01';
  break;
  case 'Febrero':
  $mes = '02';
  break;
  case 'Marzo':
  $mes = '03';
  break;
  case 'Abril':
  $mes = '04';
  break;
  case 'Mayo':
  $mes = '05';
  break;
  case 'Junio':
  $mes = '06';
  break;
  case 'Julio':
  $mes = '07';
  break;
  case 'Agosto':
  $mes = '08';
  break;
  case 'Septiembre':
  $mes = '09';
  break;
  case 'Octubre':
  $mes = '10';
  break;
  case 'Noviembre':
  $mes = '11';
  break;
  case 'Diciembre':
  $mes = '12';
  break;
 }
  return $mes;
}
?>