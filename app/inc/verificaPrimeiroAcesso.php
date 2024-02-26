<?php    
    $idUsuario = $_SESSION["id_usuario"];

    $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare('SELECT primeiro_acesso FROM usuarios WHERE id_usuario = :id_usuario');
    $stmt->execute(array('id_usuario' => $idUsuario));
    if($row = $stmt->fetch()){  
        if($row["primeiro_acesso"] == "S"){ 
?>
            <script>
                $(function() {
                 $('#modal-troca-senha').modal('show');
                });
            </script>

<?php
        }    
    }
?>