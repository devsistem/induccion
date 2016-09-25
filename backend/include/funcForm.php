<?php
// 08/11/2008 17:09
// 19/07/2012 10:37:54
//+// Formularios

function obtener_color($color) {
 switch($color) {
   
 		case 'ROJO':
 		 $color_html = "#CA0000";
 		break;

 		case 'AZUL':
 		 $color_html = "#0052A4";
 		break;
 }
 return $color_html;
}
function ValorCheckBox($valor) {
 	return ($valor==1) ? 1 : 0;
}

function ValorRadio($valor) {
  return ($valor==1) ? 1 : 0;
}

function SacarEspacios($cadena) {
  return @trim($cadena);
} 

// Se utiliza para elementos seleccionados en los combos.
function selected($valor, $valorComparar) {
	return $valor==$valorComparar ? 'selected':'';
}

	/**
	* @return string
	* @param mixed $valor
	* @param mixed $valorComparar
	* Compara los dos valores y en caso de que sean iguales devuelve "checked".
	* Se utiliza para elementos seleccionados en los combos.
	*/
	Function checked($valor, $valorComparar)
	{
		return $valor==$valorComparar ? 'checked':'';
	}
	

function ValidadarMail($email)
{
    if (!$email || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$email)){
        return 0;
    } else {
        return 1;
   	}
}

function FormatURL($url)
{
    $url = trim($url);
    if ($url != '') {
        if ((!preg_match("/^http[s]*:\/\//i", $url)) && (!preg_match("/^ftp*:\/\//i", $url)) && (!preg_match("/^ed2k*:\/\//i", $url)) ) {
            $url = 'http://'.$url;
        }
    }
    return $url;
}

function es_numero($n) {

 
}
?>