<?php require_once('Connections/dbConn.php');
unset($_SESSION['uid']);
session_destroy();
echo "<script>location.href='".$rootURL."signin'; </script>";
/*header("Location: /signin");*/
exit();
?>
