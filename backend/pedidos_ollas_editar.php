<?php
// registro.php
// formulario de registro de un pedido
// inserta
// tabla pedidos
// NOTA: Agregar validacion de repost
// 1002493714
// 01/09/2015 20:36:30
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario','Producto','Categoria');
global $BackendUsuario,$Producto, $Categoria;

loadClasses('Pedido', 'Vendedor', 'Localizacion', 'Producto');
global $Pedido, $Vendedor, $Localizacion, $Producto;

$BackendUsuario->EstaLogeadoBackend();

/*
if(!$BackendUsuario->esta_logeado_vendedor()) {
 print "No existen permisos de visualizacion";
 header("Location: acceso.php");
 die;
}
*/
$action = (!isset($_POST['action'])) ? null : $_POST['action'];	

// valores
$errores = 0;
$str_errors = "";

// configuracion del form

// provincias
$result_provincias = $Localizacion->obtener_provincias(1, ACTIVO, null, null, null);
$filas_provincias = @mysql_num_rows($result_provincias);

// productos
$result_productos = $Producto->obtener_all(null, null, null,null,null,null,ACTIVO,null,null,null,null);
$filas_productos = @mysql_num_rows($result_productos);

//$Pedido->dni_existe($cedula);

switch($action) {
	
	 case 'grabar':
	 
	 if(postBlock($_POST['postID'])) {	
	 			
			// datos
			$cedula = escapeSQLTags($_POST['registro']['dni']);
			$nombre = escapeSQLTags($_POST['registro']['nombre']);

			$cliente_telefono = escapeSQLTags($_POST['registro']['cliente_telefono']);
			$cliente_celular = escapeSQLTags($_POST['registro']['cliente_celular']);
		
		 // errores de campos

     if(strlen($cedula) !=  10 ) {
			 $str_errors  .= _LANG_REGISTER_7;
			 $css_cedula = "error";
			 $errores++;
		 }		 
	  
		 // un telefono
     if(strlen($cliente_telefono) ==  0  && strlen($cliente_celular) ==  0) {
			 $css_telefonocelular = "error";
			 $errores++;
		 }
     
 
		 if($errores == 0) {
			 // todas las validaciones ok, graba
			 $last_id = $Pedido->grabar_olla($_POST);
	  
		 	 $fecha_post = time();

			 if($last_id > 0) {
				 $accion  = 'grabado';
				 
				 // se envian los mails
				 //$Pedido->enviar_pedido_admin($last_id);
				 //$Pedido->enviar_pedido_cliente($last_id);
				 
			 }
		 }	 
	 } else {
	 		 $str_errors  .= "Ya se envio el formulario";
			 $errores++;
	 }
	break;
 }
