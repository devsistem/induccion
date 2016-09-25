<?php
class Util {

 function obtener_cargos($active=null) {
	 global $link;
   $q = "SELECT  u.* "
	    . "FROM sys_cargo AS u "
	    . "WHERE 1 "
	    . (($active) ?  "AND u.active='".$active."' " : null)
	    . " ORDER BY u.nombre ASC "
	    . "";
	 return @mysql_query($q,$link);
 }
 
 //  AREA

 function obtener_areas($active=null) {
	 global $link;
   $q = "SELECT  u.* "
	    . "FROM sys_area AS u "
	    . "WHERE 1 "
	    . (($active) ?  "AND u.active='".$active."' " : null)
	    . " ORDER BY u.nombre ASC "
	    . "";
	 return @mysql_query($q,$link);
 }


 function html_dia($dia_seleccionado) {
    
    $html = "";
    
    for($i=1; $i <= 31; $i++) {
    	
    	if($i < 10) {
    	 $i = "0".$i;
    	}
    	
    	$selected = ($dia_seleccionado == $i) ? 'selected="selected"' : '';
    	
      $html .= "<option value='".$i."'  $selected>" . $i . "</option>";
    }
  
  print $html; 
 }
}
?>