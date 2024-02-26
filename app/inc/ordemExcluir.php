<?php    
    $idOrdem = $_POST["idOrdemExcluir"];
    require_once('../../inc/global.php');
    try{
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

        //deleta itens
        $stmt = $conn->prepare('DELETE FROM itens_da_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra');
        $stmt->execute(array('id_ordem_de_compra' => $idOrdem));  
        
        $stmt = $conn->prepare('SELECT CLI.nome
                                      FROM clientes AS CLI 
                                INNER JOIN ordem_de_compra AS ODC
                                        ON CLI.id_cliente = ODC.id_cliente
                                     WHERE ODC.id_ordem_de_compra = :id_ordem_de_compra');
        $stmt->execute(array('id_ordem_de_compra' => $idOrdem));
        if($row = $stmt->fetch()){ 
            $nomeCliente = $row["nome"];  
        }
        
        
        //deleta ordem
        $stmt = $conn->prepare('DELETE FROM ordem_de_compra WHERE id_ordem_de_compra = :id_ordem_de_compra');
        $stmt->execute(array('id_ordem_de_compra' => $idOrdem));  

        session_start();
        $id_usuario = $_SESSION["id_usuario"];
        salvaHistorico(utf8_decode("Excluído Ordem de Compra (").$idOrdem." - ".$nomeCliente.")", utf8_decode("Ordem de Compra | Excluir"), $id_usuario);           
            
        $_SESSION["MSG_SUCESSO"] = "Ordem de Compra (".$idOrdem." - ".utf8_encode($nomeCliente).") excluída com sucesso :-)";
        echo "<script type='text/javascript'> window.location = '../ordem-de-compra/' </script>";                
    } catch(PDOException $e) {            
        if (isset($e->errorInfo[1]) && $e->errorInfo[1] == '1451') {
            session_start();
            $_SESSION["MSG_AVISO"] = "Primeiro delete todos os pagamentos cadastrados para esta ordem! ($idOrdem)";
            echo "<script type='text/javascript'> window.location = '../ordem-de-compra/' </script>"; 
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