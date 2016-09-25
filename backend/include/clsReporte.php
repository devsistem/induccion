<?php
/**
* @name Class Reporte
* @package Interlogical.CMS
*/

class Reporte {


 function obtener_top_vendedores($perfil, $limite) {
  global $link;
  
  $q  = "SELECT sys_backendusuario.*, COUNT(pedidos.id) AS post_count "
      . "FROM sys_backendusuario LEFT JOIN pedidos  "
      . "ON sys_backendusuario.id = pedidos.id_vendedor "
      . "GROUP BY sys_backendusuario.id "
      . "ORDER BY post_count DESC "
      . "LIMIT 10 "
      . "";
  /*
  $q  = "SELECT  u.*.  COUNT(id) AS cantidad  FROM sys_backendusuario u "
			. "LEFT JOIN pedidos "
    	. "ON pedidos.id_vendedor = i.id "
      . "WHERE  1 "
      . "AND u.perfil = '".$perfil."' "
      . "GROUP BY u.id "
      . "ORDER BY cantidad "     
      . "LIMIT 5 ";
  */
  return @mysql_query($q,$link);
 }
} // end class
?>