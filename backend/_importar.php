<?php
// _importar.php
// 11/09/2015 4:09:14 PM
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario', 'Producto');
global $BackendUsuario, $Producto;

$BackendUsuario->EstaLogeadoBackend();

$errores = 0;
$accion  = ($_POST['accion']) ? $_POST['accion'] : null;


switch($accion)
{
  case 'importar':
  
  // columnas
  // tipo,modelo,marca,color,fabricado_por,ruc_fabricante,serial,precio_costo,precio (PVP),cantidad,fecha_ingreso,fecha_egreso
  
  $tipo = "";
  $modelo = "";
  $marca = "";
  $color = "";
  $fabricado_por = "";
  $ruc_fabricante = "";
  $serial = "";
  $precio_costo = "";
  $precio = "";
  $cantidad = "";
  $fecha_ingreso = "";
  $fecha_egreso = "";
  
  break;
}
?>
<?php include("meta.php");?>

<style>
input {
 padding:20px;
}
</style>
<div class="panel-body">
    <form class="form-horizontal" action="" method="POST"  enctype="multipart/form-data">
    	<input type="hidden" name="accion" value="importar"/>
        <fieldset>
            <legend>Importar Excel</legend>
            <div class="form-group" >
                <label class="col-md-4 control-label">Subir Archivo</label>
                <div class="col-md-6">
	                	<div class="fileUpload">
                    	<input type="file"  name="file" id="file" class="form-control"/>
                    </div>
                    <br/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-sm btn-primary m-r-5">Importar</button>
                    <button type="button" class="btn btn-sm btn-default">Cancelar</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>