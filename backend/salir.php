<?php
@ob_start();
@session_start();
unset($_SESSION['backenduser']['backenduser_id']);
@session_destroy();
echo("<script>location.href = 'ingreso.php';</script>");
exit;
?>