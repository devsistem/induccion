<?php
// semanal.php
// Reporte semanal de ventas
// 05/08/2015 15:33:34
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Producto', 'Pedido', 'Incidencia');
global $BackendUsuario, $Producto, $Pedido, $Incidencia;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteVentas() && !$BackendUsuario->esRoot()) { 
 //die;
}	

$id = ($_POST['id']) ? $_POST['id'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$id_vendedor = ($_REQUEST['id_vendedor']) ? $_REQUEST['id_vendedor'] : null;
$id_estado = ($_REQUEST['id_estado']) ? $_REQUEST['id_estado'] : null;

$fecha_desde  = (!isset($_REQUEST['fecha_desde'])) ? null : $_REQUEST['fecha_desde'];
$fecha_hasta  = (!isset($_REQUEST['fecha_hasta'])) ? null : $_REQUEST['fecha_hasta'];

$mes  = (!isset($_POST['mes'])) ? null : $_POST['mes'];
$anio = (!isset($_POST['anio'])) ? null : $_POST['anio'];


// si no esta el get de la fecha, pone la mes actual
if(strlen($mes) == 0 || strlen($anio) == 0) {

 $fecha_hoy = date("Y-m-d");
 $temp_fecha_hoy = explode("-",$fecha_hoy); 
 $mes  = $temp_fecha_hoy[1];
 $anio = $temp_fecha_hoy[0];
 
}

$activo = (!isset($_POST['activo'])) ? 1 : $_POST['activo'];


// si todos
if($accion == 'todos') {
 $id_vendedor = null;
 $id_estado = null;
 $fecha_desde = null;
 $fecha_hasta = null;
}


// todos los vendedores y supervisores
$result = $BackendUsuario->obtener_vendedores_y_supervisores(1);
$filas = @mysql_num_rows($result);

///////////////////////////
// armar las semanas y quincenas
////////////////////////////////
	//fecha origen cambiar  a 9-4 para produccion
	$date = new DateTime('2016-9-4');

	// fecha actual
	$option_quincena=[];
	$dtz = new DateTimeZone("America/Bogota");
	$now = new DateTime();
	$now->setTimezone($dtz);
	$n=0;

   while($date<=$now)
   {
	   
	   $date->add(new DateInterval('P1D'));
	   $option_quincena_temp=null;
	   $option_quincena_temp["id"]=$n;
	   $option_quincena_temp["fecha_desde_quincena"]=date_format($date, 'Y-m-d');
	    $option_quincena_temp["intervalo_quincena"]=date_format($date, 'm-d');
	   $option_quincena_temp["semana1"]=date_format($date, 'm-d');
	   $option_quincena_temp["fecha_desde_semana1"]=date_format($date, 'Y-m-d');
	   $date->add(new DateInterval('P6D'));
	   $option_quincena_temp["semana1"]=$option_quincena_temp["semana1"]." / ".date_format($date, 'm-d');
	   $option_quincena_temp["fecha_hasta_semana1"]=date_format($date, 'Y-m-d');
	   $date->add(new DateInterval('P1D'));
	    $option_quincena_temp["semana2"]=date_format($date, 'm-d');
	   $option_quincena_temp["fecha_desde_semana2"]=date_format($date, 'Y-m-d');
	   $date->add(new DateInterval('P6D'));
	   $option_quincena_temp["semana2"]=$option_quincena_temp["semana2"]." / ".date_format($date, 'm-d');
	   $option_quincena_temp["fecha_hasta_semana2"]=date_format($date, 'Y-m-d');
	   $option_quincena_temp["fecha_hasta_quincena"]=date_format($date, 'Y-m-d');
	    $option_quincena_temp["intervalo_quincena"]= $option_quincena_temp["intervalo_quincena"].' / '.date_format($date, 'm-d');

	   
	   array_push($option_quincena,$option_quincena_temp);
	   $n++;
   }
   $n=$n-1;
if($n<0)
  $n=1;
$quincena= (!isset($_POST['quincena'])) ? $n : $_POST['quincena'];


$tipo_empleado= (!isset($_POST['tipo_empleado'])) ? 'sv': $_POST['tipo_empleado'];



if(sizeof($option_quincena)>0)
{
	$quincena_seleccionada=$option_quincena[$quincena];
	
	$title_semana1='Semana '.$quincena_seleccionada["semana1"];
	$fecha_desde_semana_1 = $quincena_seleccionada["fecha_desde_semana1"];
	$fecha_hasta_semana_1 =$quincena_seleccionada["fecha_hasta_semana1"];


	$title_semana2='Semana '.$quincena_seleccionada["semana2"];
	$fecha_desde_semana_2 = $quincena_seleccionada["fecha_desde_semana2"];
	$fecha_hasta_semana_2 = $quincena_seleccionada["fecha_hasta_semana2"];
	$title_quincena='Quincena'. $quincena_seleccionada["fecha_desde_quincena"].' / '.$quincena_seleccionada["fecha_hasta_quincena"];
}

 $salario_basico = 375.56;

// fechas_quincena($quincena);


//retornar comisiones y sueldos por semana
function valores_semana($total,$encimeras,$hornos){

    $comicion_unidad=0;
    $comicion_hornos=0;


    $comicion_encimeras=0;

    $sueldo_basico=375.56/4;
    if($total<5 || $total>10){
    	$comicion_unidad=10;
    	if($total<5) $sueldo_basico=0;
    }
    else if($total>5 && $total<=10)
     	$comicion_unidad=$total;

    $comicion_hornos=(double)($hornos*$comicion_unidad);
    $comicion_encimeras=(double)($encimeras*($comicion_unidad*0.5));
    return array(
    		"comicion_hornos"=>(double)$comicion_hornos,
    		"comicion_encimeras"=>(double)$comicion_encimeras,
    		"total_comicion"=>(double)((double)$comicion_hornos+(double)$comicion_encimeras),
    		"sueldo_basico"=>(double)$sueldo_basico,
    	);

}

?>
<?php include("meta.php");?>

<link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-select/bootstrap-select.min.css">
<script>
 function filtrar() {
  var id_vendedor = $("#id_vendedor").val();
  var form = document.forms['frmPrincipal'];
 }

 function filtrar_estado() {
  var id_estado = $("#id_estado").val();
  var form = document.forms['frmPrincipal'];
 } 

 function filtrar_general() {
  var form = document.forms['frmPrincipal'];
	form.submit();
 }

 function filtrar_estado() {
  var id_vendedor = $("#id_vendedor").val();
  var form = document.forms['frmPrincipal'];
 }

 function filtrar_todos() {
  var form = document.forms['frmPrincipal'];
  form['accion'].value = "todos";
	form.submit();
 }

 var handleDashboardDatepicker = function() {
	"use strict";
    $('.fecha_latina').datepicker({
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    });
   $('.fecha_latina2').datepicker({
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    });    
 };
 function comicionar(idx) {
  if(confirm('Esta seguro de querer comicionar este pedido?')) { 	
	  var form = document.forms['frmPrincipal'];
  	form['accion'].value = "comicionar";
  	form['id'].value = idx;
	  form.submit()	  
  }
 } 

 function anterior(fecha_desde, fecha_hasta) {
	  var form = document.forms['frmPrincipal'];
  	form['accion'].value = "anterior";
  	form['fecha_desde'].value = fecha_desde;
  	form['fecha_hasta'].value = fecha_hasta;
	  form.submit()	  
 } 

 function siguiente(fecha_desde, fecha_hasta) {
	  var form = document.forms['frmPrincipal'];
  	form['accion'].value = "siguiente";
  	form['fecha_desde'].value = fecha_desde;
  	form['fecha_hasta'].value = fecha_hasta;
	  form.submit()	  
 }  
</script>
<style >
	
	.s-header-table-report,
	.table-sub-report{
font-size: 12px;
		color:black !important;
		text-transform: uppercase;
		vertical-align:center;
		text-align: center;
		 background-color:#dbe1e4 !important;
    border-color: #e4b9c0;
    border-size:0px;

	}
	.header-table-report{
		font-size: 12px;
		color:black !important;
		text-transform: uppercase;
		vertical-align:center;
		text-align: center;
		 background-color: #dbe1e4 !important;
    border-color: #e4b9c0;
    border-size:0px;


	}
	.header-table-report-tr{
		
	}
	.table-detail-report .table-detail-report-name{
		font-size: 12px;
		

	}
	.table-detail-report {
		text-align: center;;

	}
	.table-detail-report-tr{
		background-color:#ffffff !important;
	}
	.group-detail-report{
		background-color:#d0d0d0 !important;

	}
	.buttons-excel, .buttons-copy{
		position: relative;
		top: 1px;
		display: inline-block;
		font-family: 'Glyphicons Halflings';
		font-style: normal;
		font-weight: normal;
		line-height: 1;

		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;

  
	}	
	.buttons-excel::before {
		content: "\e026 ";
	}
	
	.buttons-copy::before {
		content: "\e205 ";
	}
 /*.div-search > div{
		position: relative !important;
  top:0px;
 left:900px;
		display: inline-block !important;
	}
	.div-pagination > div{
		top:0px;
 left:-800px;
		position: relative !important;
		display: inline-block !important;

	}-->*/

	input[type="search"]{
		width:900px !important;
	}
.selectpicker>option{

	font-style: "color:red";
}
	

	.special {
  font-weight: bold !important;
  color: #fff !important;
  background: #bc0000 !important;
  text-transform: uppercase;
}

	
</style>


    <link href="assets/css/animate.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link href="assets/plugins/footable/css/footable.core.css" rel="stylesheet">
  
    <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-select/bootstrap-select.min.css">

</head>
<body>
    <!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>

	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<?php include("header.php")?>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<?php include("sidebar.php")?>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Home</a></li>
				<li><a href="javascript:;">Reportes</a></li>
				<li class="active">Reportes Semanal de Ventas</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Reportes <small>listado semanal de ventas</small></h1>
			<!-- end page-header -->
						
		  <form name="frmPrincipal" id="frmPrincipal" method="POST" action="">
			<input type="hidden" name="accion">
			<input type="hidden" name="id">
			<input type="hidden" name="campo">
			<input type="hidden" name="id_item">
			<input type="hidden" name="fecha_desde">
			<input type="hidden" name="fecha_hasta">
			
			<div class="row">
						<div class="col-md-12">
							<!-- start: DYNAMIC TABLE PANEL -->


							<div class="panel panel-default">
								<div class="panel-body">
								     <div class="btn-group">
                              
                              <?php if($accion == "comicionado" ) {?>
                               <div class="alert alert-success fade in">
                            			<button type="button" class="close" data-dismiss="alert">
                               			 <span aria-hidden="true">&times;</span>
                            			</button>
                                		Se ha pasado a comicionado el pedido
                        			 </div>
	                            <?php } ?>
											</div>
										<div class="row">
											<div class="col-md-12 space20">
												<div class="btn-group pull-right" style="padding:20px">

												
													<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
														Exportar <i class="fa fa-angle-down"></i>
													</button>
													
												</div>
											</div>
										</div>
										
											
													 <div colspan="9">
	                        	
														   <select name="quincena" id="quincena" data-live-search="true" class="selectpicker" data-size="7">
														    
														   			<?php
														   			
						   											$i=0;
						   											$nfila=sizeof($option_quincena);
						   											for($i=0;$i<$nfila;$i++)
						   											{
						   												$items=$option_quincena[$i];
									           						?>
										                         	        
									           						      <?php  if(($i+1)%2!=0){ 
									           						      	$fecha_i_mes=$items["fecha_desde_quincena"];
									           						      	
									           						      	if(($i+1)<$nfila){
									           						      		$itemnext=($option_quincena[($i+1)]);
									           						      		$fecha_f_mes=$itemnext["fecha_hasta_quincena"];

									           						      	}
									           						      	
									           						      	else
									           						      		$fecha_f_mes=$items["fecha_hasta_quincena"];
									           						      	$group_mes= "Induccion <br>".$fecha_i_mes." / ".$fecha_f_mes;


									           						      	?>
									           						      	<optgroup label="<?=$group_mes?>">
									           						      <?php } ?>
										                         	        <option value="<?=$items['id']?>" <?=($quincena==$items['id']) ? 'selected' : ''?>> <?=($items['id']+1).'. '.'Quincena'.' '.$items['intervalo_quincena']?></option>
									                         	   		  <?php  if($i%2!=0){ ?>
									           						      	</outgroup>
									           						      <?php } ?>

									                         	    <?php 
									                         	    
									                         	    } 
									                         	    ?>
														   
														   </select>

														   <select name="activo" id="activo" data-live-search="true" class="selectpicker" data-size="7">
														    	<option value="0" <?=($activo=='0') ? 'selected' : ''?>>Empleados No Activos</option>
														    	<option value="1" <?=($activo=='1') ? 'selected' : ''?>>Empleados Activos</option>
														    	<option value="2" <?=($activo=='2') ? 'selected' : ''?>>Todos</option>
														    	
			  												</select>
			  												<select name="tipo_empleado" id="tipo_empleado" data-live-search="true" class="selectpicker" data-size="7">
														    	<option value="s" <?=($tipo_empleado=='s') ? 'selected' : ''?>>Solo Supervisores</option>
														    	<option value="v" <?=($tipo_empleado=='v') ? 'selected' : ''?>>Solo Empleados</option>
														    	<option value="sv" <?=($tipo_empleado=='sv') ? 'selected' : ''?>>Supervisores y Vendedore</option>
														    	<option value="*" <?=($tipo_empleado=='*') ? 'selected' : ''?>>Todos</option>
														    	
			  												</select>
			  												<select id="campos_select" name="campos_select" class="selectpicker" data-live-search="true" multiple data-size="12">
																<option class="option-select" value="1">Nombres</option>
																<option class="option-select" value="2">Total Entregadas</option>
																<option class="option-select" value="3">Encimeras</option>
																<option class="option-select" value="4">Horno</option>
																<option class="option-select" value="5">Sueldo Basico</option>
																<option class="option-select" value="6">Comicion Encimeras</option>
																<option class="option-select" value="7">Comicion Horno</option>
																<option class="option-select" value="8">Total Comicion</option>
																<option class="option-select" value="10">Total Entregadas</option>
																<option class="option-select" value="11">Encimeras</option>
																<option class="option-select" value="12">Horno</option>
																<option class="option-select" value="13">Sueldo Basico</option>
																<option class="option-select" value="14">Comicion Encimeras</option>
																<option class="option-select" value="15">Comicion Horno</option>
																<option class="option-select" value="16">Total Comicion</option>
																<option class="option-select" value="18">otros<br>pedidos </option>
																<option class="option-select" value="19">total</option>
																<option class="option-select" value="20">total encimeras</option>
																<option class="option-select" value="21">total horno</option>
																<option class="option-select" value="22">total Comiciones</option>
																<option class="option-select" value="23">total sueldo</option>
																<option class="option-select" value="24">sueldo mas comisiones</option>
																<option class="option-select" value="25">30% mas </option>
															</select>
				
			  												
			  												 <button type="submit" class="btn btn-info btn-xs" name="btMostrar">
															    <span class="glyphicon glyphicon-search"></span> Mostrar
															  </button>
			  												

		  											 </div>
		  											 <div colspan="9">
														


		  											 </div>
		  											 <div class="table-responsive">
															
							                           
							                           		<table  class="table table-bordered" cellspacing="0"  width="100%" name="dataTables-report" id="dataTables-report">
															  <thead  class="unxo">
															    <tr>
																	<th  colspan="2" class="s-header-table-report "></th>
																	<th colspan="7" class="s-header-table-report "><strong><?=$title_semana1?></strong></th>
																	<th colspan="1" class="s-header-table-report ">-</th>
																	<th colspan="7" class="s-header-table-report "><strong><?=$title_semana2?></strong></th>
																	<th colspan="1" class="s-header-table-report ">-</th>
																	<th colspan="8" class="s-header-table-report "><strong>Total Primera Quincena</strong></th>
																</tr>
																<tr class="header-table-report-tr">
																    <th align="center" class="header-table-report">Supervisor</th>
																	<th align="center"  class="header-table-report"><img src="spacer.png" width="150" height="1"></th>
																	
																	<th align="center" class="header-table-report">Total</br>Entregadas</th>
																	<th align="center" class="header-table-report">Encimeras</th>
																	<th align="center" class="header-table-report">Horno</th>
																	<th align="center" class="header-table-report">Sueldo</br>Basico</th>
																	<th align="center" class="header-table-report">Comicion</br>Encimeras</th>
																	<th align="center" class="header-table-report">Comicion</br>Horno</th>
																	<th align="center" class="header-table-report">Total</br>Comicion</th>
																	
																	<th align="center" class="header-table-report">-</th>
																	
																	<th align="center" class="header-table-report">Total</br>Entregadas</th>
																	<th align="center" class="header-table-report">Encimeras</th>
																	<th align="center" class="header-table-report">Horno</th>
																	<th align="center" class="header-table-report">Sueldo</br>Basico</th>
																	<th align="center" class="header-table-report">Comicion</br>Encimeras</th>
																	<th align="center" class="header-table-report">Comicion</br>Horno</th>
																	<th align="center" class="header-table-report">Total</br>Comicion</th>
																	
																	<th align="center" class="header-table-report">-</th>
																	<th align="center" class="header-table-report">otros<br>pedidos</br></th>

																	<th align="center" class="header-table-report">total</br></th>
																	
																	<th align="center" class="header-table-report">total</br>encimeras</th>
																	<th align="center" class="header-table-report">total</br>horno</th>
																	<th align="center" class="header-table-report">total</br>Comiciones</th>
																	<th align="center" class="header-table-report">total</br>sueldo</th>
																	<th align="center" class="header-table-report">sueldo</br>mas</br>comisiones</th>
																	<th align="center" class="header-table-report">30% mas </th>

																	
																				 		
																	

																</tr>
															  </thead>
															  <tbody>
															
																<?php //+ cada item ?>
																<?php
				                         							
																  // total de pedidos mensuales
				                         							
				                         							$total_entregadas_columna1=0.0;
				                         							$encimeras_columna1=0.0;
				                         							$horno_columna1=0.0;
				                         							$sueldo_basico_columna1=0.0;
				                         							$comicion_encimera_columna1=0.0;
				                         							
				                         							$total_comicion_columna1=0.0;
				                         							

				                         							$total_entregadas_columna2=0.0;
				                         							$encimeras_columna2=0.0;
				                         							$horno_columna2=0.0;
				                         							$sueldo_basico_columna2=0.0;
				                         							$comicion_encimera_columna2=0.0;
				                         							$comicion_horno_columna2=0.0;
				                         							$total_comicion_columna2=0.0;


				                         							$total_no_entregados_columna3=0.0;

				                         							$total_cocinas_columna3=0.0;
				                         							$total_encimeras_columna3=0.0;
				                         							$total_horno_columna3=0.0;
				                         							$total_comiciones_columna3=0.0;
				                         							$total_sueldo_columna3=0.0;
				                         							$total_sueldo_comiciones_columna3=0.0;
				                         							$treinta_por_ciento_mas_columna3=0.0;
				                         							$ax=$tipo_empleado;
										                         		
																	if($ax=='*')
																		$ax=null;

																	if($BackendUsuario->esVendedor())
																		$result_vendedores = $BackendUsuario->obtener_empleados($activo, $BackendUsuario->getUsuarioId(),$ax); 
																	else if($BackendUsuario->esSupervisor())
																		$result_vendedores = $BackendUsuario->obtener_supervisados_me($activo, $BackendUsuario->getUsuarioId(),$ax); 
																	else
																		$result_vendedores = $BackendUsuario->obtener_empleados($activo, $id_vendedor,$ax); 

																	 $filas_vendedores = @mysql_num_rows($result_vendedores);

														
											                        for($k=0; $k < $filas_vendedores; $k++) 
                                                                    {
                                                                              $items_vendedores = @mysql_fetch_array($result_vendedores); 

                                                                              $supervisor=$BackendUsuario->obtener_vendedorer_id($items_vendedores['id_supervisor'],$items_vendedores['perfil'],$k+4);
                                                                            

                                                                              $total_entregadas_valor1=$Pedido->pedidos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_1, 1, 7,'ENTREGADO');
                                                                              $total_entregadas_columna1=$total_entregadas_columna1+$total_entregadas_valor1;
                                                                              $encimeras_valor1=$Pedido->pedidos_encimera_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_1, 1, 7,'ENTREGADO');
                                                                              $encimeras_columna1=$encimeras_columna1+$encimeras_valor1;
                                                                              $horno_valor1=$Pedido->pedidos_hornos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_1, 1, 7,'ENTREGADO');
                                                                              $horno_columna1=$horno_columna1+$horno_valor1;
                                                                              $valores_semana1=valores_semana($total_entregadas_valor1,$encimeras_valor1,$horno_valor1);
                                                                              $sueldo_basico_valor1=(double)$valores_semana1["sueldo_basico"];
                                                                              $sueldo_basico_columna1=(double)$sueldo_basico_columna1+(double)$sueldo_basico_valor1;
                                                                              $comicion_encimera_valor1=(double)$valores_semana1["comicion_encimeras"];
                                                                              $comicion_encimera_columna1=$comicion_encimera_columna1+$comicion_encimera_valor1;
                                                                              $comicion_horno_valor1=(double)$valores_semana1["comicion_hornos"];
                                                                              $comicion_horno_columna1=$comicion_horno_columna1+$comicion_horno_valor1;
                                                                              $total_comicion_valor1=(double)$valores_semana1["total_comicion"];
                                                                              $total_comicion_columna1=$total_comicion_columna1+$total_comicion_valor1;

                                                                             

                                                                              $total_entregadas_valor2=$Pedido->pedidos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_2, $fecha_hasta_semana_2, 1, 7,'ENTREGADO');
                                                                              $total_entregadas_columna2=$total_entregadas_columna2+$total_entregadas_valor2;
                                                                              $encimeras_valor2=$Pedido->pedidos_encimera_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_2, $fecha_hasta_semana_2, 1, 7,'ENTREGADO');
                                                                              $encimeras_columna2=$encimeras_columna2+$encimeras_valor2;
                                                                              $horno_valor2=$Pedido->pedidos_hornos_facturados_fechas($items_vendedores['id'], $fecha_desde_semana_2, $fecha_hasta_semana_2, 1, 7,'ENTREGADO');
                                                                              $horno_columna2=$horno_columna2+$horno_valor2;
                                                                              $valores_semana2=valores_semana($total_entregadas_valor2,$encimeras_valor2,$horno_valor2);
                                                                              $sueldo_basico_valor2=(double)$valores_semana2["sueldo_basico"];
																			  $sueldo_basico_columna2=(double)$sueldo_basico_columna2+(double)$sueldo_basico_valor2;
																		  	  $comicion_encimera_valor2=(double)$valores_semana2["comicion_encimeras"];
																		  	  $comicion_encimera_columna2=$comicion_encimera_columna2+$comicion_encimera_valor2;
																		  	  $comicion_horno_valor2=(double)$valores_semana2["comicion_hornos"];
																			  $comicion_horno_columna2=$comicion_horno_columna2+$comicion_horno_valor2;
																			  $total_comicion_valor2=(double)$valores_semana2["total_comicion"];
																			  $total_comicion_columna2=$total_comicion_columna2+$total_comicion_valor2;


																			  //$total_no_entregados=$Pedido->pedidos_facturados_fechas_no_estado_sipec($items_vendedores['id'], $fecha_desde_semana_1, $fecha_hasta_semana_2, null, null,'ENTREGADO');
																			  $total_no_entregados=$Pedido->pedidos_facturados_fechas_no_estado_sipec($items_vendedores['id'], null, null, null, null,'ENTREGADO');
																			  $total_no_entregados_columna3=$total_no_entregados_columna3+$total_no_entregados;

                                                                              $total_cocinas_valor3=$total_entregadas_valor1+$total_entregadas_valor2;
                                                                              $total_cocinas_columna3=$total_cocinas_columna3+$total_cocinas_valor3;
                                                                              $total_encimeras_valor3=$encimeras_valor1+$encimeras_valor2;
                                                                              $total_encimeras_columna3=$total_encimeras_columna3+$total_encimeras_valor3;
                                                                              $total_horno_valor3=$horno_valor1+$horno_valor2;
                                                                              $total_horno_columna3=$total_horno_columna3+$total_horno_valor3;
                                                                              $total_comiciones_valor3=$total_comicion_valor1+$total_comicion_valor2;
                                                                              $total_comiciones_columna3=$total_comiciones_columna3+$total_comiciones_valor3;
                                                                              $total_sueldo_valor3=$sueldo_basico_valor1+$sueldo_basico_valor2;
                                                                              $total_sueldo_columna3=$total_sueldo_columna3+$total_sueldo_valor3;
                                                                              $total_sueldo_comiciones_valor3=$total_comiciones_valor3+$total_sueldo_valor3;
                                                                              $total_sueldo_comiciones_columna3=$total_sueldo_comiciones_columna3+$total_sueldo_comiciones_valor3;
                                                                              $treinta_por_ciento_mas_valor3=0;
                                                                              if($total_entregadas_valor1>5 &&  $total_entregadas_valor2>5)
                                                                              	$treinta_por_ciento_mas_valor3=$total_sueldo_comiciones_valor3+$salario_basico*0.3;
                                                                              $treinta_por_ciento_mas_columna3=$treinta_por_ciento_mas_columna3+$treinta_por_ciento_mas_valor3;


                                                                              
                                                                              ?>   


                                                                              <?php if($items_vendedores['activo']==0){ ?>   
                                                                                 <tr class="table-detail-report-tr danger">                                                         
                                                                               <?php }else {?> 

                                                                              <tr class="table-detail-report-tr">
                                                                              <?php }?>
                                                                             		<td class="table-detail-report"><?=$supervisor?></td>
                                                                                   <td class="table-detail-report-name" width="150"><?=$items_vendedores['apellido']?>,  <?=$items_vendedores['nombre']?></td>
                                                                                   
                                                                                   <!-- 1-4 -->
                                                                                   <td class="table-detail-report">
                                                                                    
									                                            	 <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_1?>&estadosipec=ENTREGADO">
									                                            	 	<?=$total_entregadas_valor1?>
									                                            	 </a>
                                                                                   </td>
                                                                                   <td class="table-detail-report">
                                                                                    <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&tipo=encimera&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_1?>&estadosipec=ENTREGADO">
                                                                                   		<?=$encimeras_valor1?>
                                                                                   	</a>
                                                                                   </td>
                                                                                   <td class="table-detail-report">
                                                                                     <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&tipo=horno&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_1?>&estadosipec=ENTREGADO">
                                                                                   		<?=$horno_valor1?>
                                                                                   	 </a>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">

                                                                                   		<?=$sueldo_basico_valor1?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$comicion_encimera_valor1?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$comicion_horno_valor1?>
                                                                                    </td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$total_comicion_valor1?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		-
                                                                                   	</td>
																					<td class="table-detail-report">
																						 <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&desde=<?=$fecha_desde_semana_2?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
									                                            	 	<?=$total_entregadas_valor2?>
									                                            	 </a>
                                                                                   </td>
                                                                                   <td class="table-detail-report">
                                                                                    <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&tipo=encimera&desde=<?=$fecha_desde_semana_2?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$encimeras_valor2?>
                                                                                   	</a>
                                                                                   </td>
                                                                                   <td class="table-detail-report">
                                                                                     <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&tipo=horno&desde=<?=$fecha_desde_semana_2?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$horno_valor2?>
                                                                                   	 </a>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$sueldo_basico_valor2?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$comicion_encimera_valor2?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$comicion_horno_valor2?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$total_comicion_valor2?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		-
                                                                                   	</td>


                                                                                   	<td class="table-detail-report">   
																					 <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO&bandera=NO IGUAL&estado=NULO&activo=NULO">
									                                            	 	<?=$total_no_entregados?>
									                                            	 </a>
                                                                                   </td>

																					<td class="table-detail-report">   
																					 <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
									                                            	 	<?=$total_cocinas_valor3?>
									                                            	 </a>
                                                                                   </td>
                                                                                   <td class="table-detail-report">
                                                                                    <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&tipo=encimera&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$total_encimeras_valor3?>
                                                                                   	</a>
                                                                                   </td>
                                                                                   <td class="table-detail-report">
                                                                                     <a href="pedidos_quincena.php?id=<?=$items_vendedores['id']?>&tipo=horno&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$total_horno_valor3?>
                                                                                   	 </a>
                                                                                   	</td>


                                                                                   <td class="table-detail-report">
                                                                                   		<?=$total_comiciones_valor3?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$total_sueldo_valor3?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$total_sueldo_comiciones_valor3?>
                                                                                   	</td>
                                                                                   <td class="table-detail-report">
                                                                                   		<?=$treinta_por_ciento_mas_valor3?>
                                                                                   	</td>
                                                                              </tr>     
                                                                             
                                                              <?php } // f?>
                                                              </tbody>
                                                              <?php if(!$BackendUsuario->esVendedor() && !$BackendUsuario->esSupervisor()){?>
                                                              <tfoot>

                                                                               <tr > 
                                                                                   <td class="table-sub_title-report">Subtotales</td>
                                                                                   <td class="table-sub-title-report"></td>
                                                                                   <td class="table-sub-report">
                                                                                     <a href="pedidos_quincena.php?desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_1?>&estadosipec=ENTREGADO">
                                                                                      <?=$total_entregadas_columna1?>
                                                                                     </a>	
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                   	  <a href="pedidos_quincena.php?tipo=encimera&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_1?>&estadosipec=ENTREGADO">
                                                                                   		<?=$encimeras_columna1?>
                                                                                   	  </a>
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                   	  <a href="pedidos_quincena.php?tipo=horno&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_1?>&estadosipec=ENTREGADO">
                                                                                   		<?=$horno_columna1?>
                                                                                   	  <a>
                                                                                   	</td>
                                                                                   <td class="table-sub-report"><?=$sueldo_basico_columna1?></td>

                                                                                   <td class="table-sub-report"><?=$comicion_encimera_columna1?></td>
                                                                                   <td class="table-sub-report"><?=$comicion_horno_columna1?></td>
                                                                                   <td class="table-sub-report"><?=$total_comicion_columna1?></td>
                                                                                   <td class="table-sub-report">-</td>


                                                                                   <td class="table-sub-report">
                                                                                    <a href="pedidos_quincena.php?desde=<?=$fecha_desde_semana_2?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$total_entregadas_columna2?>
                                                                                   	</a>
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                    <a href="pedidos_quincena.php?tipo=encimera&desde=<?=$fecha_desde_semana_2?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$encimeras_columna2?>
                                                                                   	</a>
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                     <a href="pedidos_quincena.php?tipo=horno&desde=<?=$fecha_desde_semana_2?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$horno_columna2?>
                                                                                   	  </a>
                                                                                   	</td>
                                                                                   <td class="table-sub-report"><?=$sueldo_basico_columna2?></td>
                                                                                   <td class="table-sub-report"><?=$comicion_encimera_columna2?></td>
                                                                                   <td class="table-sub-report"><?=$comicion_horno_columna2?></td>
                                                                                   <td class="table-sub-report"><?=$total_comicion_columna2?></td>
                                                                                   <td class="table-sub-report">-</td>


                                                                                   <td class="table-sub-report">   
																					 <a href="pedidos_quincena.php?desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO&bandera=NO IGUAL&estado=NULO&activo=NULO" >
									                                            	 	<?=$total_no_entregados?>
									                                            	 </a>
                                                                                   </td>


                                                                                   <td class="table-sub-report">
                                                                                     <a href="pedidos_quincena.php?desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$total_cocinas_columna3?>
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                     <a href="pedidos_quincena.php?tipo=encimera&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$total_encimeras_columna3?>
                                                                                   	</a>
                                                                                   </td>
                                                                                   <td class="table-sub-report">
                                                                                    <a href="pedidos_quincena.php?tipo=horno&desde=<?=$fecha_desde_semana_1?>&hasta=<?=$fecha_hasta_semana_2?>&estadosipec=ENTREGADO">
                                                                                   		<?=$total_horno_columna3?>
                                                                                   		</a>
                                                                                   	</td>
                                                                                   <td class="table-sub-report"><?=$total_comiciones_columna3?></td>
                                                                                   <td class="table-sub-report"><?=$total_sueldo_columna3?></td>
                                                                                   <td class="table-sub-report"><?=$total_sueldo_comiciones_columna3?></td>
                                                                                   <td class="table-sub-report"><?=$treinta_por_ciento_mas_columna3?></td>
                                                                              </tr>
                                                               </tfoot>
                                                               <?php } else{?>
                                                               <tfoot>

                                                                               <tr > 
                                                                                   <td class="table-sub_title-report">Subtotales</td>
                                                                                   <td class="table-sub-title-report"></td>
                                                                                   <td class="table-sub-report">
                                                                                      <?=$total_entregadas_columna1?>
                                                                                     
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                   	  <?=$encimeras_columna1?>
                                                                                   	  
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                   	  <?=$horno_columna1?>
                                                                                   	 
                                                                                   	</td>
                                                                                   <td class="table-sub-report"><?=$sueldo_basico_columna1?></td>

                                                                                   <td class="table-sub-report"><?=$comicion_encimera_columna1?></td>
                                                                                   <td class="table-sub-report"><?=$comicion_horno_columna1?></td>
                                                                                   <td class="table-sub-report"><?=$total_comicion_columna1?></td>
                                                                                   <td class="table-sub-report">-</td>


                                                                                   <td class="table-sub-report">
                                                                                    	<?=$total_entregadas_columna2?>
                                                                                   	
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                    	<?=$encimeras_columna2?>
                                                                                   	
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                     	<?=$horno_columna2?>
                                                                                   	  
                                                                                   	</td>
                                                                                   <td class="table-sub-report"><?=$sueldo_basico_columna2?></td>
                                                                                   <td class="table-sub-report"><?=$comicion_encimera_columna2?></td>
                                                                                   <td class="table-sub-report"><?=$comicion_horno_columna2?></td>
                                                                                   <td class="table-sub-report"><?=$total_comicion_columna2?></td>
                                                                                   <td class="table-sub-report">-</td>


                                                                                   <td class="table-sub-report">   
																					  	<?=$total_no_entregados?>
									                                            	 
                                                                                   </td>


                                                                                   <td class="table-sub-report">
                                                                                    	<?=$total_cocinas_columna3?>
                                                                                   	</td>
                                                                                   <td class="table-sub-report">
                                                                                     	<?=$total_encimeras_columna3?>
                                                                                   	
                                                                                   </td>
                                                                                   <td class="table-sub-report">
                                                                                    		<?=$total_horno_columna3?>
                                                                                   	
                                                                                   	</td>
                                                                                   <td class="table-sub-report"><?=$total_comiciones_columna3?></td>
                                                                                   <td class="table-sub-report"><?=$total_sueldo_columna3?></td>
                                                                                   <td class="table-sub-report"><?=$total_sueldo_comiciones_columna3?></td>
                                                                                   <td class="table-sub-report"><?=$treinta_por_ciento_mas_columna3?></td>
                                                                              </tr>
                                                               </tfoot>

                                                               <?php }?>

															</table>
														
													<?php /* ?>
													<tr>
													 <td colspan="9" align="center"><h4><?=strtolower($mes)?> - Semana del <strong><?=$fecha_rango_desde_txt?></strong> al <strong><?=$fecha_rango_hasta_txt?></strong></h4></td>
													</tr>
													
													<?php */ ?>


							
												
												
                         
                     

												


									</div>
								</div>
							</div>
							<!-- end: DYNAMIC TABLE PANEL -->
						</div>
					</div>
			<!-- begin row -->

            <!-- end row -->
			</div>
		<!-- end #content -->
		</form>

        <!-- begin theme-panel -->
     
        <!-- end theme-panel -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	
   

   
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<!--tabla-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
		<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
		<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>

		<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
		
		<script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>

		<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.bootstrap.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>

	<!--script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script-->
	<script src="assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->


	
	
	

		
			
	<script>

		$(document).ready(function() {
			App.init();
			
			$('.selectpicker').selectpicker();
			handleDashboardDatepicker();
			var table = $('#dataTables-report').DataTable({
                pageLength: 8,              
                responsive: true,
  				"scrollCollapse": true,
				"order": [[ 0, 'asc' ]],
				paging: true,
				"language": {
					"lengthMenu": "Mostrando _MENU_ registros por pagina",
					"zeroRecords": "No existen registros",
					"info": "Pagina _PAGE_ de _PAGES_",
					"infoEmpty": "No hay registros validos",
					"infoFiltered": "(filtered from _MAX_ total records)",
					"search":"Busqueda",
					"paginate": {
						"previous":"Atras",
						"next":"Siguiente",
						"last": "Ultimo",
						"first":"Primero",
					}

                },
                "dom":"<'row'<'col-sm-6 div-search'f>" +
                      "<'col-sm-12 div-pagination'>>"+ 
					  "<'row'<'col-sm-12'tr>>" +
					  "<'row'<'col-sm-2'i><'col-sm-8'p><'col-sm-2'B>>",
				"columnDefs": [
	            	{ "visible": false, "targets":0 }
	        	],
		        "drawCallback": function ( settings ) {
			            var api = this.api();
			            var rows = api.rows( {page:'all'} ).nodes();
			            console.log(rows);
			            var last=null;
			 
			             api.column(0, {page:'all'} ).data().each( function ( group, i ) {
			                
			                if ( last !== group ) {
			                    $(rows).eq( i ).before(
			                        '<tr class="group group-detail-report"><td style="color:blue !important;" colspan="29" >'+group+'</td></tr>'
			                    );
			 
			                    last = group;
			                }
		            } );
		        },
    	        buttons: [
	                { extend: 'copy'},
	                /*{extend: 'csv'},*/
	                {extend: 'excel', title: 'ExampleFile'},
	               /* {extend: 'pdf', title: 'ExampleFile'},*/

	               /* {
	                	extend: 'print',
	                    customize: function (win){
	                        $(win.document.body).addClass('white-bg');
	                        $(win.document.body).css('font-size', '10px');

	                        $(win.document.body).find('table')
	                                .addClass('compact')
	                                .css('font-size', 'inherit');
	                    }
	                }*/
           		]

            });
			var arraySeleccion=["1","2","3","4","5","6","7","8","10","11","12","13","14","15","16","18","19","20","21","22","23","24","25"];
			if(sessionStorage["arraySeleccion"]!=undefined && sessionStorage["arraySeleccion"]!=null)
			{
				arraySeleccion=[];
				var arraySeleccionAx=sessionStorage["arraySeleccion"].split(",");;
				
               arraySeleccionAx.forEach(function(n){
               	  arraySeleccion.push(String(n));
               });
			}
			var arrayItems=["1","2","3","4","5","6","7","8","10","11","12","13","14","15","16","18","19","20","21","22","23","24","25"];
			function manejar_campos(arraySeleccion,arrayItems)
			{
			  
				
		    	arrayItems.forEach(function(item){
		    		var column = table.column(item);
				    var i= arraySeleccion.indexOf((item));
			    	if(i>=0)
			            column.visible( true );
			        else
			        	 column.visible( false );
				   
				  });
			}
			
			$('#campos_select').val(arraySeleccion);
			$('#campos_select').selectpicker('refresh');
			manejar_campos(arraySeleccion,arrayItems);
		    $('#campos_select').change( function (e) {
		    	e.preventDefault();
		    	arraySeleccion = $("#campos_select").val();
		    	
				sessionStorage.clear();
				sessionStorage["arraySeleccion"]=arraySeleccion;		    	
		    	
		    	manejar_campos(arraySeleccion,arrayItems);
		    });
        	

		// Order by the grouping
		    $('#dataTables-report tbody').on( 'click', 'tr.group', function () {
		        var currentOrder = table.order()[0];
		        if ( currentOrder[0] === 0 && currentOrder[1] === 'asc' ) {
		            table.order( [ 0, 'desc' ] ).draw();
		        }
		        else {
		            table.order( [ 0, 'asc' ] ).draw();
		        }
		    } );


    		
		});

	</script>
</body>
</html>
