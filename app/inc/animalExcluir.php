<?php    
    $idAnimal = $_POST["idAnimalExcluir"];
    require_once('../../inc/global.php');
    try{
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

        $stmt = $conn->prepare('SELECT * FROM animais WHERE id_animal = :id_animal');
        $stmt->execute(array('id_animal' => $idAnimal));
        
        if($row = $stmt->fetch()){                          
            $imagem = $row["imagem"];  
            $nome = utf8_encode($row["nome"]);
            
            //deleta registro
            $stmt = $conn->prepare('DELETE FROM animais WHERE id_animal = :id_animal');
            $stmt->execute(array('id_animal' => $idAnimal));        

            // deleta foto atual
            if($imagem <> ""){
                $target_dir = "../dist/img/animais/";
                if(file_exists($target_dir.$imagem)){
                    unlink($target_dir.$imagem);
                }
            }
            
            session_start();
            $id_usuario = $_SESSION["id_usuario"];
            salvaHistorico(utf8_decode("Excluído animal ").utf8_decode($nome), utf8_decode("Pets | Animais | Excluir"), $id_usuario);           
            $_SESSION["MSG_SUCESSO"] = "Animal $nome excluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../animais/' </script>";        
        }
    } catch(PDOException $e) {            
        if (isset($e->errorInfo[1]) && $e->errorInfo[1] == '1451') {
            session_start();
            $_SESSION["MSG_AVISO"] = "Este animal está vinculado a algum item da Ordem de Compra. Proibido deletar!";
            echo "<script type='text/javascript'> window.location = '../animais/' </script>"; 
        }else{
            echo 'ERROR: ' . $e->getMessage();    
        }  
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