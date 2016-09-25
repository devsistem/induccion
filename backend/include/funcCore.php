<?php
/**
 * @param array $arrVars
 * @param array[optional] $arrDefaults
 * Valida el $_REQUEST de la página para los indices indicados.
 */

function ValidateRequest($arrVars, $arrDefaults='') {
 $arrVars     = array_values((array) $arrVars);
 $arrDefaults = array_values((array) $arrDefaults);

 for($i=0; $i<sizeof($arrVars); $i++) 	{
		$default = isset($arrDefaults[$i]) ? $arrDefaults[$i] : '';
		$_REQUEST[$arrVars[$i]] = isset($_REQUEST[$arrVars[$i]]) ? $_REQUEST[$arrVars[$i]] : $default;
	}
}

// Setea las variables pasados por parametros en caso de que no existan.
function DeclareRequest()	{
	validarRequest(func_get_args());
}

	/**
	* @param array $arrVars
	* @param array[optional] $arrDefaults
	* Valida el $_REQUEST de la página para los indices indicados.
	*/
	Function validarRequest($arrVars, $arrDefaults='')
	{
		$arrVars     = array_values((array) $arrVars);
		$arrDefaults = array_values((array) $arrDefaults);

		for($i=0; $i<sizeof($arrVars); $i++)
		{
			$default = isset($arrDefaults[$i]) ? $arrDefaults[$i] : '';
			$_REQUEST[$arrVars[$i]] = isset($_REQUEST[$arrVars[$i]]) ? $_REQUEST[$arrVars[$i]] : $default;
		}
	}

	Function ShowError($msg, $title='')
	{
    echo '
    <div class="errorMsg">';
    if ($title != '') {
        echo '<h4>'.$title.'</h4>';
    }
    if (is_array($msg)) {
        foreach ($msg as $m) {
            echo $m.'<br />';
        }
    } else {
        echo $msg;
    }
    echo '</div>';
 }

 Function Forward($pagina, $parametros=null)
  {
     $parametros = (is_null($parametros)) ? "" : "&".$parametros;
     header("Location: index.php?p=".$pagina.$parametros);
  }
	
	Function loadClasses($class)
	{
		$arrClass = func_get_args();
		foreach($arrClass as $key => $className)
		{
			if(empty($GLOBALS[$className]) || strtolower($className)!=get_class($GLOBALS[$className]))
			{
				if(file_exists(FILE_PATH.'/include/cls'.$className.'.php'))
				{
					require_once FILE_PATH.'/include/cls'.$className.'.php';
					eval('$GLOBALS[\''.$className.'\'] = &new '.$className.';');
				}
			}
		}
	}

	function loadClassesFrontEnd($class)
	{
		$arrClass = func_get_args();
		foreach($arrClass as $key => $className)
		{
			if(empty($GLOBALS[$className]) || strtolower($className)!=get_class($GLOBALS[$className]))
			{
				if(file_exists(FILE_PATH_FRONT."/".FRONTEND.'/include/cls'.$className.'.php'))
				{
					require_once FILE_PATH_FRONT."/".FRONTEND.'/include/cls'.$className.'.php';
					eval('$GLOBALS[\''.$className.'\'] = &new '.$className.';');
				}
			}
		}
	}


	Function loadClassesFiles($class)
	{
		$arrClass = func_get_args();
		foreach($arrClass as $key => $className)
		{
			if(!class_exists($className) && file_exists(ROOT_PATH.'/cls'.$className.'.php'))
			{
				require_once ROOT_PATH.'/cls'.$className.'.php';
			}
		}
	}


