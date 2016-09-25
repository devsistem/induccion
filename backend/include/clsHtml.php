<?php
class Html {

 // propiedades
 public $nombre;


 // contructor
 function __construct() {
       
 }
 
 function crear_items_autocompletar() {
  global $link;
   $linea = "var countries = {\n\n";
   
   // Barrios
   $q = "SELECT * FROM barrios WHERE activo=1 ORDER BY nombre ASC";
   $r = @mysql_query($q, $link); 
   $f = @mysql_num_rows($r);

   //id_barrio o id_localidad/id_provincia
   // "1-0-2": "Ambato",
	 for($i=0; $i < $f; $i++) {
    $items = @mysql_fetch_array($r);
    $id = $items['id'];
    $id_provincia = $items['id_provincia'];
    $barrio = $items['nombre'];
    
       $q2 = "SELECT * FROM provincias WHERE id='".$id_provincia."'  LIMIT 1";
  		 $r2 = @mysql_query($q2, $link); 
  		 $arrProvincia = @mysql_fetch_array($r2); 
	     $provincia = $arrProvincia['nombre'];
       $linea .= "\"$id-$id_provincia\":\"$barrio,$provincia,Argentina\",\n";
	 }

   // localidades
   $q = "SELECT * FROM localidades WHERE activo=1 ORDER BY nombre ASC";
   $r = @mysql_query($q, $link); 
   $f = @mysql_num_rows($r);
   
	 for($k=0; $k < $f; $k++) {
    $items = @mysql_fetch_array($r);
    $id = $items['id'];
    $id_provincia = $items['id_provincia'];
    $barrio = $items['nombre'];
    
       $q2 = "SELECT * FROM provincias WHERE id='".$id_provincia."'  LIMIT 1";
  		 $r2 = @mysql_query($q2, $link); 
  		 $arrProvincia = @mysql_fetch_array($r2); 
	     $provincia = $arrProvincia['nombre'];
       $linea .= "\"$id-$id_provincia\":\"$barrio,$provincia,Argentina\",\n";
	 }
   
   $linea .= "}";

   file_put_contents(FILE_PATH."/autocompletar/scripts/items.js", $linea);
  // provincias
   $q = "SELECT * FROM provincias WHERE activo=1 ORDER BY nombre ASC";
 }

} // end class
?>