include("meta.php");
?>

  <script type="text/javascript">
      var cantidad_ollas = 0;
      function es_cedula_valida() { 
			 var errores = 0;	  	 
	  	 var cedula = $("#registro_dni" ).val(); 

	  	 //Preguntamos si la cedula consta de 10 digitos
  	   if(cedula.length == 10){
        
        //Obtenemos el digito de la region que sonlos dos primeros digitos
        var digito_region = cedula.substring(0,2);
        
        //Pregunto si la region existe ecuador se divide en 24 regiones
        if( digito_region >= 1 && digito_region <=24 ){
          
          // Extraigo el ultimo digito
          var ultimo_digito   = cedula.substring(9,10);

          //Agrupo todos los pares y los sumo
          var pares = parseInt(cedula.substring(1,2)) + parseInt(cedula.substring(3,4)) + parseInt(cedula.substring(5,6)) + parseInt(cedula.substring(7,8));

          //Agrupo los impares, los multiplico por un factor de 2, si la resultante es > que 9 le restamos el 9 a la resultante
          var numero1 = cedula.substring(0,1);
          var numero1 = (numero1 * 2);
          if( numero1 > 9 ){ var numero1 = (numero1 - 9); }

          var numero3 = cedula.substring(2,3);
          var numero3 = (numero3 * 2);
          if( numero3 > 9 ){ var numero3 = (numero3 - 9); }

          var numero5 = cedula.substring(4,5);
          var numero5 = (numero5 * 2);
          if( numero5 > 9 ){ var numero5 = (numero5 - 9); }

          var numero7 = cedula.substring(6,7);
          var numero7 = (numero7 * 2);
          if( numero7 > 9 ){ var numero7 = (numero7 - 9); }

          var numero9 = cedula.substring(8,9);
          var numero9 = (numero9 * 2);
          if( numero9 > 9 ){ var numero9 = (numero9 - 9); }

          var impares = numero1 + numero3 + numero5 + numero7 + numero9;

          //Suma total
          var suma_total = (pares + impares);

          //extraemos el primero digito
          var primer_digito_suma = String(suma_total).substring(0,1);

          //Obtenemos la decena inmediata
          var decena = (parseInt(primer_digito_suma) + 1)  * 10;

          //Obtenemos la resta de la decena inmediata - la suma_total esto nos da el digito validador
          var digito_validador = decena - suma_total;

          //Si el digito validador es = a 10 toma el valor de 0
          if(digito_validador == 10)
            var digito_validador = 0;

          //Validamos que el digito validador sea igual al de la cedula
          if(digito_validador == ultimo_digito){
            //console.log('la cedula:' + cedula + ' es correcta');
          }else{
          	errores++;
            //console.log('la cedula:' + cedula + ' es incorrecta');
          }
          
        }else{
          // imprimimos en consola si la region no pertenece
         	errores++;
          //console.log('Esta cedula no pertenece a ninguna region');
        }
     }else{
        //imprimimos en consola si la cedula tiene mas o menos de 10 digitos
       	errores++;
        //console.log('Esta cedula tiene menos de 10 Digitos');
     }
     
     if(errores > 0) {
    	  $("#cedula_error" ).html("El formato ingresado no es valido"); 
    	  $("#cedula_label" ).css('color', 'red'); 
   	 } else {
    	  $("#cedula_error" ).html(""); 
    	  $("#cedula_label" ).css('color', 'black'); 
   	 }
    } 
    
    function es_cuen_valida() {
    
    }

    function agregar_olla(indice) {
    	var indice_proximo = indice;
    	indice_proximo++;
    
    	var indice_str = indice_proximo.toString();
    	var campo_ollas = "#divollas"+indice_proximo;
    	var campo_boton_ollas = "#boton_olla"+indice;
    
    	$(campo_ollas).show();
    	$(campo_boton_ollas).hide();
    	
			/*    	
    	if(cantidad_ollas == 0) {
   		 $("#divollaspega" ).append("");
       $("#divollas" ).show();
    	 cantidad_ollas++;
    	 cantidad_ollas_texto = cantidad_ollas + 1;
    	 //$("#num_olla").html("Olla Num: #" + cantidad_ollas_texto );

    	} else {
      
       if(cantidad_ollas >= 1) {
  	    cantidad_ollas++;
    	  //$("#num_olla").html("Olla Num: #" + cantidad_ollas);
	      var html_div = $("#divollascopia").html();
  	    $("#divollaspega" ).append(html_div);
       }
      }*/
    }
  </script>

  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
  <script type="text/javascript">
  	 // generico
	

	function provincias() {
		  $("#divcantones").text("");
		  var id_provincia = $("#registro_id_provincia").val();
		  var _url =  'ax_cantones_json.php?id_provincia='+id_provincia;
      $("#divcantonestxt").text("Cargando...");	
			$.post(_url,function(result){
			  dataItem = $.parseJSON(result);
			 	var html = "";
			 	html += '<select class="select_cocina" data-size="10" data-live-search="true" data-style="btn-white" name="registro[id_canton]" style="width:160px" id="registro_id_canton">';
			 	 html += ' <option value="0">Sin Especificar</option>';
			 	for(i=0; i < dataItem['cantidad'][0]; i++) {
	  			 html += ' <option value="'+dataItem['id'][i]+'">'+dataItem['nombre'][i]+'</option>';
			  }
			 	html += '</select>';
			 	
	      $("#divcantonestxt").text("");	
		  	$("#divcantones").append(html);	 	
			});
	}
	
	function modelos(indice) {
			var registro_id_modelo = "registro_id_modelo"+indice;
		  var campo_id_marca = "#registro_marca"+indice;
		  var id_marca = $(campo_id_marca).val();

		  if(id_marca > 0) {
		  	
		  $("#divmodelos").text("");
		  var campo_divmodelos = "#divmodelos"+indice;
		  var campo_divmodelostxt = "#divmodelostxt"+indice;
		  var campo_registro_modelo_lectura = "#registro_modelo_lectura"+indice;
	
		  var _url =  'ax_modelos_json.php?id_marca='+id_marca+'&tipo=olla';
      
      $(campo_divmodelostxt).text("Cargando...");	
         
			$.post(_url,function(result){
       
			  dataItem = $.parseJSON(result);
			 	var html = "";
			 	html += '<select class="select_cocina"  name="registro_modelo[]" style="width:160px;margin-top:10px" id="'+registro_id_modelo+'">';
			 	for(i=0; i < dataItem['cantidad'][0]; i++) {
	  			 html += ' <option value="'+dataItem['id'][i]+'">'+dataItem['nombre'][i]+'</option>';
			  }
			 	html += '</select>';
			 	
	      $(campo_registro_modelo_lectura).hide();
	      $(campo_divmodelostxt).text("");	
		  	$(campo_divmodelos).append(html);	 	
			});
		}
	}	