///////////////////////////////////////// Kernel


  ////////////////////////////////////////////////


  Function paginador($total=0, $pagina=0, $porPagina=10, $paginaLado=5, $jsFuncion='irPagina', $classNormal=null, $classActivo=null, $separadorNumeros=null, $separadorNoNumeros=null, $continuacion=null, $anterior=null, $siguiente=null, $antSeccion=null, $sigSeccion=null, $noHayPaginas=null)
	{
		$total     = intval($total);
		$pagina    = intval($pagina);
		$porPagina = intval($porPagina);

		$paginasTotales = $porPagina ? ceil($total/$porPagina) : 0;
		$html = $noHayPaginas;

		if($paginasTotales)
		{
			$html = '';
			$paginasLados = $paginaLado * 2;
			$finPagina    = min($paginasTotales-1, $pagina+$paginaLado);
			$inicioPagina = max(0, $finPagina-$paginasLados);

			if($pagina>0)
			{
				$pagSeccionAnt = max($pagina-$paginasLados, 0);
				$html .= $antSeccion   ? '<a href="javascript:'.$jsFuncion.'('.$pagSeccionAnt.')"'.($classNormal ? ' class="'.$classNormal.'"' : '').'>'.$antSeccion.'</a>'.$separadorNoNumeros : '';
				$html .= $anterior     ? '<a href="javascript:'.$jsFuncion.'('.($pagina-1).')"'.($classNormal ? ' class="'.$classNormal.'"' : '').'>'.$anterior.'</a>'.$separadorNoNumeros      : '';
				$html .= $continuacion ? $continuacion.$separadorNoNumeros : '';
			}

			for($i=0; $i<=$paginasLados; $i++)
			{
				$k = $inicioPagina + $i;
				if($k<$paginasTotales)
				{
					$html .= ($i?$separadorNumeros:'').($pagina==$k ? '<strong'.($classActivo ? ' class="'.$classActivo.'"' : '').'>'.($k+1).'</strong>'
					                     : '<a href="javascript:'.$jsFuncion.'('.$k.')"'.($classNormal ? ' class="'.$classNormal.'"' : '').'>'.($k+1).'</a>');
				}
			}

			if($pagina<($paginasTotales-1))
			{
				$pagSeccionSig = min($pagina+$paginasLados, $paginasTotales-1);
				$html .= $continuacion ? $separadorNoNumeros.$continuacion : '';
				$html .= $siguiente    ? $separadorNoNumeros.'<a href="javascript:'.$jsFuncion.'('.($pagina+1).')"'.($classNormal ? ' class="'.$classNormal.'"' : '').'>'.$siguiente.'</a>'     : '';
				$html .= $sigSeccion   ? $separadorNoNumeros.'<a href="javascript:'.$jsFuncion.'('.$pagSeccionSig.')"'.($classNormal ? ' class="'.$classNormal.'"' : '').'>'.$sigSeccion.'</a>' : '';
			}

		}

		return $html;
	}
	
  Function mostrarMensajeError($strError) {}
  
	/**
	* @param array $var
	* Imprime la estructura de la variable.
	*/
	function dump($var, $exit=true)	{
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
		if($exit) exit;
	}

	/**
	* @param array $var
	* Imprime la estructura de la variable.
	*/
	function pr( $arr ){
	  echo "<pre>";
	    print_r( $arr);
	  echo "</pre>";
	}

	/**
	* @return bool
	* @param mixed $value
	* @param mixed $valueSet
	* @param string[optional] $separator
	* Indica si $value se encuentra en el set de valores (valores separados por coma) $valueSet.
	*/
	function in_set($value, $valueSet, $separator=',') {
		return in_array($value, explode($separator, $valueSet));
	}
	
	
	 function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = mysql_pconnect($server, $username, $password);
    } else {
      $$link = mysql_connect($server, $username, $password);
    }

    if ($$link) mysql_select_db($database);

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function tep_db_error($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($query, $link = 'db_link') {
    global $link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = mysql_query($query, $link) or tep_db_error($query, mysql_errno(), mysql_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
       $result_error = mysql_error();
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function tep_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
    return mysql_insert_id();
  }

  function tep_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string, $link = 'db_link') {
    global $$link;

    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($string, $$link);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($string);
    }

    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
?>