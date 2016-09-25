<?php
/**
* @name Class Objeto
* @package SQLServer
* @subpackage Administración
*/
class Modulo {
	function obtenerModulo($modulo,$type='frontend')  {
	   global $arrModulos;
	   $Comp = ($type=='frontend') ? FS_PAGINAS : FSA_MODULOS;
	   if(@is_file($Comp.$arrModulos[$modulo]) && @file_exists($Comp.$arrModulos[$modulo]))
	   require $Comp.$arrModulos[$modulo];
	}
	function crearObjetoValido($objeto) {	
	 // Eliminar Espacios
	 $objeto = @trim($objeto);
	 // Pasa a minusculas
	 $objeto = @strtolower($objeto);
	 // PHPEABLE
	 $objeto = $objeto.".php";
	 return $objeto;
	}
} // END CLASS
?>