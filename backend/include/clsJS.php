<?php
	/**
	* @name Class JS
	* @package JS
	*/

	/**
	* @package JS
	* @desc Interfase para funciones de JavaScript
	* @version 1.0
	*/
	class JS
	{
		/**
		* @desc Agrega los tags de html necesarios al script y lo imprime en pantalla
		* @param string $script
		*/
		function script($script)
		{
			echo "<script language=\"javascript\">$script</script>";
		}

		/**
		*  @desc Cierra la ventana
		*/
		function windowClose()
		{
			$script = "window.close();";
			JS::script($script);
			exit;
		}

		/**
		* @desc Cambia la direccion de la pagina hacia la url especificada
		* @param string $url
		*/
		function windowLocation($url)
		{
			$script = "window.location = '$url';";
			JS::script($script);
			exit;
		}

		/**
		* @desc Abre una ventana (sin barras)
		* @param string $url
		* @param string $nombre
		* @param int $width
		* @param int $height
		*/
		function windowOpen($url, $nombre='PopUp', $width, $height)
		{
			$script = "window.open('$url','$nombre','toolbar=no, status=no, scrollbars=no, location=no, menubar=no, directories=no, width=$width, height=$height');";
			JS::script($script);
		}

		/**
		* @desc Cambia la direccion de la pagina creadora de la ventana
		* @param string $url
		*/
		function windowOpenerLocation($url)
		{
			$script = "if(window.opener.location) { window.opener.location = '$url'; }";
			JS::script($script);
		}

		/**
		* @desc Actualiza la ventana creadora de la ventana actual (sin enviar datos de formularios)
		*/
		function windowOpenerReload()
		{
			$script = "window.opener.location.reload();";
			JS::script($script);
		}
		
			/**
		* @desc Actualiza la ventana actual)
		*/
		function windowReload()
		{
			$script = "window.reload();";
			JS::script($script);
		}

		/**
		* @desc Abre una ventana de alerta
		* @param string $texto
		*/
		function Alert($texto)
		{
			$script = "alert('$texto');";
			JS::script($script);
		}

		/*
		* @desc Envia un formulario de una pagina padre
		* @param string $formulario
		*/
		function windowOpenerSubmit($formulario, $accion=NULL)
		{
			$script = (!is_null($accion)?"window.opener.document.forms['$formulario']['accion'].value = '$accion';":'')
 			        . "window.opener.document.forms['$formulario'].submit();";
			JS::script($script);
		}

		/**
		* @param string $formulario
		* @param string $var
		* @param int|string $value
		* Setea una variable en la ventana opener.
		*/
		function setOpenerVar($formulario, $var, $value)
		{
			$script = "window.opener.document.forms['$formulario']['$var'].value = '$value';";
			JS::script($script);
		}

		/**
		* @deprecated
		* Alias de setOpenerVar(). Su uso esta depreciado.
		*/
		function setVar($formulario, $var, $value)
		{
			JS::setOpenerVar($formulario, $var, $value);
		}

		/**
		* @param string $funcion
		* @param string $parametros
		* Ejecuta una función el la ventana opener.
		*/
		function windowOpenerFunction($funcion, $parametros='')
		{
			$script = "if(window.opener.$funcion) { window.opener.$funcion($parametros); }";
			JS::script($script);
		}

		/**
		* @param string $nombre
		* @param int|string $valor
		* @param int $expira
		* @param string $path
		* @param string $dominio
		* @param bool $segura
		* Setea una cookie.
		*/
		function setCookie($nombre, $valor, $expira=null, $path=null, $dominio=null, $segura=null)
		{

			$cookie = "$nombre=$valor"
			        . ($expira  ? ";expire=".gmdate('D, d M Y H:i:s \G\M\T', $expira) :'')
			        . ($path    ? ";path=".$path                                      :'')
			        . ($dominio ? ";domain=".$dominio                                 :'')
			        . ($segura  ? ";secure"                                           :'');

			$script = "document.cookie = '$cookie' ";
			JS::script($script);
		}
		
		function windowCancel()
		{
			$script =  "location.href = 'index.php?p=admin_usuarios'";
			JS::script($script);
		}
	}
?>