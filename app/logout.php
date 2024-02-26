 <?php
    session_start();
    unset($_SESSION["logado"]);
     echo "<script type='text/javascript'> window.location = '../' </script>";
?>