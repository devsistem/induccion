<?php
class Categoria{

function eliminar($id){
 global $link;
 $q = "DELETE FROM categorias WHERE id='".$id."'";
 @mysql_query($q, $link);
}

function tiene_hijos($id){
 global $link;
 $q = "SELECT COUNT(*) AS Cantidad  FROM categorias AS P WHERE P.id_padre='".$id."' AND P.activo=1 ";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 return $a['Cantidad'];
}
 
function cantidad($activo = null, $condition=null){
 global $link;
 $q = "SELECT COUNT(*) AS Cantidad  FROM categorias ";
 if ($condition)
   $q .= " Where $condition";
   $r = mysql_query($q, $link);
   $a = mysql_fetch_array($r);
 return $a['Cantidad'];
}

function publicar($id=null, $activo=null){
 global $link;
 $activo = ($activo == 0) ? 1 : 0;
 $q = "UPDATE categorias SET activo='".escapeSQL($activo)."' WHERE id='".$id."'";
 @mysql_query($q, $link);
 return $id;
}

function destacar($id, $campo){
 global $link;
 $Cantidad = 0;
 $campo = ($campo == 0) ? 1 : 0;

 if ($campo == 1){
     $Cantidad = $this->CantidadDestacados();

     if ($Cantidad > 0){
         print "<script> alert('SOLO PUEDE HABER 2 CATEGORIAS DESTACADAS') </script>";
     }else{
         $q = "UPDATE categorias SET destacada=".escapeSQL($campo)." WHERE id='".$id."'";
         $r = @mysql_query($q, $link);
     }
 }

 if ($campo == 0){
     $q = "UPDATE categorias SET destacada=".escapeSQL($campo)." WHERE id='".$id."' ";
     $r = @mysql_query($q, $link);
 }
 return $id;
}

function obtener_all($OrderBy = null, $filtro = null, $activo = null, $padre = null, $raiz = null, $id_rubro = null, $tipo = null){
 global $link;
 $q = " SELECT c.* "
 	  . " FROM categorias AS c "
 	  . " WHERE 1 "
 	  . " AND c.id_padre=0 "
 	  //. (($id_rubro)  ? "AND c.id_rubro='".$id_rubro."' " :null)
 	  . (($tipo)  ? "AND c.tipo='".$tipo."' " :null)
 	  . (($activo) ? "AND c.activo='".$activo."' " :null)
 	  . " $OrderBy ";
 return @mysql_query($q, $link);
}

function obtener_subcategorias($order_by = null, $filtro = null, $activo = null, $padre = null){
 global $link;
 $q = " SELECT c.* "
 	  . " FROM categorias AS c "
 	  . " WHERE 1 "
 	  . (($activo) ? "AND c.activo='".$activo."' " :null)
 	  . (($padre)  ? "AND c.id_padre='".$padre."' " :null)
 	  . " $order_by ";
 return @mysql_query($q, $link);
}
 
 
function obtener($id){
     global $link;
     $q = "SELECT *  FROM categorias AS M WHERE M.id='".$id."' LIMIT 1 ";
     $r = @mysql_query($q, $link);
     return @mysql_fetch_array($r);
}

function obtenerNombre($id){
     global $link;
     $q = "SELECT categoria FROM categorias AS M WHERE M.id='".$id."' LIMIT 1 ";
     $r = @mysql_query($q, $link);
     $a = @mysql_fetch_array($r);
     return $a['nombre'];
}

function obtenerId($id){
     global $link;
     $q = "SELECT id FROM categorias AS M WHERE M.id='".$id."' LIMIT 1 ";
     $r = @mysql_query($q, $link);
     $a = @mysql_fetch_array($r);
     return $a['id_padre'];
 }
 
function obtenerPadre($id){
     global $link;
     $q = "SELECT id_padre FROM categorias AS M WHERE M.id='".$id."' LIMIT 1 ";
     $r = @mysql_query($q, $link);
     $a = @mysql_fetch_array($r);
     return $a['id_padre'];
 }

function obtenerByPadre($id){
     global $link;
     $q = "SELECT id,id_padre FROM categorias AS M WHERE M.id_padre='".$id."' LIMIT 1 ";
     $r = @mysql_query($q, $link);
     return @mysql_fetch_array($r);
 }
         
