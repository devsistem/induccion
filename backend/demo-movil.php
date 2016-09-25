<?php
ob_start();
include_once("config/conn.php");
declareRequest('accion','id','arrSeleccion', 'porPagina', 'paginacion', 'palabra');
loadClasses('BackendUsuario',  'Pedido', 'Incidencia', 'Vendedor', 'Pedido');
global $BackendUsuario, $Pedido, $Incidencia, $Vendedor, $Pedido;

$BackendUsuario->EstaLogeadoBackend();

if(!$BackendUsuario->esGerenteVentas()) {
 die;
}

// id vendedor
$id 		= ($_REQUEST['id_vendedor']) ? $_REQUEST['id_vendedor'] : 0;
$accion = ($_POST['accion']) ? $_POST['accion'] : null;
$filtro_cuen = ($_POST['filtro_cuen']) ? $_POST['filtro_cuen'] : null;

switch ($accion) {
 
}

// todos los vendedores y supervisores
$result = $BackendUsuario->obtener_vendedores_y_supervisores( null);
$filas = @mysql_num_rows($result);
																		
// todos los pedidos
$result_pedidos = $Pedido->obtener_all_cuen(null, null, $limite, $palabra, $order_by, $filtro, ACTIVO, null, null, null, null, null, $id, $filtro_cuen);
$filas_pedidos  = @mysql_num_rows($result_pedidos );
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">

  <title>Starter Template for Bootcards</title>

  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Bootcards CSS files for desktop, iOS and Android -->
  <!-- You'll only need to load one of these (depending on the device you're using) in production -->
  <link href="//cdnjs.cloudflare.com/ajax/libs/bootcards/1.1.1/css/bootcards-ios.min.css" rel="stylesheet">
  <!-- <link href="//cdnjs.cloudflare.com/ajax/libs/bootcards/1.1.1/css/bootcards-desktop.min.css" rel="stylesheet">-->
  <!--<link href="//cdnjs.cloudflare.com/ajax/libs/bootcards/1.1.1/css/bootcards-android.min.css" rel="stylesheet">-->

  <link href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />

</head>

<body>
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <button type="button" class="btn btn-default btn-back navbar-left pull-left hidden " onclick="history.back()">
      <i class="fa fa-lg fa-chevron-left"></i>
      <span>Back</span>
    </button>
    <button type="button" class="btn btn-default btn-menu navbar-left pull-left offcanvas-toggle">
      <i class="fa fa-lg fa-bars"></i>
      <span>Menu</span>
    </button>
    <a class="navbar-brand" title="Customers v{{getAppVersion}}" href="/">Customers</a>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active">
          <a href="#">
            <i class="fa fa-users"></i> 
            Contacts
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-building-o"></i> 
            Companies
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-gears"></i> 
            Settings
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>

  <div class="container">
    <div class="row">
      <div class="col-sm-5 bootcards-list">
      
        ...your Bootcards List goes here...
        
      </div>
      <div class="col-sm-7 bootcards-cards">
      
        ...your Bootcards Cards go here...
        
      </div>
    </div>
  </div>


    <!-- Bootstrap & jQuery core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
    <!-- Bootstrap and Bootcayyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyrds JS -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <!-- Bootcards JS -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootcards/1.0.0/js/bootcards.min.js"></script>

    <!--recommended: FTLabs FastClick library-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.3/fastclick.min.js"></script>

    <script type="text/javascript">
      /*
       * Initialize Bootcards.
       * 
       * Parameters:
       * - offCanvasBackdrop (boolean): show a backdrop when the offcanvas is shown
       * - offCanvasHideOnMainClick (boolean): hide the offcanvas menu on clicking outside the off canvas
       * - enableTabletPortraitMode (boolean): enable single pane mode for tablets in portraitmode
       * - disableRubberBanding (boolean): disable the iOS rubber banding effect
       * - disableBreakoutSelector (string) : for iOS apps that are added to the home screen:
                            jQuery selector to target links for which a fix should be added to not
                            allow those links to break out of fullscreen mode.
       */
       
      bootcards.init( {
        offCanvasBackdrop : true,
        offCanvasHideOnMainClick : true,
        enableTabletPortraitMode : true,
        disableRubberBanding : true,
        disableBreakoutSelector : 'a.no-break-out'
      });
      //enable FastClick
      window.addEventListener('load', function() {
          FastClick.attach(document.body);
      }, false);
      //activate the sub-menu options in the offcanvas menu
      $('.collapse').collapse();
      //theme switcher: only needed for this sample page to set the active CSS
      $('input[name=themeSwitcher]').on('change', function(ev) {
        var theme = $(ev.target).val();
        var themeCSSLoaded = false;
        $.each( document.styleSheets, function(idx, css) {
          var href = css.href;
          if (href.indexOf('bootcards')>-1) {
            if (href.indexOf(theme)>-1) {
              themeCSSLoaded = true;
              css.disabled = false;
            } else {
              css.disabled = true;
            }
          }
        });
        if (!themeCSSLoaded) {
          $("<link/>", {
             rel: "stylesheet",
             type: "text/css",
             href: "//cdnjs.cloudflare.com/ajax/libs/bootcards/1.1.1/css/bootcards-" + theme + ".min.css"
          }).appendTo("head");
        }
        
      });
    </script>
  </body>
</html>