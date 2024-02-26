<?php    
    $idFoto = $_POST["idFotoExcluir"];
    require_once('../../inc/global.php');
    try{
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

        $stmt = $conn->prepare('SELECT * FROM fotos WHERE id_foto = :id_foto');
        $stmt->execute(array('id_foto' => $idFoto));
        
        if($row = $stmt->fetch()){                          
            $imagem = $row["imagem"];  
            $descricao = utf8_encode($row["descricao"]);
            
            //deleta registro
            $stmt = $conn->prepare('DELETE FROM fotos WHERE id_foto = :id_foto');
            $stmt->execute(array('id_foto' => $idFoto));        

            // deleta foto atual
            if($imagem <> ""){
                $target_dir = "../dist/img/fotos/";
                if(file_exists($target_dir.$imagem)){
                    unlink($target_dir.$imagem);
                }
            }
            
            session_start();
            $id_usuario = $_SESSION["id_usuario"];
            salvaHistorico(utf8_decode("Excluído foto ").utf8_decode($descricao), utf8_decode("Site | Fotos | Excluir"), $id_usuario);           
            
            $_SESSION["MSG_SUCESSO"] = "Foto ($descricao) excluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../fotos/' </script>";        
        }
    } catch(PDOException $e) {            
        echo 'ERROR: ' . $e->getMessage();            
    } 

    function salvaHistorico($acao, $tela, $id_usuario){
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //Criado em         
            date_default_timezone_set('America/Sao_Paulo');
            $data_hora = date("Y-m-d H:i:s"); 

            //insere usuário           
            $stmt = $conn->prepare('INSERT INTO historico 
                    (acao, tela, data_hora, id_usuario) 
                    VALUES (:acao, :tela, :data_hora, :id_usuario)');            
            $stmt->execute(array('acao' => $acao, 'tela' => $tela, 
                                 'data_hora' => $data_hora, 'id_usuario' => $id_usuario)); 
            return true;  
            
        } catch(PDOException $e) {            
            echo 'ERROR: ' . $e->getMessage();
        }   
    }

?>