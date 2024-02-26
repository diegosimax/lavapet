<?php
    try {         
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
        
        $id_usuario = $_SESSION["id_usuario"];      
        
        $stmt = $conn->prepare('SELECT * FROM usuarios WHERE id_usuario = :id_usuario');
        $stmt->execute(array('id_usuario' => $id_usuario));    
        
        if($row = $stmt->fetch()){            
            $img_usuario = $row["imagem"];
            $nome_usuario = $row["nome"];            
        }
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }         
?>