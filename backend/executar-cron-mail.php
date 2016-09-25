<?php
ob_start();
include_once("config/conn.php");
include_once("cron/lib/mailchimp/src/mandril.php"); 
include_once("cron/clsCronEmail.php"); 

$CronEmail=new CronEmail();
loadClasses('BackendUsuario',  'Pedido', 'Incidencia', 'Localizacion');


global $BackendUsuario, $Pedido, $Incidencia, $Localizacion;
echo "hasjhdgjasgduyegwieugewiugewxxxiuwegi";
?>