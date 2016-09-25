<?php
// productos_editar.php
// 17/08/2015 6:21:11
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario','Slider');
global $BackendUsuario,$Slider;

$BackendUsuario->EstaLogeadoBackend();

$id = ($_POST['id']) ? $_POST['id'] : 0; // slider id
$ia = ($_POST['ia']) ? $_POST['ia'] : 0; // imagen id
$accion = ($_POST['accion']) ? $_POST['accion'] : null;

$errores  = 0;
$str_errors = "";

// roles y permisos
// acciones de esta pagina

switch ($accion) {
 case 'grabar':
    $last_id = $Slider->grabar_fotos($id, $_POST);
    $accion  = "grabado";
 break;

 case 'editar':
    $last_id = $Slider->editar_fotos($id, $_POST);
    $accion  = "grabado";
  break;


 case 'eliminar-image':
	   $Slider->eliminar_foto($ia);
	   $accion = "eliminado-image";
 break; 
}


if ($id > 0) {
  $item = $Slider->obtener($id);
}


// globales en
$result_globales = $Slider->obtener_fotos_all( $id, null, null, 1, 1);
$filas_globales = @mysql_num_rows($result_globales);


include("meta.php");
?>

	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="assets/plugins/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->

	<!-- ================== BEGIN PAGE CSS STYLE ================== -->	
	<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
	<link href="assets/plugins/powerange/powerange.min.css" rel="stylesheet" />
	<!-- ================== END PAGE CSS STYLE ================== -->

	<script>
  $(function() {
    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#idx_categoria" );
      $( "#log" ).scrollTop( 0 );
    }
 
    $( "#city" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "http://gd.geobytes.com/AutoCompleteCity",
          dataType: "jsonp",
          data: {
            q: request.term
          },
          success: function( data ) {
            response( data );
          }
        });
      },
      minLength: 3,
      select: function( event, ui ) {
        log( ui.item ?
          "Selected: " + ui.item.label :
          "Nothing selected, input was " + this.value);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
  });
  
 	function open_popup(url) {
      var w = 880;
      var h = 570;
      var l = Math.floor((screen.width-w)/2);
      var t = Math.floor((screen.height-h)/2);
      var win = window.open(url, 'ResponsiveFilemanager', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
	}
	
  function insertar_foto(idx,imagen,path_imagen,thumb, language_id) {
    	
    	
    	var form = document.forms['frm_fotos'];
 			var imagen = imagen
 			var path_imagen = path_imagen.replace('../','');
			var path_y_imagen = '<?=URL_PATH_ROOT?>'+'/'+path_imagen+imagen;
 			imagen = path_imagen+imagen; // es lo q graba en la base
			
 			//var campo_banner_image_path = "banner_image_path_"+idx;
 			var campo_banner_image = "banner_image_"+idx;
 			var campo_banner_image_preview = "banner_image_preview_"+idx;

 			// mostrar el preview
 			document.getElementById(campo_banner_image_preview).width = 300;
 			document.getElementById(campo_banner_image_preview).src = path_y_imagen;
 			
 			// pasar la img al hidden
 			form[campo_banner_image].value = imagen;
 			//form[campo_banner_image_path].value = path_imagen_limpio;
 			//form.submit();
  }	

  function editar() {
   	var form = document.forms['frm_fotos'];
   	form['accion'].value = 'editar';
   	form.submit();
  }
  
  function grabar(id_image) {
   	var form = document.forms['frm_fotos'];
   	form['ia'].value = "0";
   	form['accion'].value = 'grabar';
   	form.submit();
  }
  
  function delete_image(id_image) {
  	if(confirm('Esta seguro de querer eliminar la imagen?')) {
   	var form = document.forms['frm_fotos'];
   	form['ia'].value = id_image;
   	form['accion'].value = 'eliminar-image';
   	form.submit();
   }
  }

  </script>
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
				<li><a href="javascript:;">Sliders</a></li>
				<li class="active">Agregar Imagen </li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Slider<small> Imagenes de <strong><?=$item['nombre'];?></strong></small></h1>
			<!-- end page-header -->

         <?php //+ mensajes // ?>
         <?php if($accion == "eliminado-image") { ?>
	           <div class="alert alert-success fade in">
              <button type="button" class="close" data-dismiss="alert">
               <span aria-hidden="true">&times;</span>
              </button>
              La imagen ha sido eliminada
             </div>
	      <?php } ?>			
       
       <!-- begin row -->
			<div class="row">
                <!-- begin col-6 -->
			    <div class="col-md-12">
			        <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">
                            <?php if ($ia > 0) { ?>
                              Editar Imagen
	                          <?php } else { ?>
	                            Agregar Imagen 
                            <?php } ?>
                              </h4>
                        </div>
                        
							<form name="frm_fotos" id="frm_fotos" method="POST" action="">
								<input type="hidden" name="accion" value="grabar" />
  							<input type="hidden" name="id" value="<?=$id?>" />
  							<input type="hidden" name="ia" value="<?=$ia?>" />
								<div style="clear:both"></div><br/>
					
  <?php // banners en ingles // ?>
<div class="tab-content">
									
<div>  
  <table  class="table table-striped table-bordered table-hover" id="language_1" <?=(empty($id_dominio)) ? 'style="display:block"' : 'style="display:none"'?>>
     <thead>
         <tr>
            <td class="text-left" style="width:25%;">Texto 1</td>
  	        <td class="text-left" style="width:25%;">Texto 2</td>
    	      <td class="text-left" style="width:15%;">Texto 3</td>
    	      <td class="text-left" style="width:15%;">Boton</td>
    	      <td class="text-left" style="width:15%;">Boton Link</td>
            <td class="text-left" style="width:5%;">Orden</td>
            <td class="text-left" style="width:10%;">Acciones</td>
         </tr>
     </thead>
     <tbody>
			<?php
					for($i=0; $i < $filas_globales; $i++) {
						$items = @mysql_fetch_array($result_globales); ?>
				 <tr>
				  <td colspan="7">
				      
				      <div class="form-group">
  						 <label class="control-label col-md-2 col-sm-2" for="admin_nombre">Seleccionar Imagen</label>
  						  <div class="col-md-6 col-sm-6">
  								  <img src="../adj/<?=$items['imagen']?>" width="300">
  						  </div>											
							</div>
					  </td>
				 </tr>
         <tr>
            <td>
    	         <input type="text" id="banner_texto1_<?=$items['id']?>" name="banner_texto1_<?=$items['id']?>" value="<?=$items['texto1']?>" placeholder="Texto 1" class="form-control" maxlength="200" style="height:30px; width:100%"/>
            </td>
            <td>
	             <input type="text" id="banner_texto2_<?=$items['id']?>" name="banner_texto2_<?=$items['id']?>" value="<?=$items['texto2']?>" placeholder="Texto 2" class="form-control" maxlength="250" style="height:30px; width:100%"/>
            </td>
            <td>
  	           <input type="text" id="banner_texto3_<?=$items['id']?>" name="banner_texto3_<?=$items['id']?>" value="<?=$items['texto3']?>" placeholder="Texto 3" class="form-control" maxlength="250" style="height:30px; width:100%"/>
            </td>
            <td>
  	           <input type="text" id="banner_texto4_<?=$items['id']?>" name="banner_texto4_<?=$items['id']?>" value="<?=$items['texto4']?>" placeholder="Texto Boton" class="form-control" maxlength="250" style="height:30px; width:100%" />
            </td>
            <td>
	             <input type="text" id="banner_link_<?=$items['id']?>" name="banner_link_<?=$items['id']?>" value="<?=$items['link']?>" placeholder="Link del Boton" class="form-control" maxlength="100" style="height:30px; width:100%"/>
            </td>
            <td>
                <input type="text" name="banner_orden_<?=$items['id']?>" value="<?=$items['orden']?>" placeholder="" class="form-control"  maxlength="2" style="height:30px; width:50px"/>
            </td>
             <td>
             	<!--
               <input type="button" value="Edit" name="btEdit" onClick="open_popup('../biblioteca/filemanager/dialog.php?banner_image_id=<?=$items['slider_image_id']?>&l=1&mode=edit')"/>
               -->
               <button type="button" id="delete-<?=$items['id']?>" rel="attribute-row-language-1-0" data-toggle="tooltip" title="Eliminar Imagen" class="btn btn-danger btn-remove-attribute" onClick="delete_image('<?=$items['id']?>')"><i class="fa fa-minus-circle"></i></button>
             </td>
         </tr>

    <tr>
				  <td colspan="7"> <hr>  </td>
				 </tr>          
       <?php } ?>
<tr>
 <td colspan="7">
 
 <?php if($filas_globales > 0) { ?>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4"></label>
									<div class="col-md-6 col-sm-6">
										<button type="button" class="btn btn-primary" onClick="editar()">Grabar</button>
										o
										<a href="sliders.php">cancel</a>
									</div>
								</div>
 
 <?php } else { ?>
								<div><strong>No hay imagenes en este slider</strong></div>
 <?php }  ?>
 
 </td>
</tr>
              	
								
        <tr>
				  <td colspan="7"> <h4>Nueva Imagen</h4>
				  </td>
				 </tr>       
        <tr>
				  <td colspan="7">
				        <a href="#" id="thumb-image-0-1" data-toggle="image" class="img-thumbnail">
                   <img src="<?=URL_PATH_ROOT?>/adj/<?=$items['image']?>" id="banner_image_preview_0" name="banner_image_preview_0"/>
                </a>
               	 <input type="hidden" id="banner_image_0" name="banner_image_0" value="<?=$items['imagen']?>"/>
               	 <input type="hidden" id="banner_image_path_0" name="banner_image_path" />
               	 
               	    <input type="button" value="Agregar Imagen" name="btEdit" onClick="open_popup('../biblioteca/filemanager/dialog.php?banner_image_id=0&l=1&mode=nuevo')"/>

				  </td>
				 </tr>
         <tr>
            <td>
    	         <input type="text" id="banner_texto1_0" name="banner_texto1_0" value="" placeholder="Texto 1" class="form-control" maxlength="250" style="height:30px; width:100%"/>
            </td>
            <td>
	             <input type="text" id="banner_texto2_0" name="banner_texto2_0" value="" placeholder="Texto 2" class="form-control" maxlength="250" style="height:30px; width:100%"/>
            </td>
            <td>
  	           <input type="text" id="banner_texto3_0" name="banner_texto3_0" value="" placeholder="Texto 3" class="form-control" maxlength="250" style="height:30px; width:100%"/>
            </td>
            <td>
  	           <input type="text" id="banner_texto4_0" name="banner_texto4_0" value="" placeholder="Texto Boton" class="form-control" maxlength="250" style="height:30px; width:100%" />
            </td>
            <td>
	             <input type="text" id="banner_link_0" name="banner_link_0" value="" placeholder="Link del Boton" class="form-control" maxlength="100" style="height:30px; width:100%"/>
            </td>
            <td>
                <input type="text" id="banner_orden_0" name="banner_orden_0" value="" placeholder="0" class="form-control"  maxlength="2" style="height:30px; width:50px"/>
            </td>
             <td>
               <input type="button" value="Agregar" class="btn " name="btGrabar" onClick="grabar('0')"/>
             </td>
         </tr>
         </tbody>          
		   </table>
		  <?php // global ingles // ?>
</div>




 
</div><!-- fin tabs -->			
 </div>			
							
								
               </form>
             </div>
          </div>
            <!-- end panel -->
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
			//FormWysihtml5.init();
			//FormSliderSwitcher.init();
		});
	</script>
	
</body>
</html>
