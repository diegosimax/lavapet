<?php    
    $idContato = $_POST["idContatoExcluir"];
    require_once('../../inc/global.php');
    try{
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

        $stmt = $conn->prepare('SELECT * FROM contatos WHERE id_contato = :id_contato');
        $stmt->execute(array('id_contato' => $idContato));
        
        if($row = $stmt->fetch()){                          
            $nome = utf8_encode($row["nome"]);
            
            //deleta registro
            $stmt = $conn->prepare('DELETE FROM contatos WHERE id_contato = :id_contato');
            $stmt->execute(array('id_contato' => $idContato));        

            session_start();
            $id_usuario = $_SESSION["id_usuario"];
            salvaHistorico(utf8_decode("Excluído contato ").utf8_decode($nome), utf8_decode("Site | Contatos | Excluir"), $id_usuario);           
            
            $_SESSION["MSG_SUCESSO"] = "Contato $nome excluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../contatos/' </script>";        
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