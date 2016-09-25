<?php
  // 08/11/2008 17:09
  
  /*
  * @param string $s
	* @param int[optional] $level (1=normal,2=medium,3=heigh)
	*
	* Parsea los caracteres invalidos para el editor HTML FCKEditor
  */
  Function ParseFCKEditor($str,$level=1)
  {
  	  switch($level) {
  	   
  	     case 1:
    		 $str = @str_replace("�","&#39;", $str);  
   			 $str = @str_replace("'","&#39;", $str);
   			 $str = @str_replace("�","&aacute;", $str);
   			 $str = @str_replace("�","&eacute;", $str);
   			 $str = @str_replace("�","&iacute;", $str);
   			 $str = @str_replace("�","&oacute;", $str);
   			 $str = @str_replace("�","&uacute;", $str);
         break;
         default:
  	  }
  	  return $str;
  }


  /*
  * @param string $s
	* @param int[optional] $level (1=normal,2=medium,3=heigh)
	*
	* Parsea los caracteres invalidos para un TEXTAREA
  */
  Function ParseTextArea($str,$level=1)
  {
  	  switch($level) {
  	   
  	     case 1:
    		 $str = @str_replace("�","&#39;", $str);  
   			 $str = @str_replace("'","&#39;", $str);
   			 $str = @str_replace("�","&aacute;", $str);
   			 $str = @str_replace("�","&eacute;", $str);
   			 $str = @str_replace("�","&iacute;", $str);
   			 $str = @str_replace("�","&oacute;", $str);
   			 $str = @str_replace("�","&uacute;", $str);
         break;
         default:
  	  }
  	  return $str;
  }
  
	// Pasa el Primer caracter a mayuscula y el resto a minuscula
  Function MayusculaMinuscula($str,$level=1)
  {
  	$str = strtolower($str);
  	$str = ucfirst($str);
   return $str;
  }
?>