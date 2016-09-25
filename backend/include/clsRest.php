<?php
class Rest {

 function grabar( $latitude=null, $longitude=null ) {
	 global $link;
	 $latitude = escapeSQLFull($latitude);
	 $longitude = escapeSQLFull($longitude);
   $q = "INSERT INTO plugin_seguimiento_log (latitude, longitude, fecha_alta) VALUES ('".$latitude."', '".$longitude."', NOW())";
	 $r = @mysql_query($q,$link);	 
	 return @mysql_insert_id($link);
 }
} // end class
?>