</script>

<style>
.error { color:#D70000}
.error_div { color:#D70000; border:1px solid #D70000;	}
.container { 
	width: 900px;
  border:1px solid #d8d8d8;	
  padding:10px;
   background-color: #ffffff;
}

.container_ { 
	width: 900px;
  border:1px solid #d8d8d8;	
  background-color: #ffffff;
}
.cargando {
 font: normal 10px Arial, Tahoma,Verdana, Arial; 
 color:#0054A8 
 font-weight: normal;
}
#cedula_error {
 font: 10px Arial, Tahoma,Verdana, Arial;
 color:#D70000;
 font-weight: normal;
}
.vendedor {
 color:#004080;
}
.sep {
	 border:1px solid #d8d8d8;	
	 margin-bottom:10px;
}
.texto_bienvenido {
 font: 28px Verdana, Arial;
 color:#EB2D7C;
 font-weight: normal; 
}
.texto_registro {
 font: 26px Verdana, Arial;
 color:#90288D;
 font-weight: normal;
}

.texto_exp {
 font: 16px Verdana, Arial;
 color:#3A3A44;
 font-weight: normal;
}

.texto_exp14 {
 font: 14px Verdana, Arial;
 color:#3A3A44;
 font-weight: normal;
}
.texto_exp12 {
 font: 12px Verdana, Arial;
 color:#737373;
 font-weight: normal;
}
.campo_check {
 height:40px
}
.texto_cuen {
 font: 26px Verdana, Arial;
 color:#90288D;
 font-weight: normal;
}

.titulo_form {
 color:#737373;
 font-weight: normal
 font: 12px Verdana, Arial;
}

.titulo_form_24 {
 color:#737373;
 font-weight: normal
 font: 21px Verdana, Arial;
}

.select_cocina {
 width:160px;
 height:40px;
}

.select_horario {
 background-color: #737373;
 font: 14px Verdana, Arial; 
 color: #ffffff; 
 width:330px;
 height:40px;
}

.boton_color {
 background-color: #EB2D7C;
 font: 16px Arial;
 color: #ffffff; 
}
.exp {
 font: 11px Arial;
}
</style>

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
		<!-- end #sidebar -->
		
	
<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Portada</a></li>
				<li><a href="javascript:;">Pedidos</a></li>
				<li class="active">Nuevo Pedido de Olla</li>
			</ol>

<!-- begin row -->
			<div class="row">
                <!-- begin col-6 -->
			    <div class="col-md-9">
			        <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Nuevo Registro de venta</h4>
                        </div>
                        <div class="panel-body">
                           
                            	
<table width="100%" align="left" cellspacing="10" cellpadding="10">
 <tr>
 <td   bgcolor="#ffffff">			
			
  <!-- begin row -->
	<div style="padding:20px">	
	
  <h3 align="right" class="texto_bienvenido">
		<span class="texto_registro">Registro de Venta de Ollas</span>
  </h3>

	<div style="clear:both"></div><br/><br/>

<?php 
if($accion  != 'grabado') { ?>	
	<span class="texto_exp">
		</span>

<?php } ?>
	
	<div style="clear:both"></div><br/><br/>


<?php		if(!$last_id && $accion != 'grabado' && $errores > 0) { ?>
		
					<?php if(strlen($str_errors) > 0) { ?>
						<div class="error"><?=$str_errors?></div>
	  			<?php }?>


				<?php if(strlen($str_existe_cuen) > 0) { ?>
						<div class="error"><?=$str_existe_cuen?></div>
	  			<?php }?>
	  			
<?php  } ?>	

				
<?php 
	   		if($last_id > 0 && $accion  == 'grabado') { ?>
		
					<div><h2>Pedido Enviado. Pendiente de Confirmacion</h2></div>
					<div><h3>Su número de referencia es # <?=$last_id?></h3></div>
		
<?php   } else { ?>


     <form name="frm_dashboard_mapa" id="frm_dashboard_mapa" enctype="multipart/form-data" method="POST" action="" class="form-horizontal form-bordered" data-validate="parsley">
      <input type="hidden" name="action" value="grabar">

      <input type="hidden" name="id">
      <input type="hidden" name="estado" value="1">		
      <input type="hidden" name="activo" value="1">		
      <input type="hidden" name="zoom_inicial" value="8">		
      <input type="hidden" name="dragable" id="dragable" value="true">		
      <input type="hidden" name="modo" value="enviar">	
			<input type="hidden" name="postID" value="<?=md5(uniqid(rand(), true))?>">

			
			<table width="100%" border="0">
			 <tr>
			  <td width="50%" valign="top">
			   
			    <table cellpadding="10" cellspacing="5">
			     <tr>
			      <td width="50%" align="right">				
			      	<span class="titulo_form <?=$css_cedula?> <?=$css_existe_cedula?>" id="cedula_label"><strong>Cédula de Ciudadania</strong> <span class="required">(*)</span></span>
					  </td>
			      <td width="50%" style="padding-left:10px">
							<input required="required" class="form-control" style="width:100%" id="registro_dni" name="registro[dni]" style="width:20%"  maxlength="15" type="text" value="<?=$_POST['registro']['dni']?>" onBlur="es_cedula_valida();">
							<span id="cedula_error"></span>			      
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>
					<tr>
			      <td  align="right" >				
			      	<span class="titulo_form <?=$css_nombre?>" id="cedula_label"><strong>Nombres</strong> <span class="required">(*)</span></span>
					  </td>
			      <td style="padding-left:10px">
							<input required="required" class="form-control" id="register_name" name="registro[nombre]" style="width:100%"  maxlength="150" type="text" value="<?=$_POST['registro']['nombre']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_apellido?>" id="cedula_label"><strong>Apellidos</strong> <span class="required">(*)</span></span>
					  </td>
			      <td style="padding-left:10px">
							<input required="required" class="form-control" id="register_name" name="registro[apellido]" style="width:100%"  maxlength="150" type="text" value="<?=$_POST['registro']['apellido']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"></td>
					 </tr>

					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_telefono?>" id=""><strong>Teléfono</strong> <span class="required"></span></span>
					  </td>
			      <td style="padding-left:10px">
							<input  class="form-control" id="registro_telefono" name="registro[cliente_telefono]" style="width:100%" type="text" maxlength="30" value="<?=$_POST['registro']['cliente_telefono']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height=10"> </td>
					 </tr>

				  <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_celular?>" id=""><strong>Celular</strong></span>
					  </td>
			      <td style="padding-left:10px">
     					<input  class="form-control" id="registro_cliente_celular" name="registro[cliente_celular]" style="width:100%" type="text"  maxlength="30" value="<?=$_POST['registro']['cliente_celular']?>">
			      </td>
			     </tr>
					 <tr>
					  <td colspan="2" height="10"><span class="exp  <?=$css_telefonocelular?>">Un teléfono o celular debe ser ingresado</span></td>
					 </tr>
				  <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_email?>" id=""><strong>Correo</strong></span>
					  </td>
			      <td style="padding-left:10px">
							<input class="form-control" id="registro_email" name="registro[email]"  type="email" style="width:100%"  maxlength="30" value="<?=$_POST['registro']['email']?>">
			      </td>
			     </tr>

				 <tr>
					  <td colspan="2" height=10"> </td>
					 </tr>
			     <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_id_provincia?>" id="cedula_label"><strong>Provincia</strong> <span class="required">(*)</span></span>
					  </td>
			      <td  style="padding-left:10px">
			
							<select  id="registro_id_provincia" required="true" class="select_cocina" data-size="10" data-live-search="true" data-style="btn-white" style="width:190px" name="registro[id_provincia]" onChange="provincias()">
						 	<option value="">-Seleccionar-</option>
							<?php // PROVINCIAS
								for($i=0; $i < $filas_provincias; $i++) {
									$items_provincias = @mysql_fetch_array($result_provincias); ?>						 	
							 		 <option value="<?=$items_provincias['id']?>"  <?=($_POST['registro']['id_provincia']==$items_provincias['id']) ? 'selected' : ''?>><?=$items_provincias['nombre']?></option>
							<?php } ?>  
							</select>
				
			      </td>
			     </tr>
		<tr>
					  <td colspan="2" height=10"> </td>
					 </tr>
 					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_calle_principal?>" id="cedula_label"><strong>Calle Principal</strong> <span class="required">(*)</span></span>
					  </td>
			      <td style="padding-left:10px">
							<input class="form-control" id="registro_cliente_calle" name="registro[cliente_calle]" style="width:90%"   maxlength="150" type="text" value="<?=$_POST['registro']['cliente_calle']?>">
			      </td>
			     </tr>			
	 				<tr>
					  <td colspan="2" height=10"> </td>
					 </tr>
 					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_cliente_calle_numero?>" id="cedula_label"><strong>Calle Número</strong> <span class="required">(*)</span></span>
					  </td>
			      <td style="padding-left:10px">
							<input class="form-control" id="registro_cliente_calle_numero" name="registro[cliente_calle_numero]" style="width:90%"   maxlength="50" type="text" value="<?=$_POST['registro']['cliente_calle_numero']?>">
			      </td>
			     </tr>

	 				<tr>
					  <td colspan="2" height=10"> </td>
					 </tr>

 					 <tr>
			      <td  align="right">				
			      	<span class="titulo_form <?=$css_cliente_calle_secundaria?>" id="cedula_label"><strong>Calle Secundaria</strong></span>
					  </td>
			      <td style="padding-left:10px">
							<input class="form-control" id="registro_cliente_calle_secundaria" name="registro[cliente_calle_secundaria]" style="width:90%"   maxlength="150" type="text" value="<?=$_POST['registro']['cliente_calle_secundaria']?>">
			      </td>
			     </tr>

	 				<tr>
					  <td colspan="2" height=10"> </td>
					 </tr>
			     					 
			     <tr>
			      <td  align="right" style="padding-left:10px">				
			      	<span class="titulo_form <?=$css_forma_pago?>" id="cedula_label"><strong>Forma de Pago</strong> <span class="required">(*)</span></span>
					  </td>
			      <td  style="padding-left:10px">
			
				 	<select id="registro_modelo" name="registro[forma_pago]"  class="select_cocina">
		 			 <option value="">FORMA PAGO</option>
						 <option value="TARJETA DE CREDITO" <?=($_POST['registro']['forma_pago']=="TARJETA DE CREDITO") ? 'selected' : ''?>>TARJETA DE CREDITO</option>
						 <option value="CONTADO" <?=($_POST['registro']['forma_pago']=="CONTADO") ? 'selected' : ''?>>CONTADO</option>
					</select> 
				
			      </td>
			     </tr>
			    </table>
			  
			  
			  </td>
			  <td width="50%" valign="top">
			  </td>
			 </tr>
			</table>
			
 	
  		<hr size="2" color="#737373">			

		<?php	for($k=1; $k <= 10; $k++) { ?>
	  
	  <div id="divollas<?=$k?>" style="display:<?=($k=='1') ? 'block' : 'none'?>">
			<table width="100%" border="0" cellpadding="5">
			 <tr>
			  <td  valign="top" width="20%" align="center">
	  	    <img src="<?=URL_PATH_FRONT?>/images/icon_olla.jpg">
 			  </td>
			  <td  valign="top"  width="25%">
			  	<select id="registro_marca<?=$k?>" name="registro_marca[]" class="select_cocina" onChange="modelos('<?=$k?>')" style="margin-top:10px">
			  	 <option value="">MARCA</option>	
						<?php	
							// marca olla
							$result_modelos_marca_olla = $Producto->obtener_marcas( ACTIVO, 'olla');
							$filas_modelos_marca_olla = @mysql_num_rows($result_modelos_marca_olla);						
							for($i=1; $i <= $filas_modelos_marca_olla; $i++) {
								$items_modelos_marca = @mysql_fetch_array($result_modelos_marca_olla); ?>
					 		 <option value="<?=$items_modelos_marca['id']?>"   <?=($_POST['registro']['marca_olla']==$items_modelos_marca['nombre']) ? 'selected' : ''?>><?=$items_modelos_marca['nombre']?></option>
					 	<?php } ?> 
					</select>			
 			 </td>
			  <td  valign="top"  width="25%">
			  	    <select id="registro_modelo_lectura<?=$k?>" name="registro[modelo_lectura]"  DISABLED class="select_cocina" style="margin-top:10px">
			  	    	<option>MODELO</option>
			  	    </select>
							<div id="divmodelos<?=$k?>"></div>
							<div id="divmodelostxt<?=$k?>" class="cargando"></div>
							<input type="hidden" id="registro_id_modelo<?=$k?>_NO" />
 			 </td>
			  <td  valign="top"  width="100px">
				<select id="registro_color" name="registro_cantidad[]" id="registro_cantidad<?=$k?>" class="select_cocina" style="margin-top:10px">
	  		 	<option value="">CANTIDAD</option>
					 <?php	for($i=1; $i <= 50; $i++) { ?>
					 		 <option value="<?=$i?>"   <?=($_POST['registro']['cantidad']==$i) ? 'selected' : ''?>><?=$i?></option>
					 	<?php } ?>	 
				</select>				
 			 </td>
 			 <td width="20"></td>
 			 <td>		
 			 	<input type="button" id="boton_olla<?=$k?>" value="Agregar Olla" onClick="agregar_olla('<?=$k?>')"/>
 			 </td>
			 </tr>

			 <tr>
			  <td colspan="5">
			  </td>
			 </tr>
			</table>
			</div>
		<?php } ?>
			<table width="100%">
			 <tr>
			 	 <td width="50%"></td>
			 	 <td></td>
			 	 <td></td>
			 	 <td></td>
			  <td align="right"></td>
			 </tr>
			</table>			
			 			 	

			 <?php //+ div de ollas completo // ?>
			 <div id="divollas" style="display:none">
			 
			 <div id="divollaspega"></div>
			 </div>
			<?php //- div de ollas completo // ?>
			<table>
			 <tr>
			  <td height="20"></td>
			 </tr>
			 <tr>
			 	<td></td>
			  <td>
 					<input class="submit btn btn-large btn-success_ boton_color" id="bt_grabar" name="bt_grabar" type="submit" value="Enviar Pedido">
			  </td>
			 </tr>
			</table>
		
		</form>
	</div>
</div>


<?php } ?>


</div>

</td>
 </tr>
</table>
  </div>
  </div>
  </div>
  </div> 
</div>
		<!-- end #content -->
	
  <?php include("footer_meta.php")?>


	<script src="assets/plugins/ckeditor/ckeditor.js"></script>
	<script src="assets/plugins/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.js"></script>
	<script src="assets/plugins/bootstrap-wysihtml5/src/bootstrap-wysihtml5.js"></script>
	<script src="assets/js/form-wysiwyg.demo.min.js"></script>
	<script src="assets/plugins/switchery/switchery.min.js"></script>
	<script src="assets/plugins/powerange/powerange.min.js"></script>
	<script src="assets/js/form-slider-switcher.demo.min.js"></script>

	<script>
		$(document).ready(function() {
			App.init();
			FormWysihtml5.init();
			FormSliderSwitcher.init();

		});
	</script>
</body>
</html>
