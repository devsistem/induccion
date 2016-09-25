<?php
  /**
  	* @name Class Errors
    * @package   Errors
    */
  /**
    * Clase para manejo de errores
    *
    * Trapea los mensajes de error del PHP y los generados por las otras clases
    *
    * @package   Errors
    * @version   1.0
    */
  class Errors {

    /**
      * Version de la clase
      * @var    string
      * @access public
      */
  	var $Version;

    /**
      * Numero de veces que se incluyo la clase
      * @var    int
      * @access public
      */
  	var $UseCount;


    /**
      * Constructor
      * @access public
      */
    function Errors() {

      /* Valores por defecto de las variables de la clase */
    	$this->Version    =  "1.0";

    	/* Reemplaza el Error Handler de PHP por el propio */
      set_error_handler(Array(&$this, '_PHPErrorHandler'));
    	error_reporting(E_ALL);
    }
    
    Function EnviarMailSoporte($error=null,$url=null,$cliente=null)
    {
    	//MAIL_SUPPORT
    }
    

    /**
      * Manejador global de errores del PHP
      * Llamo a mi manejador de errores
      * @access private
      * @param  int
      * @param  string
      * @param  string
      * @param  int
      * @param  array
      */
    function _PHPErrorHandler ($errno, $errstr, $file, $line, $context) {

      /* Tipo de error */
      $errtype = array (
        1    => "ERROR",
        2    => "WARNING",
        4    => "PARSE ERROR",
        8    => "NOTICE",
        16   => "CORE ERROR",
        32   => "CORE WARNING",
        64   => "COMPILE ERROR",
        128  => "COMPILE WARNING",
        256  => "USER ERROR",
        512  => "USER WARNING",
        1024 => "USER NOTICE",
      );

      /* Llamo al manejador global de errores */
      $this->AddError($file, $line, "PHP Parser", "[" . $errtype[$errno] . "] $errstr");
    }


    /**
      * Manejador global de errores
      * Por el momento imprimo el error a pantalla con datos del servidor y el cliente
      * @access public
      * @param  string
      * @param  int
      * @param  string
      * @param  string
      */
    function AddError($file, $line, $function, $message) {
/*
      $strErr  = "<pre>";
      $strErr .= "DATE      : " . date("Y-m-d H:i:s") . "\n";
      $strErr .= "SERVER IP : " . $_SERVER['SERVER_ADDR'] . "\n";
      $strErr .= "CLIENT IP : " . $_SERVER['REMOTE_ADDR'] . "\n";
      $strErr .= "FILE      : $file\n";
      $strErr .= "LINE      : $line\n";
      $strErr .= "FUNCTION  : $function\n";
      $strErr .= "MESSAGE   : $message\n";
      $strErr .= "</pre>";

      if ( $_SERVER["HTTP_HOST"] != "julieta.server" ) {
        echo " <!-- $strErr -->";
      }else{
       	echo $strErr;
      }
*/
    }
  }
?>