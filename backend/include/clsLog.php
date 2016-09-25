<?php
class Log {

 function agregar_log($campos) {
	 global $link;   

	 // agrega un log a la instancia
   $q = "INSERT INTO logs () VALUES () ";
	 $r = @mysql_query($q,$link);	 
	 
	 //extras
	 if($r) {
	 } 
	 return $last_id;
 }  


}
?>