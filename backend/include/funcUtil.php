<?php
	// funciones util
	// 31/03/2014 22:34:01
	
  // esta funcion pasa un titulo de un contenido a una url permanent link valida
  function permanent_link($t=null) {
  	//sacar espacios del final y principio
  	$t = trim($t);
  	//remplazar espacios por  -
  	$t = str_replace(" ","-",$t);
  	$t = str_replace("/","-",$t);
  	
    // minusculas
  	$t = strtolower($t);
  	// caracteresespeciales off
  	$t = str_replace("á","a",$t);
  	$t = str_replace("é","e",$t);
  	$t = str_replace("í","i",$t);
  	$t = str_replace("ó","o",$t);
  	$t = str_replace("ú","u",$t);
  	$t = str_replace("ñ","n",$t);
 	 return $t;
  }
  /////////////////////////////////////////////////////////////////////////////////////////
	// FUNCIONES DE ARRAYS
	/////////////////////////////////////////////////////////////////////////////////////////

	/**
	* @return array
	* @param result mysql
	* Convierte un result de una consulta select mysql a un array
	* ERROR: No funciona pasando la variable rol_id por parametro
	*/
	Function ObjetoToArray($result,$campo='rol_id')
	{
		$return = array();
		for ($i=0; $i < mysql_num_rows($result); $i++) {
		 $Resultados  = mysql_fetch_array($result);
		 $return[$i] = $Resultados['rol_id'];	
	  }
		return $return;
	}

	/**
	* @return array
	* @param int|string $key
	* @param array $array Array bidimensional
	* Busca $key en el segundo nivel de $array y devuelve los valores en un array.
	*/
	Function array_get_value_key($key, $array)
	{
		$return = array();
		if(is_array($array)) foreach($array as $inarray)
		{
			if(is_array($inarray) && array_key_exists($key, $inarray))
			{
				$return[] = $inarray[$key];
			}
		}
		return $return;
	}

	/**
	* @return array
	* @param int|string $keyName
	* @param int|string $keyValue
	* @param array $array Array bidimensional
	* Busca $keyName y $keyValue en el segundo nivel de $array y devuelve el par de valores en un array asociativo.
	*/
	Function array_in_dictionary($keyName, $keyValue, $array)
	{
		$return = array();
		if(is_array($array) && sizeof($array)) foreach($array as $inarray)
		{
			if(is_array($inarray) && array_key_exists($keyName, $inarray) && array_key_exists($keyValue, $inarray))
			{
				$return[$inarray[$keyName]] = $inarray[$keyValue];
			}
		}
		return $return;
	}

	/**
	* Esta función existe nativamente en PHP5, por lo cual verifico que no exista antes de crearla.
	*/
	if(!function_exists('array_diff_key'))
	{
		/**
		* @return array
		* @param array $array1
		* @param array $array2
		* Computes the difference of arrays using keys for comparison
		*/
		Function array_diff_key($array1, $array2)
		{
			if(!is_array($array1))
			{
				trigger_error('El primer parametro no es un array.', E_USER_ERROR);
				return null;
			}

			if(!is_array($array2))
			{
				trigger_error('El segundo parametro no es un array.', E_USER_ERROR);
				return null;
			}

			foreach($array1 as $key => $value)
			{
				if(isset($array2[$key]))
				{
					unset($array1[$key]);
				}
			}
			return $array1;
		}
	}

	// Esta función existe nativamente en PHP5, por lo cual verifico que no exista antes de crearla.
	if(!function_exists('array_intersect_key'))
	{
		/**
		* @return array
		* @param array $arrPrincipal
		* @param array $array
		* Intersecta las claves de $arrPrincipal con el resto de los array pasados por parametro y devuelve el array resultante.
		*/
		function array_intersect_key($arrPrincipal, $array) {
			for($i=1; $i<func_num_args(); $i++)
			{
				$arrResultado = array();
				$arrToIntersect = func_get_arg($i);
				if(is_array($arrToIntersect) && sizeof($arrToIntersect))
				{
					foreach($arrToIntersect as $key => $value)
					{
						if(array_key_exists($key, $arrPrincipal))
						{
							$arrResultado[$key] = $arrPrincipal[$key];
						}
					}
					$arrPrincipal = $arrResultado;
				}
			}
			return $arrResultado;
		}
	}

	/**
	* @return array
	* @param int|string $key
	* @param array $array
	* @param array $array2
	* @uses array_in_search()
	* Busco la clave $key con el valor $array[$key] en el segundo nivel de $array2
	* y en caso de encontrarla devuelvo la unión de $array con el sub array encontrado en $array2
	*/
	function merge_key($key, $array, $array2) {
		if(is_array($array) && array_key_exists($key, $array))
		{
			if($inarray = array_in_search($key, $array[$key], $array2))
			{
				$array = array_merge($array, $inarray);
			}
		}
		return $array;
	}

	/**
	* @return mixed
	* @param array $array
	* @param int|string $key
	* Devuelve el valor de la clave del array o null si no existe.
	*/
	function key_value($key, $array) {
		$return = (is_array($array) && array_key_exists($key, $array) ? $array[$key] : null);
		return $return;
	}

	/**
	* @return array
	* @param int|string $key
	* @param array $array
	* @param array $array2
	* @uses merge_key()
	* Busco la clave $key con todos los valores de $array[][$key] en el segundo nivel de $array2
	* y en caso de encontrarla junto $array[] con el sub array encontrado en $array2.
	* Devuelvo $array con los valores agregados.
	*/
	function array_merge_key($key, $array, $array2) {
		if(is_array($array) && is_array($array2)) foreach($array as $inkey=>$inarray)
		{
			$array[$inkey] = merge_key($key, $inarray, $array2);
		}
		return $array;
	}

	/**
	* @return array
	* @param int|string $key
	* @param mixed $valor
	* @param array $array Array bidimensional
	* Busca la clave $key con el valor $valor en el segundo nivel de $array.
	* Devuelve todo el sub array de $array en caso de encontrar una coincidencia.
	*/
	function array_in_search($key, $valor, $array) {
		if(is_array($array) && sizeof($array)) foreach($array as $inarray)
		{
			if(is_array($inarray) && array_key_exists($key, $inarray) && $inarray[$key]==$valor)
			{
				return $inarray;
			}
		}
		return false;
	}

	/**
	* @return array
	* @param int|string $key
	* @param mixed $valor
	* @param array $array
	* @see array_in_search()
	* Similar a array_in_search() pero devuelve todas las coincidencias encontradas.
	*/
	Function array_select_key($key, $valor, $array)
	{
		$return = array();
		if(is_array($array) && sizeof($array)) foreach($array as $inarray)
		{
			if(is_array($inarray) && array_key_exists($key, $inarray) && $inarray[$key]==$valor)
			{
				$return[] = $inarray;
			}
		}
		return $return;
	}

/**
* @return array
* @param array $array Array de strings
* @param int[optional] $offSet
* Recorre los valores de $array y devuelve un array con el valore de la posición $offSet de cada uno (en mayuscula).
*/
function array_initial($array, $offSet=0)
{
	$return = array();
	if(is_array($array)) foreach($array as $value)
	{
		$value = strtoupper($value{$offSet});
		if(!array_key_exists($value, $return)) $return[$value] = sizeof($return);
	}
	$return = array_flip($return);
	return $return;
}
	
function GeneratePassword($length=10) {
	$base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$pwd  = '';
	for($i=0; $i < $length; $i++) $pwd .= $base{rand(0, strlen($base)-1)};
	return $pwd;
}

function EnviarMensajeAlSoporte($class) {
  $arrClass = func_get_args();
}

function encontrar_extension($fichero) {
 $fichero = strtolower($fichero) ;
 $extension = split("[/\\.]", $fichero) ;
 $n = count($extension)-1;
 $extension = $extension[$n];
 return $extension;
} 

function genera_random_numero($longitud){ 
	$numero_aleatorio = rand(1000000,9000000);
	return $numero_aleatorio;
}
?>