 function grabar($campos = null){
     global $link;

     $id_rubro =  escapeSQLFull($campos['categoria']['id_rubro']);
     $id_padre =  escapeSQLFull($campos['id_padre']);
     $nombre =  escapeSQLFull($campos['nombre']);
     $activo =  escapeSQLFull($campos['activo']);
     $orden =  escapeSQLFull($campos['orden']);

     if ($padre == 0){
         $nivel = 0;
     }else{
         $q = "SELECT nivel FROM categorias WHERE id='".$padre."' ";
         $r = @mysql_query($q, $link);
         $a = @mysql_fetch_array($r);
         $nivel = $a[0]['nivel'] + 1;
     }
     
     // orden
     //$q = "SELECT MAX(orden) AS ORDEN FROM categorias WHERE id_padre='".escapeSQL($padre)."' ";
     //$r = @mysql_query($q, $link);
     //$a = @mysql_fetch_array($r);
     //$orden = $a[0]['ORDEN'] + 1;

     $q = "INSERT INTO categorias (nombre, id_padre, nivel, activo, fecha_alta, orden) VALUES ('".$nombre."', '".$id_padre."', '".$Nivel."', 1, NOW(), '".$orden."')";
     @mysql_query($q, $link);
     return @mysql_insert_id($link);
 }

 function editar($id, $campos=null){
     global $link;
     
     $id_rubro =  escapeSQLFull($campos['categoria']['id_rubro']);
     $id_padre =  escapeSQLFull($campos['id_padre']);
     $nombre =  escapeSQLFull($campos['nombre']);
     $activo =  escapeSQLFull($campos['activo']);
     $orden =  escapeSQLFull($campos['orden']);

     $q = "UPDATE categorias SET orden='".escapeSQL($orden)."', nombre='".$nombre."',id_padre='".$id_padre."',activo='".$activo."', fecha_mod=NOW() WHERE id='".$id."'";
     @mysql_query($q, $link);
 }
 
 // //////////////////////////////////////////////////////////////////////////

 function subir($Idx, $orden){
     global $link;
     // Datos de la cat
     $arrCategoria = $this->ObtenerCategoria($Idx);

     $id_padre = $arrCategoria['id_padre'];
     $id_dominio = $arrCategoria['id_dominio'];
     $nivel = $arrCategoria['nivel'];

     // su no esta en la poscision mas baja, baja un escalon
     if ($orden > 1){
         $ordenUp = $orden - 1;
         $ordenDown = $orden + 1;

         $q = "UPDATE categorias SET orden=".escapeSQL($orden)."   WHERE orden='".escapeSQL($ordenUp)."'  AND id_padre='".escapeSQL($id_padre)."' AND nivel='".escapeSQL($nivel)."' ";
         @mysql_query($q, $link);
         $q = "UPDATE categorias SET orden=".escapeSQL($ordenUp)." WHERE id='".$id."' ";
         @mysql_query($q, $link);
     }else{ // Lleva a 0, va a lo ultimo
     }
 }

function bajar($Idx, $orden){
 global $link;
 // Padre
 $arrCategoria = $this->ObtenerCategoria($Idx);
 
 $id_padre = $arrCategoria['id_padre'];
 $id_dominio = $arrCategoria['id_dominio'];
 $categoria_nivel = $arrCategoria['categoria_nivel'];
 
 // obtener la posicion maxima
 $q = "SELECT MAX(orden) AS Maximo FROM categorias WHERE id_padre=".escapeSQL($id_padre)."  AND nivel='".escapeSQL($categoria_nivel)."' ";
 $r = @mysql_query($q, $link);
 $a = @mysql_fetch_array($r);
 $max = $a['Maximo'];
 
 if ($orden < $max){
     $ordenUp = $orden - 1;
     $ordenDown = $orden + 1;
 
     $q = "UPDATE categorias SET orden='".escapeSQL($orden)."' WHERE orden=".escapeSQL($ordenDown)." AND id_padre='".escapeSQL($id_padre)."'   ";
     @mysql_query($q, $link);
     $q = "UPDATE categorias SET orden='".escapeSQL($ordenDown)."' WHERE id='".$id."' ";
     @mysql_query($q, $link);
 }
}
} // end class
?>